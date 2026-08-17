<?php
/**
 * One product data section — reads a single pre-rendered `_addlar_*_html`
 * fragment (or the raw description/viscosity text) straight from the
 * current product's post meta and prints it inside the theme's `.adl`
 * scope, so it picks up .spec-table/.chip-list/.formulation-list styling.
 *
 * Renders nothing when the product has no data for the chosen fragment
 * (e.g. KC420/Z 2612 have no performance table, 9342 has no approvals) —
 * that's real, not a bug: several of the 22 documented products genuinely
 * don't carry every section.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_ProductFragment extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_product_fragment';
	}

	public function get_title() {
		return __( 'ADDLAR Product Fragment', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-table';
	}

	/** fragment key => array( meta_key, is_prerendered_html, default label ). */
	private function fragment_map() {
		return array(
			'description' => array( '_addlar_description', false, '' ),
			'applications' => array( '_addlar_applications_html', true, __( 'Applications', 'addlar' ) ),
			'performance' => array( '_addlar_performance_table_html', true, __( 'Performance Levels', 'addlar' ) ),
			'approvals'   => array( '_addlar_approvals_html', true, __( 'OEM & Industry Approvals', 'addlar' ) ),
			'formulation' => array( '_addlar_formulation_html', true, __( 'Formulation Example', 'addlar' ) ),
			'properties'  => array( '_addlar_properties_table_html', true, __( 'Typical Properties', 'addlar' ) ),
			'viscosity'   => array( '_addlar_viscosity_note', false, __( 'Viscosity Grades', 'addlar' ) ),
		);
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$options = array();
		foreach ( $this->fragment_map() as $key => $row ) {
			$options[ $key ] = $row[2] ? $row[2] : __( 'Description', 'addlar' );
		}

		$this->add_control( 'fragment', array(
			'label'   => __( 'Section', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $options,
			'default' => 'description',
		) );

		$this->add_control( 'heading_override', array(
			'label'       => __( 'Heading override', 'addlar' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'description' => __( 'Leave blank to use the section\'s default label (or no heading, for Description).', 'addlar' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$post_id = get_the_ID();
		if ( ! $post_id || 'addlar_product' !== get_post_type( $post_id ) ) {
			return;
		}

		$s   = $this->get_settings_for_display();
		$map = $this->fragment_map();
		$key = isset( $map[ $s['fragment'] ] ) ? $s['fragment'] : 'description';
		list( $meta_key, $is_html, $default_label ) = $map[ $key ];

		$raw = get_post_meta( $post_id, $meta_key, true );
		if ( '' === trim( wp_strip_all_tags( (string) $raw ) ) ) {
			return; // Nothing to show for this product — no empty section left behind.
		}

		$label = '' !== $s['heading_override'] ? $s['heading_override'] : $default_label;

		$this->open_section( 'section', '' );
		?>
		<div class="wrap" style="max-width:860px;">
			<?php if ( $label ) : ?>
				<p class="spec-table-note"><?php echo esc_html( $label ); ?></p>
			<?php endif; ?>
			<?php if ( $is_html ) : ?>
				<?php echo wp_kses_post( $raw ); ?>
			<?php else : ?>
				<div class="spec-description"><?php echo wp_kses_post( wpautop( $raw ) ); ?></div>
			<?php endif; ?>
		</div>
		<?php
		$this->close_section();
	}
}
