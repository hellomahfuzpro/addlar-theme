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
	<main class="section">
		<div class="wrap center">
			<span class="eyebrow"><?php esc_html_e( 'Product Category', 'addlar' ); ?></span>
			<h1 class="title"><?php echo esc_html( $term->name ); ?></h1>
			<?php if ( $term->description ) : ?>
				<p class="lead"><?php echo wp_kses_post( $term->description ); ?></p>
			<?php endif; ?>
		</div>

		<div class="wrap">
			<?php if ( have_posts() ) : ?>
				<div class="prod-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						$code = get_post_meta( get_the_ID(), '_addlar_code', true );
						$sub  = get_post_meta( get_the_ID(), '_addlar_subcategory', true );
						?>
						<a class="pcard reveal" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="imgwrap"><?php the_post_thumbnail( 'medium' ); ?></div>
							<?php endif; ?>
							<div class="body">
								<?php if ( $sub ) : ?>
									<span class="cat"><?php echo esc_html( $sub ); ?></span>
								<?php endif; ?>
								<h3><?php the_title(); ?></h3>
								<?php if ( $code ) : ?>
									<div class="sub"><?php echo esc_html( $code ); ?></div>
								<?php endif; ?>
								<div class="foot"><span class="arw">&rarr;</span></div>
							</div>
						</a>
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
