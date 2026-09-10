<?php
/**
 * Trust strip — the certification band under the hero.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_TrustStrip extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_trust_strip';
	}

	public function get_title() {
		return __( 'ADDLAR Trust Strip', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-menu-bar';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array(
			'label' => __( 'Specifications Ribbon', 'addlar' ),
		) );

		$this->add_control( 'heading', array(
			'label'   => __( 'Ribbon Heading', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Meets the following specifications', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'strong', array(
			'label'   => __( 'Bold lead', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'API',
		) );
		$rep->add_control( 'text', array(
			'label'   => __( 'Text', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Licensed Standards', 'addlar' ),
		) );

		$this->add_control( 'items', array(
			'label'       => __( 'Items', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ strong }}} {{{ text }}}',
			'default'     => array(
				array( 'strong' => 'API', 'text' => __( 'Licensed Standards', 'addlar' ) ),
				array( 'strong' => 'ACEA', 'text' => __( 'Full Spectrum Performance', 'addlar' ) ),
				array( 'strong' => 'ILSAC', 'text' => 'GF-5 / GF-6' ),
				array( 'strong' => 'JASO', 'text' => 'MA / MA2' ),
				array( 'strong' => 'UAE', 'text' => __( 'Manufactured', 'addlar' ) ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['items'] ) ) {
			return;
		}
		?>
		<div class="adl">
			<div class="trust trust-spec-ribbon">
				<?php if ( ! empty( $s['heading'] ) ) : ?>
					<div class="wrap center">
						<span class="eyebrow"><?php echo esc_html( $s['heading'] ); ?></span>
					</div>
				<?php endif; ?>
				<div class="wrap">
					<?php foreach ( $s['items'] as $item ) : ?>
						<div class="item">
							<b><?php echo esc_html( $item['strong'] ); ?></b>
							<span><?php echo esc_html( $item['text'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
