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
			'label' => __( 'Items', 'addlar' ),
		) );

		$this->add_control( 'lead_label', array(
			'label'   => __( 'Ribbon prefix', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Meets the following specifications:', 'addlar' ),
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
			'default' => __( 'Specifications', 'addlar' ),
		) );

		$this->add_control( 'items', array(
			'label'       => __( 'Items', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ strong }}} {{{ text }}}',
			'default'     => array(
				array( 'strong' => 'API', 'text' => __( 'Specifications', 'addlar' ) ),
				array( 'strong' => 'ACEA', 'text' => __( 'Full Spectrum', 'addlar' ) ),
				array( 'strong' => 'ILSAC', 'text' => 'GF-5 / GF-6' ),
				array( 'strong' => 'JASO', 'text' => 'MA / MA2' ),
				array( 'strong' => 'UAE', 'text' => __( 'Manufactured', 'addlar' ) ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['items'] ) && empty( $s['lead_label'] ) ) {
			return;
		}
		// This band is a plain div in the mockup, not a .section.
		echo '<div class="adl"><div class="trust"><div class="wrap trust-bar">';
		if ( ! empty( $s['lead_label'] ) ) {
			printf( '<div class="trust-prefix">%s</div>', esc_html( $s['lead_label'] ) );
		}
		echo '<div class="trust-items">';
		if ( ! empty( $s['items'] ) ) {
			foreach ( $s['items'] as $item ) {
				printf(
					'<div class="trust-pill"><b>%1$s</b>&nbsp;%2$s</div>',
					esc_html( $item['strong'] ),
					esc_html( $item['text'] )
				);
			}
		}
		echo '</div>';
		echo '</div></div></div>';
	}
}
