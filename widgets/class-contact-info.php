<?php
/**
 * Contact channel cards (email, LinkedIn, location) — real ADDLAR/Rchemie
 * channels only. No manufacturing/sales street address is used anywhere on
 * the site — the client's Drive content confirms only "Dubai, United Arab
 * Emirates" as the HQ location, no specific street address, so this widget
 * shows that city-level location rather than inventing a fuller one.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_ContactInfo extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_contact_info';
	}

	public function get_title() {
		return __( 'ADDLAR Contact Info', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-contact-icon';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Section', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'items_section', array(
			'label' => __( 'Cards', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'icon', array( 'label' => __( 'Icon', 'addlar' ), 'type' => Controls_Manager::SELECT, 'options' => addlar_icon_choices(), 'default' => 'mail' ) );
		$rep->add_control( 'label', array( 'label' => __( 'Label', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'value', array( 'label' => __( 'Value', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'link', array( 'label' => __( 'Link (optional)', 'addlar' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '' ) ) );

		$this->add_control( 'items', array(
			'label'       => __( 'Cards', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => array(
				array( 'icon' => 'mail', 'label' => __( 'Email', 'addlar' ), 'value' => 'info@rchemie.com', 'link' => array( 'url' => 'mailto:info@rchemie.com' ) ),
				array( 'icon' => 'pin', 'label' => __( 'Headquarters', 'addlar' ), 'value' => __( 'Dubai, United Arab Emirates', 'addlar' ), 'link' => array( 'url' => '' ) ),
				array( 'icon' => 'globe', 'label' => __( 'Parent company', 'addlar' ), 'value' => 'www.rchemie.com', 'link' => array( 'url' => 'https://www.rchemie.com' ) ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['items'] ) ) {
			return;
		}

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<div class="wrap">
			<div class="pkg-grid contact-cards">
				<?php foreach ( (array) $s['items'] as $item ) : ?>
					<?php $url = ! empty( $item['link']['url'] ) ? $item['link']['url'] : ''; ?>
					<div class="pkg contact-card reveal">
						<div class="phex"><?php $this->render_icon( $item['icon'] ); ?></div>
						<div>
							<?php if ( ! empty( $item['label'] ) ) : ?>
								<div class="lab"><?php echo esc_html( $item['label'] ); ?></div>
							<?php endif; ?>
							<?php if ( $url ) : ?>
								<a class="contact-value" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $item['value'] ); ?></a>
							<?php else : ?>
								<div class="contact-value"><?php echo esc_html( $item['value'] ); ?></div>
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
