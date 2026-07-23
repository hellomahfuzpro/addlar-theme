<?php
/**
 * Optional GitHub over-the-air theme updates via Plugin Update Checker (MIT).
 *
 * This is entirely optional. With no repo configured the theme runs normally,
 * shows no notices, and simply offers no updates. Nothing else depends on it.
 *
 * Configure it in whichever way suits the install — no theme file needs editing:
 *
 *   1. wp-config.php:  define( 'ADDLAR_GITHUB_REPO', 'https://github.com/acme/addlar' );
 *   2. WP-CLI:         wp option update addlar_github_repo https://github.com/acme/addlar
 *   3. A filter:       add_filter( 'addlar_github_repo', fn() => '…' );
 *
 * For a private repo add a token the same way, via ADDLAR_GITHUB_TOKEN, the
 * addlar_github_token option, or the addlar_github_token filter.
 *
 * Note: the first install is always manual — a version that predates this file
 * has no updater to run.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Configured repository URL, or '' when updates are not set up.
 *
 * @return string
 */
function addlar_github_repo() {
	$repo = '';

	if ( defined( 'ADDLAR_GITHUB_REPO' ) && ADDLAR_GITHUB_REPO ) {
		$repo = (string) ADDLAR_GITHUB_REPO;
	}

	if ( '' === $repo ) {
		$repo = (string) get_option( 'addlar_github_repo', '' );
	}

	$repo = (string) apply_filters( 'addlar_github_repo', $repo );

	// Treat the historical placeholder as "not configured".
	if ( false !== strpos( $repo, 'OWNER/REPO' ) ) {
		return '';
	}

	return trim( $repo );
}

/**
 * Access token for a private repo, or '' for a public one.
 *
 * @return string
 */
function addlar_github_token() {
	$token = '';

	if ( defined( 'ADDLAR_GITHUB_TOKEN' ) && ADDLAR_GITHUB_TOKEN ) {
		$token = (string) ADDLAR_GITHUB_TOKEN;
	}

	if ( '' === $token ) {
		$token = (string) get_option( 'addlar_github_token', '' );
	}

	return trim( (string) apply_filters( 'addlar_github_token', $token ) );
}

/** True when over-the-air updates are available on this install. */
function addlar_updates_enabled() {
	return '' !== addlar_github_repo()
		&& file_exists( ADDLAR_DIR . '/lib/plugin-update-checker/plugin-update-checker.php' );
}

function addlar_bootstrap_updater() {
	if ( ! addlar_updates_enabled() ) {
		return; // Not configured, or library absent — stay completely silent.
	}

	require_once ADDLAR_DIR . '/lib/plugin-update-checker/plugin-update-checker.php';

	if ( ! class_exists( '\YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
		return;
	}

	$checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		addlar_github_repo(),
		ADDLAR_DIR . '/style.css',
		'addlar'
	);

	// Use GitHub Releases with an attached theme zip as the version source.
	$api = $checker->getVcsApi();
	if ( $api && method_exists( $api, 'enableReleaseAssets' ) ) {
		$api->enableReleaseAssets();
	}

	$token = addlar_github_token();
	if ( $token ) {
		$checker->setAuthentication( $token );
	}
}
add_action( 'after_setup_theme', 'addlar_bootstrap_updater' );

/* ---------------------------------------------------------------- WP-CLI */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Show or set the GitHub repo used for theme updates.
	 *
	 * ## EXAMPLES
	 *
	 *     wp addlar updates
	 *     wp addlar updates https://github.com/acme/addlar
	 */
	WP_CLI::add_command(
		'addlar updates',
		function ( $args ) {
			if ( ! empty( $args[0] ) ) {
				update_option( 'addlar_github_repo', esc_url_raw( $args[0] ) );
				WP_CLI::success( 'Repo set. Updates will appear under Appearance → Themes.' );
				return;
			}
			$repo = addlar_github_repo();
			if ( '' === $repo ) {
				WP_CLI::log( 'Over-the-air updates are not configured (optional).' );
				return;
			}
			WP_CLI::log( 'Repo: ' . $repo );
			WP_CLI::log( 'Enabled: ' . ( addlar_updates_enabled() ? 'yes' : 'no (library missing)' ) );
		}
	);
}
