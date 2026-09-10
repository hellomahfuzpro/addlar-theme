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
			array(
				'number'   => '01',
				'part'     => 'Rear Axle',
				'category' => 'Hypoid EP',
				'title'    => 'Differential & Hypoid Gear EP',
				'text'     => 'Sulfur-phosphorus chemistry resisting extreme shock loads in hypoid differentials with silicone anti-foam for zero cavitation.',
				'badge'    => 'Shock Load Protection',
				'dot_x'    => '36.5%',
				'dot_y'    => '43.5%',
			),
			array(
				'number'   => '02',
				'part'     => 'Wheel Bearings',
				'category' => 'EP Grease',
				'title'    => 'Wheel Bearing & Hub Protection',
				'text'     => 'Extreme pressure complex greases and polar passivators protecting rotating wheel hub bearings from moisture and heavy radial load.',
				'badge'    => 'Radial Load Film',
				'dot_x'    => '26.5%',
				'dot_y'    => '46.5%',
			),
			array(
				'number'   => '03',
				'part'     => 'Driveshaft',
				'category' => 'Viscometry',
				'title'    => 'Viscosity Index Improvers (VII)',
				'text'     => 'High-shear OCP polymers providing stable hydrodynamic film thickness across the full temperature gradient, cold start to 160°C.',
				'badge'    => 'Viscometric Stability',
				'dot_x'    => '44%',
				'dot_y'    => '49%',
			),
			array(
				'number'   => '04',
				'part'     => 'Valvetrain & Sump',
				'category' => 'Antioxidants',
				'title'    => 'Antioxidants & Thermal Shield',
				'text'     => 'Hindered phenols and aromatic amines arresting oxidation chains, preventing viscosity thickening and sludge in the sump.',
				'badge'    => 'Extended Drain Life',
				'dot_x'    => '57%',
				'dot_y'    => '49.5%',
			),
			array(
				'number'   => '05',
				'part'     => 'Engine Block',
				'category' => 'AW / EP',
				'title'    => 'Anti-Wear & Extreme Pressure',
				'text'     => 'Sacrificial ZDDP films preventing boundary metal-to-metal contact under severe RPM, high contact pressures, and peak thermal load.',
				'badge'    => 'Boundary Film Protection',
				'dot_x'    => '63%',
				'dot_y'    => '55%',
			),
			array(
				'number'   => '06',
				'part'     => 'Pistons',
				'category' => 'Detergency',
				'title'    => 'Detergents & Dispersants',
				'text'     => 'Overbased calcium/magnesium sulfonates keep soot and carbon precursors suspended, preventing ring sticking and bore polishing.',
				'badge'    => 'Deposit & Soot Control',
				'dot_x'    => '54%',
				'dot_y'    => '56.5%',
			),
			array(
				'number'   => '07',
				'part'     => 'Transmission',
				'category' => 'Driveline EP',
				'title'    => 'Automatic & Manual Driveline Chemistry',
				'text'     => 'High-torque shear-stable friction modifiers and gear EP packages protecting synchronizers and planetary gear sets under continuous heavy load.',
				'badge'    => 'FZG Stage 12+ Tested',
				'dot_x'    => '49.5%',
				'dot_y'    => '52.5%',
			),
		);
	}

	private function default_beats() {
		return array(
			array( 'bn' => '01', 'title' => 'A microscopic war zone',               'text' => 'Every moving powertrain part is a battlefield of friction, extreme heat, and contact pressure.' ),
			array( 'bn' => '02', 'title' => 'Smooth to the eye, jagged underneath', 'text' => 'Even mirror-polished steel asperities collide violently at microscopic surface levels.' ),
			array( 'bn' => '03', 'title' => "Base oil alone isn't enough",          'text' => 'Raw base stock breaks down under severe shear, heat, and combustion blowby.' ),
			array( 'bn' => '04', 'title' => 'Introducing ADDLAR',                   'text' => 'Broader performance spectrum, lighter viscosities, lower treat rates, zero compromise.' ),
			array( 'bn' => '05', 'title' => 'One package, many grades',             'text' => 'Viscometry versatility cascades single core additive packages across multiple viscosity grades.' ),
			array( 'bn' => '06', 'title' => 'Certified. Consistent. Dependable.',   'text' => 'Engineered to meet global API, ACEA, ILSAC, and JASO standards — batch after batch.' ),
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
			__( 'Technical Architecture • Seven Families', 'addlar' ),
			__( 'The chemistry inside every package.', 'addlar' ),
			__( 'Seven families of advanced additive chemistry engineered into a unified package, creating an unbreakable molecular shield across every moving powertrain component.', 'addlar' )
		);

		$this->end_controls_section();

		$this->start_controls_section( 'media', array(
			'label' => __( '3D Car Video Stage', 'addlar' ),
		) );

		$this->add_control( 'video', array(
			'label'       => __( 'Video (mp4)', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'media_types' => array( 'video' ),
			'description' => __( 'Defaults to theme assets/video/wheels-spinning-car.mp4', 'addlar' ),
		) );
		$this->add_control( 'poster', array(
			'label' => __( 'Poster image', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'chem', array(
			'label' => __( 'Seven Component Chemistries', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'number',   array( 'label' => __( 'Number', 'addlar' ),   'type' => Controls_Manager::TEXT, 'default' => '01' ) );
		$rep->add_control( 'part',     array( 'label' => __( 'Car Part', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'category', array( 'label' => __( 'Category', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'title',    array( 'label' => __( 'Title', 'addlar' ),    'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'text',     array( 'label' => __( 'Text', 'addlar' ),     'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '' ) );
		$rep->add_control( 'badge',    array( 'label' => __( 'Footer Tag', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'dot_x',    array( 'label' => __( 'Hotspot X %', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '50%' ) );
		$rep->add_control( 'dot_y',    array( 'label' => __( 'Hotspot Y %', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '50%' ) );

		$this->add_control( 'chemistries', array(
			'label'       => __( 'Chemistries', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ number }}} • {{{ part }}} ({{{ title }}})',
			'default'     => $this->default_chemistries(),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'beats_section', array(
			'label' => __( 'Film beats', 'addlar' ),
		) );

		$brep = new Repeater();
		$brep->add_control( 'bn',    array( 'label' => __( 'Number', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '01' ) );
		$brep->add_control( 'title', array( 'label' => __( 'Title', 'addlar' ),  'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$brep->add_control( 'text',  array( 'label' => __( 'Text', 'addlar' ),   'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '' ) );

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
		$s           = $this->get_settings_for_display();
		$theme_uri   = get_template_directory_uri();
		$video       = $this->media_url( isset( $s['video'] ) ? $s['video'] : array() );
		if ( empty( $video ) ) {
			$video = $theme_uri . '/assets/video/wheels-spinning-car.mp4';
		}
		$poster      = $this->media_url( isset( $s['poster'] ) ? $s['poster'] : array(), 'full' );
		$chemistries = ! empty( $s['chemistries'] ) ? $s['chemistries'] : $this->default_chemistries();
		$beats       = ! empty( $s['beats'] ) ? $s['beats'] : $this->default_beats();
		$unique_id   = 'chem-' . $this->get_id();
		$anchor_id   = ! empty( $s['anchor'] ) ? $s['anchor'] : 'applications';
		?>
		<div class="adl">
			<section class="chem-section" id="<?php echo esc_attr( $anchor_id ); ?>" data-widget-id="<?php echo esc_attr( $unique_id ); ?>">
				<div class="chem-wrap">
					<!-- Header -->
					<div class="chem-head">
						<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
							<div class="chem-eyebrow">
								<span class="chem-eyebrow-dot"></span>
								<span><?php echo esc_html( $s['eyebrow'] ); ?></span>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $s['title'] ) ) : ?>
							<h2 class="chem-title"><?php echo wp_kses_post( $s['title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $s['lede'] ) ) : ?>
							<p class="chem-lead"><?php echo wp_kses_post( $s['lede'] ); ?></p>
						<?php endif; ?>
					</div>

					<!-- Interactive 3D Stage -->
					<div class="chem-stage" id="<?php echo esc_attr( $unique_id ); ?>-stage">
						<!-- SVG Dynamic Lines Layer -->
						<svg class="chem-svg-layer" id="<?php echo esc_attr( $unique_id ); ?>-svg"></svg>

						<!-- Center Video Box — NO BORDER, NO SHADOW, SEAMLESS BLACK BLEND -->
						<div class="chem-video-box" id="<?php echo esc_attr( $unique_id ); ?>-video-box">
							<video class="chem-video" autoplay muted loop playsinline<?php echo $poster ? ' poster="' . esc_url( $poster ) . '"' : ''; ?>>
								<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
							</video>
							<div class="chem-video-vignette"></div>

							<!-- Hotspot Pins: Calibrated coordinates on car components (Pure Red, No White) -->
							<?php foreach ( $chemistries as $idx => $c ) : ?>
								<?php
								$i     = $idx + 1;
								$dot_x = ! empty( $c['dot_x'] ) ? $c['dot_x'] : '50%';
								$dot_y = ! empty( $c['dot_y'] ) ? $c['dot_y'] : '50%';
								$title = ! empty( $c['part'] ) ? $c['part'] : ( ! empty( $c['title'] ) ? $c['title'] : '' );
								?>
								<div class="chem-hotspot" id="<?php echo esc_attr( $unique_id ); ?>-dot-<?php echo esc_attr( $i ); ?>" style="left:<?php echo esc_attr( $dot_x ); ?>;top:<?php echo esc_attr( $dot_y ); ?>;" data-index="<?php echo esc_attr( $i ); ?>" title="<?php echo esc_attr( $title ); ?>">
									<div class="chem-hotspot-core"></div>
									<div class="chem-hotspot-ring"></div>
								</div>
							<?php endforeach; ?>
						</div>

						<!-- Overlapping Red Box Text Cards (Sharp 0 radius, 2px red border, red header/footer) -->
						<div class="chem-cards-layer">
							<?php foreach ( $chemistries as $idx => $c ) : ?>
								<?php
								$i     = $idx + 1;
								$num   = ! empty( $c['number'] ) ? $c['number'] : sprintf( '%02d', $i );
								$part  = ! empty( $c['part'] ) ? $c['part'] : '';
								$cat   = ! empty( $c['category'] ) ? $c['category'] : '';
								$title = ! empty( $c['title'] ) ? $c['title'] : '';
								$text  = ! empty( $c['text'] ) ? $c['text'] : '';
								$badge = ! empty( $c['badge'] ) ? $c['badge'] : '';
								?>
								<div class="chem-card chem-card-<?php echo esc_attr( $i ); ?>" id="<?php echo esc_attr( $unique_id ); ?>-card-<?php echo esc_attr( $i ); ?>" data-index="<?php echo esc_attr( $i ); ?>">
									<div class="chem-card-header">
										<span><?php echo esc_html( $num ); ?><?php echo $part ? ' &bull; ' . esc_html( $part ) : ''; ?></span>
										<span><?php echo esc_html( $cat ); ?></span>
									</div>
									<div class="chem-card-body">
										<h4 class="chem-card-title"><?php echo esc_html( $title ); ?></h4>
										<p class="chem-card-desc"><?php echo esc_html( $text ); ?></p>
									</div>
									<?php if ( $badge ) : ?>
										<div class="chem-card-footer">
											<span><?php echo esc_html( $badge ); ?></span>
											<span>&rarr;</span>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- Storytelling Beats Strip -->
					<?php if ( ! empty( $beats ) ) : ?>
						<div class="chem-beats-grid">
							<?php foreach ( $beats as $b ) : ?>
								<div class="chem-beat">
									<div class="chem-beat-num"><?php echo esc_html( $b['bn'] ); ?></div>
									<div class="chem-beat-title"><?php echo esc_html( $b['title'] ); ?></div>
									<p class="chem-beat-desc"><?php echo esc_html( $b['text'] ); ?></p>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>

		<script>
		(function() {
			var widgetId = <?php echo wp_json_encode( $unique_id ); ?>;
			var stage = document.getElementById(widgetId + '-stage');
			var svg = document.getElementById(widgetId + '-svg');
			if (!stage || !svg) return;

			var totalItems = <?php echo count( $chemistries ); ?>;
			var cards = stage.querySelectorAll('.chem-card');
			var hotspots = stage.querySelectorAll('.chem-hotspot');
			var highlightedIdx = null;

			function updateLines() {
				if (window.innerWidth <= 900) {
					svg.innerHTML = '';
					return;
				}

				var stageRect = stage.getBoundingClientRect();
				var svgWidth = stageRect.width;
				var svgHeight = stageRect.height;
				svg.setAttribute('viewBox', '0 0 ' + svgWidth + ' ' + svgHeight);

				var pathsHtml = '';

				for (var i = 1; i <= totalItems; i++) {
					var card = document.getElementById(widgetId + '-card-' + i);
					var dot = document.getElementById(widgetId + '-dot-' + i);
					if (!card || !dot) continue;

					var cardRect = card.getBoundingClientRect();
					var dotRect = dot.getBoundingClientRect();

					var dotX = (dotRect.left + dotRect.width / 2) - stageRect.left;
					var dotY = (dotRect.top + dotRect.height / 2) - stageRect.top;

					var cardX, cardY;
					if (i === 7) {
						cardX = (cardRect.left + cardRect.width / 2) - stageRect.left;
						cardY = cardRect.top - stageRect.top;
					} else if (i <= 3) {
						cardX = cardRect.right - stageRect.left;
						cardY = (cardRect.top + cardRect.height / 2) - stageRect.top;
					} else {
						cardX = cardRect.left - stageRect.left;
						cardY = (cardRect.top + cardRect.height / 2) - stageRect.top;
					}

					var isHighlight = (i === highlightedIdx);
					var lineClass = 'chem-connector-line' + (isHighlight ? ' active' : '');
					pathsHtml += '<path class="' + lineClass + '" d="M ' + cardX + ' ' + cardY + ' L ' + dotX + ' ' + dotY + '" />';
				}

				svg.innerHTML = pathsHtml;
			}

			function highlight(idx) {
				highlightedIdx = idx;
				cards.forEach(function(c) {
					if (idx && parseInt(c.dataset.index, 10) === idx) {
						c.classList.add('highlight');
					} else {
						c.classList.remove('highlight');
					}
				});

				hotspots.forEach(function(h) {
					if (idx && parseInt(h.dataset.index, 10) === idx) {
						h.classList.add('active');
					} else {
						h.classList.remove('active');
					}
				});

				updateLines();
			}

			cards.forEach(function(c) {
				c.addEventListener('mouseenter', function() {
					highlight(parseInt(this.dataset.index, 10));
				});
				c.addEventListener('mouseleave', function() {
					highlight(null);
				});
			});

			hotspots.forEach(function(h) {
				h.addEventListener('mouseenter', function() {
					highlight(parseInt(this.dataset.index, 10));
				});
				h.addEventListener('mouseleave', function() {
					highlight(null);
				});
			});

			window.addEventListener('resize', updateLines);
			setTimeout(updateLines, 200);
		})();
		</script>
		<?php
	}
}
