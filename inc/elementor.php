<?php
/**
 * Elementor integration: panel category + glob widget autoloader.
 *
 * Adding a section is just dropping widgets/class-{slug}.php in place — the
 * autoloader derives the class name from the filename, so the mapping must be
 * exact (skill gotcha #7): kebab-case file -> Addlar_Widget_StudlyCase.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function addlar_elementor_category( $elements_manager ) {
	$elements_manager->add_category( 'addlar', array(
		'title' => __( 'ADDLAR', 'addlar' ),
		'icon'  => 'fa fa-tint',
	) );
}
add_action( 'elementor/elements/categories_registered', 'addlar_elementor_category' );

/**
 * Map widgets/class-foo-bar.php -> Addlar_Widget_FooBar.
 *
 * @param string $file Absolute path.
 * @return string
 */
function addlar_widget_class_for_file( $file ) {
	$slug = substr( basename( $file, '.php' ), strlen( 'class-' ) );
	return 'Addlar_Widget_' . str_replace( ' ', '', ucwords( str_replace( '-', ' ', $slug ) ) );
}

function addlar_register_widgets( $widgets_manager ) {
	$dir = ADDLAR_DIR . '/widgets/';

	require_once $dir . 'class-base-widget.php';

	$files = glob( $dir . 'class-*.php' );
	if ( ! $files ) {
		return;
	}

	foreach ( $files as $file ) {
		if ( false !== strpos( $file, 'class-base-widget.php' ) ) {
			continue;
		}
		require_once $file;
	}

	foreach ( $files as $file ) {
		if ( false !== strpos( $file, 'class-base-widget.php' ) ) {
			continue;
		}
		$class = addlar_widget_class_for_file( $file );
		if ( class_exists( $class ) ) {
			$widgets_manager->register( new $class() );
		}
	}
}
add_action( 'elementor/widgets/register', 'addlar_register_widgets' );
