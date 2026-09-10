<?php
/**
 * Journey — layered hexagon chain with bracket rules and a colour progression.
 *
 * Rows alternate sides automatically (h-a / h-b); the accent and tint colours
 * are injected as the namespaced --adl-ja / --adl-jt custom properties the
 * ported CSS reads.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_Journey extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_journey';
	}

	public function get_title() {
		return __( 'ADDLAR Journey', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	/** The approved eight milestones, with their colour progression. */
	private function default_rows() {
		return array(
			array( 'icon' => 'building', 'accent' => '#D32F2F', 'tint' => '#F8F9FA', 'ph' => 'Milestone 01', 'num' => '2006',    'sub' => 'The Beginning', 'title' => 'Founded in Sharjah, UAE',            'text' => 'Rchemie International begins as a chemical raw materials distributor.' ),
			array( 'icon' => 'droplet',  'accent' => '#D32F2F', 'tint' => '#F8F9FA', 'ph' => 'Milestone 02', 'num' => '2008–12', 'sub' => 'Coatings Era',  'title' => 'Paints, coatings & construction',  'text' => 'Expansion into paints, coatings and construction chemicals.' ),
			array( 'icon' => 'globe',    'accent' => '#D32F2F', 'tint' => '#F8F9FA', 'ph' => 'Milestone 03', 'num' => '2012–15', 'sub' => 'Going Global',  'title' => 'Türkiye & USA, plus plastics',     'text' => 'Territorial expansion and entry into the plastics additive market.' ),
			array( 'icon' => 'flask',    'accent' => '#D32F2F', 'tint' => '#F8F9FA', 'ph' => 'Milestone 04', 'num' => '2015',    'sub' => 'New Plant',     'title' => 'UAE adhesives plant',              'text' => 'A dedicated adhesives & hot-melt manufacturing plant opens in the UAE.' ),
			array( 'icon' => 'factory',  'accent' => '#D32F2F', 'tint' => '#F8F9FA', 'ph' => 'Milestone 05', 'num' => '2019',    'sub' => 'Scaling Up',    'title' => 'Türkiye manufacturing',            'text' => 'A new plant scales regional production capacity.' ),
			array( 'icon' => 'gear',     'accent' => '#D32F2F', 'tint' => '#F8F9FA', 'ph' => 'Milestone 06', 'num' => '2019–22', 'sub' => 'Foundation',    'title' => 'Rubber & lubricant additives',     'text' => 'The portfolio widens into rubber additives and lubricant additives.' ),
			array( 'icon' => 'spark',    'accent' => '#D32F2F', 'tint' => '#F8F9FA', 'ph' => 'Milestone 07', 'num' => '2025',    'sub' => 'ADDLAR',        'title' => 'ADDLAR is launched',               'text' => "Rchemie formulates and launches ADDLAR's groundbreaking technology." ),
			array( 'icon' => 'globe',    'accent' => '#D32F2F', 'tint' => '#F8F9FA', 'ph' => 'Milestone 08', 'num' => '2026',    'sub' => 'Worldwide',     'title' => 'Serving formulators globally',     'text' => 'ADDLAR now serves lubricant formulators across the globe.' ),
		);
	}

	protected function register_controls() {

		$this->start_controls_section( 'head', array(
			'label' => __( 'Heading', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'journey',
		) );

		$this->add_control( 'layout_style', array(
			'label'   => __( 'Layout style', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'horizontal_wave'   => __( 'Horizontal: Alternating Wave (Legacy Hexagons)', 'addlar' ),
				'horizontal_linear' => __( 'Horizontal: Linear (Legacy Hexagons)', 'addlar' ),
				'vertical'          => __( 'Vertical: Interlocking Chain (Legacy Original)', 'addlar' ),
			),
			'default' => 'horizontal_wave',
		) );

		$this->add_control( 'container_width', array(
			'label'     => __( 'Container width', 'addlar' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'boxed' => __( 'Boxed Width (1240px Centered)', 'addlar' ),
				'full'  => __( 'Full Width (100% Edge-to-Edge)', 'addlar' ),
			),
			'default'   => 'boxed',
			'condition' => array(
				'layout_style!' => 'vertical',
			),
		) );

		$this->add_control( 'enable_drag', array(
			'label'        => __( 'Draggable / Scroll track', 'addlar' ),
			'description'  => __( 'Enable a continuous horizontal draggable track. When disabled, milestones divide into a multi-row centered grid.', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'condition'    => array(
				'layout_style!' => 'vertical',
			),
		) );

		$this->add_control( 'items_per_row', array(
			'label'       => __( 'Items per row (When Drag is Off)', 'addlar' ),
			'description' => __( 'Number of milestones to display per row when draggable track is turned off.', 'addlar' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				'3' => __( '3 Milestones per row', 'addlar' ),
				'4' => __( '4 Milestones per row', 'addlar' ),
				'5' => __( '5 Milestones per row', 'addlar' ),
				'6' => __( '6 Milestones per row', 'addlar' ),
			),
			'default'     => '4',
			'condition'   => array(
				'layout_style!' => 'vertical',
				'enable_drag!'  => 'yes',
			),
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_heading_controls(
			__( 'Addlar — A Journey of Excellence', 'addlar' ),
			__( 'From specialty chemicals to lubricant leadership.', 'addlar' ),
			__( 'Two decades of growth — each milestone building toward ADDLAR.', 'addlar' )
		);

		$this->end_controls_section();

		$this->start_controls_section( 'rows_section', array(
			'label' => __( 'Milestones', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'icon', array(
			'label'   => __( 'Icon', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => addlar_icon_choices(),
			'default' => 'building',
		) );
		$rep->add_control( 'accent', array(
			'label'   => __( 'Accent colour', 'addlar' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#E2231A',
		) );
		$rep->add_control( 'tint', array(
			'label'       => __( 'Hexagon fill', 'addlar' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#FBE4E2',
			'description' => __( 'A pale version of the accent colour.', 'addlar' ),
		) );
		$rep->add_control( 'ph', array(
			'label'   => __( 'Kicker', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Milestone 01',
		) );
		$rep->add_control( 'num', array(
			'label'   => __( 'Year', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '2006',
		) );
		$rep->add_control( 'sub', array(
			'label'   => __( 'Phase', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'The Beginning',
		) );
		$rep->add_control( 'title', array(
			'label'   => __( 'Heading', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Founded in Sharjah, UAE',
		) );
		$rep->add_control( 'text', array(
			'label'   => __( 'Text', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 3,
			'default' => 'Rchemie International begins as a chemical raw materials distributor.',
		) );

		$this->add_control( 'rows', array(
			'label'       => __( 'Milestones', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ num }}} — {{{ title }}}',
			'default'     => $this->default_rows(),
		) );

		$this->end_controls_section();
	}

	/** Render legacy layered hexagon. */
	private function render_hexagon( $icon ) {
		?>
		<div class="jhexwrap">
			<div class="jhex">
				<div class="jhin">
					<?php $this->render_icon( $icon ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/** Render legacy year metadata block. */
	private function render_meta( $row ) {
		?>
		<div class="jmeta">
			<div class="ph"><?php echo esc_html( $row['ph'] ); ?></div>
			<div class="num"><?php echo esc_html( $row['num'] ); ?></div>
			<div class="sub"><?php echo esc_html( $row['sub'] ); ?></div>
		</div>
		<?php
	}

	/** Render legacy text block. */
	private function render_text( $row ) {
		?>
		<div class="jtxt">
			<h4><?php echo esc_html( $row['title'] ); ?></h4>
			<p><?php echo esc_html( $row['text'] ); ?></p>
		</div>
		<?php
	}

	/** Render legacy bracket rule line with ring terminal. */
	private function render_bracket( $pos = 'l' ) {
		?>
		<div class="jbracket">
			<span class="jbracket-line jbracket-ring-<?php echo esc_attr( $pos ); ?>"></span>
		</div>
		<?php
	}

	protected function render() {
		$s           = $this->get_settings_for_display();
		$raw_style   = ! empty( $s['layout_style'] ) ? $s['layout_style'] : ( ! empty( $s['layout'] ) ? $s['layout'] : 'horizontal_wave' );
		if ( 'vertical' === $raw_style ) {
			$style = 'vertical';
		} elseif ( in_array( $raw_style, array( 'horizontal_linear', 'roadmap_linear' ), true ) ) {
			$style = 'horizontal_linear';
		} else {
			$style = 'horizontal_wave';
		}

		$is_vertical = ( 'vertical' === $style );
		$is_drag     = ( 'yes' === ( isset( $s['enable_drag'] ) ? $s['enable_drag'] : 'yes' ) );
		$is_full     = ( 'full' === ( isset( $s['container_width'] ) ? $s['container_width'] : 'boxed' ) );
		$cols        = ! empty( $s['items_per_row'] ) ? intval( $s['items_per_row'] ) : 4;
		if ( $cols < 2 ) {
			$cols = 4;
		}

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<div class="wrap center">
			<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
		</div>
		<div class="wrap <?php echo $is_full ? 'jrny-wrap-full wrap-full' : 'jrny-wrap-boxed'; ?>">
			<?php if ( ! $is_vertical ) : ?>
				<div class="jrny-h-outer <?php echo $is_full ? 'is-full' : 'is-boxed'; ?>">
					<div class="jrny-drag-nav">
						<button type="button" class="jrny-nav-arrow prev" aria-label="<?php esc_attr_e( 'Scroll left', 'addlar' ); ?>">&#8249;</button>
						<div class="jrny-drag-hint"><span>&#8592;</span> <span><?php esc_html_e( 'Drag or swipe to explore our journey', 'addlar' ); ?></span> <span>&#8594;</span></div>
						<button type="button" class="jrny-nav-arrow next" aria-label="<?php esc_attr_e( 'Scroll right', 'addlar' ); ?>">&#8250;</button>
					</div>
					<div class="jrny-h jrny-drag-track">
						<div id="timeline-track" class="jh-track-angle min-w-[1560px] relative">
							<!-- Dynamic Zig-Zag Dashed SVG Line -->
							<svg id="poly-svg" class="angle-border-svg pointer-events-none" style="position:absolute;inset:0;width:100%;height:100%;z-index:1;">
								<polyline id="poly-main" points="" fill="none" stroke="#CBD5E1" stroke-dasharray="7 5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></polyline>
							</svg>

							<!-- 8 Milestone Columns Grid -->
							<div class="jrny-cols-8">
								<?php
								$i = 0;
								foreach ( (array) $s['rows'] as $row ) {
									$is_top = ( 0 === $i % 2 );
									?>
									<div class="milestone-col">
										<?php if ( $is_top ) : ?>
											<!-- Top Card -->
											<div class="card-wrap">
												<div class="milestone-card">
													<span class="milestone-kicker"><?php echo esc_html( $row['ph'] ); ?></span>
													<h3 class="milestone-title"><?php echo esc_html( $row['title'] ); ?></h3>
													<p class="milestone-text"><?php echo esc_html( $row['text'] ); ?></p>
												</div>
												<div class="milestone-phase"><?php echo esc_html( $row['sub'] ); ?></div>
											</div>

											<!-- Vertical Stem directly connecting Card to Lower Hexagon -->
											<div class="stem-line"></div>

											<!-- Lower Hexagon -->
											<div class="jhexwrap">
												<div class="jhex">
													<div class="jhin">
														<?php $this->render_icon( $row['icon'] ); ?>
													</div>
												</div>
											</div>

											<!-- Year Pill Below Hexagon -->
											<div class="year-pill-wrap mt-2">
												<span class="year-pill"><?php echo esc_html( $row['num'] ); ?></span>
											</div>

											<!-- Bottom Spacer to balance column height -->
											<div class="col-spacer-b"></div>
										<?php else : ?>
											<!-- Top Spacer to shift Upper Hexagon to higher wave position -->
											<div class="col-spacer-t"></div>

											<!-- Year Pill Above Hexagon -->
											<div class="year-pill-wrap mb-2">
												<span class="year-pill"><?php echo esc_html( $row['num'] ); ?></span>
											</div>

											<!-- Upper Hexagon -->
											<div class="jhexwrap">
												<div class="jhex">
													<div class="jhin">
														<?php $this->render_icon( $row['icon'] ); ?>
													</div>
												</div>
											</div>

											<!-- Vertical Stem directly connecting Upper Hexagon to Bottom Card -->
											<div class="stem-line"></div>

											<!-- Bottom Card -->
											<div class="card-wrap">
												<div class="milestone-card">
													<span class="milestone-kicker"><?php echo esc_html( $row['ph'] ); ?></span>
													<h3 class="milestone-title"><?php echo esc_html( $row['title'] ); ?></h3>
													<p class="milestone-text"><?php echo esc_html( $row['text'] ); ?></p>
												</div>
												<div class="milestone-phase"><?php echo esc_html( $row['sub'] ); ?></div>
											</div>
										<?php endif; ?>
									</div>
									<?php
									$i++;
								}
								?>
							</div>
						</div>
					</div>
				</div>
				<script>
				(function(){
					function calibrateAngleBorder(){
						var track = document.getElementById('timeline-track');
						var poly = document.getElementById('poly-main');
						if(!track || !poly) return;
						var hexes = track.querySelectorAll('.jhex');
						if(hexes.length < 2) return;
						var trackRect = track.getBoundingClientRect();
						var coords = [];
						hexes.forEach(function(hex){
							var r = hex.getBoundingClientRect();
							var cx = Math.round((r.left + r.width / 2) - trackRect.left);
							var cy = Math.round((r.top + r.height / 2) - trackRect.top);
							coords.push(cx + ',' + cy);
						});
						poly.setAttribute('points', coords.join(' '));
					}
					document.addEventListener('DOMContentLoaded', calibrateAngleBorder);
					window.addEventListener('load', calibrateAngleBorder);
					window.addEventListener('resize', calibrateAngleBorder);
					if(document.fonts){ document.fonts.ready.then(calibrateAngleBorder); }
					var scroller = document.querySelector('.adl .jrny-h');
					if(scroller){ scroller.addEventListener('scroll', calibrateAngleBorder); }

					// Smooth drag & button navigation
					var tracks = document.querySelectorAll('.adl .jrny-drag-track');
					tracks.forEach(function(slider){
						if(slider.dataset.dragInit) return;
						slider.dataset.dragInit = '1';
						var isDown = false, startX, scrollLeft;
						var parent = slider.closest('.jrny-h-outer');
						if(parent){
							var btnPrev = parent.querySelector('.jrny-nav-arrow.prev');
							var btnNext = parent.querySelector('.jrny-nav-arrow.next');
							if(btnPrev){ btnPrev.addEventListener('click', function(){ slider.scrollBy({ left: -320, behavior: 'smooth' }); }); }
							if(btnNext){ btnNext.addEventListener('click', function(){ slider.scrollBy({ left: 320, behavior: 'smooth' }); }); }
						}
						slider.addEventListener('mousedown', function(e){
							isDown = true;
							slider.classList.add('is-dragging');
							startX = e.pageX - slider.offsetLeft;
							scrollLeft = slider.scrollLeft;
						});
						slider.addEventListener('mouseleave', function(){ isDown = false; slider.classList.remove('is-dragging'); });
						slider.addEventListener('mouseup', function(){ isDown = false; slider.classList.remove('is-dragging'); });
						slider.addEventListener('mousemove', function(e){
							if(!isDown) return;
							e.preventDefault();
							var x = e.pageX - slider.offsetLeft;
							slider.scrollLeft = scrollLeft - (x - startX) * 1.5;
							calibrateAngleBorder();
						});
					});
				})();
				</script>
			<?php else : ?>
				<div class="jrny">
					<?php
					$i = 0;
					foreach ( (array) $s['rows'] as $row ) {
						$side = ( 0 === $i % 2 ) ? 'h-a' : 'h-b';
						$i++;

						printf(
							'<div class="jr %1$s reveal" style="--adl-ja:%2$s;--adl-jt:%3$s">',
							esc_attr( $side ),
							esc_attr( $row['accent'] ),
							esc_attr( $row['tint'] )
						);

						ob_start();
						?>
						<div class="jtxt">
							<h4><?php echo esc_html( $row['title'] ); ?></h4>
							<p><?php echo esc_html( $row['text'] ); ?></p>
						</div>
						<?php
						$txt = ob_get_clean();

						ob_start();
						?>
						<div class="jmeta">
							<div class="ph"><?php echo esc_html( $row['ph'] ); ?></div>
							<div class="num"><?php echo esc_html( $row['num'] ); ?></div>
							<div class="sub"><?php echo esc_html( $row['sub'] ); ?></div>
						</div>
						<?php
						$meta = ob_get_clean();

						// Mirror the mockup: copy leads on h-a rows, the year on h-b.
						echo 'h-a' === $side ? $txt : $meta; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts above.
						echo '<div class="jhexwrap"><div class="jhex"><div class="jhin">';
						$this->render_icon( $row['icon'] );
						echo '</div></div></div>';
						echo 'h-a' === $side ? $meta : $txt; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts above.
						echo '<span class="jline t"></span><span class="jline b"></span>';
						echo '</div>';
					}
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		$this->close_section();
	}
}
