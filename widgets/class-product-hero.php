<?php
/**
 * Product hero — dark ground, red diagonal wedge, product image on the
 * right, spec chips under the headline.
 *
 * Deliberately built to the same visual weight as the homepage hero
 * (Addlar_Widget_Hero): full-bleed dark section, oversized headline, red
 * accent geometry, chips instead of a plain paragraph. The earlier
 * white-background product header read as a generic content page next to
 * the homepage, which is exactly the gap the client flagged.
 *
 * Every value is a normal Elementor control — nothing is read from post
 * meta, so the client edits a product page's headline, spec chips and
 * image directly on the canvas.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_ProductHero extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_product_hero';
	}

	public function get_title() {
		return __( 'ADDLAR Product Hero', 'addlar' );
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
			'default' => 'top',
		) );

		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'title', array(
			'label'   => __( 'Product name', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'subtitle', array(
			'label'   => __( 'Subtitle', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'default' => '',
		) );

		$this->add_control( 'doc_code', array(
			'label'   => __( 'Document code', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'image', array(
			'label' => __( 'Product image', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
		) );

		$this->add_control( 'mark', array(
			'label' => __( 'Corner mark', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
		) );

		$this->add_control( 'show_crumbs', array(
			'label'        => __( 'Show breadcrumb', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'description'  => __( 'Rendered inside the hero so it always clears the fixed header.', 'addlar' ),
		) );

		$this->add_control( 'crumb_parent', array(
			'label'   => __( 'Breadcrumb parent label', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Products', 'addlar' ),
		) );

		$this->add_control( 'crumb_parent_link', array(
			'label'   => __( 'Breadcrumb parent link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '/products/' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'chips_section', array(
			'label' => __( 'Specification chips', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'text', array( 'label' => __( 'Chip', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );

		$this->add_control( 'chips', array(
			'label'       => __( 'Chips', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => array(),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'cta_section', array(
			'label' => __( 'Buttons', 'addlar' ),
		) );

		$this->add_control( 'btn1_text', array( 'label' => __( 'Primary button', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Request data sheet →', 'addlar' ) ) );
		$this->add_control( 'btn1_link', array( 'label' => __( 'Primary link', 'addlar' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '/contact-us/' ) ) );
		$this->add_control( 'btn2_text', array( 'label' => __( 'Secondary button', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Talk to a formulator', 'addlar' ) ) );
		$this->add_control( 'btn2_link', array( 'label' => __( 'Secondary link', 'addlar' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '/ask-the-expert/' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$img  = $this->media_url( isset( $s['image'] ) ? $s['image'] : array(), 'full' );
		$mark = $this->media_url( isset( $s['mark'] ) ? $s['mark'] : array(), 'full' );

		printf(
			'<div class="adl"><section class="prod-hero"%s>',
			! empty( $s['anchor'] ) ? ' id="' . esc_attr( $s['anchor'] ) . '"' : ''
		);
		?>
		<div class="prod-hero-wedge" aria-hidden="true"></div>

		<?php if ( 'yes' === $s['show_crumbs'] ) : ?>
			<?php
			$post_id   = get_the_ID();
			$crumbs    = array( array( 'label' => __( 'Home', 'addlar' ), 'url' => home_url( '/' ) ) );
			$parent_url = ! empty( $s['crumb_parent_link']['url'] ) ? $s['crumb_parent_link']['url'] : '';
			if ( ! empty( $s['crumb_parent'] ) ) {
				$crumbs[] = array( 'label' => $s['crumb_parent'], 'url' => $parent_url );
			}
			if ( $post_id && 'addlar_product' === get_post_type( $post_id ) ) {
				$terms = get_the_terms( $post_id, 'addlar_product_category' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$tlink    = get_term_link( $terms[0] );
					$crumbs[] = array( 'label' => $terms[0]->name, 'url' => is_wp_error( $tlink ) ? '' : $tlink );
				}
			}
			$crumbs[] = array( 'label' => $s['title'], 'url' => '' );
			?>
			<div class="wrap prod-hero-crumbs">
				<nav class="crumbs crumbs-dark crumbs-bare" aria-label="<?php esc_attr_e( 'Breadcrumb', 'addlar' ); ?>">
					<?php foreach ( $crumbs as $i => $crumb ) : ?>
						<?php if ( $i > 0 ) : ?><span class="sep" aria-hidden="true">&rsaquo;</span><?php endif; ?>
						<?php if ( ! empty( $crumb['url'] ) && $i < count( $crumbs ) - 1 ) : ?>
							<a href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a>
						<?php else : ?>
							<span class="cur"><?php echo esc_html( $crumb['label'] ); ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</nav>
			</div>
		<?php endif; ?>

		<div class="wrap prod-hero-grid">

			<div class="prod-hero-copy">
				<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
					<span class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $s['title'] ) ) : ?>
					<h1><?php echo esc_html( $s['title'] ); ?></h1>
				<?php endif; ?>

				<?php if ( ! empty( $s['subtitle'] ) ) : ?>
					<p class="lead"><?php echo wp_kses_post( $s['subtitle'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $s['chips'] ) ) : ?>
					<div class="prod-hero-chips">
						<?php foreach ( (array) $s['chips'] as $chip ) : ?>
							<?php if ( ! empty( $chip['text'] ) ) : ?>
								<span class="hchip"><?php echo esc_html( $chip['text'] ); ?></span>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="prod-hero-btns">
					<?php
					$this->render_button( $s['btn1_text'], $s['btn1_link'], 'btn-red' );
					$this->render_button( $s['btn2_text'], $s['btn2_link'], 'btn-white' );
					?>
				</div>

				<?php if ( ! empty( $s['doc_code'] ) ) : ?>
					<div class="prod-hero-doc"><?php echo esc_html( $s['doc_code'] ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( $img ) : ?>
				<div class="prod-hero-media reveal">
					<img class="prod-hero-img" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>">
					<?php if ( $mark ) : ?>
						<img class="cmark" src="<?php echo esc_url( $mark ); ?>" alt="">
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>
		<?php
		$this->close_section();
	}
}
