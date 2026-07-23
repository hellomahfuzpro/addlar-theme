<?php
/**
 * Generic page.
 *
 * @package Addlar
 */
get_header();
while ( have_posts() ) {
	the_post();
	?>
	<div class="adl"><main class="section"><div class="wrap"><?php the_content(); ?></div></main></div>
	<?php
}
get_footer();
