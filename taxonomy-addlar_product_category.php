<?php
/**
 * Product category archive — coded, not Theme Builder (see Phase 2 plan
 * §"Category archives"): the client's Theme Builder choice was specifically
 * for the single-product page, and native WP taxonomy archive URLs need no
 * extra templating mechanism here. Reuses the same `.pcard` grid markup/CSS
 * already shipped for the homepage's Product Grid widget.
 *
 * @package Addlar
 */

get_header();

$term = get_queried_object();
?>
<div class="adl">
	<?php // Matches the dark archive hero the Elementor template uses, so the
	// coded fallback isn't visibly a different design. `.prod-hero` also
	// carries the offset that clears the fixed site header. ?>
	<section class="prod-hero">
		<div class="prod-hero-wedge" aria-hidden="true"></div>
		<div class="wrap prod-hero-crumbs">
			<nav class="crumbs crumbs-dark crumbs-bare" aria-label="<?php esc_attr_e( 'Breadcrumb', 'addlar' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'addlar' ); ?></a>
				<span class="sep" aria-hidden="true">&rsaquo;</span>
				<a href="<?php echo esc_url( home_url( '/products/' ) ); ?>"><?php esc_html_e( 'Products', 'addlar' ); ?></a>
				<span class="sep" aria-hidden="true">&rsaquo;</span>
				<span class="cur"><?php echo esc_html( $term->name ); ?></span>
			</nav>
		</div>
		<div class="wrap prod-hero-grid">
			<div class="prod-hero-copy">
				<span class="eyebrow"><?php esc_html_e( 'Product Category', 'addlar' ); ?></span>
				<h1><?php echo esc_html( $term->name ); ?></h1>
				<?php if ( $term->description ) : ?>
					<p class="lead"><?php echo wp_kses_post( $term->description ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<main class="section">

		<div class="wrap">
			<?php if ( have_posts() ) : ?>
				<div class="prod-grid prod-grid-clean">
					<?php
					while ( have_posts() ) :
						the_post();
						$code = get_post_meta( get_the_ID(), '_addlar_code', true );
						$sub  = get_post_meta( get_the_ID(), '_addlar_subcategory', true );
						?>
						<div class="pcard pcard-clean reveal">
							<div class="body">
								<div class="pcard-top">
									<?php if ( $sub ) : ?>
										<span class="cat"><?php echo esc_html( $sub ); ?></span>
									<?php endif; ?>
									<?php if ( $code ) : ?>
										<span class="pcard-code-badge"><?php echo esc_html( $code ); ?></span>
									<?php endif; ?>
								</div>
								<h3><a href="<?php the_permalink(); ?>" class="pcard-title-link"><?php the_title(); ?></a></h3>
								<div class="foot pcard-foot">
									<a href="<?php the_permalink(); ?>" class="pcard-details-link"><?php esc_html_e( 'View Details →', 'addlar' ); ?></a>
									<button type="button" class="btn-tds-action btn-tds-trigger" data-tds-product="<?php the_title_attribute(); ?>">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
										<span><?php esc_html_e( 'Request Data Sheet', 'addlar' ); ?></span>
									</button>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					?>
				</div>
			<?php else : ?>
				<p class="lead"><?php esc_html_e( 'No products in this category yet.', 'addlar' ); ?></p>
			<?php endif; ?>
		</div>
	</main>
</div>
<?php
get_footer();
