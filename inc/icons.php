<?php
/**
 * Inline SVG icons.
 *
 * Kept as PHP helpers rather than an icon font so they inherit currentColor
 * and need no extra HTTP request. Markup matches the mockup exactly.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'ADDLAR_TESTING' ) ) {
	exit;
}

function addlar_icon_linkedin() {
	echo '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-.95 1.83-1.95 3.77-1.95 4.03 0 4.78 2.5 4.78 5.76V21h-4v-5.6c0-1.34-.03-3.06-1.9-3.06-1.9 0-2.2 1.45-2.2 2.96V21H9z"/></svg>';
}

function addlar_icon_youtube() {
	echo '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23 12s0-3.8-.5-5.6a2.9 2.9 0 0 0-2-2C18.7 4 12 4 12 4s-6.7 0-8.5.4a2.9 2.9 0 0 0-2 2C1 8.2 1 12 1 12s0 3.8.5 5.6a2.9 2.9 0 0 0 2 2C5.3 20 12 20 12 20s6.7 0 8.5-.4a2.9 2.9 0 0 0 2-2C23 15.8 23 12 23 12zM9.8 15.4V8.6l5.9 3.4z"/></svg>';
}

/**
 * Line icons used by the Journey, Packages, About and Applications widgets.
 * Each returns raw <path>/<circle> markup for a 24x24 viewBox; the widget
 * supplies the wrapping <svg> so it can control size and stroke.
 *
 * @param string $key Icon slug.
 * @return string
 */
function addlar_icon_path( $key ) {
	$icons = array(
		'building'   => '<path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5"/>',
		'droplet'    => '<path d="M12 3s6 7 6 11a6 6 0 0 1-12 0c0-4 6-11 6-11z"/>',
		'globe'      => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18a15 15 0 0 1 0-18"/>',
		'flask'      => '<path d="M9 3h6M10 3v6l-5 9a2 2 0 0 0 2 3h10a2 2 0 0 0 2-3l-5-9V3"/>',
		'factory'    => '<path d="M3 21V10l6 4V10l6 4V10l6 4v7z"/>',
		'gear'       => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>',
		'spark'      => '<path d="M12 2l2.6 6.8L21 11l-6.4 2.2L12 20l-2.6-6.8L3 11l6.4-2.2z"/>',
		'layers'     => '<path d="M3 7l9-4 9 4-9 4z"/><path d="M3 12l9 4 9-4M3 17l9 4 9-4"/>',
		'shield'     => '<path d="M12 2l8 4v6c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6z"/>',
		'plant'      => '<path d="M3 21V8l9-5 9 5v13"/><path d="M9 21v-6h6v6"/>',
		'people'     => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/>',
		'wear'       => '<path d="M4 18h16M6 18l3-9 3 5 3-8 3 12"/>',
		'viscosity'  => '<path d="M3 17c3-8 6-8 9 0s6 8 9 0"/>',
		'detergent'  => '<circle cx="12" cy="12" r="8"/><path d="M8 12h8M12 8v8"/>',
		'antiox'     => '<path d="M12 3v18M5 8l14 8M19 8L5 16"/>',
		'corrosion'  => '<path d="M12 3l7 4v6c0 4-3 6.5-7 8-4-1.5-7-4-7-8V7z"/>',
		'foam'       => '<circle cx="8" cy="15" r="3"/><circle cx="15" cy="10" r="4"/>',
		'pourpoint'  => '<path d="M12 2v20M5 9l7-7 7 7M8 15l4 4 4-4"/>',
		'mail'       => '<path d="M3 5h18v14H3z"/><path d="M3 6l9 7 9-7"/>',
		'pin'        => '<path d="M12 21s7-6.4 7-12a7 7 0 0 0-14 0c0 5.6 7 12 7 12z"/><circle cx="12" cy="9" r="2.5"/>',
	);
	return isset( $icons[ $key ] ) ? $icons[ $key ] : $icons['droplet'];
}

/** Options array for Elementor SELECT controls. */
function addlar_icon_choices() {
	return array(
		'building'  => 'Building',
		'droplet'   => 'Droplet',
		'globe'     => 'Globe',
		'flask'     => 'Flask',
		'factory'   => 'Factory',
		'gear'      => 'Gear',
		'spark'     => 'Spark',
		'layers'    => 'Layers',
		'shield'    => 'Shield',
		'plant'     => 'Plant',
		'people'    => 'People',
		'wear'      => 'Wear / AW-EP',
		'viscosity' => 'Viscosity',
		'detergent' => 'Detergent',
		'antiox'    => 'Antioxidant',
		'corrosion' => 'Corrosion',
		'foam'      => 'Anti-foam',
		'pourpoint' => 'Pour point',
		'mail'      => 'Mail',
		'pin'       => 'Pin',
	);
}
