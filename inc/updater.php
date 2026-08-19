<?php
/**
 * GitHub over-the-air theme updates via Plugin Update Checker (MIT).
 *
 * **This ships enabled**, pointed at the theme's own repository
 * (ADDLAR_GITHUB_REPO_DEFAULT below). Earlier versions defaulted to an
 * empty repo and stayed deliberately silent until someone added a constant
 * to wp-config.php — which nobody ever did, so no update ever appeared in
 * the dashboard and every release had to be uploaded by hand. An updater
 * that is silently off by default is indistinguishable from one that is
 * broken, so the default is now the real repo.
 *
 * Overridable per install, highest priority first — no theme file needs editing:
 *
 *   1. wp-config.php:  define( 'ADDLAR_GITHUB_REPO', 'https://github.com/acme/addlar' );
 *   2. WP-CLI:         wp option update addlar_github_repo https://github.com/acme/addlar
 *   3. A filter:       add_filter( 'addlar_github_repo', fn() => '…' );
 *
 * Set any of them to an empty string to turn updates off entirely.
 *
 * For a private repo add a token the same way, via ADDLAR_GITHUB_TOKEN, the
 * addlar_github_token option, or the addlar_github_token filter.
 *
 * Updates are read from GitHub **Releases with an attached .zip asset**
 * (see enableReleaseAssets() below), not from bare tags or commits — so
 * pushing to main alone will never surface an update; a release must be
 * published. The release's zip must also contain a top-level folder whose
 * name matches this theme's directory, or WordPress will install it
 * alongside rather than over the current theme.
 *
 * Note: the first install is always manual — a version that predates this
 * file has no updater to run.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** The repository this theme is released from. */
define( 'ADDLAR_GITHUB_REPO_DEFAULT', 'https://github.com/hellomahfuzpro/addlar-theme' );

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
		$repo = (string) get_option( 'addlar_github_repo', ADDLAR_GITHUB_REPO_DEFAULT );
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

/**
 * The update checker instance, or null when updates are off/unavailable.
 * Memoised so the admin "check now" action can reuse the same object the
 * scheduled check uses rather than building a second one.
 *
 * @return object|null
 */
function addlar_updater() {
	static $checker = null;
	static $built   = false;

	if ( $built ) {
		return $checker;
	}
	$built = true;

	if ( ! addlar_updates_enabled() ) {
		return null; // Not configured, or library absent.
	}

	require_once ADDLAR_DIR . '/lib/plugin-update-checker/plugin-update-checker.php';

	if ( ! class_exists( '\YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
		return null;
	}

	$checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		addlar_github_repo(),
		ADDLAR_DIR . '/style.css',
		// Derive the slug from the actual directory rather than hardcoding
		// "addlar": WordPress matches a theme update by directory name, so a
		// site where the theme was installed under a different folder name
		// would silently never update if this were a fixed string.
		basename( ADDLAR_DIR )
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

	return $checker;
}

function addlar_bootstrap_updater() {
	addlar_updater();
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
