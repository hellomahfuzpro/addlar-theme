<?php
/**
 * Dark icon list — the homepage Applications section's `.applist`/`.appitem`
 * treatment (bordered icon square, title, supporting line, red hover rule),
 * reused standalone so a product page's Applications section looks exactly
 * like the homepage's rather than a row of small chips.
 *
 * Uses the homepage's own markup and CSS, so the two match by construction.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_IconList extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_icon_list';
	}

	public function get_title() {
		return __( 'ADDLAR Icon List', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
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

		$this->add_control( 'columns', array(
			'label'   => __( 'Columns', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array( '1' => '1', '2' => '2', '3' => '3' ),
			'default' => '2',
		) );

		$this->add_heading_controls( '', '', '' );

		$this->end_controls_section();

		$this->start_controls_section( 'items_section', array(
			'label' => __( 'Items', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'icon', array(
			'label'   => __( 'Icon', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => addlar_icon_choices(),
			'default' => 'gear',
		) );
		$rep->add_control( 'title', array( 'label' => __( 'Title', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'text', array( 'label' => __( 'Text', 'addlar' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '' ) );

		$this->add_control( 'items', array(
			'label'       => __( 'Items', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => array(),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['items'] ) ) {
			return;
		}

		// `apps` carries the homepage Applications section's dark ground.
		$this->open_section( 'section apps', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );
		?>
		<?php if ( ! empty( $s['eyebrow'] ) || ! empty( $s['title'] ) ) : ?>
			<div class="wrap center">
				<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
					<span class="eyebrow" style="color:#fff"><?php echo esc_html( $s['eyebrow'] ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $s['title'] ) ) : ?>
					<h2 class="title"><?php echo wp_kses_post( $s['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $s['lede'] ) ) : ?>
					<p class="lead"><?php echo wp_kses_post( $s['lede'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="wrap">
			<div class="applist applist-<?php echo esc_attr( $s['columns'] ); ?>">
				<?php foreach ( (array) $s['items'] as $item ) : ?>
					<div class="appitem reveal">
						<div class="aic"><?php $this->render_icon( $item['icon'] ); ?></div>
						<div>
							<?php if ( ! empty( $item['title'] ) ) : ?>
								<h4><?php echo esc_html( $item['title'] ); ?></h4>
							<?php endif; ?>
							<?php if ( ! empty( $item['text'] ) ) : ?>
								<p><?php echo esc_html( $item['text'] ); ?></p>
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
