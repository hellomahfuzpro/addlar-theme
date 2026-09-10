<?php
/**
 * Contact/Ask-the-Expert form field presets + the wp_mail() submission
 * handler. Kept out of widgets/class-contact-form.php (which only loads
 * conditionally, inside Elementor's widget-registration pass) and loaded
 * unconditionally from functions.php instead, so admin-post.php always has
 * the `admin_post_addlar_contact_submit` hook registered regardless of
 * whether/when Elementor's widget autoloader has run on a given request.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * preset key => array of field definitions (name, label, type, required).
 *
 * "contact" is the exact field set Baskar Palani (Technical Consultant)
 * proposed in the client's own requirements survey. "expert" is a lighter
 * set for the Ask the Expert inquiry form.
 *
 * @return array
 */
function addlar_contact_form_presets() {
	return array(
		'contact' => array(
			array( 'name' => 'name', 'label' => __( 'Name', 'addlar' ), 'type' => 'text', 'required' => true ),
			array( 'name' => 'company', 'label' => __( 'Company Name', 'addlar' ), 'type' => 'text', 'required' => false ),
			array( 'name' => 'email', 'label' => __( 'Email Address', 'addlar' ), 'type' => 'email', 'required' => true ),
			array( 'name' => 'phone', 'label' => __( 'Phone Number', 'addlar' ), 'type' => 'text', 'required' => false ),
			array( 'name' => 'country', 'label' => __( 'Country / Shipping Location', 'addlar' ), 'type' => 'text', 'required' => false ),
			array( 'name' => 'product', 'label' => __( 'Product / Sample Requested', 'addlar' ), 'type' => 'text', 'required' => false ),
			array( 'name' => 'message', 'label' => __( 'Message', 'addlar' ), 'type' => 'textarea', 'required' => false ),
		),
		'expert'  => array(
			array( 'name' => 'name', 'label' => __( 'Name', 'addlar' ), 'type' => 'text', 'required' => true ),
			array( 'name' => 'email', 'label' => __( 'Email', 'addlar' ), 'type' => 'email', 'required' => true ),
			array( 'name' => 'company', 'label' => __( 'Company Name', 'addlar' ), 'type' => 'text', 'required' => false ),
			array( 'name' => 'question', 'label' => __( 'Your Question', 'addlar' ), 'type' => 'textarea', 'required' => true ),
		),
	);
}

/**
 * Handle a contact/expert form submission: verify the nonce, drop obvious
 * bot traffic via honeypot, sanitize every field, store in DB, email
 * configured recipients, then redirect back with a success/error flag.
 */
function addlar_handle_contact_form() {
	$redirect = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/' );

	if (
		! isset( $_POST['addlar_contact_nonce'] ) ||
		! wp_verify_nonce( $_POST['addlar_contact_nonce'], 'addlar_contact_form' )
	) {
		wp_safe_redirect( add_query_arg( 'addlar_sent', 'error', $redirect ) );
		exit;
	}

	// Honeypot: a real visitor never fills this hidden field.
	if ( ! empty( $_POST['addlar_hp'] ) ) {
		wp_safe_redirect( add_query_arg( 'addlar_sent', 'contact', $redirect ) );
		exit;
	}

	$presets = addlar_contact_form_presets();
	$preset  = ( isset( $_POST['preset'] ) && isset( $presets[ sanitize_key( wp_unslash( $_POST['preset'] ) ) ] ) )
		? sanitize_key( wp_unslash( $_POST['preset'] ) )
		: 'contact';
	$fields  = $presets[ $preset ];

	$lines    = array();
	$data     = array();
	$is_valid = true;
	foreach ( $fields as $f ) {
		$raw = isset( $_POST[ $f['name'] ] ) ? wp_unslash( $_POST[ $f['name'] ] ) : '';
		$val = 'textarea' === $f['type'] ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );

		if ( 'email' === $f['type'] ) {
			$val = sanitize_email( $val );
		}
		if ( $f['required'] && '' === trim( $val ) ) {
			$is_valid = false;
		}

		$data[ $f['name'] ] = $val;

		if ( '' !== trim( $val ) ) {
			$lines[] = $f['label'] . ': ' . $val;
		}
	}

	if ( ! $is_valid ) {
		wp_safe_redirect( add_query_arg( 'addlar_sent', 'error', $redirect ) );
		exit;
	}

	// Store submission in database.
	if ( function_exists( 'addlar_insert_submission' ) ) {
		addlar_insert_submission( array(
			'preset'     => $preset,
			'name'       => isset( $data['name'] ) ? $data['name'] : '',
			'company'    => isset( $data['company'] ) ? $data['company'] : '',
			'email'      => isset( $data['email'] ) ? $data['email'] : '',
			'phone'      => isset( $data['phone'] ) ? $data['phone'] : '',
			'country'    => isset( $data['country'] ) ? $data['country'] : '',
			'product'    => isset( $data['product'] ) ? $data['product'] : '',
			'message'    => isset( $data['message'] ) ? $data['message'] : ( isset( $data['question'] ) ? $data['question'] : '' ),
			'ip_address' => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
			'status'     => 'unread',
		) );
	}

	// Determine notification recipients.
	$notify_enabled = get_option( 'addlar_notify_enabled', '1' );
	if ( '1' === $notify_enabled ) {
		$notify_emails = get_option( 'addlar_notify_emails', '' );
		if ( ! empty( $notify_emails ) ) {
			$to = array_map( 'trim', explode( ',', $notify_emails ) );
		} else {
			$to = addlar_mod( 'addlar_email' );
			if ( empty( $to ) ) {
				$to = get_option( 'admin_email' );
			}
		}

		$prefix  = get_option( 'addlar_notify_subject_prefix', '[ADDLAR]' );
		$subject = 'contact' === $preset
			? $prefix . ' ' . __( 'New website enquiry — Contact Us', 'addlar' )
			: $prefix . ' ' . __( 'New website enquiry — Ask the Expert', 'addlar' );
		$body    = implode( "\n", $lines );

		$headers     = array( 'Content-Type: text/plain; charset=UTF-8' );
		$email_field = isset( $data['email'] ) ? $data['email'] : '';
		if ( $email_field && is_email( $email_field ) ) {
			$headers[] = 'Reply-To: ' . $email_field;
		}

		// Temporarily override From name/email.
		add_filter( 'wp_mail_from', 'addlar_notification_mail_from' );
		add_filter( 'wp_mail_from_name', 'addlar_notification_mail_from_name' );

		wp_mail( $to, $subject, $body, $headers );

		remove_filter( 'wp_mail_from', 'addlar_notification_mail_from' );
		remove_filter( 'wp_mail_from_name', 'addlar_notification_mail_from_name' );
	}

	wp_safe_redirect( add_query_arg( 'addlar_sent', $preset, $redirect ) );
	exit;
}
add_action( 'admin_post_addlar_contact_submit', 'addlar_handle_contact_form' );
add_action( 'admin_post_nopriv_addlar_contact_submit', 'addlar_handle_contact_form' );

