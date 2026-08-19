<?php
/**
 * Product spec header — two-column hero (spec text left, product image
 * right), matching the reference competitor pages the client asked to
 * match (Afton Chemical / Lubimax): a real product image up top, a short
 * spec subtitle, and a clear CTA — rather than a plain text-only header.
 *
 * Reads the current product's post meta directly at render time rather
 * than via Elementor Dynamic Tags: it's a real Addlar_Base_Widget, so it
 * gets the theme's `.adl` scope wrapper (see open_section()) the same way
 * every homepage widget does — Elementor's native widgets don't, which is
 * why the first cut of this template rendered unstyled.
 *
 * The image is the product's real LinkedIn campaign graphic where one
 * exists (7 of the 22 products), or its category's stock photo otherwise
 * (addlar_product_hero_image() in inc/demo-import.php resolves which, at
 * seed time, and sets it as the post's featured image) — every product
 * gets a real, on-brand photo, never a placeholder box.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_ProductSpecHeader extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_product_spec_header';
	}

	public function get_title() {
		return __( 'ADDLAR Product Spec Header', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-single-post';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'show_image', array(
			'label'        => __( 'Show featured image', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'btn_text', array(
			'label'   => __( 'Button text', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Request data sheet →', 'addlar' ),
		) );

		$this->add_control( 'btn_link', array(
			'label'   => __( 'Button link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '/contact-us/' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$post_id = get_the_ID();
		if ( ! $post_id || 'addlar_product' !== get_post_type( $post_id ) ) {
			// Editor preview outside a real product context — nothing sensible to show.
			return;
		}

		$s = $this->get_settings_for_display();

		$this->open_section( 'section', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );
		?>
		<div class="wrap">
			<div class="spec-hero-grid">

				<div class="spec-hero-text">
					<?php
					$terms   = get_the_terms( $post_id, 'addlar_product_category' );
					$cat     = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
					$sub     = get_post_meta( $post_id, '_addlar_subcategory', true );
					$eyebrow = trim( $cat . ( $sub ? ' · ' . $sub : '' ) );
					?>
					<?php if ( $eyebrow ) : ?>
						<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
					<?php endif; ?>

					<h1 class="title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>

					<?php $spec = get_post_meta( $post_id, '_addlar_spec_string', true ); ?>
					<?php if ( $spec ) : ?>
						<p class="lead"><?php echo wp_kses_post( $spec ); ?></p>
					<?php endif; ?>

					<?php $this->render_button( $s['btn_text'], $s['btn_link'], 'btn-red' ); ?>

					<?php $doc = get_post_meta( $post_id, '_addlar_doc_code', true ); ?>
					<?php if ( $doc ) : ?>
						<p class="spec-table-note spec-doc-code"><?php echo esc_html( $doc ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( 'yes' === $s['show_image'] && has_post_thumbnail( $post_id ) ) : ?>
					<div class="spec-hero-image">
						<?php echo get_the_post_thumbnail( $post_id, 'large' ); ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
		<?php
		$this->close_section();
	}
}
