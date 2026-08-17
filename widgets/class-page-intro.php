<?php
/**
 * Minimal eyebrow / title / lede / optional button intro — used for the
 * stub pages (About Us, Contact Us, Ask the Expert) and, with "use current
 * archive term" on, as the intro block for the category archive Theme
 * Builder template (pulls the term name/description at render time instead
 * of a static value).
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_PageIntro extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_page_intro';
	}

	public function get_title() {
		return __( 'ADDLAR Page Intro', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-heading';
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

		$this->add_control( 'use_archive_term', array(
			'label'        => __( 'Use current archive term', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '',
			'return_value' => 'yes',
			'description'  => __( 'On a taxonomy archive template, pulls the term\'s name and description instead of the static fields below.', 'addlar' ),
		) );

		$this->add_heading_controls( '', __( 'Title', 'addlar' ), __( 'Lede text goes here.', 'addlar' ) );

		$this->end_controls_section();

		$this->start_controls_section( 'cta_section', array(
			'label' => __( 'Button (optional)', 'addlar' ),
		) );

		$this->add_control( 'btn_text', array(
			'label'   => __( 'Button text', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );
		$this->add_control( 'btn_link', array(
			'label'   => __( 'Button link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$eyebrow = $s['eyebrow'];
		$title   = $s['title'];
		$lede    = $s['lede'];

		if ( 'yes' === $s['use_archive_term'] ) {
			$term = get_queried_object();
			if ( $term && ! empty( $term->taxonomy ) ) {
				$title = $term->name;
				$lede  = $term->description ? $term->description : $lede;
			}
		}

		$this->open_section( 'section center', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );
		?>
		<div class="wrap center">
			<?php $this->render_heading( $eyebrow, $title, $lede ); ?>
			<?php $this->render_button( $s['btn_text'], $s['btn_link'], 'btn-red' ); ?>
		</div>
		<?php
		$this->close_section();
	}
}
