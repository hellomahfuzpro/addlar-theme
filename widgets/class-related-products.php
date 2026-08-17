<?php
/**
 * Related/archive product grid — replaces Elementor Pro's native "Posts"
 * widget in the seeded templates. The native widget's default skin (title +
 * date + comment count + "Read More »") doesn't match this theme's design
 * at all and isn't styled by any of our CSS, since it never gets the
 * `.adl` scope wrapper. This widget renders the exact `.pcard`/`.prod-grid`
 * markup already shipped for the homepage's Product Grid, so it's styled
 * for free and looks the same everywhere products are listed.
 *
 * Two modes: "current" (default — same-category products, excluding the
 * product being viewed; for the single-product template) and "archive"
 * (the current taxonomy archive's own query; for the category archive
 * template).
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_RelatedProducts extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_related_products';
	}

	public function get_title() {
		return __( 'ADDLAR Related Products', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_control( 'mode', array(
			'label'   => __( 'Query', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'current' => __( 'Related to current product (same category, excludes current)', 'addlar' ),
				'archive' => __( 'Current taxonomy archive', 'addlar' ),
			),
			'default' => 'current',
		) );

		$this->add_control( 'heading', array(
			'label'   => __( 'Heading', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Related Products', 'addlar' ),
		) );

		$this->add_control( 'count', array(
			'label'   => __( 'Number of products', 'addlar' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 6,
			'min'     => 1,
			'max'     => 24,
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$mode  = ! empty( $s['mode'] ) ? $s['mode'] : 'current';
		$count = ! empty( $s['count'] ) ? (int) $s['count'] : 6;

		$args = array(
			'post_type'      => 'addlar_product',
			'posts_per_page' => $count,
			'no_found_rows'  => true,
		);

		if ( 'archive' === $mode ) {
			$term = get_queried_object();
			if ( ! $term || empty( $term->term_id ) ) {
				return;
			}
			$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery -- one small archive-grid query per page load.
				array(
					'taxonomy' => $term->taxonomy,
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				),
			);
		} else {
			$post_id = get_the_ID();
			if ( ! $post_id || 'addlar_product' !== get_post_type( $post_id ) ) {
				return;
			}
			$terms = get_the_terms( $post_id, 'addlar_product_category' );
			if ( ! $terms || is_wp_error( $terms ) ) {
				return;
			}
			$args['tax_query']    = array( // phpcs:ignore WordPress.DB.SlowDBQuery -- one small related-grid query per page load.
				array(
					'taxonomy' => 'addlar_product_category',
					'field'    => 'term_id',
					'terms'    => $terms[0]->term_id,
				),
			);
			$args['post__not_in'] = array( $post_id );
		}

		$query = new WP_Query( $args );
		if ( ! $query->have_posts() ) {
			return;
		}

		$this->open_section( 'section soft', '' );
		?>
		<div class="wrap">
			<?php if ( ! empty( $s['heading'] ) ) : ?>
				<h2 class="title"><?php echo esc_html( $s['heading'] ); ?></h2>
			<?php endif; ?>
			<div class="prod-grid">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					$sub = get_post_meta( get_the_ID(), '_addlar_subcategory', true );
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
							<div class="foot"><span class="arw">&rarr;</span></div>
						</div>
					</a>
					<?php
				endwhile;
				?>
			</div>
		</div>
		<?php
		$this->close_section();
		wp_reset_postdata();
	}
}
