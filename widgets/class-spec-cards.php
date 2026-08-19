<?php
/**
 * Icon spec cards — the homepage Package Grid's hex-icon card language
 * (`.pkg-grid` / `.pkg` / `.phex`), reused for a product page's "Key
 * Performance Benefits" and any other icon+text card row.
 *
 * Uses the homepage's own markup and CSS rather than a lookalike, so the
 * two pages are visually identical by construction — that was the
 * specific gap the client flagged between the homepage and product pages.
 *
 * Standalone: every card is a normal Elementor repeater row (icon, title,
 * text), nothing derived from post meta.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_SpecCards extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_spec_cards';
	}

	public function get_title() {
		return __( 'ADDLAR Spec Cards', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Heading', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'columns', array(
			'label'   => __( 'Columns', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array( '2' => '2', '3' => '3' ),
			'default' => '3',
		) );

		$this->add_heading_controls( '', '', '' );

		$this->end_controls_section();

		$this->start_controls_section( 'cards_section', array(
			'label' => __( 'Cards', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'icon', array(
			'label'   => __( 'Icon', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => addlar_icon_choices(),
			'default' => 'shield',
		) );
		$rep->add_control( 'lab', array( 'label' => __( 'Kicker', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'title', array( 'label' => __( 'Title', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'text', array( 'label' => __( 'Text', 'addlar' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => '' ) );

		$this->add_control( 'cards', array(
			'label'       => __( 'Cards', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => array(),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['cards'] ) ) {
			return;
		}

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<?php if ( ! empty( $s['eyebrow'] ) || ! empty( $s['title'] ) ) : ?>
			<div class="wrap center">
				<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
			</div>
		<?php endif; ?>
		<div class="wrap">
			<div class="pkg-grid pkg-grid-<?php echo esc_attr( $s['columns'] ); ?>">
				<?php foreach ( (array) $s['cards'] as $card ) : ?>
					<div class="pkg reveal">
						<div class="phex"><?php $this->render_icon( $card['icon'] ); ?></div>
						<div>
							<?php if ( ! empty( $card['lab'] ) ) : ?>
								<div class="lab"><?php echo esc_html( $card['lab'] ); ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $card['title'] ) ) : ?>
								<h3><?php echo esc_html( $card['title'] ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $card['text'] ) ) : ?>
								<div class="spec"><?php echo wp_kses_post( $card['text'] ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
