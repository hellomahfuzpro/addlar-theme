<?php
/**
 * Archive and fallback template.
 *
 * Handles category, tag, author, date and search results — anything without
 * a more specific template. The blog listing itself is not here: it is a
 * normal Elementor page using the Post Grid widget (see
 * addlar_seed_blog_page()), so it can be edited like every other page.
 *
 * Uses the same dark hero and `.licard` grid as the rest of the site so an
 * archive never looks like a different theme.
 *
 * @package Addlar
 */

get_header();

// A context-aware heading, so a category archive says which category.
if ( is_search() ) {
	$eyebrow = __( 'Search results', 'addlar' );
	/* translators: %s: search term */
	$heading = sprintf( __( 'Results for “%s”', 'addlar' ), get_search_query() );
} elseif ( is_category() || is_tag() || is_tax() ) {
	$eyebrow = __( 'Archive', 'addlar' );
	$heading = single_term_title( '', false );
} elseif ( is_author() ) {
	$eyebrow = __( 'Author', 'addlar' );
	$heading = get_the_author();
} elseif ( is_date() ) {
	$eyebrow = __( 'Archive', 'addlar' );
	$heading = get_the_archive_title();
} else {
	$eyebrow = __( 'From the ADDLAR desk', 'addlar' );
	$heading = __( 'Insights', 'addlar' );
}

$blog_url = function_exists( 'addlar_nav_blog_url' ) ? addlar_nav_blog_url() : home_url( '/' );
?>
<div class="adl">

	<section class="prod-hero">
		<div class="prod-hero-wedge" aria-hidden="true"></div>
		<div class="wrap prod-hero-crumbs">
			<nav class="crumbs crumbs-dark crumbs-bare" aria-label="<?php esc_attr_e( 'Breadcrumb', 'addlar' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'addlar' ); ?></a>
				<span class="sep" aria-hidden="true">&rsaquo;</span>
				<a href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Insights', 'addlar' ); ?></a>
				<span class="sep" aria-hidden="true">&rsaquo;</span>
				<span class="cur"><?php echo esc_html( wp_strip_all_tags( $heading ) ); ?></span>
			</nav>
		</div>
		<div class="wrap prod-hero-grid">
			<div class="prod-hero-copy">
				<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<h1><?php echo esc_html( wp_strip_all_tags( $heading ) ); ?></h1>
				<?php $desc = term_description(); ?>
				<?php if ( $desc ) : ?>
					<p class="lead"><?php echo wp_kses_post( $desc ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<main class="section">
		<div class="wrap">
			<?php if ( have_posts() ) : ?>
				<div class="li-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						$cats = get_the_category();
						?>
						<a class="licard reveal" href="<?php the_permalink(); ?>">
							<div class="imgwrap">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large' ); ?>
								<?php endif; ?>
							</div>
							<div class="body">
								<span class="kind"><?php echo esc_html( $cats ? $cats[0]->name : get_the_date() ); ?></span>
								<h3><?php the_title(); ?></h3>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32 ) ); ?></p>
								<div class="go">
									<span><?php esc_html_e( 'Read article', 'addlar' ); ?></span>
									<span class="arw">&rarr;</span>
								</div>
							</div>
						</a>
						<?php
					endwhile;
					?>
				</div>

				<?php
				the_posts_pagination( array(
					'mid_size'  => 1,
					'prev_text' => __( '&larr; Newer', 'addlar' ),
					'next_text' => __( 'Older &rarr;', 'addlar' ),
				) );
				?>

			<?php else : ?>
				<p class="band-note band-empty">
					<?php esc_html_e( 'Nothing found here. Try the Insights page for our latest articles.', 'addlar' ); ?>
				</p>
			<?php endif; ?>
		</div>
	</main>

</div>
<?php
get_footer();
