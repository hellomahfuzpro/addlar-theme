<?php
/**
 * ADDLAR theme bootstrap.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ADDLAR_VERSION', '1.8.0' );
define( 'ADDLAR_DIR', get_template_directory() );
define( 'ADDLAR_URI', get_template_directory_uri() );

/* -------------------------------------------------------------------------
 * Theme supports & menus
 * ---------------------------------------------------------------------- */

function addlar_setup() {
	load_theme_textdomain( 'addlar', ADDLAR_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 68,
		'width'       => 260,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	register_nav_menus( array(
		'primary'  => __( 'Primary (header + mobile)', 'addlar' ),
		'footer-1' => __( 'Footer column 1', 'addlar' ),
		'footer-2' => __( 'Footer column 2', 'addlar' ),
		'footer-3' => __( 'Footer column 3', 'addlar' ),
		'legal'    => __( 'Footer bottom bar (legal)', 'addlar' ),
	) );
}
add_action( 'after_setup_theme', 'addlar_setup' );

/* -------------------------------------------------------------------------
 * Assets
 * ---------------------------------------------------------------------- */

function addlar_assets() {
	// Dependency order: tokens (vars + chrome) -> widgets (sections) -> style.css (type enforcement).
	wp_enqueue_style( 'addlar-tokens', ADDLAR_URI . '/assets/css/tokens.css', array(), ADDLAR_VERSION );
	wp_enqueue_style( 'addlar-widgets', ADDLAR_URI . '/assets/css/widgets.css', array( 'addlar-tokens' ), ADDLAR_VERSION );
	wp_enqueue_style( 'addlar-theme-css', ADDLAR_URI . '/assets/css/theme.css', array( 'addlar-widgets' ), ADDLAR_VERSION );
	wp_enqueue_style( 'addlar-style', get_stylesheet_uri(), array( 'addlar-theme-css' ), ADDLAR_VERSION );

	wp_enqueue_script( 'addlar-theme', ADDLAR_URI . '/assets/js/theme.js', array(), ADDLAR_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'addlar_assets' );

/**
 * Elementor PREVIEW iframe only — never the editor panel.
 *
 * Enqueuing our reset/tokens into the panel overrides Elementor's own UI
 * styling and makes dark-mode controls unreadable. See skill gotcha #4.
 */
function addlar_elementor_preview_assets() {
	wp_enqueue_style( 'addlar-tokens', ADDLAR_URI . '/assets/css/tokens.css', array(), ADDLAR_VERSION );
	wp_enqueue_style( 'addlar-widgets', ADDLAR_URI . '/assets/css/widgets.css', array( 'addlar-tokens' ), ADDLAR_VERSION );
	wp_enqueue_style( 'addlar-theme-css', ADDLAR_URI . '/assets/css/theme.css', array( 'addlar-widgets' ), ADDLAR_VERSION );
	wp_enqueue_style( 'addlar-style', get_stylesheet_uri(), array( 'addlar-theme-css' ), ADDLAR_VERSION );
}
add_action( 'elementor/preview/enqueue_styles', 'addlar_elementor_preview_assets' );

/**
 * The mockup uses a pure system font stack, so there is no webfont to load.
 * Kept as a documented no-op so nobody "helpfully" adds an empty @font-face
 * block later (skill gotcha #9).
 */

/* -------------------------------------------------------------------------
 * Modules
 * ---------------------------------------------------------------------- */

require_once ADDLAR_DIR . '/inc/icons.php';
require_once ADDLAR_DIR . '/inc/finder-data.php';
require_once ADDLAR_DIR . '/inc/products-cpt.php';
require_once ADDLAR_DIR . '/inc/products-render.php';
require_once ADDLAR_DIR . '/inc/products-data.php';
require_once ADDLAR_DIR . '/inc/contact-form.php';
require_once ADDLAR_DIR . '/inc/customizer.php';
require_once ADDLAR_DIR . '/inc/nav-walker.php';
require_once ADDLAR_DIR . '/inc/nav-defaults.php';
require_once ADDLAR_DIR . '/inc/elementor.php';
require_once ADDLAR_DIR . '/inc/updater.php';
require_once ADDLAR_DIR . '/inc/demo-import.php';

/* -------------------------------------------------------------------------
 * Elementor dependency notice
 * ---------------------------------------------------------------------- */

function addlar_dependency_notice() {
	if ( did_action( 'elementor/loaded' ) ) {
		return;
	}
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>ADDLAR theme:</strong> ';
	esc_html_e( 'Elementor is not active. The homepage sections are Elementor widgets and will not render until Elementor is installed and activated.', 'addlar' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'addlar_dependency_notice' );

/**
 * Scope class on <body> is not used — each widget and the header/footer emit
 * their own `.adl` wrapper so the ported CSS never leaks onto Elementor or
 * plugin markup.
 */
