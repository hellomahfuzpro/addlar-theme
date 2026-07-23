<?php
/**
 * Nav walkers.
 *
 * The mockup's header is a flex row of bare <a> elements (`.navlinks a`), with
 * the Products item expanding into a `.navdrop > .dropwrap` panel whose links
 * carry a <span> sub-label. WordPress' default ul/li markup would break the
 * flex layout, so both walkers emit the mockup's markup verbatim.
 *
 * Sub-labels come from the menu item's Description field (enable it via
 * Screen Options on the Menus admin screen).
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header navigation: anchors at depth 0, dropdown panel at depth 1.
 */
class Addlar_Nav_Walker extends Walker_Nav_Menu {

	/** Open the dropdown panel. */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '<div class="dropwrap">';
		}
	}

	/** Close the dropdown panel. */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</div>';
		}
	}

	/** Render one item. */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_kids    = in_array( 'menu-item-has-children', $classes, true );
		$url         = ! empty( $item->url ) ? $item->url : '#';
		$title       = apply_filters( 'the_title', $item->title, $item->ID );
		$description = ! empty( $item->description ) ? $item->description : '';

		if ( 0 === $depth && $has_kids ) {
			$output .= '<div class="navdrop">';
		}

		$output .= sprintf( '<a href="%s">%s', esc_url( $url ), esc_html( $title ) );

		// Sub-label only makes sense inside the dropdown panel.
		if ( $depth > 0 && $description ) {
			$output .= sprintf( '<span>%s</span>', esc_html( $description ) );
		}

		$output .= '</a>';
	}

	/** Close the .navdrop wrapper after its panel has been emitted. */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$classes  = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_kids = in_array( 'menu-item-has-children', $classes, true );

		if ( 0 === $depth && $has_kids ) {
			$output .= '</div>';
		}
	}
}

/**
 * Mobile panel: every item flattened to a plain anchor, no nesting.
 */
class Addlar_Mobile_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$url   = ! empty( $item->url ) ? $item->url : '#';
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$output .= sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $title ) );
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

/**
 * Footer columns: plain anchors inside the column's <ul>.
 */
class Addlar_Footer_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$url   = ! empty( $item->url ) ? $item->url : '#';
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$output .= sprintf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $title ) );
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}
