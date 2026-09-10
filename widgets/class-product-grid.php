<?php
/**
 * Product range — Full-Width Tabbed Slider Showcase (Stitch Design) or Classic 6-card grid.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_ProductGrid extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_product_grid';
	}

	public function get_title() {
		return __( 'ADDLAR Product Range Showcase', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	private function default_slides() {
		$theme_uri = get_template_directory_uri();
		return array(
			array(
				'cat'              => 'Engine Oils',
				'icon'             => 'engine',
				'counter'          => '01 / 06',
				'cat_name'         => 'Automotive Engine Oil',
				'cat_desc'         => 'Engineered for passenger cars (PCMO) and heavy-duty diesel engines (HDDO) with extreme thermal stability.',
				'eyebrow'          => 'Product Range — Six Additive Families',
				'title'            => 'One partner. Every lubrication challenge.',
				'desc'             => 'Six additive families plus complementary specialty products — each engineered for a specific world of machinery, operating environment, and severe mechanical stress.',
				'specs'            => 'API CK-4 / SP, ACEA E11 / E9 / C5, ILSAC GF-6, JASO MA2, Viscometry: 0W-16 to 15W-40',
				'btn_primary_text' => 'Explore Specifications',
				'btn_primary_link' => array( 'url' => '#finder' ),
				'btn_sec_text'     => 'Request Formulation TDS',
				'btn_sec_link'     => array( 'url' => '#contact' ),
				'metric1_label'    => 'Certification',
				'metric1_val'      => 'API & ACEA Dual',
				'metric2_label'    => 'Viscosity Range',
				'metric2_val'      => '0W-16 to 50 Grade',
				'metric3_label'    => 'Supply Guarantee',
				'metric3_val'      => 'Direct Blending & Bulk',
				'bg_image'         => array( 'url' => $theme_uri . '/assets/images/golden-oil-flow.jpg' ),
				'thumb_image'      => array( 'url' => $theme_uri . '/assets/images/engine-oil-2.jpg' ),
			),
			array(
				'cat'              => 'Driveline',
				'icon'             => 'gear',
				'counter'          => '02 / 06',
				'cat_name'         => 'Driveline & Transmission Additives',
				'cat_desc'         => 'High-load extreme pressure packages designed for manual transmissions, planetary reduction gears, and hypoid differentials.',
				'eyebrow'          => 'Product Range — Driveline Fluids',
				'title'            => 'Extreme shear resistance. Pure gear durability.',
				'desc'             => 'High-load extreme pressure packages designed for manual transmissions, planetary reduction gears, hypoid differentials, and continuous heavy axle punishment.',
				'specs'            => 'API GL-5, TO-4, Off-Road, Heavy Axle, Extreme Pressure',
				'btn_primary_text' => 'Explore Specifications',
				'btn_primary_link' => array( 'url' => '#finder' ),
				'btn_sec_text'     => 'Request Formulation TDS',
				'btn_sec_link'     => array( 'url' => '#contact' ),
				'metric1_label'    => 'Gear Protection',
				'metric1_val'      => 'FZG Stage 12+',
				'metric2_label'    => 'Thermal Stability',
				'metric2_val'      => '160°C Bulk Oil',
				'metric3_label'    => 'Axle Durability',
				'metric3_val'      => '500,000 km Field Tested',
				'bg_image'         => array( 'url' => $theme_uri . '/assets/images/driveline.jpg' ),
				'thumb_image'      => array( 'url' => $theme_uri . '/assets/images/driveline-2.jpg' ),
			),
			array(
				'cat'              => 'Marine Additives',
				'icon'             => 'marine',
				'counter'          => '03 / 06',
				'cat_name'         => 'Marine Cylinder & System Lubricants',
				'cat_desc'         => 'Combating severe acidic corrosion and thermal stress across international ocean routes and heavy bunker propulsion.',
				'eyebrow'          => 'Product Range — Marine Propulsion Additives',
				'title'            => 'Neutralizing severe fuels across international oceans.',
				'desc'             => 'High Base Number (BN) cylinder packages combating acidic corrosion in slow-speed 2-stroke crosshead engines and heavy bunker propulsion machinery.',
				'specs'            => 'High Base Number (BN), Slow-Speed 2-Stroke, Trunk Piston, Cylinder Oil, Bunker Fuel',
				'btn_primary_text' => 'Explore Specifications',
				'btn_primary_link' => array( 'url' => '#finder' ),
				'btn_sec_text'     => 'Request Formulation TDS',
				'btn_sec_link'     => array( 'url' => '#contact' ),
				'metric1_label'    => 'Base Number Range',
				'metric1_val'      => 'BN 20 to 140',
				'metric2_label'    => 'Engine Compatibility',
				'metric2_val'      => '2-Stroke & 4-Stroke',
				'metric3_label'    => 'Corrosion Control',
				'metric3_val'      => 'Zero Liner Scuffing',
				'bg_image'         => array( 'url' => $theme_uri . '/assets/images/marine.jpg' ),
				'thumb_image'      => array( 'url' => $theme_uri . '/assets/images/marine-2.jpg' ),
			),
			array(
				'cat'              => 'Industrial Fluids',
				'icon'             => 'industrial',
				'counter'          => '04 / 06',
				'cat_name'         => 'Industrial Hydraulic & Circulating Fluids',
				'cat_desc'         => 'Ashless and zinc-containing chemistries with rapid demulsibility and superior anti-wear protection for manufacturing equipment.',
				'eyebrow'          => 'Product Range — Industrial Fluids',
				'title'            => 'High-pressure hydraulics. Zero downtime tolerance.',
				'desc'             => 'Ashless and zinc-containing additive chemistries with rapid demulsibility, superior anti-foaming, and anti-wear protection for manufacturing equipment.',
				'specs'            => 'DIN 51524 Part II/III, ISO 11158, Parker Denison HF-0, Eaton Vickers, Cincinnati Machine',
				'btn_primary_text' => 'Explore Specifications',
				'btn_primary_link' => array( 'url' => '#finder' ),
				'btn_sec_text'     => 'Request Formulation TDS',
				'btn_sec_link'     => array( 'url' => '#contact' ),
				'metric1_label'    => 'Filterability',
				'metric1_val'      => 'AFNOR Wet/Dry Pass',
				'metric2_label'    => 'Demulsibility',
				'metric2_val'      => '< 15 min at 54°C',
				'metric3_label'    => 'Pump Wear',
				'metric3_val'      => '< 15 mg Total Vane',
				'bg_image'         => array( 'url' => $theme_uri . '/assets/images/industrial.jpg' ),
				'thumb_image'      => array( 'url' => $theme_uri . '/assets/images/industrial-2.jpg' ),
			),
			array(
				'cat'              => 'Metalworking',
				'icon'             => 'metal',
				'counter'          => '05 / 06',
				'cat_name'         => 'Metalworking Fluids & Neat Oils',
				'cat_desc'         => 'Soluble oils, semi-synthetics, and neat cutting additives that reduce friction and resist microbial degradation under intense tool feeds.',
				'eyebrow'          => 'Product Range — Metalworking Chemistries',
				'title'            => 'Precision cutting chemistry. Superior tool longevity.',
				'desc'             => 'Soluble oils, semi-synthetics, and neat cutting additives that reduce friction, manage heat dissipation, and resist microbial degradation under intense tool feeds.',
				'specs'            => 'Neat Cutting, Soluble Oils, Semi-Synthetic, Chlorine-Free EP, Biostable Emulsions',
				'btn_primary_text' => 'Explore Specifications',
				'btn_primary_link' => array( 'url' => '#finder' ),
				'btn_sec_text'     => 'Request Formulation TDS',
				'btn_sec_link'     => array( 'url' => '#contact' ),
				'metric1_label'    => 'Tool Life Extension',
				'metric1_val'      => 'Up to +40%',
				'metric2_label'    => 'Sump Life',
				'metric2_val'      => 'Extended Biostability',
				'metric3_label'    => 'Environmental',
				'metric3_val'      => 'Chlorine & Boron Free',
				'bg_image'         => array( 'url' => $theme_uri . '/assets/images/metalworking.jpg' ),
				'thumb_image'      => array( 'url' => $theme_uri . '/assets/images/metalworking-2.jpg' ),
			),
			array(
				'cat'              => 'Components',
				'icon'             => 'component',
				'counter'          => '06 / 06',
				'cat_name'         => 'Specialty Components & VI Improvers',
				'cat_desc'         => 'Polyisobutylene (PIB), OCP Viscosity Index Improvers, pour point depressants (PPD), corrosion inhibitors, and tailored booster chemistries.',
				'eyebrow'          => 'Product Range — Chemical Components',
				'title'            => 'Fundamental molecular blocks. Tailored formulations.',
				'desc'             => 'Polyisobutylene (PIB), OCP Viscosity Index Improvers, pour point depressants (PPD), corrosion inhibitors, and tailored booster chemistries.',
				'specs'            => 'OCP Polymer (SSI 22/35), Polyisobutylene (PIB), PPD, Calcium Sulfonates, ZDDP',
				'btn_primary_text' => 'Explore Specifications',
				'btn_primary_link' => array( 'url' => '#packages' ),
				'btn_sec_text'     => 'Request Formulation TDS',
				'btn_sec_link'     => array( 'url' => '#contact' ),
				'metric1_label'    => 'Polymer Shear Stability',
				'metric1_val'      => 'SSI 22 & 35 Index',
				'metric2_label'    => 'Pour Point Depression',
				'metric2_val'      => 'Down to -45°C',
				'metric3_label'    => 'TBN Boosters',
				'metric3_val'      => '400 TBN Sulfonates',
				'bg_image'         => array( 'url' => $theme_uri . '/assets/images/components.jpg' ),
				'thumb_image'      => array( 'url' => $theme_uri . '/assets/images/components-2.jpg' ),
			),
		);
	}

	private function default_cards() {
		return array(
			array( 'cat' => 'Automotive',     'title' => 'Engine Oil Additives',   'sub' => 'Heavy Duty · Passenger Car · Motorcycle',  'count' => '22 products', 'link' => array( 'url' => '#finder' ) ),
			array( 'cat' => 'Transmission',   'title' => 'Driveline Additives',    'sub' => 'Gear · ATF · Manual · Off-Road',            'count' => '6 products',  'link' => array( 'url' => '#finder' ) ),
			array( 'cat' => 'Marine',         'title' => 'Marine Additives',       'sub' => 'Trunk Piston · System · Cylinder Oil',      'count' => '3 products',  'link' => array( 'url' => '#finder' ) ),
			array( 'cat' => 'Industrial',     'title' => 'Industrial Additives',   'sub' => 'Gear · Grease · Hydraulic · Slideway',      'count' => '8 products',  'link' => array( 'url' => '#finder' ) ),
			array( 'cat' => 'Metalworking',   'title' => 'Metalworking Fluids',    'sub' => 'Neat Cutting · Soluble Oil',                'count' => '6 products',  'link' => array( 'url' => '#finder' ) ),
			array( 'cat' => 'Building Blocks','title' => 'Lubricant Components',   'sub' => 'Detergents · Dispersants · VII · AO & more','count' => '30 products', 'link' => array( 'url' => '#packages' ) ),
		);
	}

	protected function register_controls() {

		$this->start_controls_section( 'layout_section', array(
			'label' => __( 'Layout & Style', 'addlar' ),
		) );

		$this->add_control( 'layout_style', array(
			'label'   => __( 'Layout Mode', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'tabbed_slider' => __( 'Full-Width Tabbed Slider (Stitch Design)', 'addlar' ),
				'grid'          => __( 'Classic 6-Card Grid', 'addlar' ),
			),
			'default' => 'tabbed_slider',
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor ID', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'products',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background (Grid mode only)', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'condition'    => array(
				'layout_style' => 'grid',
			),
		) );

		$this->end_controls_section();

		/* -------------------------------------------------------------------------
		 * Tabbed Slider Content Repeater
		 * ---------------------------------------------------------------------- */
		$this->start_controls_section( 'slider_section', array(
			'label'     => __( 'Showcase Slides', 'addlar' ),
			'condition' => array(
				'layout_style' => 'tabbed_slider',
			),
		) );

		$slide_rep = new Repeater();
		$slide_rep->add_control( 'cat', array( 'label' => __( 'Tab Label', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Engine Oils' ) );
		$slide_rep->add_control( 'icon', array(
			'label'   => __( 'Tab Icon', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'engine'     => __( 'Engine Oil', 'addlar' ),
				'gear'       => __( 'Driveline / Gear', 'addlar' ),
				'marine'     => __( 'Marine / Propulsion', 'addlar' ),
				'industrial' => __( 'Industrial Hydraulics', 'addlar' ),
				'metal'      => __( 'Metalworking', 'addlar' ),
				'component'  => __( 'Components / Molecule', 'addlar' ),
			),
			'default' => 'engine',
		) );
		$slide_rep->add_control( 'counter', array( 'label' => __( 'Counter (e.g. 01 / 06)', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '01 / 06' ) );
		$slide_rep->add_control( 'cat_name', array( 'label' => __( 'Category Name', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Automotive Engine Oil' ) );
		$slide_rep->add_control( 'cat_desc', array( 'label' => __( 'Short Descriptor', 'addlar' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '' ) );
		$slide_rep->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Product Range — Six Additive Families' ) );
		$slide_rep->add_control( 'title', array( 'label' => __( 'Headline', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'One partner. Every lubrication challenge.' ) );
		$slide_rep->add_control( 'desc', array( 'label' => __( 'Description', 'addlar' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => '' ) );
		$slide_rep->add_control( 'specs', array( 'label' => __( 'Specifications (comma separated)', 'addlar' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '' ) );
		$slide_rep->add_control( 'btn_primary_text', array( 'label' => __( 'Primary CTA Text', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Explore Specifications' ) );
		$slide_rep->add_control( 'btn_primary_link', array( 'label' => __( 'Primary CTA Link', 'addlar' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#finder' ) ) );
		$slide_rep->add_control( 'btn_sec_text', array( 'label' => __( 'Secondary CTA Text', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Request Formulation TDS' ) );
		$slide_rep->add_control( 'btn_sec_link', array( 'label' => __( 'Secondary CTA Link', 'addlar' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#contact' ) ) );
		$slide_rep->add_control( 'metric1_label', array( 'label' => __( 'Metric 1 Label', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Certification' ) );
		$slide_rep->add_control( 'metric1_val', array( 'label' => __( 'Metric 1 Value', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'API & ACEA Dual' ) );
		$slide_rep->add_control( 'metric2_label', array( 'label' => __( 'Metric 2 Label', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Viscosity Range' ) );
		$slide_rep->add_control( 'metric2_val', array( 'label' => __( 'Metric 2 Value', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '0W-16 to 50 Grade' ) );
		$slide_rep->add_control( 'metric3_label', array( 'label' => __( 'Metric 3 Label', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Supply Guarantee' ) );
		$slide_rep->add_control( 'metric3_val', array( 'label' => __( 'Metric 3 Value', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Direct Blending & Bulk' ) );
		$slide_rep->add_control( 'bg_image', array( 'label' => __( 'Background Image', 'addlar' ), 'type' => Controls_Manager::MEDIA ) );
		$slide_rep->add_control( 'thumb_image', array( 'label' => __( 'Thumbnail Image', 'addlar' ), 'type' => Controls_Manager::MEDIA ) );

		$this->add_control( 'slides', array(
			'label'       => __( 'Category Slides', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $slide_rep->get_controls(),
			'title_field' => '{{{ cat }}}',
			'default'     => $this->default_slides(),
		) );

		$this->end_controls_section();

		/* -------------------------------------------------------------------------
		 * Classic Grid Controls (Preserved for backwards compatibility)
		 * ---------------------------------------------------------------------- */
		$this->start_controls_section( 'head', array(
			'label'     => __( 'Grid Heading', 'addlar' ),
			'condition' => array(
				'layout_style' => 'grid',
			),
		) );

		$this->add_heading_controls(
			__( 'Product Range', 'addlar' ),
			__( 'One partner. Every lubrication challenge.', 'addlar' ),
			__( 'Six additive families plus complementary products — each engineered for a specific world of machinery.', 'addlar' )
		);

		$this->end_controls_section();

		$this->start_controls_section( 'cards_section', array(
			'label'     => __( 'Grid Cards', 'addlar' ),
			'condition' => array(
				'layout_style' => 'grid',
			),
		) );

		$rep = new Repeater();
		$rep->add_control( 'image', array( 'label' => __( 'Image', 'addlar' ), 'type' => Controls_Manager::MEDIA ) );
		$rep->add_control( 'cat', array( 'label' => __( 'Category', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => 'Automotive' ) );
		$rep->add_control( 'title', array( 'label' => __( 'Title', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'sub', array( 'label' => __( 'Sub-line', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'count', array( 'label' => __( 'Count label', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'link', array( 'label' => __( 'Link', 'addlar' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#finder' ) ) );

		$this->add_control( 'cards', array(
			'label'       => __( 'Cards', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => $this->default_cards(),
		) );

		$this->add_control( 'mark', array(
			'label'       => __( 'Corner mark', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'ADDLAR droplet overlaid on every card image.', 'addlar' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'promo', array(
			'label'     => __( 'Complementary row', 'addlar' ),
			'condition' => array(
				'layout_style' => 'grid',
			),
		) );

		$this->add_control( 'promo_cat', array( 'label' => __( 'Kicker', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Complementary', 'addlar' ) ) );
		$this->add_control( 'promo_title', array( 'label' => __( 'Title', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Brake Fluids & Customised Solutions', 'addlar' ) ) );
		$this->add_control( 'promo_btn', array( 'label' => __( 'Button', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Talk to us →', 'addlar' ) ) );
		$this->add_control( 'promo_link', array( 'label' => __( 'Button link', 'addlar' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#contact' ) ) );

		$this->end_controls_section();

		/* -------------------------------------------------------------------------
		 * Style Tab Controls
		 * ---------------------------------------------------------------------- */
		$this->start_controls_section( 'style_showcase_section', array(
			'label'     => __( 'Showcase Colors & Accents', 'addlar' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => array(
				'layout_style' => 'tabbed_slider',
			),
		) );

		$this->add_control( 'active_tab_bg', array(
			'label'     => __( 'Active Tab & Accent Color', 'addlar' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#D32F2F',
			'selectors' => array(
				'{{WRAPPER}} .showcase-tab-btn.active'     => 'background-color: {{VALUE}} !important;',
				'{{WRAPPER}} .showcase-btn-primary'        => 'background-color: {{VALUE}} !important;',
				'{{WRAPPER}} .showcase-counter'            => 'color: {{VALUE}} !important;',
				'{{WRAPPER}} .showcase-eyebrow-dot'        => 'background-color: {{VALUE}} !important;',
				'{{WRAPPER}} .showcase-arrow-btn.next'     => 'border-color: {{VALUE}} !important; color: #FFFFFF !important;',
			),
		) );

		$this->end_controls_section();
	}

	private function render_tab_svg( $icon ) {
		switch ( $icon ) {
			case 'gear':
				echo '<svg viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>';
				break;
			case 'marine':
				echo '<svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
				break;
			case 'industrial':
				echo '<svg viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>';
				break;
			case 'metal':
				echo '<svg viewBox="0 0 24 24"><path d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L10.757 4.757a3 3 0 114.243 4.243L12.12 11.88"></path></svg>';
				break;
			case 'component':
				echo '<svg viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>';
				break;
			case 'engine':
			default:
				echo '<svg viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>';
				break;
		}
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$layout = ! empty( $s['layout_style'] ) ? $s['layout_style'] : 'tabbed_slider';

		if ( 'tabbed_slider' === $layout ) {
			$this->render_tabbed_slider( $s );
		} else {
			$this->render_grid( $s );
		}
	}

	private function render_tabbed_slider( $s ) {
		$slides_raw = ! empty( $s['slides'] ) ? $s['slides'] : $this->default_slides();
		$anchor_id  = ! empty( $s['anchor'] ) ? $s['anchor'] : 'products';
		$unique_id  = 'showcase-' . $this->get_id();

		// Normalize slides data for JS
		$slides_data = array();
		foreach ( (array) $slides_raw as $idx => $slide ) {
			$bg_url = $this->media_url( isset( $slide['bg_image'] ) ? $slide['bg_image'] : array(), 'full' );
			if ( empty( $bg_url ) && ! empty( $slide['bg_image']['url'] ) ) {
				$bg_url = $slide['bg_image']['url'];
			}
			$thumb_url = $this->media_url( isset( $slide['thumb_image'] ) ? $slide['thumb_image'] : array(), 'full' );
			if ( empty( $thumb_url ) && ! empty( $slide['thumb_image']['url'] ) ) {
				$thumb_url = $slide['thumb_image']['url'];
			}

			// Parse specs into array
			$specs_list = array();
			if ( ! empty( $slide['specs'] ) ) {
				$specs_list = array_map( 'trim', explode( ',', $slide['specs'] ) );
			}

			$slides_data[] = array(
				'id'            => $idx,
				'cat'           => ! empty( $slide['cat'] ) ? $slide['cat'] : '',
				'icon'          => ! empty( $slide['icon'] ) ? $slide['icon'] : 'engine',
				'counter'       => ! empty( $slide['counter'] ) ? $slide['counter'] : sprintf( '%02d / %02d', $idx + 1, count( $slides_raw ) ),
				'catName'       => ! empty( $slide['cat_name'] ) ? $slide['cat_name'] : '',
				'catDesc'       => ! empty( $slide['cat_desc'] ) ? $slide['cat_desc'] : '',
				'eyebrow'       => ! empty( $slide['eyebrow'] ) ? $slide['eyebrow'] : '',
				'title'         => ! empty( $slide['title'] ) ? $slide['title'] : '',
				'desc'          => ! empty( $slide['desc'] ) ? $slide['desc'] : '',
				'specs'         => $specs_list,
				'btnPrimary'    => array(
					'text' => ! empty( $slide['btn_primary_text'] ) ? $slide['btn_primary_text'] : __( 'Explore Specifications', 'addlar' ),
					'url'  => ! empty( $slide['btn_primary_link']['url'] ) ? $slide['btn_primary_link']['url'] : '#finder',
				),
				'btnSec'        => array(
					'text' => ! empty( $slide['btn_sec_text'] ) ? $slide['btn_sec_text'] : __( 'Request Formulation TDS', 'addlar' ),
					'url'  => ! empty( $slide['btn_sec_link']['url'] ) ? $slide['btn_sec_link']['url'] : '#contact',
				),
				'metric1Label'  => ! empty( $slide['metric1_label'] ) ? $slide['metric1_label'] : '',
				'metric1Val'    => ! empty( $slide['metric1_val'] ) ? $slide['metric1_val'] : '',
				'metric2Label'  => ! empty( $slide['metric2_label'] ) ? $slide['metric2_label'] : '',
				'metric2Val'    => ! empty( $slide['metric2_val'] ) ? $slide['metric2_val'] : '',
				'metric3Label'  => ! empty( $slide['metric3_label'] ) ? $slide['metric3_label'] : '',
				'metric3Val'    => ! empty( $slide['metric3_val'] ) ? $slide['metric3_val'] : '',
				'bgImage'       => $bg_url,
				'thumbImage'    => $thumb_url,
			);
		}

		$first = ! empty( $slides_data[0] ) ? $slides_data[0] : array();
		?>
		<div class="adl">
			<section class="section prod-slider-showcase" id="<?php echo esc_attr( $anchor_id ); ?>" data-widget-id="<?php echo esc_attr( $unique_id ); ?>">
			<!-- Cinematic Background Slide -->
			<div class="showcase-bg-layer">
				<img id="<?php echo esc_attr( $unique_id ); ?>-bg-img" class="showcase-bg-img" src="<?php echo esc_url( $first['bgImage'] ); ?>" alt="<?php echo esc_attr( $first['title'] ); ?>">
				<div class="showcase-overlay-gradient"></div>
				<div class="showcase-overlay-vignette"></div>
				<div class="showcase-grid-pattern"></div>
			</div>

			<!-- Main Showcase Grid Content -->
			<div class="showcase-main-body">
				<!-- Left Column: Primary Content -->
				<div class="showcase-left">
					<div class="showcase-eyebrow-wrap" style="margin-bottom: 20px;">
						<span class="eyebrow" id="<?php echo esc_attr( $unique_id ); ?>-eyebrow"><?php echo esc_html( $first['eyebrow'] ); ?></span>
					</div>

					<h2 id="<?php echo esc_attr( $unique_id ); ?>-title" class="showcase-headline"><?php echo esc_html( $first['title'] ); ?></h2>

					<p id="<?php echo esc_attr( $unique_id ); ?>-desc" class="showcase-desc"><?php echo esc_html( $first['desc'] ); ?></p>

					<div id="<?php echo esc_attr( $unique_id ); ?>-specs" class="showcase-specs">
						<?php foreach ( (array) $first['specs'] as $spec_idx => $spec_item ) : ?>
							<?php $is_accent = ( false !== stripos( $spec_item, 'Viscometry' ) ); ?>
							<span class="showcase-spec-pill <?php echo $is_accent ? 'accent' : ''; ?>"><?php echo esc_html( $spec_item ); ?></span>
						<?php endforeach; ?>
					</div>

					<div class="showcase-actions">
						<a id="<?php echo esc_attr( $unique_id ); ?>-btn-primary" class="showcase-btn-primary" href="<?php echo esc_url( $first['btnPrimary']['url'] ); ?>">
							<span><?php echo esc_html( $first['btnPrimary']['text'] ); ?></span>
							<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
						</a>
						<a id="<?php echo esc_attr( $unique_id ); ?>-btn-sec" class="showcase-btn-secondary" href="<?php echo esc_url( $first['btnSec']['url'] ); ?>">
							<span><?php echo esc_html( $first['btnSec']['text'] ); ?></span>
							<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
						</a>
					</div>

					<div class="showcase-metrics">
						<div>
							<div id="<?php echo esc_attr( $unique_id ); ?>-m1-label" class="showcase-metric-label"><?php echo esc_html( $first['metric1Label'] ); ?></div>
							<div id="<?php echo esc_attr( $unique_id ); ?>-m1-val" class="showcase-metric-val"><?php echo esc_html( $first['metric1Val'] ); ?></div>
						</div>
						<div>
							<div id="<?php echo esc_attr( $unique_id ); ?>-m2-label" class="showcase-metric-label"><?php echo esc_html( $first['metric2Label'] ); ?></div>
							<div id="<?php echo esc_attr( $unique_id ); ?>-m2-val" class="showcase-metric-val"><?php echo esc_html( $first['metric2Val'] ); ?></div>
						</div>
						<div>
							<div id="<?php echo esc_attr( $unique_id ); ?>-m3-label" class="showcase-metric-label"><?php echo esc_html( $first['metric3Label'] ); ?></div>
							<div id="<?php echo esc_attr( $unique_id ); ?>-m3-val" class="showcase-metric-val"><?php echo esc_html( $first['metric3Val'] ); ?></div>
						</div>
					</div>
				</div>

				<!-- Right Column: Navigation Controls & Previews -->
				<div class="showcase-right">
					<div>
						<div id="<?php echo esc_attr( $unique_id ); ?>-counter" class="showcase-counter"><?php echo esc_html( $first['counter'] ); ?></div>
						<h3 id="<?php echo esc_attr( $unique_id ); ?>-cat-name" class="showcase-cat-name"><?php echo esc_html( $first['catName'] ); ?></h3>
						<p id="<?php echo esc_attr( $unique_id ); ?>-cat-desc" class="showcase-cat-desc"><?php echo esc_html( $first['catDesc'] ); ?></p>
					</div>

					<div class="showcase-arrows">
						<button type="button" class="showcase-arrow-btn prev" id="<?php echo esc_attr( $unique_id ); ?>-prev-btn" aria-label="<?php esc_attr_e( 'Previous Category', 'addlar' ); ?>">
							<svg viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
						<button type="button" class="showcase-arrow-btn next" id="<?php echo esc_attr( $unique_id ); ?>-next-btn" aria-label="<?php esc_attr_e( 'Next Category', 'addlar' ); ?>">
							<svg viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
					</div>

					<div id="<?php echo esc_attr( $unique_id ); ?>-mini-previews" class="showcase-mini-previews">
						<?php
						// Show next 3 thumbnails
						$total_count = count( $slides_data );
						for ( $t = 1; $t <= 3; $t++ ) {
							$target_idx   = ( 0 + $t ) % $total_count;
							$target_slide = $slides_data[ $target_idx ];
							$thumb_src    = ! empty( $target_slide['thumbImage'] ) ? $target_slide['thumbImage'] : $target_slide['bgImage'];
							?>
							<div class="showcase-mini-thumb" data-goto-index="<?php echo esc_attr( $target_idx ); ?>" title="<?php echo esc_attr( $target_slide['cat'] ); ?>">
								<img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php echo esc_attr( $target_slide['cat'] ); ?>">
								<span><?php echo esc_html( sprintf( '%02d', $target_idx + 1 ) ); ?></span>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>

			<!-- Bottom Floating Dock Tab Bar -->
			<div class="showcase-dock">
				<nav class="showcase-tab-bar" aria-label="<?php esc_attr_e( 'Additive Categories', 'addlar' ); ?>">
					<?php foreach ( $slides_data as $btn_idx => $tab_item ) : ?>
						<button type="button" class="showcase-tab-btn <?php echo ( 0 === $btn_idx ) ? 'active' : ''; ?>" data-slide-index="<?php echo esc_attr( $btn_idx ); ?>">
							<?php $this->render_tab_svg( $tab_item['icon'] ); ?>
							<span><?php echo esc_html( $tab_item['cat'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</nav>
			</div>
		</section>
		</div>

		<script>
		(function() {
			var uid = '<?php echo esc_js( $unique_id ); ?>';
			var slides = <?php echo wp_json_encode( $slides_data ); ?>;
			if (!slides || !slides.length) return;

			var currentIndex = 0;
			var total = slides.length;

			var bgImg = document.getElementById(uid + '-bg-img');
			var eyebrow = document.getElementById(uid + '-eyebrow');
			var title = document.getElementById(uid + '-title');
			var desc = document.getElementById(uid + '-desc');
			var specsBox = document.getElementById(uid + '-specs');
			var btnPrimary = document.getElementById(uid + '-btn-primary');
			var btnSec = document.getElementById(uid + '-btn-sec');
			var m1Label = document.getElementById(uid + '-m1-label');
			var m1Val = document.getElementById(uid + '-m1-val');
			var m2Label = document.getElementById(uid + '-m2-label');
			var m2Val = document.getElementById(uid + '-m2-val');
			var m3Label = document.getElementById(uid + '-m3-label');
			var m3Val = document.getElementById(uid + '-m3-val');
			var counter = document.getElementById(uid + '-counter');
			var catName = document.getElementById(uid + '-cat-name');
			var catDesc = document.getElementById(uid + '-cat-desc');
			var miniPreviews = document.getElementById(uid + '-mini-previews');
			var section = document.querySelector('[data-widget-id="' + uid + '"]');

			function switchSlide(idx) {
				if (idx < 0) idx = total - 1;
				if (idx >= total) idx = 0;
				currentIndex = idx;

				var item = slides[currentIndex];
				if (!item) return;

				// Background crossfade
				if (bgImg && item.bgImage) {
					bgImg.style.opacity = '0.35';
					setTimeout(function() {
						bgImg.src = item.bgImage;
						bgImg.style.opacity = '1';
					}, 160);
				}

				// Text updates with gentle opacity
				if (eyebrow) eyebrow.textContent = item.eyebrow;
				if (title) title.textContent = item.title;
				if (desc) desc.textContent = item.desc;
				if (counter) counter.textContent = item.counter;
				if (catName) catName.textContent = item.catName;
				if (catDesc) catDesc.textContent = item.catDesc;

				// Specs pills
				if (specsBox && item.specs) {
					specsBox.innerHTML = '';
					item.specs.forEach(function(sp) {
						var pill = document.createElement('span');
						var isAccent = sp.toLowerCase().indexOf('viscometry') !== -1;
						pill.className = 'showcase-spec-pill' + (isAccent ? ' accent' : '');
						pill.textContent = sp;
						specsBox.appendChild(pill);
					});
				}

				// Buttons
				if (btnPrimary && item.btnPrimary) {
					btnPrimary.href = item.btnPrimary.url;
					var pSpan = btnPrimary.querySelector('span');
					if (pSpan) pSpan.textContent = item.btnPrimary.text;
				}
				if (btnSec && item.btnSec) {
					btnSec.href = item.btnSec.url;
					var sSpan = btnSec.querySelector('span');
					if (sSpan) sSpan.textContent = item.btnSec.text;
				}

				// Metrics
				if (m1Label) m1Label.textContent = item.metric1Label;
				if (m1Val) m1Val.textContent = item.metric1Val;
				if (m2Label) m2Label.textContent = item.metric2Label;
				if (m2Val) m2Val.textContent = item.metric2Val;
				if (m3Label) m3Label.textContent = item.metric3Label;
				if (m3Val) m3Val.textContent = item.metric3Val;

				// Update Tabs Active state
				if (section) {
					var tabBtns = section.querySelectorAll('.showcase-tab-btn');
					tabBtns.forEach(function(btn, bIdx) {
						if (bIdx === currentIndex) {
							btn.classList.add('active');
						} else {
							btn.classList.remove('active');
						}
					});
				}

				// Update Mini Thumbnails
				if (miniPreviews) {
					miniPreviews.innerHTML = '';
					for (var t = 1; t <= 3; t++) {
						var tIdx = (currentIndex + t) % total;
						var tSlide = slides[tIdx];
						var tSrc = tSlide.thumbImage || tSlide.bgImage;
						var thumbDiv = document.createElement('div');
						thumbDiv.className = 'showcase-mini-thumb';
						thumbDiv.title = tSlide.cat;
						thumbDiv.dataset.gotoIndex = tIdx;
						thumbDiv.innerHTML = '<img src="' + tSrc + '" alt="' + tSlide.cat + '"><span>' + String(tIdx + 1).padStart(2, '0') + '</span>';
						thumbDiv.addEventListener('click', function() {
							switchSlide(parseInt(this.dataset.gotoIndex, 10));
						});
						miniPreviews.appendChild(thumbDiv);
					}
				}
			}

			// Attach Tab Button Clicks
			if (section) {
				var tabButtons = section.querySelectorAll('.showcase-tab-btn');
				tabButtons.forEach(function(btn) {
					btn.addEventListener('click', function() {
						var idx = parseInt(this.dataset.slideIndex, 10);
						switchSlide(idx);
					});
				});
			}

			// Attach Arrow Clicks
			var prevBtn = document.getElementById(uid + '-prev-btn');
			var nextBtn = document.getElementById(uid + '-next-btn');
			if (prevBtn) {
				prevBtn.addEventListener('click', function() {
					switchSlide(currentIndex - 1);
				});
			}
			if (nextBtn) {
				nextBtn.addEventListener('click', function() {
					switchSlide(currentIndex + 1);
				});
			}

			// Attach Initial Mini Thumbnails Click
			if (miniPreviews) {
				var initialThumbs = miniPreviews.querySelectorAll('.showcase-mini-thumb');
				initialThumbs.forEach(function(th) {
					th.addEventListener('click', function() {
						switchSlide(parseInt(this.dataset.gotoIndex, 10));
					});
				});
			}
		})();
		</script>
		<?php
	}

	private function render_grid( $s ) {
		$mark = $this->media_url( isset( $s['mark'] ) ? $s['mark'] : array(), 'full' );

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : 'products'
		);
		?>
		<div class="wrap center">
			<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
		</div>
		<div class="wrap">
			<div class="prod-grid">
				<?php foreach ( (array) $s['cards'] as $card ) : ?>
					<?php $url = ! empty( $card['link']['url'] ) ? $card['link']['url'] : '#'; ?>
					<a class="pcard reveal" href="<?php echo esc_url( $url ); ?>">
						<div class="imgwrap">
							<?php $this->render_media( $card['image'], $card['title'] ); ?>
							<?php if ( $mark ) : ?>
								<img class="cmark" src="<?php echo esc_url( $mark ); ?>" alt="">
							<?php endif; ?>
						</div>
						<div class="body">
							<span class="cat"><?php echo esc_html( $card['cat'] ); ?></span>
							<h3><?php echo esc_html( $card['title'] ); ?></h3>
							<div class="sub"><?php echo esc_html( $card['sub'] ); ?></div>
							<div class="foot">
								<span class="cnt"><?php echo esc_html( $card['count'] ); ?></span>
								<span class="arw">&rarr;</span>
							</div>
						</div>
					</a>
				<?php endforeach; ?>

				<?php if ( ! empty( $s['promo_title'] ) ) : ?>
					<div class="pcard-comp reveal">
						<div>
							<span class="cat"><?php echo esc_html( $s['promo_cat'] ); ?></span>
							<h3><?php echo esc_html( $s['promo_title'] ); ?></h3>
						</div>
						<?php $this->render_button( $s['promo_btn'], $s['promo_link'], 'btn-red' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
