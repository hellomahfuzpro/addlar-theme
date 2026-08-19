<?php
/**
 * Standalone data table — headers + rows typed straight into Elementor,
 * no post meta and no pre-rendered HTML fragment behind it.
 *
 * Replaces the previous meta-backed table pipeline (custom-field textareas
 * → `save_post` → stored HTML → a widget that echoed it). The client asked
 * for widget inputs instead of custom fields, and this is also simply
 * fewer moving parts: what you type in Elementor is what renders.
 *
 * Blank cells render an em dash rather than an empty cell, so a genuinely
 * unreported lab value stays visibly unreported instead of looking like a
 * layout bug.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_SpecTable extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_spec_table';
	}

	public function get_title() {
		return __( 'ADDLAR Spec Table', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-table';
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

		$this->add_control( 'note', array(
			'label'   => __( 'Note above the table', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'headers', array(
			'label'       => __( 'Column headers', 'addlar' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'description' => __( 'Separated by | — e.g. <code>Level | Treat Rate % | TBN</code>', 'addlar' ),
		) );

		$this->add_control( 'rows', array(
			'label'       => __( 'Rows', 'addlar' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 12,
			'default'     => '',
			'description' => __( 'One row per line, cells separated by | — same column count as the headers. Leave a cell empty for an unreported value; it renders as “—”.', 'addlar' ),
		) );

		$this->end_controls_section();
	}

	/** Split "a | b | c" into trimmed cells, keeping empties so columns don't shift. */
	private function cells( $line ) {
		return array_map( 'trim', explode( '|', (string) $line ) );
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$headers = array_values( array_filter( $this->cells( $s['headers'] ), function ( $h ) {
			return '' !== $h;
		} ) );

		$rows = array();
		foreach ( preg_split( '/\r\n|\r|\n/', (string) $s['rows'] ) as $line ) {
			if ( '' !== trim( $line ) ) {
				$rows[] = $this->cells( $line );
			}
		}

		if ( ! $headers || ! $rows ) {
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
			<?php if ( ! empty( $s['note'] ) ) : ?>
				<p class="spec-table-note"><?php echo esc_html( $s['note'] ); ?></p>
			<?php endif; ?>

			<div class="spec-table-wrap">
				<table class="spec-table">
					<thead>
						<tr>
							<?php foreach ( $headers as $h ) : ?>
								<th><?php echo esc_html( $h ); ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $rows as $cells ) : ?>
							<tr>
								<?php foreach ( $headers as $i => $h ) : ?>
									<?php $cell = isset( $cells[ $i ] ) ? trim( $cells[ $i ] ) : ''; ?>
									<td><?php echo '' !== $cell ? esc_html( $cell ) : '&#8212;'; ?></td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
