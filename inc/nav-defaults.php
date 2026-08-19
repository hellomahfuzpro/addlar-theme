<?php
/**
 * Fallback navigation.
 *
 * A fresh install has no menus assigned, which would leave the header with a
 * logo and a button and nothing else. These fallbacks render the navigation
 * from the approved design so the site is complete the moment it is seeded;
 * assigning a real menu to a location overrides them entirely.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A stub/real page's URL by slug, or a homepage-anchor fallback if that
 * page hasn't been seeded yet (Tools → ADDLAR setup → Seed products + pages)
 * — so the nav never links to a 404 on a fresh install.
 *
 * @param string $slug     Page slug, e.g. "about-us".
 * @param string $fallback Anchor to use if the page doesn't exist yet.
 * @return string
 */
function addlar_nav_page_url( $slug, $fallback ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : $fallback;
}

/**
 * The blog URL — WordPress's assigned "Posts page" if one is set (the
 * seeder sets it), otherwise the `/blog/` page, otherwise home.
 *
 * @return string
 */
function addlar_nav_blog_url() {
	$posts_page = (int) get_option( 'page_for_posts' );
	if ( $posts_page && 'publish' === get_post_status( $posts_page ) ) {
		return get_permalink( $posts_page );
	}
	return addlar_nav_page_url( 'blog', home_url( '/' ) );
}

/**
 * Header navigation as designed.
 *
 * The Products dropdown children and the top-level About Us/Contact Us
 * items point at the real Phase 2 pages once seeded (addlar_nav_page_url() /
 * addlar_product_category_link() fall back to the original homepage anchors
 * otherwise). Applications and Finder stay as homepage anchors — those
 * sections live only on the homepage.
 *
 * @return array Each item: label, url, and optional children (label/url/desc).
 */
function addlar_default_primary_links() {
	return apply_filters( 'addlar_default_primary_links', array(
		array( 'label' => __( 'Home', 'addlar' ), 'url' => '#top' ),
		array( 'label' => __( 'About Us', 'addlar' ), 'url' => addlar_nav_page_url( 'about-us', '#about' ) ),
		array(
			'label'    => __( 'Products', 'addlar' ),
			'url'      => addlar_nav_page_url( 'products', '#products' ),
			'children' => array(
				array( 'label' => __( 'Engine Oil Additives', 'addlar' ), 'url' => addlar_product_category_link( 'Engine Oil Additive', '#products' ), 'desc' => __( 'Heavy Duty · Passenger Car · Motorcycle', 'addlar' ) ),
				array( 'label' => __( 'Driveline Additives', 'addlar' ), 'url' => addlar_product_category_link( 'Driveline', '#products' ), 'desc' => __( 'Gear · ATF · Manual · Off-Road', 'addlar' ) ),
				array( 'label' => __( 'Marine Additives', 'addlar' ), 'url' => addlar_product_category_link( 'Marine', '#products' ), 'desc' => __( 'Trunk Piston · System · Cylinder', 'addlar' ) ),
				array( 'label' => __( 'Industrial Additives', 'addlar' ), 'url' => addlar_product_category_link( 'Industrial', '#products' ), 'desc' => __( 'Gear · Grease · Hydraulic · Slideway', 'addlar' ) ),
				array( 'label' => __( 'Metalworking Fluids', 'addlar' ), 'url' => addlar_product_category_link( 'Metal Working Fluid', '#products' ), 'desc' => __( 'Neat Cutting · Soluble Oil', 'addlar' ) ),
				array( 'label' => __( 'Lubricant Components', 'addlar' ), 'url' => addlar_product_category_link( 'Lubricant Component', '#packages' ), 'desc' => __( 'Detergents · Dispersants · VII · more', 'addlar' ) ),
			),
		),
		// Applications and Finder are homepage sections, so they're linked
		// absolutely — a bare "#applications" would do nothing from a
		// product page, which is where most visitors now start.
		array( 'label' => __( 'Applications', 'addlar' ), 'url' => home_url( '/#applications' ) ),
		array( 'label' => __( 'Finder', 'addlar' ), 'url' => addlar_nav_page_url( 'products', home_url( '/' ) ) . '#finder' ),
		array( 'label' => __( 'Insights', 'addlar' ), 'url' => addlar_nav_blog_url() ),
		array( 'label' => __( 'Contact Us', 'addlar' ), 'url' => addlar_nav_page_url( 'contact-us', '#contact' ) ),
	) );
}

