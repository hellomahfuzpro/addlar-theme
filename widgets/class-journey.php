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

		$this->add_responsive_control( 'items_per_row', array(
			'label'           => __( 'Items per row / view', 'addlar' ),
			'description'     => __( 'Number of milestones visible across the row without wrapping.', 'addlar' ),
			'type'            => Controls_Manager::SELECT,
			'options'         => array(
				'2' => __( '2 Milestones', 'addlar' ),
				'3' => __( '3 Milestones', 'addlar' ),
				'4' => __( '4 Milestones', 'addlar' ),
				'5' => __( '5 Milestones', 'addlar' ),
				'6' => __( '6 Milestones', 'addlar' ),
				'7' => __( '7 Milestones', 'addlar' ),
				'8' => __( '8 Milestones', 'addlar' ),
			),
			'desktop_default' => '8',
			'tablet_default'  => '4',
			'mobile_default'  => '2',
			'condition'       => array(
				'layout_style!' => 'vertical',
			),
			'selectors'       => array(
				'{{WRAPPER}} .jh-track-angle' => '--jrny-visible-cols: {{VALUE}};',
			),
		) );

		$this->add_control( 'arrow_position', array(
			'label'     => __( 'Arrow Position', 'addlar' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'top_right'   => __( 'Top Right', 'addlar' ),
				'middle'      => __( 'Middle Left & Right', 'addlar' ),
				'top_bottom'  => __( 'Bottom Center', 'addlar' ),
				'none'        => __( 'Hidden', 'addlar' ),
			),
			'default'   => 'top_right',
			'condition' => array(
				'layout_style!' => 'vertical',
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
			'default' => '#D32F2F',
		) );
		$rep->add_control( 'tint', array(
			'label'       => __( 'Hexagon fill', 'addlar' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#FFF5F5',
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

		/* -------------------------------------------------------------------------
		 * Style: Hexagons & Line
		 * ---------------------------------------------------------------------- */
		$this->start_controls_section( 'style_hex_section', array(
			'label' => __( 'Hexagons & Line', 'addlar' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'hex_color', array(
			'label'     => __( 'Hexagon & Icon Color', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#D32F2F',
			'selectors' => array(
				'{{WRAPPER}} .jh-track-angle' => '--jrny-hex-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'hex_bg', array(
			'label'     => __( 'Hexagon Inner Plate Background', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#FFF5F5',
			'selectors' => array(
				'{{WRAPPER}} .jh-track-angle' => '--jrny-hex-bg: {{VALUE}};',
			),
		) );

		$this->add_responsive_control( 'hex_size', array(
			'label'      => __( 'Hexagon Size (px)', 'addlar' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array(
				'px' => array( 'min' => 60, 'max' => 120 ),
			),
			'default'    => array( 'unit' => 'px', 'size' => 82 ),
			'selectors'  => array(
				'{{WRAPPER}} .jh-track-angle' => '--jrny-hex-size: {{SIZE}}{{UNIT}};',
			),
		) );

		$this->add_control( 'line_color', array(
			'label'     => __( 'Connecting Dash Line Color', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#CBD5E1',
			'selectors' => array(
				'{{WRAPPER}} #poly-main'     => 'stroke: {{VALUE}} !important;',
				'{{WRAPPER}} .stem-line'     => 'background: {{VALUE}} !important;',
				'{{WRAPPER}} .jh-track-angle' => '--jrny-line-color: {{VALUE}};',
			),
		) );

		$this->end_controls_section();

		/* -------------------------------------------------------------------------
		 * Style: Year Badges
		 * ---------------------------------------------------------------------- */
		$this->start_controls_section( 'style_year_section', array(
			'label' => __( 'Year Badges', 'addlar' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_responsive_control( 'year_radius', array(
			'label'      => __( 'Border Radius (px)', 'addlar' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array(
				'px' => array( 'min' => 0, 'max' => 30 ),
			),
			'default'    => array( 'unit' => 'px', 'size' => 8 ),
			'selectors'  => array(
				'{{WRAPPER}} .year-pill' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
			),
		) );

		$this->add_responsive_control( 'year_spacing', array(
			'label'      => __( 'Spacing to Hexagon (px)', 'addlar' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array(
				'px' => array( 'min' => 6, 'max' => 35 ),
			),
			'default'    => array( 'unit' => 'px', 'size' => 16 ),
			'selectors'  => array(
				'{{WRAPPER}} .year-pill-wrap.year-below' => 'margin-top: {{SIZE}}{{UNIT}} !important;',
				'{{WRAPPER}} .year-pill-wrap.year-above' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
			),
		) );

		$this->add_control( 'year_bg', array(
			'label'     => __( 'Background Color', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#1A1D20',
			'selectors' => array(
				'{{WRAPPER}} .year-pill' => 'background-color: {{VALUE}} !important;',
			),
		) );

		$this->add_control( 'year_color', array(
			'label'     => __( 'Text Color', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#FFFFFF',
			'selectors' => array(
				'{{WRAPPER}} .year-pill' => 'color: {{VALUE}} !important;',
			),
		) );

		$this->end_controls_section();

		/* -------------------------------------------------------------------------
		 * Style: Milestone Cards
		 * ---------------------------------------------------------------------- */
		$this->start_controls_section( 'style_card_section', array(
			'label' => __( 'Milestone Cards', 'addlar' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_responsive_control( 'card_radius', array(
			'label'      => __( 'Card Border Radius (px)', 'addlar' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array(
				'px' => array( 'min' => 0, 'max' => 30 ),
			),
			'default'    => array( 'unit' => 'px', 'size' => 0 ),
			'selectors'  => array(
				'{{WRAPPER}} .milestone-card'   => 'border-radius: {{SIZE}}{{UNIT}} !important;',
				'{{WRAPPER}} .milestone-kicker' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
			),
		) );

		$this->add_responsive_control( 'card_height', array(
			'label'      => __( 'Card Height (px)', 'addlar' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array(
				'px' => array( 'min' => 140, 'max' => 260 ),
			),
			'default'    => array( 'unit' => 'px', 'size' => 175 ),
			'selectors'  => array(
				'{{WRAPPER}} .milestone-card' => 'height: {{SIZE}}{{UNIT}} !important;',
			),
		) );

		$this->add_control( 'card_bg', array(
			'label'     => __( 'Card Background', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#FFFFFF',
			'selectors' => array(
				'{{WRAPPER}} .milestone-card' => 'background-color: {{VALUE}} !important;',
			),
		) );

		$this->add_control( 'card_border_color', array(
			'label'     => __( 'Card Border Color', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#E2E8F0',
			'selectors' => array(
				'{{WRAPPER}} .milestone-card' => 'border-color: {{VALUE}} !important;',
			),
		) );

		$this->end_controls_section();

		/* -------------------------------------------------------------------------
		 * Style: Navigation Arrows
		 * ---------------------------------------------------------------------- */
		$this->start_controls_section( 'style_arrow_section', array(
			'label'     => __( 'Navigation Arrows', 'addlar' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => array(
				'layout_style!'   => 'vertical',
				'arrow_position!' => 'none',
			),
		) );

		$this->add_control( 'arrow_bg', array(
			'label'     => __( 'Arrow Background', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#FFFFFF',
			'selectors' => array(
				'{{WRAPPER}} .jrny-nav-arrow' => 'background-color: {{VALUE}} !important;',
			),
		) );

		$this->add_control( 'arrow_color', array(
			'label'     => __( 'Arrow Icon Color', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#1A1D20',
			'selectors' => array(
				'{{WRAPPER}} .jrny-nav-arrow' => 'color: {{VALUE}} !important;',
			),
		) );

		$this->add_control( 'arrow_hover_bg', array(
			'label'     => __( 'Arrow Hover Background', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#D32F2F',
			'selectors' => array(
				'{{WRAPPER}} .jrny-nav-arrow:hover' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;',
			),
		) );

		$this->add_control( 'arrow_hover_color', array(
			'label'     => __( 'Arrow Hover Icon Color', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#FFFFFF',
			'selectors' => array(
				'{{WRAPPER}} .jrny-nav-arrow:hover' => 'color: {{VALUE}} !important;',
			),
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
		$is_full     = ( 'full' === ( isset( $s['container_width'] ) ? $s['container_width'] : 'boxed' ) );
		$arrow_pos   = ! empty( $s['arrow_position'] ) ? $s['arrow_position'] : 'top_right';
		$arrow_class = 'arrows-' . esc_attr( $arrow_pos );

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
				<div class="jrny-h-outer <?php echo $is_full ? 'is-full' : 'is-boxed'; ?> <?php echo esc_attr( $arrow_class ); ?>">
					<?php if ( 'none' !== $arrow_pos ) : ?>
						<div class="jrny-nav-cluster">
							<button type="button" class="jrny-nav-arrow prev" aria-label="<?php esc_attr_e( 'Previous', 'addlar' ); ?>">&#8249;</button>
							<button type="button" class="jrny-nav-arrow next" aria-label="<?php esc_attr_e( 'Next', 'addlar' ); ?>">&#8250;</button>
						</div>
					<?php endif; ?>
					<div class="jrny-h">
						<div id="timeline-track" class="jh-track-angle relative">
							<!-- Dynamic Zig-Zag Dashed SVG Line -->
							<svg id="poly-svg" class="angle-border-svg pointer-events-none" style="position:absolute;inset:0;width:100%;height:100%;z-index:1;">
								<polyline id="poly-main" points="" fill="none" stroke="#CBD5E1" stroke-dasharray="7 5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></polyline>
							</svg>

							<!-- Milestone Columns Grid -->
							<div class="jrny-cols-8 jrny-cols-grid">
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

											<!-- Year Pill Below Hexagon with clear space -->
											<div class="year-pill-wrap year-below mt-3.5">
												<span class="year-pill"><?php echo esc_html( $row['num'] ); ?></span>
											</div>

											<!-- Bottom Spacer to balance column height -->
											<div class="col-spacer-b"></div>
										<?php else : ?>
											<!-- Top Spacer to shift Upper Hexagon to higher wave position -->
											<div class="col-spacer-t"></div>

											<!-- Year Pill Above Hexagon with clear space -->
											<div class="year-pill-wrap year-above mb-3.5">
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
					var scrollers = document.querySelectorAll('.adl .jrny-h');
					scrollers.forEach(function(scroller){
						scroller.addEventListener('scroll', calibrateAngleBorder);
						var parent = scroller.closest('.jrny-h-outer');
						if(parent && !scroller.dataset.navInit){
							scroller.dataset.navInit = '1';
							var btnPrev = parent.querySelector('.jrny-nav-arrow.prev');
							var btnNext = parent.querySelector('.jrny-nav-arrow.next');
							if(btnPrev){
								btnPrev.addEventListener('click', function(){
									scroller.scrollBy({ left: -Math.max(280, Math.round(scroller.clientWidth * 0.75)), behavior: 'smooth' });
								});
							}
							if(btnNext){
								btnNext.addEventListener('click', function(){
									scroller.scrollBy({ left: Math.max(280, Math.round(scroller.clientWidth * 0.75)), behavior: 'smooth' });
								});
							}
						}
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
