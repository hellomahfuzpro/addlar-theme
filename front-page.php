<?php
/**
 * Front page.
 *
 * WordPress ignores a page's assigned template on the front page and always
 * loads this file, so honour the Coming Soon template here — otherwise setting
 * the homepage to it would have no effect. Any other case falls through to
 * Elementor, which outputs the full section markup via the_content().
 *
 * @package Addlar
 */

$addlar_front_id = (int) get_option( 'page_on_front' );
$addlar_template = $addlar_front_id ? get_page_template_slug( $addlar_front_id ) : '';

if ( 'templates/coming-soon.php' === $addlar_template ) {
	$addlar_cs = locate_template( 'templates/coming-soon.php' );
	if ( $addlar_cs ) {
		require $addlar_cs;
		return;
	}
}

get_header();
while ( have_posts() ) {
	the_post();
	the_content();
}
get_footer();