/** Footer column links as designed, keyed by menu location. */
function addlar_default_footer_links( $location ) {
	// Every entry points at a page that actually exists, or a homepage
	// section that actually exists — no placeholder "#" links. The legal
	// column is the one exception and is documented below.
	$sets = apply_filters( 'addlar_default_footer_links', array(
		'footer-1' => array(
			array( 'label' => __( 'Home', 'addlar' ), 'url' => home_url( '/' ) ),
			array( 'label' => __( 'About Us', 'addlar' ), 'url' => addlar_nav_page_url( 'about-us', '#about' ) ),
			array( 'label' => __( 'Products', 'addlar' ), 'url' => addlar_nav_page_url( 'products', '#products' ) ),
			array( 'label' => __( 'Contact Us', 'addlar' ), 'url' => addlar_nav_page_url( 'contact-us', '#contact' ) ),
		),
		'footer-2' => array(
			array( 'label' => __( 'Engine Oil Additives', 'addlar' ), 'url' => addlar_product_category_link( 'Engine Oil Additive', '#products' ) ),
			array( 'label' => __( 'Driveline Additives', 'addlar' ), 'url' => addlar_product_category_link( 'Driveline', '#products' ) ),
			array( 'label' => __( 'Marine Additives', 'addlar' ), 'url' => addlar_product_category_link( 'Marine', '#products' ) ),
			array( 'label' => __( 'Industrial Additives', 'addlar' ), 'url' => addlar_product_category_link( 'Industrial', '#products' ) ),
			array( 'label' => __( 'Metalworking Fluids', 'addlar' ), 'url' => addlar_product_category_link( 'Metal Working Fluid', '#products' ) ),
			array( 'label' => __( 'Lubricant Components', 'addlar' ), 'url' => addlar_product_category_link( 'Lubricant Component', '#packages' ) ),
		),
		'footer-3' => array(
			array( 'label' => __( 'Ask the Expert', 'addlar' ), 'url' => addlar_nav_page_url( 'ask-the-expert', '#contact' ) ),
			array( 'label' => __( 'Product Finder', 'addlar' ), 'url' => addlar_nav_page_url( 'products', home_url( '/' ) ) . '#finder' ),
			array( 'label' => __( 'Applications', 'addlar' ), 'url' => home_url( '/#applications' ) ),
			array( 'label' => __( 'Insights', 'addlar' ), 'url' => addlar_nav_blog_url() ),
		),
		// Placeholder targets on purpose: these are real legal pages that
		// have to be written and approved before they can be linked. Left
		// as "#" rather than pointed somewhere misleading.
		'legal'    => array(
			array( 'label' => __( 'Privacy Policy', 'addlar' ), 'url' => '#' ),
			array( 'label' => __( 'Terms of Use', 'addlar' ), 'url' => '#' ),
			array( 'label' => __( 'FAQs', 'addlar' ), 'url' => '#' ),
		),
	) );

	return isset( $sets[ $location ] ) ? $sets[ $location ] : array();
}

/** Header markup for the fallback menu (mirrors Addlar_Nav_Walker output). */
function addlar_render_default_primary() {
	echo '<nav class="navlinks">';
	foreach ( addlar_default_primary_links() as $item ) {
		if ( ! empty( $item['children'] ) ) {
			echo '<div class="navdrop">';
			printf( '<a href="%s">%s</a>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
			echo '<div class="dropwrap">';
			foreach ( $item['children'] as $child ) {
				printf(
					'<a href="%s">%s<span>%s</span></a>',
					esc_url( $child['url'] ),
					esc_html( $child['label'] ),
					esc_html( $child['desc'] )
				);
			}
			echo '</div></div>';
		} else {
			printf( '<a href="%s">%s</a>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
		}
	}
	echo '</nav>';
}

/** Flat anchor list for the mobile panel. */
function addlar_render_default_mobile() {
	foreach ( addlar_default_primary_links() as $item ) {
		printf( '<a href="%s">%s</a>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
	}
}

/** Footer column list. */
function addlar_render_default_footer_column( $location ) {
	$links = addlar_default_footer_links( $location );
	if ( ! $links ) {
		return;
	}
	echo '<ul>';
	foreach ( $links as $link ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $link['url'] ), esc_html( $link['label'] ) );
	}
	echo '</ul>';
}

/** Bottom-bar links (no <ul>, matches the .legal flex row). */
function addlar_render_default_legal() {
	foreach ( addlar_default_footer_links( 'legal' ) as $link ) {
		printf( '<a href="%s">%s</a>', esc_url( $link['url'] ), esc_html( $link['label'] ) );
	}
}
