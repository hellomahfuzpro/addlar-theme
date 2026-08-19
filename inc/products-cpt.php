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
		// /products/{category}/ — the CPT itself now lives at the *singular*
		// /product/ base (below), so the taxonomy owns the plural base
		// outright. The only thing it shares a prefix with is the curated
		// `/products/` Page, and that resolves cleanly because this rule
		// requires a trailing segment the bare page URL doesn't have.
		'rewrite'           => array(
			'slug'         => 'products',
			'with_front'   => false,
			'hierarchical' => false,
		),
	) );

	// Lets `%addlar_product_category%` be used inside the CPT's rewrite slug
	// below. Registered explicitly rather than relying on register_taxonomy()
	// having done it internally — if the tag is missing, WordPress leaves the
	// literal "%addlar_product_category%" in the generated rule and every
	// single-product URL 404s.
	add_rewrite_tag( '%addlar_product_category%', '([^/]+)', 'addlar_product_category=' );

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
		// /product/{category}/{code}/ — singular base, with the category
		// folded into the path. The %addlar_product_category% placeholder is
		// substituted per post by addlar_product_permalink() below.
		'rewrite'       => array(
			'slug'       => 'product/%addlar_product_category%',
			'with_front' => false,
		),
	) );
}
add_action( 'init', 'addlar_register_product_cpt' );

/**
 * Swap the %addlar_product_category% placeholder in a product's permalink
 * for its actual category slug. A product with no term falls back to
 * "uncategorised" rather than leaving a raw placeholder in the URL.
 *
 * @param string  $link Permalink.
 * @param WP_Post $post Post object.
 * @return string
 */
function addlar_product_permalink( $link, $post ) {
	if ( ! $post || 'addlar_product' !== $post->post_type ) {
		return $link;
	}
	if ( false === strpos( $link, '%addlar_product_category%' ) ) {
		return $link;
	}

	$slug  = 'uncategorised';
	$terms = get_the_terms( $post->ID, 'addlar_product_category' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		$slug = $terms[0]->slug;
	}

	return str_replace( '%addlar_product_category%', $slug, $link );
}
add_filter( 'post_type_link', 'addlar_product_permalink', 10, 2 );

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
