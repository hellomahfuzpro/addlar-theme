<?php
/**
 * ADDLAR packages — hex-icon spec cards.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_PackageGrid extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_package_grid';
	}

	public function get_title() {
		return __( 'ADDLAR Package Grid', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	private function default_packages() {
		return array(
			array( 'icon' => 'flask', 'lab' => 'High-Performance Mid-SAPS', 'name' => 'ADDLAR 7375', 'spec' => "<b>API</b> SN/CF, SL to SJ<br><b>ACEA</b> C3/C4, A3/B4, A5/B5<br><b>ILSAC</b> GF-5 · <b>JASO</b> MA2" ),
			array( 'icon' => 'flask', 'lab' => 'High-Performance Mid-SAPS', 'name' => 'ADDLAR 7376', 'spec' => "<b>API</b> SN, SM, SL/CF-4<br><b>ACEA</b> C3/C4, A3/B4<br><b>ILSAC</b> GF-5 · <b>JASO</b> MA2" ),
			array( 'icon' => 'flask', 'lab' => 'High-Performance Mid-SAPS', 'name' => 'ADDLAR 7395', 'spec' => "<b>API</b> SN/CF, SM<br><b>ACEA</b> C3/C5, A3/B4<br><b>ILSAC</b> GF-5 · <b>JASO</b> MA2" ),
			array( 'icon' => 'gear',  'lab' => 'Multifunctional',           'name' => 'ADDLAR 7157', 'spec' => "<b>ACEA</b> A3/B3 · <b>API</b> SL/CF-4 to SB/CB<br>Mid-tier gasoline &amp; diesel" ),
			array( 'icon' => 'layers','lab' => 'Advanced Cascade',          'name' => 'ADDLAR 7158', 'spec' => "<b>ACEA</b> A3/B3 · <b>JASO</b> MA2, MB<br><b>API</b> SL/CF-4 to SB/CB" ),
			array( 'icon' => 'droplet','lab' => 'Low Ash 2-Stroke',         'name' => 'ADDLAR 9312', 'spec' => "Oil-injection &amp; premix systems<br>Economical treat rates" ),
		);
	}

	protected function register_controls() {

		$this->start_controls_section( 'head', array(
			'label' => __( 'Heading', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'packages',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
		) );

		$this->add_heading_controls(
			__( 'ADDLAR Packages', 'addlar' ),
			__( 'The complete engine oil range.', 'addlar' ),
			__( 'Ready-to-blend additive packages engineered across the full performance spectrum — each meeting global API, ACEA, ILSAC and JASO standards.', 'addlar' )
		);

		$this->end_controls_section();

		$this->start_controls_section( 'items_section', array(
			'label' => __( 'Packages', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'icon', array(
			'label'   => __( 'Icon', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => addlar_icon_choices(),
			'default' => 'flask',
		) );
		$rep->add_control( 'lab', array(
			'label'   => __( 'Kicker', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );
		$rep->add_control( 'name', array(
			'label'   => __( 'Package name', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );
		$rep->add_control( 'spec', array(
			'label'       => __( 'Specifications', 'addlar' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 4,
			'description' => __( 'Use &lt;b&gt; for the standard names and &lt;br&gt; between lines.', 'addlar' ),
			'default'     => '',
		) );

		$this->add_control( 'items', array(
			'label'       => __( 'Packages', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ name }}}',
			'default'     => $this->default_packages(),
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
			<div class="pkg-grid">
				<?php foreach ( (array) $s['items'] as $item ) : ?>
					<div class="pkg reveal">
						<div class="phex"><?php $this->render_icon( $item['icon'] ); ?></div>
						<div>
							<div class="lab"><?php echo esc_html( $item['lab'] ); ?></div>
							<h3><?php echo esc_html( $item['name'] ); ?></h3>
							<div class="spec"><?php echo wp_kses_post( $item['spec'] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
