<?php
/**
 * "Key Performance Benefits" box — the bordered checklist that sits near
 * the top of the reference competitor pages the client asked to match
 * (Afton Chemical's product pages). Bullets come from
 * addlar_product_benefit_bullets() (inc/products-render.php), which derives
 * every line from data already transcribed for this product — a real
 * application, the real spec string, a real count of approvals or
 * performance levels — never an invented claim.
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

		$this->add_control( 'heading', array(
			'label'   => __( 'Heading', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Key Performance Benefits', 'addlar' ),
		) );

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
		<div class="wrap">
			<div class="benefits-box">
				<?php if ( ! empty( $s['heading'] ) ) : ?>
					<h2 class="benefits-heading"><?php echo esc_html( $s['heading'] ); ?></h2>
				<?php endif; ?>
				<ul class="benefits-list">
					<?php foreach ( $bullets as $bullet ) : ?>
						<li><span class="check" aria-hidden="true">&#10003;</span><?php echo esc_html( $bullet ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
