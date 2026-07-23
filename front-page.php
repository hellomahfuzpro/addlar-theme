<?php
/**
 * Front page — Elementor outputs the full section markup via the_content().
 *
 * @package Addlar
 */
get_header();
while ( have_posts() ) {
	the_post();
	the_content();
}
get_footer();
