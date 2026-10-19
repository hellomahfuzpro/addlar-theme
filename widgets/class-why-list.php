<?php
/**
 * Why ADDLAR — numbered rows where each numeral is filled with a photograph.
 *
 * The numeral is generated from the row index (01, 02 …) and rows alternate
 * sides automatically, so reordering in Elementor renumbers correctly.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_WhyList extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_why_list';
	}

	public function get_title() {
		return __( 'ADDLAR Why List', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-number-field';
	}

	private function default_rows() {
		return array(
			array( 'lbl' => 'Legacy',                 'title' => '20+ Years of Proven Industry Legacy',       'text' => 'Headquartered in the UAE, Rchemie brings two decades of market stability, financial reliability, and trusted global supply-chain partnerships across five continents.' ),
			array( 'lbl' => 'Quality ★ Primary USP',  'title' => 'Unwavering Quality Delivered Consistently', 'text' => 'Our primary USP: Strict quality-control protocols ensure absolute batch-to-batch consistency, physical-chemical stability, and predictable, flawless performance in every blend.' ),
			array( 'lbl' => 'Range & Customization',  'title' => 'Broader Spectrum & Customized Products',    'text' => 'Beyond our comprehensive portfolio spanning automotive, industrial, marine, and specialized fluids, we engineer bespoke, tailor-made additive packages precision-formulated to your exact target specifications.' ),
			array( 'lbl' => 'Efficiency',             'title' => 'Cascading Viscometry Versatility',           'text' => '“One fits many” — a single ADDLAR package cascades across multiple viscosity grades, maximising raw material utility, reducing inventory complexity, and streamlining blending operations.' ),
			array( 'lbl' => 'Modern Lubrication',     'title' => 'Optimized for Lighter Products',             'text' => "As the automotive industry accelerates to 0W-16, 0W-20, and 5W-20, ADDLAR's lighter packages maintain superior film strength, thermal resistance, and boundary lubrication in thin fluid regimes." ),
			array( 'lbl' => 'Economic Value',         'title' => 'Distinct Treat Rate Advantage',              'text' => 'Engineered for peak performance at lower treat rates — directly lowering blending cost per tonne without compromising finished oil performance or OEM specifications.' ),
			array( 'lbl' => 'Compliance',             'title' => 'Strict Adherence to Global Standards',       'text' => 'Application-specific packages precision-engineered to meet or exceed rigorous international API, ACEA, ILSAC, and JASO specification benchmarks.' ),
			array( 'lbl' => 'Logistics',              'title' => 'Strategic Global Logistics Hub',             'text' => "Operating from the UAE at one of the world's premier shipping crossroads — agile supply chain management, minimised transit lead times, and dependable international dispatch." ),
		);
	}

	protected function register_controls() {

		$this->start_controls_section( 'head', array(
			'label' => __( 'Heading', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'why',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
		) );

		$this->add_control( 'hide_subtext', array(
			'label'        => __( 'Hide Subtext (Headings Only)', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '',
			'return_value' => 'yes',
			'description'  => __( 'Directive A: Remove descriptive paragraphs for minimal headings-only layout.', 'addlar' ),
		) );

		$this->add_heading_controls(
			__( 'Choose ADDLAR', 'addlar' ),
			__( 'Eight reasons formulators depend on us.', 'addlar' ),
			__( 'Why leading blenders and industrial giants across the globe trust Rchemie and the ADDLAR product family.', 'addlar' )
		);

		$this->end_controls_section();

		$this->start_controls_section( 'rows_section', array(
			'label' => __( 'Reasons', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'lbl', array(
			'label'   => __( 'Kicker', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Legacy',
		) );
		$rep->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );
		$rep->add_control( 'text', array(
			'label'   => __( 'Text', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => '',
		) );
		$rep->add_control( 'image', array(
			'label'       => __( 'Numeral fill image', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'Shows through the big numeral. High-contrast images read best.', 'addlar' ),
		) );

		$this->add_control( 'rows', array(
			'label'       => __( 'Reasons', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => $this->default_rows(),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<div class="wrap center">
			<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
		</div>
		<div class="wrap">
			<div class="why2">
				<?php
				$i = 0;
				foreach ( (array) $s['rows'] as $row ) {
					$i++;
					$rev   = ( 0 === $i % 2 ) ? ' rev' : '';
					$num   = str_pad( (string) $i, 2, '0', STR_PAD_LEFT );
					$img   = $this->media_url( isset( $row['image'] ) ? $row['image'] : array() );
					$style = $img ? sprintf( "background-image:url('%s')", esc_url( $img ) ) : '';
					?>
					<div class="wrow<?php echo esc_attr( $rev ); ?> reveal">
						<div class="imgnum-wrap">
							<div class="imgnum"<?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; ?>><?php echo esc_html( $num ); ?></div>
						</div>
						<div class="wtxt">
							<div class="lbl">
								<?php
								$lbl = $row['lbl'];
								if ( false !== strpos( $lbl, 'Primary USP' ) ) {
									echo esc_html( str_replace( array( '★ Primary USP', 'Primary USP' ), '', $lbl ) );
									echo ' <span class="usp-pill">&starf; Primary USP</span>';
								} else {
									echo esc_html( $lbl );
								}
								?>
							</div>
							<h3><?php echo esc_html( $row['title'] ); ?></h3>
							<?php if ( 'yes' !== $s['hide_subtext'] && ! empty( $row['text'] ) ) : ?>
								<p><?php echo esc_html( $row['text'] ); ?></p>
							<?php endif; ?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
