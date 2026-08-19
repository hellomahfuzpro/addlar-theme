<?php
/**
 * Single product.
 *
 * Product content is authored in the page's own Elementor widgets, so this
 * template just renders the content — Elementor filters `the_content()` to
 * output its widget tree. There is no meta-reading fallback any more (the
 * custom-field pipeline it used to mirror was removed); if Elementor is
 * deactivated, a product shows its title and whatever plain content exists,
 * exactly like any other page.
 *
 * @package Addlar
 */

get_header();

while ( have_posts() ) {
	the_post();
	?>
	<div class="adl">
		<main class="section">
			<div class="wrap">
				<?php the_content(); ?>
			</div>
		</main>
	</div>
	<?php
}

get_footer();
