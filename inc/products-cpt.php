<?php
/**
 * Product custom post type + category taxonomy.
 *
 * The CPT exists so products are real, linkable, individually
 * Elementor-editable pages, and so the Product Finder / related-products
 * grids can be derived queries rather than hand-maintained lists.
 *
 * Product *content* does NOT live in post meta — it's authored directly in
 * each page's own Elementor widgets (client's explicit request: "instead of
 * using custom fields use standalone elementor widget inputs"). The seeder
 * writes the real PDS data into those widget settings once; after that the
 * client edits everything in Elementor's own UI, with no custom-fields
 * metabox involved. Only two tiny structural values stay in meta —
 * `_addlar_code` and `_addlar_subcategory` — because the Finder and the
 * related-products query need to look products up by code/sub-category
 * without parsing Elementor's JSON.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function addlar_register_product_cpt() {

	// Taxonomy first: WordPress generates rewrite rules in registration
	// order, so the taxonomy's rules are matched before the CPT's broader
	// `products/...` ones.
	register_taxonomy( 'addlar_product_category', 'addlar_product', array(
		'label'             => __( 'Product Categories', 'addlar' ),
		'labels'            => array(
			'name'          => __( 'Product Categories', 'addlar' ),
			'singular_name' => __( 'Product Category', 'addlar' ),
		),
		'public'            => true,
		'publicly_queryable' => true,
		'show_in_rest'      => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'show_in_nav_menus' => true,
		'show_admin_column' => true,
		'query_var'         => 'addlar_product_category',
		// Deliberately NOT nested under `products/`. The previous
		// `products/category` slug sat underneath both the hand-curated
		// `/products/` Page and the CPT's own `products` rewrite base — a
		// three-way collision that resolved to a 404 no matter how many
		// times rewrite rules were flushed. A distinct top-level base can't
		// collide with either.
		'rewrite'           => array(
			'slug'         => 'product-category',
			'with_front'   => false,
			'hierarchical' => false,
		),
	) );

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
		'public'        => true,
		'show_in_rest'  => true,
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-tag',
		'menu_position' => 20,
		'taxonomies'    => array( 'addlar_product_category' ),
		'supports'      => array( 'title', 'thumbnail', 'editor' ),
		'rewrite'       => array( 'slug' => 'products', 'with_front' => false ),
	) );
}
add_action( 'init', 'addlar_register_product_cpt' );

/**
 * The two structural meta values kept on a product (see the file docblock
 * for why everything else moved into Elementor widget settings).
 *
 * @return array meta_key => args for register_post_meta()
 */
function addlar_product_meta_fields() {
	$text = array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'default' => '' );

	return array(
		// Bare code, e.g. "7375", "KC420", "Z 2612" — matches the codes in the
		// Product Finder's catalogue text, which is how a Finder pill knows
		// whether a real product page exists to link to.
		'_addlar_code'        => $text,
		// e.g. "Passenger Car" — shown on related-product cards and used by
		// addlar_finder_catalogue_merged() to file a product correctly.
		'_addlar_subcategory' => $text,
	);
}

function addlar_register_product_meta() {
	foreach ( addlar_product_meta_fields() as $key => $args ) {
		register_post_meta( 'addlar_product', $key, $args );
	}
}
add_action( 'init', 'addlar_register_product_meta' );

/**
 * One-shot rewrite-rule flush after an update that changes the taxonomy's
 * rewrite base. Without this the old `products/category/...` rules stay
 * cached in the database and the new URLs 404 until someone re-saves
 * Settings → Permalinks by hand — the exact failure this version fixes, so
 * it must not require a manual step to take effect.
 */
function addlar_maybe_flush_rewrites() {
	if ( get_option( 'addlar_rewrite_version' ) === ADDLAR_VERSION ) {
		return;
	}
	flush_rewrite_rules();
	update_option( 'addlar_rewrite_version', ADDLAR_VERSION );
}
add_action( 'init', 'addlar_maybe_flush_rewrites', 99 );
