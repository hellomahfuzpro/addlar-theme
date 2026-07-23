<?php
/**
 * Closing CTA — photographic band with red brand gradient and spec chips.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_ClosingCta extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_closing_cta';
	}

	public function get_title() {
		return __( 'ADDLAR Closing CTA', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'acea',
		) );

		$this->add_control( 'bg', array(
			'label'       => __( 'Background image', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'Sits at 34% opacity behind the red gradient, so a darker image works best.', 'addlar' ),
		) );

		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Choose wisely — choose ADDLAR', 'addlar' ),
		) );

		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'default' => __( 'Every ACEA spec. One ADDLAR partner.', 'addlar' ),
		) );

		$this->add_control( 'text', array(
			'label'   => __( 'Text', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => __( 'Full specification mapping — from your application to the right ADDLAR package, engineered across the full ACEA spectrum: C3, C4, C5, A3/B4, E7, E9, E11.', 'addlar' ),
		) );

		$this->add_control( 'btn_text', array(
			'label'   => __( 'Button', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Request specification mapping →', 'addlar' ),
		) );

		$this->add_control( 'btn_link', array(
			'label'   => __( 'Button link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '#contact' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'chips', array(
			'label' => __( 'Spec chips', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'name', array(
			'label'   => __( 'Package', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'ADDLAR 7375',
		) );
		$rep->add_control( 'spec', array(
			'label'   => __( 'Specification', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'ACEA C3/C4 · A3/B4 · A5/B5',
		) );

		$this->add_control( 'maps', array(
			'label'       => __( 'Chips', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ name }}}',
			'default'     => array(
				array( 'name' => 'ADDLAR 7375', 'spec' => 'ACEA C3/C4 · A3/B4 · A5/B5' ),
				array( 'name' => 'ADDLAR 7750', 'spec' => 'ACEA E11 · E9' ),
				array( 'name' => 'ADDLAR 7730', 'spec' => 'ACEA E7 · A3/B4 · E5 · E2' ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$bg = $this->media_url( isset( $s['bg'] ) ? $s['bg'] : array(), 'full' );

		$this->open_section( 'cta', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );

		if ( $bg ) {
			printf( '<img class="ctabg" src="%s" alt="">', esc_url( $bg ) );
		}
		?>
		<div class="wrap">
			<div class="reveal">
				<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
					<span class="eyebrow" style="color:#fff"><?php echo esc_html( $s['eyebrow'] ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $s['title'] ) ) : ?>
					<h2><?php echo wp_kses_post( $s['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $s['text'] ) ) : ?>
					<p><?php echo wp_kses_post( $s['text'] ); ?></p>
				<?php endif; ?>
				<?php $this->render_button( $s['btn_text'], $s['btn_link'], 'btn-white' ); ?>
			</div>

			<?php if ( ! empty( $s['maps'] ) ) : ?>
				<div class="maps reveal">
					<?php foreach ( $s['maps'] as $map ) : ?>
						<div class="map">
							<b><?php echo esc_html( $map['name'] ); ?></b>
							<span><?php echo esc_html( $map['spec'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		$this->close_section();
	}
}
