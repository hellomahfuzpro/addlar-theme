<?php
/**
 * Black CTA bar — icon square, title, one supporting line, red button.
 *
 * The homepage's LinkedIn follow bar (`.li-follow`, from the Insights
 * section) reused standalone, so a page can close on a compact dark
 * call-to-action instead of the full photographic Closing CTA band.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_CtaBar extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_cta_bar';
	}

	public function get_title() {
		return __( 'ADDLAR CTA Bar', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
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

		$this->add_control( 'icon', array(
			'label'   => __( 'Icon', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array_merge( array( 'linkedin' => __( 'LinkedIn', 'addlar' ) ), addlar_icon_choices() ),
			'default' => 'flask',
		) );

		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'text', array(
			'label'   => __( 'Supporting line', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'btn_text', array(
			'label'   => __( 'Button', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Get in touch →', 'addlar' ),
		) );

		$this->add_control( 'btn_link', array(
			'label'   => __( 'Button link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '/contact-us/' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['title'] ) ) {
			return;
		}

		$this->open_section( 'section section-tight', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );
		?>
		<div class="wrap">
			<div class="li-follow reveal" style="margin-top:0">
				<div class="lf-l">
					<div class="lf-ic">
						<?php
						if ( 'linkedin' === $s['icon'] ) {
							addlar_icon_linkedin();
						} else {
							$this->render_icon( $s['icon'], 'width="26" height="26" fill="none" stroke="#fff" stroke-width="1.7"' );
						}
						?>
					</div>
					<div>
						<h3><?php echo esc_html( $s['title'] ); ?></h3>
						<?php if ( ! empty( $s['text'] ) ) : ?>
							<p><?php echo esc_html( $s['text'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<?php $this->render_button( $s['btn_text'], $s['btn_link'], 'btn-red' ); ?>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
