<?php
/**
 * Heading + a row of images — two styles:
 * - `caption`: small square photo + caption below (About Us "industries we
 *   serve" band, per the client's Web Design blueprint).
 * - `tile`: larger bordered cards with the title overlaid on the photo,
 *   matching the reference competitor page's 4-tile row
 *   ("Light-Duty Vehicle Performance Additives" etc.) — used to break up
 *   the product page's data tables with imagery.
 *
 * Every image can carry the ADDLAR mark in its bottom corner (`mark`
 * control), matching the corner-mark treatment already used on the
 * homepage's Product Grid cards.
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

		$this->add_control( 'style', array(
			'label'   => __( 'Style', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array( 'caption' => __( 'Caption under image', 'addlar' ), 'tile' => __( 'Bordered tile, title overlaid', 'addlar' ) ),
			'default' => 'caption',
		) );

		$this->add_control( 'columns', array(
			'label'   => __( 'Columns', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array( '2' => '2', '3' => '3', '4' => '4', '6' => '6' ),
			'default' => '6',
		) );

		$this->add_control( 'mark', array(
			'label' => __( 'Corner mark (optional)', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
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

		$style = 'tile' === $s['style'] ? 'tile' : 'caption';
		$cols  = ! empty( $s['columns'] ) ? $s['columns'] : '6';
		$mark  = $this->media_url( isset( $s['mark'] ) ? $s['mark'] : array(), 'full' );

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
			<div class="image-grid image-grid-<?php echo esc_attr( $style ); ?> image-grid-cols-<?php echo esc_attr( $cols ); ?>">
				<?php foreach ( (array) $s['items'] as $item ) : ?>
					<?php $url = ! empty( $item['link']['url'] ) ? $item['link']['url'] : ''; ?>
					<?php $tag = $url ? 'a' : 'div'; ?>
					<<?php echo esc_html( $tag ); // phpcs:ignore WordPress.Security.EscapeOutput -- constant 'a'/'div', not user input. ?> class="image-grid-item reveal"<?php echo $url ? ' href="' . esc_url( $url ) . '"' : ''; ?>>
						<?php $this->render_media( $item['image'], $item['caption'], '', 'medium' ); ?>
						<?php if ( $mark ) : ?>
							<img class="cmark" src="<?php echo esc_url( $mark ); ?>" alt="">
						<?php endif; ?>
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
