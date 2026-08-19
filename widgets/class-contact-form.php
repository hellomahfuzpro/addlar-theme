<?php
/**
 * A real, working contact form — plain HTML/CSS (so it's `.adl`-scoped and
 * styled for free, unlike Elementor Pro's native Form widget, whose exact
 * settings schema this project has already gotten wrong once guessing at
 * raw JSON) submitting to the wp_mail() handler in inc/contact-form.php.
 * No form plugin dependency.
 *
 * Field presets (addlar_contact_form_presets()) and the submission handler
 * live in inc/contact-form.php rather than here, so they're loaded
 * unconditionally from functions.php — this widget file only loads when
 * Elementor's widget-registration pass runs.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_ContactForm extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_contact_form';
	}

	public function get_title() {
		return __( 'ADDLAR Contact Form', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'form',
		) );

		$this->add_control( 'preset', array(
			'label'   => __( 'Fields', 'addlar' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'contact' => __( 'Contact Us (Name, Email, Company, Country, Product, Message)', 'addlar' ),
				'expert'  => __( 'Ask the Expert (Name, Email, Company, Question)', 'addlar' ),
			),
			'default' => 'contact',
		) );

		$this->add_control( 'submit_label', array(
			'label'   => __( 'Submit button text', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Send →', 'addlar' ),
		) );

		$this->add_control( 'success_message', array(
			'label'   => __( 'Success message', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( 'Thanks — we\'ve received your message and will be in touch shortly.', 'addlar' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$presets = addlar_contact_form_presets();
		$preset  = isset( $presets[ $s['preset'] ] ) ? $s['preset'] : 'contact';
		$fields  = $presets[ $preset ];

		$this->open_section( 'section', ! empty( $s['anchor'] ) ? $s['anchor'] : '' );
		?>
		<div class="wrap" style="max-width:640px;">

			<?php if ( isset( $_GET['addlar_sent'] ) && $preset === sanitize_key( wp_unslash( $_GET['addlar_sent'] ) ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only success flag, no state change on this request. ?>
				<div class="form-success"><?php echo esc_html( $s['success_message'] ); ?></div>
			<?php else : ?>

				<?php if ( isset( $_GET['addlar_sent'] ) && 'error' === sanitize_key( wp_unslash( $_GET['addlar_sent'] ) ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only error flag. ?>
					<div class="form-error"><?php esc_html_e( 'Something went wrong sending your message — please check the required fields and try again.', 'addlar' ); ?></div>
				<?php endif; ?>

				<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'addlar_contact_form', 'addlar_contact_nonce' ); ?>
					<input type="hidden" name="action" value="addlar_contact_submit">
					<input type="hidden" name="preset" value="<?php echo esc_attr( $preset ); ?>">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>">
					<div class="form-hp" aria-hidden="true"><label>Leave blank<input type="text" name="addlar_hp" tabindex="-1" autocomplete="off"></label></div>

					<?php foreach ( $fields as $f ) : ?>
						<div class="form-row">
							<label for="addlar_<?php echo esc_attr( $f['name'] ); ?>"><?php echo esc_html( $f['label'] ); ?><?php echo $f['required'] ? ' *' : ''; ?></label>
							<?php if ( 'textarea' === $f['type'] ) : ?>
								<textarea id="addlar_<?php echo esc_attr( $f['name'] ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" rows="4"<?php echo $f['required'] ? ' required' : ''; ?>></textarea>
							<?php else : ?>
								<input type="<?php echo esc_attr( $f['type'] ); ?>" id="addlar_<?php echo esc_attr( $f['name'] ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>"<?php echo $f['required'] ? ' required' : ''; ?>>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>

					<button type="submit" class="btn btn-red"><?php echo esc_html( $s['submit_label'] ); ?></button>
				</form>

			<?php endif; ?>
		</div>
		<?php
		$this->close_section();
	}
}
