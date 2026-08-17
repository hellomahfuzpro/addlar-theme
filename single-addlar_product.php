<?php
/**
 * Coded fallback for a single product — only renders if Elementor Pro's
 * Theme Builder single-product template (seeded by
 * addlar_seed_product_theme_builder_template() in inc/demo-import.php)
 * isn't active. Mirrors the same content/order as that template, using the
 * same pre-rendered `_addlar_*_html` fragments (inc/products-render.php)
 * so the two never show materially different data.
 *
 * @package Addlar
 */

get_header();

while ( have_posts() ) {
	the_post();
	$post_id = get_the_ID();

	$get = function ( $key ) use ( $post_id ) {
		return get_post_meta( $post_id, $key, true );
	};

	$terms = get_the_terms( $post_id, 'addlar_product_category' );
	$term  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
	?>
	<div class="adl">
		<main class="section">
			<div class="wrap" style="max-width:860px;">

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="imgwrap" style="margin-bottom:24px;border-radius:var(--adl-radius,12px);overflow:hidden;">
						<?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;display:block;' ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $term ) : ?>
					<span class="eyebrow"><?php echo esc_html( $term->name ); ?><?php echo $get( '_addlar_subcategory' ) ? ' · ' . esc_html( $get( '_addlar_subcategory' ) ) : ''; ?></span>
				<?php endif; ?>

				<h1 class="title"><?php the_title(); ?></h1>

				<?php if ( $get( '_addlar_spec_string' ) ) : ?>
					<p class="lead"><?php echo wp_kses_post( $get( '_addlar_spec_string' ) ); ?></p>
				<?php endif; ?>

				<?php if ( $get( '_addlar_description' ) ) : ?>
					<div class="spec-description"><?php echo wp_kses_post( wpautop( $get( '_addlar_description' ) ) ); ?></div>
				<?php endif; ?>

				<?php echo wp_kses_post( $get( '_addlar_applications_html' ) ); ?>
				<?php echo wp_kses_post( $get( '_addlar_performance_table_html' ) ); ?>
				<?php echo wp_kses_post( $get( '_addlar_approvals_html' ) ); ?>
				<?php echo wp_kses_post( $get( '_addlar_formulation_html' ) ); ?>
				<?php echo wp_kses_post( $get( '_addlar_properties_table_html' ) ); ?>

				<?php if ( $get( '_addlar_viscosity_note' ) ) : ?>
					<h3><?php esc_html_e( 'Viscosity Grades', 'addlar' ); ?></h3>
					<div class="spec-description"><?php echo wp_kses_post( wpautop( $get( '_addlar_viscosity_note' ) ) ); ?></div>
				<?php endif; ?>

				<?php if ( $get( '_addlar_doc_code' ) ) : ?>
					<p class="spec-table-note"><?php echo esc_html( $get( '_addlar_doc_code' ) ); ?></p>
				<?php endif; ?>

				<?php
				if ( $term ) :
					$related = new WP_Query( array(
						'post_type'      => 'addlar_product',
						'posts_per_page' => 3,
						'post__not_in'   => array( $post_id ),
						'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery -- single product page, one small related query.
							array(
								'taxonomy' => 'addlar_product_category',
								'field'    => 'term_id',
								'terms'    => $term->term_id,
							),
						),
					) );
					if ( $related->have_posts() ) :
						?>
						<h3><?php esc_html_e( 'Related Products', 'addlar' ); ?></h3>
						<div class="prod-grid">
							<?php
							while ( $related->have_posts() ) :
								$related->the_post();
								?>
								<a class="pcard reveal" href="<?php the_permalink(); ?>">
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="imgwrap"><?php the_post_thumbnail( 'medium' ); ?></div>
									<?php endif; ?>
									<div class="body">
										<span class="cat"><?php echo esc_html( $term->name ); ?></span>
										<h3><?php the_title(); ?></h3>
										<div class="foot"><span class="arw">&rarr;</span></div>
									</div>
								</a>
								<?php
							endwhile;
							?>
						</div>
						<?php
					endif;
					wp_reset_postdata();
				endif;
				?>

				<div style="text-align:center;margin-top:40px;">
					<a class="btn btn-red" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Talk to us →', 'addlar' ); ?></a>
				</div>

			</div>
		</main>
	</div>
	<?php
}

get_footer();
