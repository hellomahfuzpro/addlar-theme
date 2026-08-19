<?php
/**
 * Numbers — dark band of count-up statistics over a greyscale backdrop.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_StatBand extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_stat_band';
	}

	public function get_title() {
		return __( 'ADDLAR Stat Band', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-counter';
	}

	private function default_stats() {
		return array(
			// The market figure is a placeholder pending a source — see CONTENT-CHECKLIST.md.
			array( 'count' => '17',    'prefix' => '$', 'suffix' => 'B+', 'comma' => '', 'label' => "Lubricant additive<br>market served" ),
			array( 'count' => '50',    'prefix' => '',  'suffix' => '+',  'comma' => '', 'label' => "Years of combined<br>industry experience" ),
			array( 'count' => '45',    'prefix' => '',  'suffix' => '',   'comma' => '', 'label' => "Products &amp;<br>additive packages" ),
			array( 'count' => '10000', 'prefix' => '',  'suffix' => '+',  'comma' => 'yes', 'label' => "MT supplied<br>annually" ),
			array( 'count' => '25',    'prefix' => '',  'suffix' => '+',  'comma' => '', 'label' => "Countries<br>served" ),
		);
	}

	protected function register_controls() {

		$this->start_controls_section( 'head', array(
			'label' => __( 'Heading', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'numbers',
		) );

		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'ADDLAR by the numbers', 'addlar' ),
		) );

		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Scale you can formulate against.', 'addlar' ),
		) );

		$this->add_control( 'bg', array(
			'label'       => __( 'Background image', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'Rendered greyscale at 16% opacity behind the band.', 'addlar' ),
		) );

		$this->add_control( 'columns', array(
			'label'       => __( 'Columns', 'addlar' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array( '5' => '5 (default)', '4' => '4', '3' => '3', '2' => '2' ),
			'default'     => '5',
			'description' => __( 'Match this to how many statistics you actually add below.', 'addlar' ),
		) );

		$this->add_control( 'stage_image', array(
			'label'       => __( 'Hexagon image', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'Optional. Shown hex-clipped beside the numbers, matching the homepage Applications section. Leave empty for the full-width number band.', 'addlar' ),
		) );

		$this->add_control( 'stage_drop', array(
			'label' => __( 'Centre mark', 'addlar' ),
			'type'  => Controls_Manager::MEDIA,
		) );

		$this->add_control( 'stage_caption', array(
			'label'   => __( 'Caption under the hexagon', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'stats_section', array(
			'label' => __( 'Statistics', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'count', array(
			'label'       => __( 'Target number', 'addlar' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '25',
			'description' => __( 'Digits only — the count-up animation reads this.', 'addlar' ),
		) );
		$rep->add_control( 'prefix', array( 'label' => __( 'Prefix', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'suffix', array( 'label' => __( 'Suffix', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '+' ) );
		$rep->add_control( 'comma', array(
			'label'        => __( 'Thousands separator', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		) );
		$rep->add_control( 'label', array(
			'label'       => __( 'Label', 'addlar' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 2,
			'description' => __( 'Use &lt;br&gt; to control the line break.', 'addlar' ),
			'default'     => '',
		) );

		$this->add_control( 'stats', array(
			'label'       => __( 'Statistics', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ prefix }}}{{{ count }}}{{{ suffix }}}',
			'default'     => $this->default_stats(),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$bg = $this->media_url( isset( $s['bg'] ) ? $s['bg'] : array(), 'full' );

		printf(
			'<div class="adl"><section class="nums"%s>',
			! empty( $s['anchor'] ) ? ' id="' . esc_attr( $s['anchor'] ) . '"' : ''
		);

		if ( $bg ) {
			printf( '<img class="nbg" src="%s" alt="">', esc_url( $bg ) );
		}
		?>
		<div class="wrap center">
			<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
				<span class="eyebrow" style="color:#fff"><?php echo esc_html( $s['eyebrow'] ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $s['title'] ) ) : ?>
				<h2 style="margin-top:16px"><?php echo esc_html( $s['title'] ); ?></h2>
			<?php endif; ?>
		</div>
		<?php
		$stage = $this->media_url( isset( $s['stage_image'] ) ? $s['stage_image'] : array(), 'full' );
		$drop  = $this->media_url( isset( $s['stage_drop'] ) ? $s['stage_drop'] : array(), 'full' );
		// With a hexagon the numbers stack beside it rather than spanning the
		// full width — used on product pages that have no Applications
		// section, so the hexagon treatment still appears somewhere.
		$cols  = $stage ? '2' : ( ! empty( $s['columns'] ) ? $s['columns'] : '5' );
		?>
		<div class="wrap">
			<div class="<?php echo $stage ? 'appwrap' : ''; ?>">
				<?php if ( $stage ) : ?>
					<div class="appstage reveal">
						<div class="appvid">
							<img src="<?php echo esc_url( $stage ); ?>" alt="" style="width:100%;height:100%;object-fit:cover;opacity:.82">
							<?php if ( $drop ) : ?>
								<img class="appdrop" src="<?php echo esc_url( $drop ); ?>" alt="">
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $s['stage_caption'] ) ) : ?>
							<div class="appcap"><?php echo esc_html( $s['stage_caption'] ); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="numgrid numgrid-<?php echo esc_attr( $cols ); ?><?php echo $stage ? ' numgrid-staged' : ''; ?>">
					<?php foreach ( (array) $s['stats'] as $stat ) : ?>
						<?php
						// Initial text mirrors the final format so there is no layout
						// shift when the count-up starts.
						$initial = $stat['prefix'] . '0' . ( $stat['suffix'] ? '<small>' . esc_html( $stat['suffix'] ) . '</small>' : '' );
						?>
						<div class="nstat reveal">
							<div class="n"
								data-count="<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $stat['count'] ) ); ?>"
								<?php echo $stat['prefix'] ? 'data-prefix="' . esc_attr( $stat['prefix'] ) . '"' : ''; ?>
								<?php echo $stat['suffix'] ? 'data-suffix="' . esc_attr( $stat['suffix'] ) . '"' : ''; ?>
								<?php echo 'yes' === $stat['comma'] ? 'data-comma="1"' : ''; ?>
							><?php echo wp_kses_post( $initial ); ?></div>
							<div class="l"><?php echo wp_kses_post( $stat['label'] ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
		$this->close_section();
	}
}
