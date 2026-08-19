<?php
/**
 * Heading + a row of captioned images — used for the About Us page's
 * "industries we serve" band (the client's own Web Design blueprint calls
 * for "images of all industries where the product is applicable" on that
 * page). Generic enough to reuse anywhere a simple image row is needed.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_ImageGrid extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_image_grid';
	}

	public function get_title() {
		return __( 'ADDLAR Image Grid', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
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
			'return_value' => 'yes',
		) );

		$this->add_heading_controls( '', __( 'Heading', 'addlar' ), '' );

		$this->end_controls_section();

		$this->start_controls_section( 'items_section', array(
			'label' => __( 'Images', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'image', array( 'label' => __( 'Image', 'addlar' ), 'type' => Controls_Manager::MEDIA ) );
		$rep->add_control( 'caption', array( 'label' => __( 'Caption', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'link', array( 'label' => __( 'Link', 'addlar' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '' ) ) );

		$this->add_control( 'items', array(
			'label'       => __( 'Images', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ caption }}}',
			'default'     => array(),
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
		<div class="wrap center">
			<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
		</div>
		<div class="wrap">
			<div class="image-grid">
				<?php foreach ( (array) $s['items'] as $item ) : ?>
					<?php $url = ! empty( $item['link']['url'] ) ? $item['link']['url'] : ''; ?>
					<?php $tag = $url ? 'a' : 'div'; ?>
					<<?php echo esc_html( $tag ); // phpcs:ignore WordPress.Security.EscapeOutput -- constant 'a'/'div', not user input. ?> class="image-grid-item reveal"<?php echo $url ? ' href="' . esc_url( $url ) . '"' : ''; ?>>
						<?php $this->render_media( $item['image'], $item['caption'], '', 'medium' ); ?>
						<?php if ( ! empty( $item['caption'] ) ) : ?>
							<span class="image-grid-caption"><?php echo esc_html( $item['caption'] ); ?></span>
						<?php endif; ?>
					</<?php echo esc_html( $tag ); // phpcs:ignore WordPress.Security.EscapeOutput -- constant 'a'/'div'. ?>>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
