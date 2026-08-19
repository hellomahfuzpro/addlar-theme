<?php
/**
 * Full-bleed background-photo band with a dark overlay and centered text —
 * the section type the reference competitor pages (Afton Chemical) use
 * repeatedly and the product page was missing entirely: everything before
 * this widget was plain white sections with at most one contained photo.
 * This is what actually makes a page look "full of images... in different
 * style sections with images in background."
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_ImageBanner extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_image_banner';
	}

	public function get_title() {
		return __( 'ADDLAR Image Banner', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
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

		$this->add_control( 'image', array(
			'label' => __( 'Background image', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
		) );

		$this->add_control( 'height', array(
			'label'   => __( 'Band height', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array( 'tall' => __( 'Tall (hero-style)', 'addlar' ), 'short' => __( 'Short (divider)', 'addlar' ) ),
			'default' => 'short',
		) );

		$this->add_control( 'align', array(
			'label'   => __( 'Text align', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array( 'center' => __( 'Center', 'addlar' ), 'left' => __( 'Left', 'addlar' ) ),
			'default' => 'center',
		) );

		$this->add_heading_controls( '', __( 'Title', 'addlar' ), '' );

		$this->add_control( 'btn_text', array(
			'label'   => __( 'Button text (optional)', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );
		$this->add_control( 'btn_link', array(
			'label'   => __( 'Button link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '' ),
		) );

		$this->add_control( 'mark', array(
			'label' => __( 'Corner mark', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s   = $this->get_settings_for_display();
		$img = $this->media_url( isset( $s['image'] ) ? $s['image'] : array(), 'full' );
		if ( ! $img ) {
			return;
		}
		$mark = $this->media_url( isset( $s['mark'] ) ? $s['mark'] : array(), 'full' );

		$this->open_section( 'section', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );
		?>
		<div class="img-banner img-banner-<?php echo esc_attr( $s['height'] ); ?>">
			<img class="img-banner-bg" src="<?php echo esc_url( $img ); ?>" alt="">
			<div class="img-banner-scrim"></div>
			<?php if ( $mark ) : ?>
				<img class="cmark" src="<?php echo esc_url( $mark ); ?>" alt="">
			<?php endif; ?>
			<div class="wrap">
				<div class="img-banner-text align-<?php echo esc_attr( $s['align'] ); ?>">
					<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
					<?php $this->render_button( $s['btn_text'], $s['btn_link'], 'btn-white' ); ?>
				</div>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
