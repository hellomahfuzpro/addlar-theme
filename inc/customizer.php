<?php
/**
 * Customizer — "general options" for the header/footer chrome.
 *
 * Everything here drives header.php / footer.php. Section content lives in
 * Elementor widgets instead, so this stays small and stable.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Single source of truth for Customizer defaults.
 *
 * get_theme_mod() returns its own second argument when a mod is unset — the
 * default registered with add_setting() is NOT applied at render time. So the
 * templates must read through addlar_mod() or they would silently fall back to
 * empty strings and the chrome would look unfinished on a fresh install.
 *
 * @return array
 */
function addlar_defaults() {
	return apply_filters( 'addlar_customizer_defaults', array(
		'addlar_cta_label'    => __( 'Get in touch', 'addlar' ),
		'addlar_cta_link'     => '#contact',
		'addlar_email'        => 'info@rchemie.com',
		'addlar_website'      => 'https://www.rchemie.com',
		'addlar_address'      => 'Sharjah, United Arab Emirates',
		'addlar_linkedin_url' => 'https://www.linkedin.com/showcase/addlar-lubricant-additives/',
		'addlar_youtube_url'  => '',
		'addlar_foot_head_1'  => __( 'Our Information', 'addlar' ),
		'addlar_foot_head_2'  => __( 'Product Range', 'addlar' ),
		'addlar_foot_head_3'  => __( 'Resources', 'addlar' ),
		'addlar_foot_head_4'  => __( 'Social Media', 'addlar' ),
		'addlar_footer_mark'  => '',
		'addlar_copyright'    => '',
	) );
}

/**
 * Theme mod with the registered default applied.
 *
 * @param string $key Setting id.
 * @return mixed
 */
function addlar_mod( $key ) {
	$defaults = addlar_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	if ( 'addlar_copyright' === $key && '' === $default ) {
		/* translators: 1: year, 2: company name */
		$default = sprintf( __( 'Copyright &copy; %1$s &nbsp;<b>%2$s</b>', 'addlar' ), gmdate( 'Y' ), 'Rchemie International' );
	}

	return get_theme_mod( $key, $default );
}

function addlar_customize_register( $wp_customize ) {
	$defaults = addlar_defaults();

	/* ---------------------------------------------------------------- Header */
	$wp_customize->add_section( 'addlar_header', array(
		'title'    => __( 'ADDLAR — Header', 'addlar' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'addlar_cta_label', array(
		'default'           => __( 'Get in touch', 'addlar' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'addlar_cta_label', array(
		'label'   => __( 'Header button label', 'addlar' ),
		'section' => 'addlar_header',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'addlar_cta_link', array(
		'default'           => '#contact',
		'sanitize_callback' => 'addlar_sanitize_link',
	) );
	$wp_customize->add_control( 'addlar_cta_link', array(
		'label'       => __( 'Header button link', 'addlar' ),
		'description' => __( 'An anchor such as #contact, or a full URL.', 'addlar' ),
		'section'     => 'addlar_header',
		'type'        => 'text',
	) );

	/* --------------------------------------------------------------- Contact */
	$wp_customize->add_section( 'addlar_contact', array(
		'title'    => __( 'ADDLAR — Contact & Social', 'addlar' ),
		'priority' => 31,
	) );

	// Defaults come from the approved design so the footer and header are
	// complete on a fresh install; the client can change any of them.
	$fields = array(
		'addlar_email'        => array( __( 'Email address', 'addlar' ), 'sanitize_email', 'info@rchemie.com' ),
		'addlar_website'      => array( __( 'Website URL', 'addlar' ), 'esc_url_raw', 'https://www.rchemie.com' ),
		'addlar_address'      => array( __( 'Address', 'addlar' ), 'sanitize_text_field', 'Sharjah, United Arab Emirates' ),
		'addlar_linkedin_url' => array( __( 'LinkedIn URL', 'addlar' ), 'esc_url_raw', 'https://www.linkedin.com/showcase/addlar-lubricant-additives/' ),
		'addlar_youtube_url'  => array( __( 'YouTube URL', 'addlar' ), 'esc_url_raw', '' ),
	);
	foreach ( $fields as $id => $cfg ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $cfg[2],
			'sanitize_callback' => $cfg[1],
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $cfg[0],
			'section' => 'addlar_contact',
			'type'    => 'text',
		) );
	}

	/* ---------------------------------------------------------------- Footer */
	$wp_customize->add_section( 'addlar_footer', array(
		'title'    => __( 'ADDLAR — Footer', 'addlar' ),
		'priority' => 32,
	) );

	$headings = array(
		'addlar_foot_head_1' => array( __( 'Column 1 heading', 'addlar' ), __( 'Our Information', 'addlar' ) ),
		'addlar_foot_head_2' => array( __( 'Column 2 heading', 'addlar' ), __( 'Product Range', 'addlar' ) ),
		'addlar_foot_head_3' => array( __( 'Column 3 heading', 'addlar' ), __( 'Resources', 'addlar' ) ),
		'addlar_foot_head_4' => array( __( 'Column 4 heading (social)', 'addlar' ), __( 'Social Media', 'addlar' ) ),
	);
	foreach ( $headings as $id => $cfg ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $cfg[1],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $cfg[0],
			'section' => 'addlar_footer',
			'type'    => 'text',
		) );
	}

	$wp_customize->add_setting( 'addlar_footer_mark', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'addlar_footer_mark', array(
		'label'       => __( 'Footer mark (white)', 'addlar' ),
		'description' => __( 'Small white ADDLAR mark shown above the footer columns.', 'addlar' ),
		'section'     => 'addlar_footer',
	) ) );

	$wp_customize->add_setting( 'addlar_copyright', array(
		'default'           => sprintf( 'Copyright &copy; %1$s &nbsp;<b>%2$s</b>', gmdate( 'Y' ), 'Rchemie International' ),
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'addlar_copyright', array(
		'label'       => __( 'Copyright line', 'addlar' ),
		'description' => __( 'Basic HTML allowed (e.g. &lt;b&gt;).', 'addlar' ),
		'section'     => 'addlar_footer',
		'type'        => 'textarea',
	) );
}
add_action( 'customize_register', 'addlar_customize_register' );

/**
 * Allow either a full URL or an in-page anchor (#contact).
 *
 * @param string $value Raw value.
 * @return string
 */
function addlar_sanitize_link( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( 0 === strpos( $value, '#' ) ) {
		return sanitize_text_field( $value );
	}
	return esc_url_raw( $value );
}
