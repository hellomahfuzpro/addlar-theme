<?php
/**
 * Product range — six family cards plus the wide complementary promo row.
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
		return __( 'ADDLAR Product Grid', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
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

		$this->start_controls_section( 'head', array(
			'label' => __( 'Heading', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'products',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_heading_controls(
			__( 'Product Range', 'addlar' ),
			__( 'One partner. Every lubrication challenge.', 'addlar' ),
			__( 'Six additive families plus complementary products — each engineered for a specific world of machinery.', 'addlar' )
		);

		$this->end_controls_section();

		$this->start_controls_section( 'cards_section', array(
			'label' => __( 'Cards', 'addlar' ),
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
			'label' => __( 'Complementary row', 'addlar' ),
		) );

		$this->add_control( 'promo_cat', array( 'label' => __( 'Kicker', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Complementary', 'addlar' ) ) );
		$this->add_control( 'promo_title', array( 'label' => __( 'Title', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Brake Fluids & Customised Solutions', 'addlar' ) ) );
		$this->add_control( 'promo_btn', array( 'label' => __( 'Button', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Talk to us →', 'addlar' ) ) );
		$this->add_control( 'promo_link', array( 'label' => __( 'Button link', 'addlar' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#contact' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$mark = $this->media_url( isset( $s['mark'] ) ? $s['mark'] : array(), 'full' );

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
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
