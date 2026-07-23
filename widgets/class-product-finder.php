<?php
/**
 * Product finder — three-step click-through.
 *
 * Elementor has no nested repeater, and a Products CPT is disproportionate for
 * a one-page site, so each category row carries a textarea of
 *   Sub-category: CODE, CODE, CODE
 * lines. That is parsed here into a nested array and handed to theme.js as
 * JSON, mirroring the structure of the client's Product_Line_Navigation_Tool.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_ProductFinder extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_product_finder';
	}

	public function get_title() {
		return __( 'ADDLAR Product Finder', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-search-results';
	}

	private function default_categories() {
		return array(
			array(
				'name'  => 'Engine Oil Additive',
				'lines' => "Heavy Duty: 7750, 7889, 7730, 7883, 7732, 7706, 7616, 7511\nPassenger Car: 7465, 7395, 7392, 7157, 7158, 7152, 7135, 7125, 7116, 7107, 7009\nMotorcycle: 9312, 9342, 9295",
			),
			array(
				'name'  => 'Driveline',
				'lines' => "Automotive Gear: KC561, KC562, KC563\nATF: KC631\nManual Transmission: KC564\nOff-Road: 9630",
			),
			array(
				'name'  => 'Marine',
				'lines' => "Trunk Piston: 9100\nSystem Oil: 9200\nCylinder Oil: 9300",
			),
			array(
				'name'  => 'Industrial',
				'lines' => "Gear: KC561, KC562, KC563, KC565\nGrease: KC311\nHydraulic: KC521, KC523\nSlideway: KC566",
			),
			array(
				'name'  => 'Metal Working Fluid',
				'lines' => "Neat Cutting: KC410, KC415, KC415A, KC20, KC426\nSoluble Oil: KC710",
			),
			array(
				'name'  => 'Lubricant Component',
				'lines' => "Detergents: 2063, 2230, 2240, 2340, 2130\nDispersants: 2417, 2422, 2443, 2569\nAnti-wear & Friction Modifier: 2604, 2610, 2611, 2641, 2651, 5883, 2995\nAnti Oxidants: 2907, 2935\nPour Point Depressants: 224, 226\nViscosity Index Improvers: 2500, 8081, 8084\nSpeciality Component: KC720, KC721, KC810, KC820",
			),
			array(
				'name'  => 'Complementary',
				'lines' => "Brake Fluid: Custom blend\nCustomised Solutions: Made to spec",
			),
		);
	}

	protected function register_controls() {

		$this->start_controls_section( 'head', array(
			'label' => __( 'Heading', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'finder',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
		) );

		$this->add_heading_controls(
			__( 'Find the right package', 'addlar' ),
			__( 'Find your ADDLAR package.', 'addlar' ),
			__( 'Pick a category, then a sub-category — the matching ADDLAR product codes appear in step three.', 'addlar' )
		);

		$this->end_controls_section();

		$this->start_controls_section( 'data', array(
			'label' => __( 'Catalogue', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'name', array(
			'label'   => __( 'Category', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );
		$rep->add_control( 'lines', array(
			'label'       => __( 'Sub-categories and codes', 'addlar' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 8,
			'description' => __( 'One sub-category per line, in the form <code>Sub-category: CODE, CODE, CODE</code>. Numeric codes render as “ADDLAR 7750”; lettered codes (KC561) render on their own.', 'addlar' ),
			'default'     => '',
		) );

		$this->add_control( 'categories', array(
			'label'       => __( 'Categories', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ name }}}',
			'default'     => $this->default_categories(),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'labels', array(
			'label' => __( 'Step labels', 'addlar' ),
		) );

		$this->add_control( 'step1', array( 'label' => __( 'Step 1', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Step 1 — Category', 'addlar' ) ) );
		$this->add_control( 'step2', array( 'label' => __( 'Step 2', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Step 2 — Sub-category', 'addlar' ) ) );
		$this->add_control( 'step3', array( 'label' => __( 'Step 3', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Step 3 — Products', 'addlar' ) ) );
		$this->add_control( 'msg_cat', array( 'label' => __( 'Prompt: choose category', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Choose a category to begin.', 'addlar' ) ) );
		$this->add_control( 'msg_sub', array( 'label' => __( 'Prompt: choose sub-category', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Now choose a sub-category.', 'addlar' ) ) );
		$this->add_control( 'msg_idle', array( 'label' => __( 'Prompt: results', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Your matching ADDLAR codes will be listed here.', 'addlar' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$data = addlar_parse_finder_rows( $s['categories'] );

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<div class="wrap center">
			<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
		</div>
		<div class="wrap">
			<div class="finder"
				data-finder="<?php echo esc_attr( wp_json_encode( $data ) ); ?>"
				data-msg-cat="<?php echo esc_attr( $s['msg_cat'] ); ?>"
				data-msg-sub="<?php echo esc_attr( $s['msg_sub'] ); ?>"
				data-msg-products="<?php echo esc_attr__( 'products', 'addlar' ); ?>"
				data-msg-product="<?php echo esc_attr__( 'product', 'addlar' ); ?>">
				<div class="fcols">
					<div class="fcol">
						<div class="step"><?php echo esc_html( $s['step1'] ); ?></div>
						<div data-role="cats"></div>
					</div>
					<div class="fcol">
						<div class="step"><?php echo esc_html( $s['step2'] ); ?></div>
						<div data-role="subs"><div class="fmsg"><?php echo esc_html( $s['msg_cat'] ); ?></div></div>
					</div>
					<div class="fcol">
						<div class="step"><?php echo esc_html( $s['step3'] ); ?></div>
						<div data-role="msg"><div class="fmsg"><?php echo esc_html( $s['msg_idle'] ); ?></div></div>
						<div class="fpills" data-role="pills"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
