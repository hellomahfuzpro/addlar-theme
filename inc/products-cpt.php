<?php
/**
 * Product custom post type + category taxonomy.
 *
 * Real, documented products (ones with a finished Product Data Sheet) get a
 * post here instead of living only inside the Product Finder's manual
 * catalogue text — that is what lets "related products" and the Finder's
 * own default data be derived queries instead of two hand-maintained lists
 * that can drift apart (see addlar_finder_catalogue_merged() in
 * products-render.php).
 *
 * `has_archive` is deliberately false: `/products/` is a hand-curated Page
 * (seeded reusing Addlar_Widget_ProductGrid), not an auto-generated CPT
 * archive. Letting both want the same slug is a classic WordPress collision;
 * disabling the CPT archive avoids it outright.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function addlar_register_product_cpt() {

	register_post_type( 'addlar_product', array(
		'label'        => __( 'Products', 'addlar' ),
		'labels'       => array(
			'name'               => __( 'Products', 'addlar' ),
			'singular_name'      => __( 'Product', 'addlar' ),
			'add_new_item'       => __( 'Add New Product', 'addlar' ),
			'edit_item'          => __( 'Edit Product', 'addlar' ),
			'all_items'          => __( 'All Products', 'addlar' ),
			'search_items'       => __( 'Search Products', 'addlar' ),
			'not_found'          => __( 'No products found', 'addlar' ),
		),
		'public'       => true,
		'show_in_rest' => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-tag',
		'menu_position' => 20,
		'supports'     => array( 'title', 'thumbnail', 'custom-fields' ),
		'rewrite'      => array( 'slug' => 'products', 'with_front' => false ),
	) );

	register_taxonomy( 'addlar_product_category', 'addlar_product', array(
		'label'             => __( 'Product Categories', 'addlar' ),
		'labels'            => array(
			'name'          => __( 'Product Categories', 'addlar' ),
			'singular_name' => __( 'Product Category', 'addlar' ),
		),
		'public'            => true,
		'show_in_rest'      => true,
		'hierarchical'      => true,
		'rewrite'           => array( 'slug' => 'products/category', 'with_front' => false ),
	) );
}
add_action( 'init', 'addlar_register_product_cpt' );

/**
 * Meta keys used by the Theme Builder Dynamic Tags, and by the coded
 * fallback/archive templates. Registered centrally so both read the same
 * list, and so `register_post_meta()` exposes them cleanly to Elementor's
 * "Post Custom Field" dynamic tag.
 *
 * @return array meta_key => args for register_post_meta()
 */
function addlar_product_meta_fields() {
	$text = array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'default' => '' );
	$html = array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'default' => '', 'sanitize_callback' => 'wp_kses_post' );
	$int  = array( 'type' => 'integer', 'single' => true, 'show_in_rest' => true, 'default' => 0 );

	return array(
		'_addlar_code'                     => $text, // bare code, e.g. "7375", "KC420", "Z 2612" — matches Finder textarea codes.
		'_addlar_spec_string'             => $text, // e.g. "API SN/CF to API SJ | ILSAC GF-5 | ..."
		'_addlar_subcategory'              => $text, // e.g. "Passenger Car" — feeds the Finder merge.
		'_addlar_description'             => array_merge( $text, array( 'sanitize_callback' => 'wp_kses_post' ) ),
		'_addlar_applications_text'        => $text, // one per line — raw components list use-cases before the description.
		'_addlar_performance_headers'      => $text, // one row: pipe-delimited column headers, or empty if no table.
		'_addlar_performance_rows_text'    => $text, // one row per line, pipe-delimited, matching the headers count.
		'_addlar_performance_note'         => $text, // e.g. "Multigrade" / "Automotive" label above the table.
		'_addlar_approvals_text'           => $text, // one per line — flat OEM/industry approval chips.
		'_addlar_formulation_text'         => $text, // 9200-style recipe: one "Component: value" per line.
		'_addlar_formulation_label'        => $text, // e.g. "SAE 30 (TBN 5 mg KOH/g) Formulation"
		'_addlar_properties_text'          => $text, // one row per line: Test | Method | Value | Unit
		'_addlar_viscosity_note'           => array_merge( $text, array( 'sanitize_callback' => 'wp_kses_post' ) ),
		'_addlar_doc_code'                 => $text, // e.g. "RCH/V1.1/7375"
		'_addlar_performance_table_html'   => $html, // pre-rendered by products-render.php on save.
		'_addlar_properties_table_html'    => $html,
		'_addlar_applications_html'        => $html,
		'_addlar_approvals_html'           => $html,
		'_addlar_formulation_html'         => $html,
		'_addlar_pds_pdf_id'               => $int,
		'_addlar_hero_image_id'            => $int,
	);
}

function addlar_register_product_meta() {
	foreach ( addlar_product_meta_fields() as $key => $args ) {
		register_post_meta( 'addlar_product', $key, $args );
	}
}
add_action( 'init', 'addlar_register_product_meta' );
