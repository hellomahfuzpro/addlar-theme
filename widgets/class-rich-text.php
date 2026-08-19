<?php
/**
 * Heading + WYSIWYG body copy, with an optional full-width image — the
 * generic "block of real prose" section used to carry the client's actual
 * About Us copy (Drive: `Content/About Us Page.docx`) onto the page instead
 * of a one-line placeholder.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_RichText extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_rich_text';
	}

	public function get_title() {
		return __( 'ADDLAR Rich Text', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-text';
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

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
		) );

		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );
		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'body', array(
			'label'   => __( 'Body', 'addlar' ),
			'type'    => Controls_Manager::WYSIWYG,
			'default' => '',
		) );

		$this->add_control( 'image', array(
			'label' => __( 'Image (optional, full width)', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
		) );
		$this->add_control( 'image_position', array(
			'label'   => __( 'Image position', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array( 'before' => __( 'Before text', 'addlar' ), 'after' => __( 'After text', 'addlar' ) ),
			'default' => 'after',
			'condition' => array( 'image[url]!' => '' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<div class="wrap" style="max-width:860px;">
			<?php if ( ! empty( $s['eyebrow'] ) || ! empty( $s['title'] ) ) : ?>
				<?php $this->render_heading( $s['eyebrow'], $s['title'], '' ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $s['image']['url'] ) && 'before' === $s['image_position'] ) : ?>
				<div class="rich-text-image"><?php $this->render_media( $s['image'], $s['title'] ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $s['body'] ) ) : ?>
				<div class="rich-text"><?php echo wp_kses_post( $s['body'] ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $s['image']['url'] ) && 'after' === $s['image_position'] ) : ?>
				<div class="rich-text-image"><?php $this->render_media( $s['image'], $s['title'] ); ?></div>
			<?php endif; ?>
		</div>
		<?php
		$this->close_section();
	}
}
