<?php
/**
 * Standalone chip/tag list — applications, viscosity grades, or any short
 * scannable list. One item per line, typed directly into Elementor.
 *
 * Replaces the meta-backed chip fragment. Chips are bordered and sized to
 * read as deliberate UI (matching the homepage's `.map` spec chips and
 * Finder pills) rather than the small grey pills the first pass used,
 * which looked incidental next to the homepage's components.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_ChipList extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_chip_list';
	}

	public function get_title() {
		return __( 'ADDLAR Chip List', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-tags';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
		) );

		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'items', array(
			'label'       => __( 'Items', 'addlar' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 10,
			'default'     => '',
			'description' => __( 'One per line.', 'addlar' ),
		) );

		$this->add_control( 'style', array(
			'label'   => __( 'Style', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'outline' => __( 'Outlined', 'addlar' ),
				'solid'   => __( 'Solid', 'addlar' ),
			),
			'default' => 'outline',
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = array();
		foreach ( preg_split( '/\r\n|\r|\n/', (string) $s['items'] ) as $line ) {
			$line = trim( $line );
			if ( '' !== $line ) {
				$items[] = $line;
			}
		}
		if ( ! $items ) {
			return;
		}

		$this->open_section(
			'yes' === $s['soft'] ? 'section section-tight soft' : 'section section-tight',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<div class="wrap">
			<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
				<span class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $s['title'] ) ) : ?>
				<h2 class="title spec-table-title"><?php echo esc_html( $s['title'] ); ?></h2>
			<?php endif; ?>
			<ul class="chip-list chips-<?php echo esc_attr( $s['style'] ); ?>">
				<?php foreach ( $items as $item ) : ?>
					<li class="chip"><?php echo esc_html( $item ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		$this->close_section();
	}
}
