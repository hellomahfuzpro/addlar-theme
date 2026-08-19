<?php
/**
 * "Key Performance Benefits" — icon capability cards, reusing the exact
 * `.about-feats`/`.afeat` markup and CSS the homepage's About section
 * already uses for its 4 capability cards. A first version of this widget
 * used a plain bordered checklist; client feedback was that the product
 * page needed to look as considered as the homepage, not a flatter
 * approximation of it — reusing the homepage's own component directly is
 * what actually closes that gap (same CSS, so guaranteed visual parity).
 *
 * Bullets come from addlar_product_benefit_bullets() (inc/products-render.php),
 * which derives every line — and its icon — from data already transcribed
 * for this product: a real application, the real spec string, a real
 * count of approvals or performance levels, never an invented claim. The
 * icon is a direct 1:1 mapping from which data field a bullet came from
 * (applications → gear, spec string → shield, approvals count → globe,
 * performance-level count → layers, viscosity → viscosity), not a guess.
 *
 * Renders nothing if the product has no derivable bullets at all (won't
 * happen for any of the 22 real products, but kept graceful rather than
 * assumed).
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_ProductBenefits extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_product_benefits';
	}

	public function get_title() {
		return __( 'ADDLAR Product Benefits', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-check-circle-o';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_heading_controls(
			__( 'Key Performance Benefits', 'addlar' ),
			'',
			''
		);

		$this->end_controls_section();
	}

	protected function render() {
		$post_id = get_the_ID();
		if ( ! $post_id || 'addlar_product' !== get_post_type( $post_id ) ) {
			return;
		}

		$bullets = addlar_product_benefit_bullets( $post_id );
		if ( ! $bullets ) {
			return;
		}

		$s = $this->get_settings_for_display();

		$this->open_section( 'section', '' );
		?>
		<div class="wrap center">
			<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
		</div>
		<div class="wrap">
			<div class="about-feats">
				<?php foreach ( $bullets as $bullet ) : ?>
					<div class="afeat reveal">
						<div class="ic"><?php $this->render_icon( $bullet['icon'], 'width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"' ); ?></div>
						<h4><?php echo esc_html( $bullet['text'] ); ?></h4>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
