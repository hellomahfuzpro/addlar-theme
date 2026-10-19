<?php
/**
 * Hero — full-bleed looping video with the headline over the clip's white side.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_Hero extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_hero';
	}

	public function get_title() {
		return __( 'ADDLAR Hero', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-video-playlist';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'top',
		) );

		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Advanced Lubricant Additive Technology', 'addlar' ),
		) );

		$this->add_control( 'heading', array(
			'label'       => __( 'Headline', 'addlar' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 3,
			'description' => __( 'Wrap words in &lt;span class="r"&gt;…&lt;/span&gt; to colour them red, and use &lt;br&gt; for line breaks.', 'addlar' ),
			'default'     => 'Formulating <span class="r">Excellence.</span><br>Driving Performance.',
		) );

		$this->add_control( 'lede', array(
			'label'   => __( 'Lede', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => __( 'ADDLAR by Rchemie engineers a broader spectrum of high-performance additive packages — from engine oils to marine, industrial and metalworking fluids — formulated for lighter viscosities, lower treat rates and uncompromising global standards.', 'addlar' ),
		) );

		$this->add_control( 'btn1_text', array(
			'label'   => __( 'Primary button', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Explore the range →', 'addlar' ),
		) );
		$this->add_control( 'btn1_link', array(
			'label'   => __( 'Primary button link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '#products' ),
		) );

		$this->add_control( 'btn2_text', array(
			'label'   => __( 'Secondary button', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Find your additive', 'addlar' ),
		) );
		$this->add_control( 'btn2_link', array(
			'label'   => __( 'Secondary button link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '#finder' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'media', array(
			'label' => __( 'Background video', 'addlar' ),
		) );

		$this->add_control( 'video', array(
			'label'       => __( 'Video (mp4)', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'media_types' => array( 'video' ),
			'description' => __( 'Upload the hero clip to the Media Library and select it here. Leave empty to show only the poster image.', 'addlar' ),
		) );

		$this->add_control( 'poster', array(
			'label'       => __( 'Poster image', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'Shown before the video plays — use a frame from the clip so there is no visible jump.', 'addlar' ),
		) );

		$this->add_control( 'focal', array(
			'label'       => __( 'Video focal point', 'addlar' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'center right',
			'description' => __( 'CSS object-position. The clip is 16:9 in a taller box, so this decides what survives the crop.', 'addlar' ),
		) );

		$this->add_control( 'scrim', array(
			'label'        => __( 'Legibility scrim', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'description'  => __( 'White gradient over the left of the clip so the headline stays readable.', 'addlar' ),
			'label_on'     => __( 'On', 'addlar' ),
			'label_off'    => __( 'Off', 'addlar' ),
			'return_value' => 'yes',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'lens_section', array(
			'label' => __( 'Search Circle Lens', 'addlar' ),
		) );

		$this->add_control( 'enable_lens', array(
			'label'        => __( 'Enable Search Circle Lens', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'label_on'     => __( 'Yes', 'addlar' ),
			'label_off'    => __( 'No', 'addlar' ),
			'return_value' => 'yes',
		) );

		$this->add_control( 'lens_handle_scale', array(
			'label'       => __( 'Handle Scale', 'addlar' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => array( 'px' ),
			'range'       => array(
				'px' => array(
					'min'  => 0.7,
					'max'  => 1.5,
					'step' => 0.05,
				),
			),
			'default'     => array(
				'unit' => 'px',
				'size' => 1.0,
			),
			'condition'   => array( 'enable_lens' => 'yes' ),
		) );

		$this->add_control( 'lens_symbol_color', array(
			'label'     => __( 'Symbol Style / Glow', 'addlar' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'white',
			'options'   => array(
				'white' => __( 'Semi-Transparent White (Default)', 'addlar' ),
				'red'   => __( 'Semi-Opacity Original Red', 'addlar' ),
			),
			'condition' => array( 'enable_lens' => 'yes' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$video = $this->media_url( isset( $s['video'] ) ? $s['video'] : array() );
		$post  = $this->media_url( isset( $s['poster'] ) ? $s['poster'] : array(), 'full' );

		$this->open_section( 'hero', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );
		?>
		<div class="hero-visual"<?php echo 'yes' !== $s['scrim'] ? ' data-noscrim="1"' : ''; ?>>
			<?php if ( $video ) : ?>
				<video autoplay muted loop playsinline<?php echo $post ? ' poster="' . esc_url( $post ) . '"' : ''; ?>
					<?php echo ! empty( $s['focal'] ) ? 'style="object-position:' . esc_attr( $s['focal'] ) . '"' : ''; ?>>
					<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
				</video>
			<?php elseif ( $post ) : ?>
				<img src="<?php echo esc_url( $post ); ?>" alt=""
					<?php echo ! empty( $s['focal'] ) ? 'style="width:100%;height:100%;object-fit:cover;object-position:' . esc_attr( $s['focal'] ) . '"' : ''; ?>>
			<?php endif; ?>

			<?php
			if ( ! empty( $s['enable_lens'] ) && 'yes' === $s['enable_lens'] ) :
				$handle_scale = ! empty( $s['lens_handle_scale']['size'] ) ? floatval( $s['lens_handle_scale']['size'] ) : 1.0;
				$is_red       = ( ! empty( $s['lens_symbol_color'] ) && 'red' === $s['lens_symbol_color'] );
				$lens_mode    = $is_red ? 'mode-red-glow' : '';
				$plus_style   = $is_red ? 'plus-style-original' : 'plus-style-white';
				$graphic_url  = get_template_directory_uri() . '/assets/images/addlar-graphic.png';
				?>
				<div class="hud-stage" id="hudStage">
					<div class="search-circle-unit <?php echo esc_attr( $lens_mode ); ?>" id="searchCircle" style="--lens-handle-scale: <?php echo esc_attr( $handle_scale ); ?>;">
						<div class="lens-outer-ring"></div>
						<div class="lens-inner-ring"></div>
						<div class="lens-crosshair"></div>
						<div class="center-aperture"></div>
						<div class="lens-handle-wrap" style="transform: translateY(-50%) rotate(45deg) scale(var(--lens-handle-scale, 1));">
							<div class="lens-handle-collar"></div>
							<div class="lens-handle-shaft"></div>
						</div>
						<div class="center-plus-wrap">
							<img src="<?php echo esc_url( $graphic_url ); ?>" alt="Addlar Symbol" class="center-plus-img <?php echo esc_attr( $plus_style ); ?>">
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="hero-inner">
			<div class="hero-copy">
				<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
					<span class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $s['heading'] ) ) : ?>
					<h1><?php echo wp_kses_post( $s['heading'] ); ?></h1>
				<?php endif; ?>

				<?php if ( ! empty( $s['lede'] ) ) : ?>
					<p class="lead"><?php echo wp_kses_post( $s['lede'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $s['btn1_text'] ) || ! empty( $s['btn2_text'] ) ) : ?>
					<div class="hero-btns">
						<?php
						$this->render_button( $s['btn1_text'], $s['btn1_link'], 'btn-red' );
						$this->render_button( $s['btn2_text'], $s['btn2_link'], 'btn-ghost' );
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
