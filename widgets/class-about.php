<?php
/**
 * About — two-column intro plus the four capability cards.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_About extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_about';
	}

	public function get_title() {
		return __( 'ADDLAR About', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-image-box';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'about',
		) );

		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( '20 Years of Chemical Expertise', 'addlar' ),
		) );

		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'default' => __( 'Now powering the world of lubricants.', 'addlar' ),
		) );

		$this->add_control( 'para1', array(
			'label'   => __( 'Paragraph 1', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 5,
			'default' => __( 'Founded in 2006 in Sharjah, UAE, Rchemie International built two decades of formulation mastery across paints, plastics, adhesives and rubber chemicals — then channelled it all into ADDLAR, a complete range of lubricant additive packages engineered to a broader spectrum than the industry norm.', 'addlar' ),
		) );

		$this->add_control( 'para2', array(
			'label'   => __( 'Paragraph 2', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => __( 'From our UAE facility we ship fully TDS-documented packages with dedicated formulation support to blenders in 25+ countries. One dependable partner, every lubrication challenge.', 'addlar' ),
		) );

		$this->add_control( 'image', array(
			'label' => __( 'Image', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
		) );

		$this->add_control( 'mark', array(
			'label'       => __( 'Corner mark', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'Small ADDLAR droplet overlaid on the image.', 'addlar' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'feats', array(
			'label' => __( 'Capability cards', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'icon', array(
			'label'   => __( 'Icon', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => addlar_icon_choices(),
			'default' => 'shield',
		) );
		$rep->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Technical Precision', 'addlar' ),
		) );
		$rep->add_control( 'text', array(
			'label'   => __( 'Text', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 3,
			'default' => __( 'Stringent quality controls ensure top performance, batch after batch.', 'addlar' ),
		) );

		$this->add_control( 'cards', array(
			'label'       => __( 'Cards', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => array(
				array(
					'icon'  => 'shield',
					'title' => __( 'Technical Precision', 'addlar' ),
					'text'  => __( 'Stringent quality controls ensure top performance, batch after batch.', 'addlar' ),
				),
				array(
					'icon'  => 'plant',
					'title' => __( 'Manufacturing Excellence', 'addlar' ),
					'text'  => __( '10,000 MT / year capacity with advanced blending infrastructure.', 'addlar' ),
				),
				array(
					'icon'  => 'people',
					'title' => __( 'Formulation Partnership', 'addlar' ),
					'text'  => __( 'Collaborative R&D and technical support tailored to your blend.', 'addlar' ),
				),
				array(
					'icon'  => 'globe',
					'title' => __( 'Global Reliability', 'addlar' ),
					'text'  => __( 'Efficient logistics ensure timely, consistent delivery worldwide.', 'addlar' ),
				),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$mark = $this->media_url( isset( $s['mark'] ) ? $s['mark'] : array(), 'full' );

		$this->open_section( 'section', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );
		?>
		<div class="wrap about-grid">
			<div class="reveal">
				<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
					<span class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $s['title'] ) ) : ?>
					<h2 class="title"><?php echo wp_kses_post( $s['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $s['para1'] ) ) : ?>
					<p class="lead"><?php echo wp_kses_post( $s['para1'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $s['para2'] ) ) : ?>
					<p class="lead" style="margin-top:18px"><?php echo wp_kses_post( $s['para2'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="about-img reveal">
				<?php $this->render_media( $s['image'], esc_attr__( 'ADDLAR manufacturing', 'addlar' ) ); ?>
				<?php if ( $mark ) : ?>
					<img class="cmark" src="<?php echo esc_url( $mark ); ?>" alt="">
				<?php endif; ?>
			</div>
		</div>

		<?php if ( ! empty( $s['cards'] ) ) : ?>
			<div class="wrap">
				<div class="about-feats">
					<?php foreach ( $s['cards'] as $card ) : ?>
						<div class="afeat reveal">
							<div class="ic"><?php $this->render_icon( $card['icon'], 'width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"' ); ?></div>
							<h4><?php echo esc_html( $card['title'] ); ?></h4>
							<p><?php echo esc_html( $card['text'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php
		$this->close_section();
	}
}
