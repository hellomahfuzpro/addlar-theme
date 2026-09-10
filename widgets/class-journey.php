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
				<style>
				.adl .jrny-wrap-boxed{max-width:1240px;margin:0 auto;width:100%;padding:0 20px;box-sizing:border-box}
				.adl .jrny-wrap-full{max-width:100%;margin:0;width:100%;padding:0 30px;box-sizing:border-box}
				.adl .jrny-h-outer{position:relative;width:100%;margin:28px auto 0}
				.adl .jrny-drag-nav{display:flex;align-items:center;justify-content:space-between;margin-bottom:15px;padding:0 10px}
				.adl .jrny-drag-hint{font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--adl-grey-2,#888);display:flex;align-items:center;gap:8px}
				.adl .jrny-nav-arrow{width:36px;height:36px;border-radius:50%;border:1px solid var(--adl-line,#e2e2e0);background:#fff;color:var(--adl-ink,#141412);font-size:20px;line-height:1;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s ease;box-shadow:0 2px 6px rgba(0,0,0,.06)}
				.adl .jrny-nav-arrow:hover{background:var(--adl-red,#E2231A);border-color:var(--adl-red,#E2231A);color:#fff;transform:scale(1.08)}
				.adl .jrny-h{position:relative;width:100%;overflow-x:auto;overflow-y:hidden;padding:30px 10px 40px;scrollbar-width:thin;scrollbar-color:var(--adl-red,#E2231A) var(--adl-soft,#f4f4f2);box-sizing:border-box}
				.adl .jrny-h::-webkit-scrollbar{height:6px}
				.adl .jrny-h::-webkit-scrollbar-track{background:var(--adl-soft,#f4f4f2);border-radius:3px}
				.adl .jrny-h::-webkit-scrollbar-thumb{background:var(--adl-red,#E2231A);border-radius:3px}
				.adl .jrny-drag-track{cursor:grab;user-select:none;-webkit-overflow-scrolling:touch}
				.adl .jrny-drag-track.is-dragging{cursor:grabbing;scroll-behavior:auto}
				.adl .jh-track{display:flex;position:relative;min-width:1440px;align-items:center;height:500px}
				.adl .jh-axis-line{position:absolute;top:50%;left:40px;right:40px;height:3px;background:linear-gradient(90deg,var(--adl-red,#E2231A) 0%,#8b120c 60%,var(--adl-ink,#141412) 100%);transform:translateY(-50%);z-index:1}
				.adl .jh-list{display:flex;position:relative;z-index:2;width:100%;height:100%;justify-content:space-between}
				.adl .jh-col{flex:1;display:flex;flex-direction:column;align-items:center;position:relative;min-width:175px;padding:0 10px;height:100%;box-sizing:border-box}
				.adl .jh-half{flex:1;width:100%;display:flex;flex-direction:column;position:relative}
				.adl .jh-half.top{justify-content:flex-end;padding-bottom:20px}
				.adl .jh-half.bottom{justify-content:flex-start;padding-top:20px}
				.adl .jhexwrap{position:relative;z-index:3;display:flex;align-items:center;justify-content:center;flex-shrink:0}
				.adl .jhex{position:relative;width:110px;height:124px;display:flex;align-items:center;justify-content:center;flex:none;transition:transform .25s ease;filter:drop-shadow(0 4px 12px rgba(0,0,0,.08))}
				.adl .jh-col:hover .jhex{transform:scale(1.08)}
				.adl .jhex::before{content:'';position:absolute;inset:0;background:var(--adl-ja);clip-path:polygon(50% 0,100% 25%,100% 75%,50% 100%,0 75%,0 25%)}
				.adl .jhex::after{content:'';position:absolute;inset:3px;background:#fff;clip-path:polygon(50% 0,100% 25%,100% 75%,50% 100%,0 75%,0 25%)}
				.adl .jhin{position:relative;z-index:2;width:84px;height:94px;display:flex;align-items:center;justify-content:center;background:var(--adl-jt);clip-path:polygon(50% 0,100% 25%,100% 75%,50% 100%,0 75%,0 25%)}
				.adl .jhin svg{width:32px;height:32px;stroke:var(--adl-ja);fill:none;stroke-width:1.4;display:block}
				.adl .jbracket{position:relative;width:100%;margin-bottom:8px}
				.adl .jbracket-line{position:relative;width:100%;height:2px;background:var(--adl-ja);opacity:.75;display:block}
				.adl .jbracket-ring-l::before{content:'';position:absolute;left:0;top:50%;transform:translate(-50%,-50%);width:11px;height:11px;border:2px solid var(--adl-ja);border-radius:50%;background:#fff}
				.adl .jbracket-ring-r::after{content:'';position:absolute;right:0;top:50%;transform:translate(50%,-50%);width:11px;height:11px;border:2px solid var(--adl-ja);border-radius:50%;background:#fff}
				.adl .jh-col .jtxt{position:relative;z-index:2;text-align:left;width:100%}
				.adl .jh-col .jtxt h4{font-size:14px;font-weight:800;margin:0 0 5px;color:var(--adl-ink,#141412);line-height:1.25}
				.adl .jh-col .jtxt p{font-size:11.5px;color:var(--adl-grey,#666);line-height:1.45;margin:0}
				.adl .jh-col .jmeta{position:relative;z-index:2;text-align:left;width:100%}
				.adl .jh-col .jmeta .ph{font-size:9.5px;font-weight:800;letter-spacing:.16em;text-transform:uppercase;color:var(--adl-grey-2,#888)}
				.adl .jh-col .jmeta .num{font-size:34px;font-weight:700;line-height:1.05;letter-spacing:-.02em;color:var(--adl-ja);margin:3px 0 2px}
				.adl .jh-col .jmeta .sub{font-size:10px;font-weight:800;letter-spacing:.16em;text-transform:uppercase;color:var(--adl-ja);opacity:.85}
				.adl .jrny-grid-system{width:100%;margin:30px auto 0;display:flex;flex-direction:column;gap:50px;box-sizing:border-box}
				.adl .jrny-grid-row{position:relative;width:100%}
				.adl .jrny-grid-row .jh-row-track{position:relative;width:100%;height:460px}
				.adl .jrny-grid-row .jh-axis-line{left:40px;right:40px}
				.adl .jh-grid-cols{display:grid;grid-template-columns:repeat(var(--jrny-cols,4),minmax(0,1fr));gap:20px;position:relative;z-index:2;width:100%;height:100%;justify-content:center}
				.adl .jrny-row-divider{display:flex;align-items:center;justify-content:center;height:32px;position:relative;margin:-16px 0}
				.adl .jrny-row-connector{width:3px;height:100%;background:linear-gradient(180deg,var(--adl-red,#E2231A),var(--adl-ink,#141412));border-radius:2px}
				@media(max-width:960px){
				 .adl .jrny-drag-nav{display:none}
				 .adl .jrny-h{padding:10px 0;overflow-x:visible}
				 .adl .jh-track,
				 .adl .jrny-grid-row .jh-row-track{min-width:0;width:100%;height:auto!important;display:block}
				 .adl .jh-axis-line{top:30px!important;bottom:30px;left:55px!important;right:auto!important;width:3px;height:auto!important;background:linear-gradient(180deg,var(--adl-red,#E2231A) 0%,var(--adl-ink,#141412) 100%)!important;transform:none!important}
				 .adl .jh-list,
				 .adl .jh-grid-cols{display:flex!important;flex-direction:column!important;width:100%!important;gap:28px!important;height:auto!important}
				 .adl .jh-col{flex-direction:row!important;align-items:center!important;min-width:0!important;width:100%!important;padding:0!important;gap:20px!important;height:auto!important}
				 .adl .jh-half{display:block!important;flex:1!important;width:auto!important;padding:0!important}
				 .adl .jh-half:empty{display:none!important}
				 .adl .jhex{width:90px;height:102px}
				 .adl .jhin{width:68px;height:78px}
				 .adl .jhin svg{width:26px;height:26px}
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
						<div class="jrny-h jrny-drag-track">
							<div class="jh-track">
								<div class="jh-axis-line"></div>
								<div class="jh-list">
									<?php
									$i = 0;
									foreach ( (array) $s['rows'] as $row ) {
										$accent = ! empty( $row['accent'] ) ? $row['accent'] : '#E2231A';
										$tint   = ! empty( $row['tint'] ) ? $row['tint'] : '#FBE4E2';
										$is_top = ( 'horizontal_linear' === $style ) ? false : ( 0 === $i % 2 );
										?>
										<div class="jh-col" style="--adl-ja:<?php echo esc_attr( $accent ); ?>; --adl-jt:<?php echo esc_attr( $tint ); ?>;">
											<div class="jh-half top">
												<?php if ( $is_top ) : ?>
													<?php $this->render_bracket( 'l' ); ?>
													<?php $this->render_text( $row ); ?>
												<?php else : ?>
													<?php $this->render_bracket( 'r' ); ?>
													<?php $this->render_meta( $row ); ?>
												<?php endif; ?>
											</div>
											<?php $this->render_hexagon( $row['icon'] ); ?>
											<div class="jh-half bottom">
												<?php if ( $is_top ) : ?>
													<?php $this->render_bracket( 'l' ); ?>
													<?php $this->render_meta( $row ); ?>
												<?php else : ?>
													<?php $this->render_bracket( 'r' ); ?>
													<?php $this->render_text( $row ); ?>
												<?php endif; ?>
											</div>
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
										btnPrev.addEventListener('click', function(){ slider.scrollBy({ left: -320, behavior: 'smooth' }); });
									}
									if(btnNext){
										btnNext.addEventListener('click', function(){ slider.scrollBy({ left: 320, behavior: 'smooth' }); });
									}
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
					<div class="jrny-grid-system" style="--jrny-cols:<?php echo esc_attr( $cols ); ?>;">
						<?php
						foreach ( $chunks as $c_idx => $items_in_row ) :
							$row_num     = $c_idx + 1;
							$items_count = count( $items_in_row );
							?>
							<div class="jrny-grid-row jrny-grid-row-<?php echo esc_attr( $row_num ); ?>">
								<div class="jh-row-track">
									<div class="jh-axis-line"></div>
									<div class="jh-grid-cols" style="<?php echo ( $items_count < $cols ) ? 'grid-template-columns: repeat(' . esc_attr( $items_count ) . ', minmax(0, 1fr)); max-width: calc((100% / ' . esc_attr( $cols ) . ') * ' . esc_attr( $items_count ) . '); margin: 0 auto;' : ''; ?>">
										<?php
										$col_i = 0;
										foreach ( $items_in_row as $row ) {
											$accent = ! empty( $row['accent'] ) ? $row['accent'] : '#E2231A';
											$tint   = ! empty( $row['tint'] ) ? $row['tint'] : '#FBE4E2';
											$is_top = ( 'horizontal_linear' === $style ) ? false : ( 0 === $col_i % 2 );
											?>
											<div class="jh-col" style="--adl-ja:<?php echo esc_attr( $accent ); ?>; --adl-jt:<?php echo esc_attr( $tint ); ?>;">
												<div class="jh-half top">
													<?php if ( $is_top ) : ?>
														<?php $this->render_bracket( 'l' ); ?>
														<?php $this->render_text( $row ); ?>
													<?php else : ?>
														<?php $this->render_bracket( 'r' ); ?>
														<?php $this->render_meta( $row ); ?>
													<?php endif; ?>
												</div>
												<?php $this->render_hexagon( $row['icon'] ); ?>
												<div class="jh-half bottom">
													<?php if ( $is_top ) : ?>
														<?php $this->render_bracket( 'l' ); ?>
														<?php $this->render_meta( $row ); ?>
													<?php else : ?>
														<?php $this->render_bracket( 'r' ); ?>
														<?php $this->render_text( $row ); ?>
													<?php endif; ?>
												</div>
											</div>
											<?php
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
