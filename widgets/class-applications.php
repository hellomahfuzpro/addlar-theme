<?php
/**
 * Applications — dark band: hex-clipped video with the droplet mark, the seven
 * additive chemistries, and the six film beats.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_Applications extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_applications';
	}

	public function get_title() {
		return __( 'ADDLAR Applications', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
	}

	private function default_chemistries() {
		return array(
			array( 'icon' => 'wear',      'title' => 'Anti-Wear (AW) / Extreme Pressure (EP)', 'text' => 'Sacrificial films that carry load where metal would otherwise meet metal.' ),
			array( 'icon' => 'viscosity', 'title' => 'Viscosity Index Improvers (VII)',        'text' => 'Stable viscosity across the full temperature range, cold start to full load.' ),
			array( 'icon' => 'detergent', 'title' => 'Detergents & Dispersants',                'text' => 'Keep soot and deposits suspended, keeping ring packs and pistons clean.' ),
			array( 'icon' => 'antiox',    'title' => 'Antioxidants',                            'text' => 'Extend oil life by arresting thermal and oxidative degradation.' ),
			array( 'icon' => 'corrosion', 'title' => 'Corrosion & Rust Inhibitors',             'text' => 'Polar films that shield ferrous and yellow metals from acids and water.' ),
			array( 'icon' => 'foam',      'title' => 'Anti-Foam Agents',                        'text' => 'Collapse entrained air so the film stays intact under agitation.' ),
			array( 'icon' => 'pourpoint', 'title' => 'Pour Point Depressants',                  'text' => 'Keep oil flowing at low temperature by disrupting wax crystal growth.' ),
		);
	}

	private function default_beats() {
		return array(
			array( 'bn' => '01', 'title' => 'A microscopic war zone',              'text' => 'Every moving part is a battlefield of friction, heat and wear.' ),
			array( 'bn' => '02', 'title' => 'Smooth to the eye, jagged underneath','text' => 'Even polished metal is rough at the microscopic scale.' ),
			array( 'bn' => '03', 'title' => "Base oil alone isn't enough",         'text' => "Lubricant base stock can't survive these demands on its own." ),
			array( 'bn' => '04', 'title' => 'Introducing ADDLAR',                  'text' => 'Broader spectrum, lighter viscosities, lower treat rates.' ),
			array( 'bn' => '05', 'title' => 'One package, many grades',            'text' => 'Viscometry versatility cascades a single package across grades.' ),
			array( 'bn' => '06', 'title' => 'Certified. Consistent. Dependable.',  'text' => 'Meeting API, ACEA, ILSAC and JASO — batch after batch.' ),
		);
	}

	protected function register_controls() {

		$this->start_controls_section( 'head', array(
			'label' => __( 'Heading', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'applications',
		) );

		$this->add_heading_controls(
			__( "ADDLAR's Applications", 'addlar' ),
			__( 'The chemistry inside every package.', 'addlar' ),
			__( 'Seven families of additive chemistry, engineered to create an unbreakable shield inside your machinery.', 'addlar' )
		);

		$this->end_controls_section();

		$this->start_controls_section( 'media', array(
			'label' => __( 'Stage', 'addlar' ),
		) );

		$this->add_control( 'video', array(
			'label'       => __( 'Video (mp4)', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'media_types' => array( 'video' ),
		) );
		$this->add_control( 'poster', array(
			'label' => __( 'Poster image', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
		) );
		$this->add_control( 'drop', array(
			'label'       => __( 'Centre mark', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'White ADDLAR droplet, centred over the video.', 'addlar' ),
		) );
		$this->add_control( 'caption', array(
			'label'   => __( 'Caption', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Additive science — in motion', 'addlar' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'chem', array(
			'label' => __( 'Chemistries', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'icon', array(
			'label'   => __( 'Icon', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => addlar_icon_choices(),
			'default' => 'wear',
		) );
		$rep->add_control( 'title', array( 'label' => __( 'Title', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'text', array( 'label' => __( 'Text', 'addlar' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '' ) );

		$this->add_control( 'chemistries', array(
			'label'       => __( 'Chemistries', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => $this->default_chemistries(),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'beats_section', array(
			'label' => __( 'Film beats', 'addlar' ),
		) );

		$brep = new Repeater();
		$brep->add_control( 'bn', array( 'label' => __( 'Number', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '01' ) );
		$brep->add_control( 'title', array( 'label' => __( 'Title', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$brep->add_control( 'text', array( 'label' => __( 'Text', 'addlar' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '' ) );

		$this->add_control( 'beats', array(
			'label'       => __( 'Beats', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $brep->get_controls(),
			'title_field' => '{{{ bn }}} — {{{ title }}}',
			'default'     => $this->default_beats(),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$video  = $this->media_url( isset( $s['video'] ) ? $s['video'] : array() );
		$poster = $this->media_url( isset( $s['poster'] ) ? $s['poster'] : array(), 'full' );
		$drop   = $this->media_url( isset( $s['drop'] ) ? $s['drop'] : array(), 'full' );

		$this->open_section( 'section apps', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );
		?>
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

		<div class="wrap">
			<div class="appwrap">
				<div class="appstage reveal">
					<div class="appvid">
						<?php if ( $video ) : ?>
							<video autoplay muted loop playsinline<?php echo $poster ? ' poster="' . esc_url( $poster ) . '"' : ''; ?>>
								<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
							</video>
						<?php elseif ( $poster ) : ?>
							<img src="<?php echo esc_url( $poster ); ?>" alt="" style="width:100%;height:100%;object-fit:cover;opacity:.82">
						<?php endif; ?>
						<?php if ( $drop ) : ?>
							<img class="appdrop" src="<?php echo esc_url( $drop ); ?>" alt="">
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $s['caption'] ) ) : ?>
						<div class="appcap"><?php echo esc_html( $s['caption'] ); ?></div>
					<?php endif; ?>
				</div>

				<div class="applist">
					<?php foreach ( (array) $s['chemistries'] as $c ) : ?>
						<div class="appitem reveal">
							<div class="aic"><?php $this->render_icon( $c['icon'] ); ?></div>
							<div>
								<h4><?php echo esc_html( $c['title'] ); ?></h4>
								<p><?php echo esc_html( $c['text'] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( ! empty( $s['beats'] ) ) : ?>
				<div class="beats">
					<?php foreach ( (array) $s['beats'] as $b ) : ?>
						<div class="beat reveal">
							<div class="bn"><?php echo esc_html( $b['bn'] ); ?></div>
							<h4><?php echo esc_html( $b['title'] ); ?></h4>
							<p><?php echo esc_html( $b['text'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		$this->close_section();
	}
}
