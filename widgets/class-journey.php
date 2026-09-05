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

		$this->add_control( 'layout', array(
			'label'   => __( 'Layout mode', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'horizontal' => __( 'Horizontal (Landscape)', 'addlar' ),
				'vertical'   => __( 'Vertical (Hexagon Chain)', 'addlar' ),
			),
			'default' => 'horizontal',
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

	protected function render() {
		$s             = $this->get_settings_for_display();
		$is_horizontal = ( 'horizontal' === ( isset( $s['layout'] ) ? $s['layout'] : 'horizontal' ) );

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<div class="wrap center">
			<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
		</div>
		<div class="wrap<?php echo $is_horizontal ? ' wrap-wide' : ''; ?>">
			<?php if ( $is_horizontal ) : ?>
				<style>
				.adl .jrny-h{position:relative;margin:44px auto 0;width:100%;overflow-x:auto;overflow-y:hidden;padding:70px 10px 70px;scrollbar-width:thin;scrollbar-color:var(--adl-red) var(--adl-soft)}
				.adl .jrny-h::-webkit-scrollbar{height:6px}
				.adl .jrny-h::-webkit-scrollbar-track{background:var(--adl-soft)}
				.adl .jrny-h::-webkit-scrollbar-thumb{background:var(--adl-red);border-radius:3px}
				.adl .jh-track{display:flex;position:relative;min-width:1160px;align-items:center;min-height:360px}
				.adl .jh-line{position:absolute;top:50%;left:20px;right:20px;height:3px;background:linear-gradient(90deg,var(--adl-red) 0%,#8b120c 60%,var(--adl-ink) 100%);transform:translateY(-50%);z-index:1}
				.adl .jh-list{display:flex;position:relative;z-index:2;width:100%;justify-content:space-between;align-items:center}
				.adl .jh-item{position:relative;flex:1;display:flex;flex-direction:column;align-items:center;min-width:160px;padding:0 8px}
				.adl .jh-item.jh-top{justify-content:flex-end;padding-bottom:170px}
				.adl .jh-item.jh-bottom{justify-content:flex-start;padding-top:170px}
				.adl .jh-node{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:3;display:flex;flex-direction:column;align-items:center}
				.adl .jh-dot{width:36px;height:36px;border-radius:50%;background:#fff;border:3px solid var(--adl-ja,var(--adl-red));display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 4px rgba(226,35,26,.15);transition:.25s;cursor:pointer}
				.adl .jh-icon{width:18px;height:18px;color:var(--adl-ja,var(--adl-red));display:flex;align-items:center;justify-content:center}
				.adl .jh-icon svg{width:18px!important;height:18px!important;stroke:currentColor!important;fill:none!important;stroke-width:1.6!important}
				.adl .jh-item:hover .jh-dot{transform:scale(1.18);box-shadow:0 0 0 7px rgba(226,35,26,.25);background:var(--adl-ja,var(--adl-red))}
				.adl .jh-item:hover .jh-icon{color:#fff}
				.adl .jh-stem{width:2px;background:var(--adl-line)}
				.adl .jh-top .jh-stem{height:54px;margin-top:-54px;order:1}
				.adl .jh-bottom .jh-stem{height:54px;margin-bottom:-54px;order:2}
				.adl .jh-card{background:#fff;border:1px solid var(--adl-line);padding:16px 14px;box-shadow:0 6px 20px rgba(20,20,18,.05);transition:.25s;text-align:left;width:100%;max-width:184px}
				.adl .jh-item:hover .jh-card{border-color:var(--adl-ja,var(--adl-red));transform:translateY(-4px);box-shadow:0 12px 28px rgba(20,20,18,.1)}
				.adl .jh-header{display:flex;align-items:baseline;justify-content:space-between;margin-bottom:6px;gap:6px}
				.adl .jh-ph{font-size:9.5px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--adl-grey-2)}
				.adl .jh-num{font-size:22px;font-weight:900;letter-spacing:-.03em;color:var(--adl-ja,var(--adl-red));line-height:1}
				.adl .jh-title{font-size:13.5px;font-weight:800;color:var(--adl-ink);margin:0 0 5px;line-height:1.25}
				.adl .jh-text{font-size:11.5px;color:var(--adl-grey);line-height:1.45;margin:0}
				@media(max-width:960px){
				 .adl .jrny-h{padding:20px 0;overflow-x:visible}
				 .adl .jh-track{min-width:0;height:auto;flex-direction:column;gap:18px;align-items:stretch}
				 .adl .jh-list{flex-direction:column;gap:20px;align-items:stretch}
				 .adl .jh-line,.adl .jh-stem{display:none}
				 .adl .jh-item{flex-direction:row;align-items:flex-start;text-align:left;gap:14px;min-width:0;padding:0}
				 .adl .jh-item.jh-top,.adl .jh-item.jh-bottom{padding:0;justify-content:flex-start}
				 .adl .jh-node{position:static;transform:none!important;margin-top:5px;flex:none}
				 .adl .jh-dot{position:static;transform:none!important}
				 .adl .jh-card{position:static;width:100%;max-width:none;transform:none!important}
				}
				</style>
				<div class="jrny-h">
					<div class="jh-track">
						<div class="jh-line"></div>
						<div class="jh-list">
							<?php
							$i = 0;
							foreach ( (array) $s['rows'] as $row ) {
								$pos = ( 0 === $i % 2 ) ? 'top' : 'bottom';
								$i++;
								?>
								<div class="jh-item jh-<?php echo esc_attr( $pos ); ?> reveal" style="--adl-ja:<?php echo esc_attr( $row['accent'] ); ?>">
									<div class="jh-card">
										<div class="jh-header">
											<span class="jh-ph"><?php echo esc_html( $row['ph'] ); ?></span>
											<span class="jh-num"><?php echo esc_html( $row['num'] ); ?></span>
										</div>
										<h4 class="jh-title"><?php echo esc_html( $row['title'] ); ?></h4>
										<p class="jh-text"><?php echo esc_html( $row['text'] ); ?></p>
									</div>
									<div class="jh-node">
										<div class="jh-stem"></div>
										<div class="jh-dot">
											<div class="jh-icon">
												<?php $this->render_icon( $row['icon'], 'width="18" height="18" style="width:18px;height:18px;max-width:18px;max-height:18px;display:block;fill:none;stroke:currentColor;stroke-width:1.6;"' ); ?>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
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
