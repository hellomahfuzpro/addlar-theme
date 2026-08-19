<?php
/**
 * Breadcrumb trail — Home / Products / {Category} / {This page}.
 *
 * Builds the trail from the current post's own taxonomy term at render
 * time (so a product filed under a different category later needs no
 * edit), with every label overridable in Elementor for pages that aren't
 * products.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_Breadcrumb extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_breadcrumb';
	}

	public function get_title() {
		return __( 'ADDLAR Breadcrumb', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_control( 'home_label', array(
			'label'   => __( 'Home label', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Home', 'addlar' ),
		) );

		$this->add_control( 'products_label', array(
			'label'   => __( 'Products label', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Products', 'addlar' ),
		) );

		$this->add_control( 'products_url', array(
			'label'   => __( 'Products link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '/products/' ),
		) );

		$this->add_control( 'current_label', array(
			'label'       => __( 'Current page label', 'addlar' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'description' => __( 'Leave blank to use this page\'s own title.', 'addlar' ),
		) );

		$this->add_control( 'dark', array(
			'label'        => __( 'Light text (for a dark background)', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '',
			'return_value' => 'yes',
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = array();

		$items[] = array( 'label' => $s['home_label'], 'url' => home_url( '/' ) );

		if ( ! empty( $s['products_label'] ) ) {
			$items[] = array(
				'label' => $s['products_label'],
				'url'   => ! empty( $s['products_url']['url'] ) ? $s['products_url']['url'] : '',
			);
		}

		$current = $s['current_label'];

		if ( is_tax() || is_category() || is_tag() ) {
			// On an archive get_the_ID() is the first post in the loop, not the
			// thing being listed — the term itself is the current crumb.
			$term = get_queried_object();
			if ( '' === $current && $term && ! empty( $term->name ) ) {
				$current = $term->name;
			}
		} else {
			$post_id = get_the_ID();

			if ( $post_id && 'addlar_product' === get_post_type( $post_id ) ) {
				$terms = get_the_terms( $post_id, 'addlar_product_category' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$link    = get_term_link( $terms[0] );
					$items[] = array(
						'label' => $terms[0]->name,
						'url'   => is_wp_error( $link ) ? '' : $link,
					);
				}
			}

			if ( '' === $current && $post_id ) {
				$current = get_the_title( $post_id );
			}
		}

		if ( $current ) {
			$items[] = array( 'label' => $current, 'url' => '' );
		}

		$classes = 'crumbs' . ( 'yes' === $s['dark'] ? ' crumbs-dark' : '' );
		?>
		<div class="adl">
			<nav class="<?php echo esc_attr( $classes ); ?>" aria-label="<?php esc_attr_e( 'Breadcrumb', 'addlar' ); ?>">
				<div class="wrap">
					<?php foreach ( $items as $i => $item ) : ?>
						<?php if ( $i > 0 ) : ?>
							<span class="sep" aria-hidden="true">&rsaquo;</span>
						<?php endif; ?>
						<?php if ( ! empty( $item['url'] ) && $i < count( $items ) - 1 ) : ?>
							<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
						<?php else : ?>
							<span class="cur"><?php echo esc_html( $item['label'] ); ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</nav>
		</div>
		<?php
	}
}
