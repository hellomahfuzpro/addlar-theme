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
			array( 'icon' => 'building', 'accent' => '#E2231A', 'tint' => '#FBE4E2', 'ph' => 'Milestone 01', 'num' => '2006',    'sub' => 'The Beginning', 'title' => 'Founded in Sharjah, UAE',            'text' => 'Rchemie International begins as a chemical raw materials distributor.' ),
			array( 'icon' => 'droplet',  'accent' => '#E98A1F', 'tint' => '#FCEEDC', 'ph' => 'Milestone 02', 'num' => '2008–12', 'sub' => 'Coatings Era',  'title' => 'Paints, coatings & construction',  'text' => 'Expansion into paints, coatings and construction chemicals.' ),
			array( 'icon' => 'globe',    'accent' => '#D8A54A', 'tint' => '#FAF0DC', 'ph' => 'Milestone 03', 'num' => '2012–15', 'sub' => 'Going Global',  'title' => 'Türkiye & USA, plus plastics',     'text' => 'Territorial expansion and entry into the plastics additive market.' ),
			array( 'icon' => 'flask',    'accent' => '#3FA34D', 'tint' => '#E4F3E6', 'ph' => 'Milestone 04', 'num' => '2015',    'sub' => 'New Plant',     'title' => 'UAE adhesives plant',              'text' => 'A dedicated adhesives & hot-melt manufacturing plant opens in the UAE.' ),
			array( 'icon' => 'factory',  'accent' => '#12A5A0', 'tint' => '#DDF1F0', 'ph' => 'Milestone 05', 'num' => '2019',    'sub' => 'Scaling Up',    'title' => 'Türkiye manufacturing',            'text' => 'A new plant scales regional production capacity.' ),
			array( 'icon' => 'gear',     'accent' => '#2C6FB5', 'tint' => '#E1EAF6', 'ph' => 'Milestone 06', 'num' => '2019–22', 'sub' => 'Foundation',    'title' => 'Rubber & lubricant additives',     'text' => 'The portfolio widens into rubber additives and lubricant additives.' ),
			array( 'icon' => 'spark',    'accent' => '#E2231A', 'tint' => '#FBE4E2', 'ph' => 'Milestone 07', 'num' => '2025',    'sub' => 'ADDLAR',        'title' => 'ADDLAR is launched',               'text' => "Rchemie formulates and launches ADDLAR's groundbreaking technology." ),
			array( 'icon' => 'globe',    'accent' => '#E2231A', 'tint' => '#FBE4E2', 'ph' => 'Milestone 08', 'num' => '2026',    'sub' => 'Worldwide',     'title' => 'Serving formulators globally',     'text' => 'ADDLAR now serves lubricant formulators across the globe.' ),
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
				'roadmap_wave'        => __( 'Design 2: Alternating Wave (Modern)', 'addlar' ),
				'roadmap_linear'      => __( 'Design 2: Linear Roadmap (Cards Below)', 'addlar' ),
				'alternating_classic' => __( 'Design 1: Classic Alternating Wave', 'addlar' ),
				'vertical'            => __( 'Legacy: Vertical Hexagon Chain', 'addlar' ),
			),
			'default' => 'roadmap_wave',
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
			'description'  => __( 'Enable a single continuous horizontal draggable track. When disabled, milestones divide into a multi-row centered grid.', 'addlar' ),
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

	/** Render milestone card markup. */
	private function render_card_markup( $row ) {
		?>
		<div class="jh-card">
			<div class="jh-header">
				<span class="jh-ph"><?php echo esc_html( $row['ph'] ); ?></span>
				<span class="jh-num"><?php echo esc_html( $row['num'] ); ?></span>
			</div>
			<?php if ( ! empty( $row['sub'] ) ) : ?>
				<div class="jh-sub"><?php echo esc_html( $row['sub'] ); ?></div>
			<?php endif; ?>
			<h4 class="jh-title"><?php echo esc_html( $row['title'] ); ?></h4>
			<p class="jh-text"><?php echo esc_html( $row['text'] ); ?></p>
		</div>
		<?php
	}

	/** Render milestone dot node markup. */
	private function render_dot_markup( $row ) {
		?>
		<div class="jh-dot-wrap">
			<div class="jh-dot">
				<div class="jh-icon">
					<?php $this->render_icon( $row['icon'], 'width="18" height="18" style="width:18px;height:18px;max-width:18px;max-height:18px;display:block;fill:none;stroke:currentColor;stroke-width:1.6;"' ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function render() {
		$s           = $this->get_settings_for_display();
		$style       = ! empty( $s['layout_style'] ) ? $s['layout_style'] : ( ! empty( $s['layout'] ) && 'vertical' === $s['layout'] ? 'vertical' : 'roadmap_wave' );
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
				<style>
				.adl .jrny-wrap-boxed{max-width:1240px;margin:0 auto;width:100%;padding:0 20px;box-sizing:border-box}
				.adl .jrny-wrap-full{max-width:100%;margin:0;width:100%;padding:0 30px;box-sizing:border-box}
				.adl .jrny-h-outer{position:relative;width:100%;margin:28px auto 0}
				.adl .jrny-drag-nav{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;padding:0 10px}
				.adl .jrny-drag-hint{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--adl-grey,#666);opacity:.85;display:flex;align-items:center;gap:8px}
				.adl .jrny-nav-arrow{width:34px;height:34px;border-radius:50%;border:1px solid var(--adl-line,#e2e2e0);background:#fff;color:var(--adl-ink,#141412);font-size:20px;line-height:1;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s ease;box-shadow:0 2px 6px rgba(0,0,0,.06)}
				.adl .jrny-nav-arrow:hover{background:var(--adl-red,#E2231A);border-color:var(--adl-red,#E2231A);color:#fff;transform:scale(1.08)}
				.adl .jrny-h{position:relative;width:100%;overflow-x:auto;overflow-y:hidden;padding:24px 10px 30px;scrollbar-width:thin;scrollbar-color:var(--adl-red,#E2231A) var(--adl-soft,#f4f4f2);box-sizing:border-box}
				.adl .jrny-h::-webkit-scrollbar{height:6px}
				.adl .jrny-h::-webkit-scrollbar-track{background:var(--adl-soft,#f4f4f2);border-radius:3px}
				.adl .jrny-h::-webkit-scrollbar-thumb{background:var(--adl-red,#E2231A);border-radius:3px}
				.adl .jrny-drag-track{cursor:grab;user-select:none;-webkit-overflow-scrolling:touch}
				.adl .jrny-drag-track.is-dragging{cursor:grabbing;scroll-behavior:auto}
				.adl .jh-track{display:flex;position:relative;min-width:1360px;align-items:center}
				.adl .jrny-style-roadmap_wave .jh-track,
				.adl .jrny-style-alternating_classic .jh-track{height:420px}
				.adl .jrny-style-roadmap_linear .jh-track{height:320px;align-items:flex-start;padding-top:15px}
				.adl .jh-line{position:absolute;left:30px;right:30px;height:3px;background:linear-gradient(90deg,var(--adl-red,#E2231A) 0%,#8b120c 60%,var(--adl-ink,#141412) 100%);z-index:1}
				.adl .jrny-style-roadmap_wave .jh-line,
				.adl .jrny-style-alternating_classic .jh-line{top:50%;transform:translateY(-50%)}
				.adl .jrny-style-roadmap_linear .jh-line{top:35px}
				.adl .jh-list{display:flex;position:relative;z-index:2;width:100%;height:100%;justify-content:space-between}
				.adl .jh-col{flex:1;display:flex;flex-direction:column;align-items:center;position:relative;min-width:160px;padding:0 6px;box-sizing:border-box}
				.adl .jrny-style-roadmap_wave .jh-col,
				.adl .jrny-style-alternating_classic .jh-col{height:100%}
				.adl .jrny-style-roadmap_linear .jh-col{height:auto}
				.adl .jh-half{flex:1;width:100%;display:flex;flex-direction:column;align-items:center}
				.adl .jh-half.top{justify-content:flex-end}
				.adl .jh-half.bottom{justify-content:flex-start}
				.adl .jh-stem{width:2px;background:var(--adl-line,#e2e2e0);flex-shrink:0}
				.adl .jrny-style-roadmap_wave .jh-stem,
				.adl .jrny-style-alternating_classic .jh-stem{height:26px}
				.adl .jrny-style-roadmap_linear .jh-stem{height:22px}
				.adl .jh-dot-wrap{position:relative;z-index:3;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
				.adl .jh-dot{width:38px;height:38px;border-radius:50%;background:#fff;border:3px solid var(--ja,var(--adl-red,#E2231A));display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 4px rgba(226,35,26,.12);transition:transform .25s ease,box-shadow .25s ease,background .25s ease;cursor:pointer}
				.adl .jh-col:hover .jh-dot{transform:scale(1.15);box-shadow:0 0 0 6px rgba(226,35,26,.25);background:var(--ja,var(--adl-red,#E2231A))}
				.adl .jh-icon{width:18px;height:18px;color:var(--ja,var(--adl-red,#E2231A));display:flex;align-items:center;justify-content:center;transition:color .25s ease}
				.adl .jh-col:hover .jh-icon{color:#fff}
				.adl .jh-icon svg{width:18px!important;height:18px!important;max-width:18px!important;max-height:18px!important;display:block!important;fill:none!important;stroke:currentColor!important;stroke-width:1.6!important}
				.adl .jh-card{background:#fff;border:1px solid var(--adl-line,#e2e2e0);border-radius:8px;padding:14px 13px;box-shadow:0 4px 14px rgba(20,20,18,.05);transition:transform .25s ease,border-color .25s ease,box-shadow .25s ease;text-align:left;width:100%;box-sizing:border-box}
				.adl .jh-col:hover .jh-card{border-color:var(--ja,var(--adl-red,#E2231A));transform:translateY(var(--hover-y,-4px));box-shadow:0 10px 24px rgba(20,20,18,.1)}
				.adl .jh-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;gap:6px}
				.adl .jh-ph{font-size:8.5px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--adl-grey-2,#888)}
				.adl .jh-num{font-size:14px;font-weight:900;letter-spacing:-.02em;color:var(--ja,var(--adl-red,#E2231A));background:rgba(226,35,26,.06);padding:2px 7px;border-radius:4px;line-height:1.1}
				.adl .jh-sub{font-size:10px;font-weight:700;color:var(--adl-grey,#666);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px}
				.adl .jh-title{font-size:13px;font-weight:800;color:var(--adl-ink,#141412);margin:0 0 5px;line-height:1.28}
				.adl .jh-text{font-size:11px;color:var(--adl-grey,#666);line-height:1.45;margin:0}
				.adl .jrny-grid-system{width:100%;margin:28px auto 0;display:flex;flex-direction:column;gap:40px;box-sizing:border-box}
				.adl .jrny-grid-row{position:relative;width:100%}
				.adl .jrny-grid-row .jh-row-track{position:relative;width:100%}
				.adl .jrny-style-roadmap_wave .jrny-grid-row .jh-row-track,
				.adl .jrny-style-alternating_classic .jrny-grid-row .jh-row-track{height:410px}
				.adl .jrny-style-roadmap_linear .jrny-grid-row .jh-row-track{height:auto;min-height:280px;padding-top:15px}
				.adl .jrny-grid-row .jh-line{left:40px;right:40px}
				.adl .jh-grid-cols{display:grid;grid-template-columns:repeat(var(--jrny-cols,4),minmax(0,1fr));gap:16px;position:relative;z-index:2;width:100%;height:100%;justify-content:center}
				.adl .jrny-grid-row .jh-col{min-width:0;width:100%}
				.adl .jrny-row-divider{display:flex;align-items:center;justify-content:center;height:28px;position:relative;margin:-10px 0}
				.adl .jrny-row-connector{width:3px;height:100%;background:linear-gradient(180deg,var(--adl-red,#E2231A),var(--adl-ink,#141412));border-radius:2px}
				@media(max-width:960px){
				 .adl .jrny-h-outer{margin-top:15px}
				 .adl .jrny-drag-nav{display:none}
				 .adl .jrny-h{padding:10px 0;overflow-x:visible}
				 .adl .jh-track,
				 .adl .jrny-grid-row .jh-row-track{min-width:0;width:100%;height:auto!important;display:block;padding-top:0}
				 .adl .jh-line{top:20px!important;bottom:20px;left:22px!important;right:auto!important;width:3px;height:auto!important;background:linear-gradient(180deg,var(--adl-red,#E2231A) 0%,var(--adl-ink,#141412) 100%)!important;transform:none!important}
				 .adl .jh-list,
				 .adl .jh-grid-cols{display:flex!important;flex-direction:column!important;width:100%!important;gap:20px!important;height:auto!important}
				 .adl .jh-col{flex-direction:row!important;align-items:flex-start!important;min-width:0!important;width:100%!important;padding:0!important;gap:16px!important;height:auto!important}
				 .adl .jh-dot-wrap{width:44px;height:44px}
				 .adl .jh-half{display:block!important;flex:1!important;width:auto!important}
				 .adl .jh-half:empty{display:none!important}
				 .adl .jh-stem{display:none!important}
				 .adl .jh-card{width:100%!important;transform:none!important}
				 .adl .jrny-row-divider{display:none}
				}
				</style>

				<?php if ( $is_drag ) : ?>
					<div class="jrny-h-outer <?php echo $is_full ? 'is-full' : 'is-boxed'; ?>">
						<div class="jrny-drag-nav">
							<button type="button" class="jrny-nav-arrow prev" aria-label="<?php esc_attr_e( 'Scroll left', 'addlar' ); ?>">&#8249;</button>
							<div class="jrny-drag-hint"><span>&#8592;</span> <span><?php esc_html_e( 'Drag or swipe to explore our journey', 'addlar' ); ?></span> <span>&#8594;</span></div>
							<button type="button" class="jrny-nav-arrow next" aria-label="<?php esc_attr_e( 'Scroll right', 'addlar' ); ?>">&#8250;</button>
						</div>
						<div class="jrny-h jrny-drag-track jrny-style-<?php echo esc_attr( $style ); ?>">
							<div class="jh-track">
								<div class="jh-line"></div>
								<div class="jh-list">
									<?php
									$i = 0;
									foreach ( (array) $s['rows'] as $row ) {
										$accent = ! empty( $row['accent'] ) ? $row['accent'] : '#E2231A';
										if ( 'roadmap_linear' === $style ) {
											?>
											<div class="jh-col jh-linear" style="--ja:<?php echo esc_attr( $accent ); ?>; --hover-y:-4px;">
												<?php $this->render_dot_markup( $row ); ?>
												<div class="jh-stem"></div>
												<?php $this->render_card_markup( $row ); ?>
											</div>
											<?php
										} else {
											$is_top = ( 0 === $i % 2 );
											?>
											<div class="jh-col" style="--ja:<?php echo esc_attr( $accent ); ?>; --hover-y:<?php echo $is_top ? '-4px' : '4px'; ?>;">
												<div class="jh-half top">
													<?php if ( $is_top ) : ?>
														<?php $this->render_card_markup( $row ); ?>
														<div class="jh-stem"></div>
													<?php endif; ?>
												</div>
												<?php $this->render_dot_markup( $row ); ?>
												<div class="jh-half bottom">
													<?php if ( ! $is_top ) : ?>
														<div class="jh-stem"></div>
														<?php $this->render_card_markup( $row ); ?>
													<?php endif; ?>
												</div>
											</div>
											<?php
										}
										$i++;
									}
									?>
								</div>
							</div>
						</div>
					</div>
					<script>
					(function(){
						var initJourneyDrag = function(){
							var tracks = document.querySelectorAll('.adl .jrny-drag-track');
							tracks.forEach(function(slider){
								if(slider.dataset.dragInit) return;
								slider.dataset.dragInit = '1';
								var isDown = false, startX, scrollLeft;
								var parent = slider.closest('.jrny-h-outer');
								if(parent){
									var btnPrev = parent.querySelector('.jrny-nav-arrow.prev');
									var btnNext = parent.querySelector('.jrny-nav-arrow.next');
									if(btnPrev){
										btnPrev.addEventListener('click', function(){
											slider.scrollBy({ left: -320, behavior: 'smooth' });
										});
									}
									if(btnNext){
										btnNext.addEventListener('click', function(){
											slider.scrollBy({ left: 320, behavior: 'smooth' });
										});
									}
								}
								slider.addEventListener('mousedown', function(e){
									isDown = true;
									slider.classList.add('is-dragging');
									startX = e.pageX - slider.offsetLeft;
									scrollLeft = slider.scrollLeft;
								});
								slider.addEventListener('mouseleave', function(){
									isDown = false;
									slider.classList.remove('is-dragging');
								});
								slider.addEventListener('mouseup', function(){
									isDown = false;
									slider.classList.remove('is-dragging');
								});
								slider.addEventListener('mousemove', function(e){
									if(!isDown) return;
									e.preventDefault();
									var x = e.pageX - slider.offsetLeft;
									var walk = (x - startX) * 1.5;
									slider.scrollLeft = scrollLeft - walk;
								});
							});
						};
						if(document.readyState === 'loading'){
							document.addEventListener('DOMContentLoaded', initJourneyDrag);
						} else {
							initJourneyDrag();
						}
					})();
					</script>
				<?php else : ?>
					<?php
					$chunks      = array_chunk( (array) $s['rows'], $cols );
					$chunk_count = count( $chunks );
					?>
					<div class="jrny-grid-system jrny-style-<?php echo esc_attr( $style ); ?> <?php echo $is_full ? 'is-full' : 'is-boxed'; ?>" style="--jrny-cols:<?php echo esc_attr( $cols ); ?>;">
						<?php
						foreach ( $chunks as $c_idx => $items_in_row ) :
							$row_num     = $c_idx + 1;
							$items_count = count( $items_in_row );
							?>
							<div class="jrny-grid-row jrny-grid-row-<?php echo esc_attr( $row_num ); ?>">
								<div class="jh-row-track">
									<div class="jh-line"></div>
									<div class="jh-grid-cols" style="<?php echo ( $items_count < $cols ) ? 'grid-template-columns: repeat(' . esc_attr( $items_count ) . ', minmax(0, 1fr)); max-width: calc((100% / ' . esc_attr( $cols ) . ') * ' . esc_attr( $items_count ) . '); margin: 0 auto;' : ''; ?>">
										<?php
										$col_i = 0;
										foreach ( $items_in_row as $row ) {
											$accent = ! empty( $row['accent'] ) ? $row['accent'] : '#E2231A';
											if ( 'roadmap_linear' === $style ) {
												?>
												<div class="jh-col jh-linear" style="--ja:<?php echo esc_attr( $accent ); ?>; --hover-y:-4px;">
													<?php $this->render_dot_markup( $row ); ?>
													<div class="jh-stem"></div>
													<?php $this->render_card_markup( $row ); ?>
												</div>
												<?php
											} else {
												$is_top = ( 0 === $col_i % 2 );
												?>
												<div class="jh-col" style="--ja:<?php echo esc_attr( $accent ); ?>; --hover-y:<?php echo $is_top ? '-4px' : '4px'; ?>;">
													<div class="jh-half top">
														<?php if ( $is_top ) : ?>
															<?php $this->render_card_markup( $row ); ?>
															<div class="jh-stem"></div>
														<?php endif; ?>
													</div>
													<?php $this->render_dot_markup( $row ); ?>
													<div class="jh-half bottom">
														<?php if ( ! $is_top ) : ?>
															<div class="jh-stem"></div>
															<?php $this->render_card_markup( $row ); ?>
														<?php endif; ?>
													</div>
												</div>
												<?php
											}
											$col_i++;
										}
										?>
									</div>
								</div>
							</div>
							<?php if ( $c_idx < $chunk_count - 1 ) : ?>
								<div class="jrny-row-divider"><span class="jrny-row-connector"></span></div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
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
