<?php
/**
 * GitHub over-the-air theme updates via Plugin Update Checker (MIT),
 * plus Theme Rollback & Version Switcher to downgrade or upgrade
 * to any available release.
 *
 * Updates and releases are read from GitHub Releases with an attached .zip asset.
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

/* -------------------------------------------------------------------------
 * Available Releases API & Rollback Logic
 * ---------------------------------------------------------------------- */

/**
 * Known historical releases used as a fallback if the GitHub API is rate-limited or unreachable.
 *
 * @return array
 */
function addlar_get_fallback_releases() {
	$repo_url = addlar_github_repo();
	$path     = $repo_url ? trim( (string) wp_parse_url( $repo_url, PHP_URL_PATH ), '/' ) : 'hellomahfuzpro/addlar-theme';

	$fallback_list = array(
		'1.13.0' => array( 'tag' => 'v1.13.0', 'name' => 'v1.13.0 — Theme Rollback & Version Switcher', 'published' => '2026-09-05' ),
		'1.12.1' => array( 'tag' => 'v1.12.1', 'name' => 'v1.12.1 — Cache Bust & Milestone SVG Fix', 'published' => '2026-09-05' ),
		'1.12.0' => array( 'tag' => 'v1.12.0', 'name' => 'v1.12.0 — Client Feedback Updates', 'published' => '2026-09-05' ),
		'1.11.0' => array( 'tag' => 'v1.11.0', 'name' => 'v1.11.0 — Blog is now a standalone Elementor page', 'published' => '2026-08-19' ),
		'1.10.0' => array( 'tag' => 'v1.10.0', 'name' => 'v1.10.0 — LinkedIn band on the blog page', 'published' => '2026-08-19' ),
		'1.9.0'  => array( 'tag' => 'v1.9.0',  'name' => 'v1.9.0 — All 22 PDS re-transcribed', 'published' => '2026-08-19' ),
		'1.8.0'  => array( 'tag' => 'v1.8.0',  'name' => 'v1.8.0 — Admin bar fix, blog templates, real menu & footer', 'published' => '2026-08-19' ),
		'1.7.0'  => array( 'tag' => 'v1.7.0',  'name' => 'v1.7.0 — Red CTA restored, hexagon stage', 'published' => '2026-08-19' ),
		'1.6.0'  => array( 'tag' => 'v1.6.0',  'name' => 'v1.6.0 — New URL scheme, homepage-matched sections', 'published' => '2026-08-19' ),
		'1.5.1'  => array( 'tag' => 'v1.5.1',  'name' => 'v1.5.1 — Enable automatic theme updates', 'published' => '2026-08-19' ),
		'1.5.0'  => array( 'tag' => 'v1.5.0',  'name' => 'v1.5.0 — Widget-authored content, redesigned hero', 'published' => '2026-08-19' ),
		'1.4.0'  => array( 'tag' => 'v1.4.0',  'name' => 'v1.4.0 — Bug fixes + product page rebuilt', 'published' => '2026-08-19' ),
		'1.3.0'  => array( 'tag' => 'v1.3.0',  'name' => 'v1.3.0 — Image sections, corner marks', 'published' => '2026-08-19' ),
		'1.2.0'  => array( 'tag' => 'v1.2.0',  'name' => 'v1.2.0 — Product page redesign + standalone pages', 'published' => '2026-08-19' ),
		'1.1.1'  => array( 'tag' => 'v1.1.1',  'name' => 'v1.1.1 — Product pages + Theme Builder templates', 'published' => '2026-08-17' ),
		'1.0.2'  => array( 'tag' => 'v1.0.2',  'name' => 'v1.0.2 — Coming Soon page template', 'published' => '2026-07-27' ),
	);

	$releases = array();
	foreach ( $fallback_list as $ver => $info ) {
		$releases[ $ver ] = array(
			'tag'       => $info['tag'],
			'version'   => $ver,
			'name'      => $info['name'],
			'published' => $info['published'],
			'zip_url'   => 'https://github.com/' . $path . '/releases/download/' . $info['tag'] . '/addlar.zip',
		);
	}

	return $releases;
}

/**
 * Fetch available releases from GitHub API with transient caching.
 *
 * @param bool $force_refresh
 * @return array
 */
function addlar_get_available_releases( $force_refresh = false ) {
	$cache_key = 'addlar_github_releases';

	if ( ! $force_refresh ) {
		$cached = get_transient( $cache_key );
		if ( is_array( $cached ) && ! empty( $cached ) ) {
			return $cached;
		}
	}

	$repo_url = addlar_github_repo();
	if ( ! $repo_url ) {
		return addlar_get_fallback_releases();
	}

	$path = trim( (string) wp_parse_url( $repo_url, PHP_URL_PATH ), '/' );
	if ( ! $path ) {
		return addlar_get_fallback_releases();
	}

	$api_url = 'https://api.github.com/repos/' . $path . '/releases?per_page=50';
	$headers = array(
		'Accept'     => 'application/vnd.github.v3+json',
		'User-Agent' => 'WordPress/ADDLAR-Theme-Updater',
	);

	$token = addlar_github_token();
	if ( $token ) {
		$headers['Authorization'] = 'Bearer ' . $token;
	}

	$response = wp_remote_get(
		$api_url,
		array(
			'headers' => $headers,
			'timeout' => 15,
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return addlar_get_fallback_releases();
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( ! is_array( $data ) || empty( $data ) ) {
		return addlar_get_fallback_releases();
	}

	$releases = array();
	foreach ( $data as $rel ) {
		if ( ! empty( $rel['draft'] ) ) {
			continue;
		}

		$tag = isset( $rel['tag_name'] ) ? (string) $rel['tag_name'] : '';
		if ( '' === $tag ) {
			continue;
		}

		$ver       = ltrim( $tag, 'v' );
		$name      = ! empty( $rel['name'] ) ? (string) $rel['name'] : $tag;
		$published = ! empty( $rel['published_at'] ) ? gmdate( 'Y-m-d', strtotime( $rel['published_at'] ) ) : '';

		// Locate the attached addlar.zip asset
		$zip_url = '';
		if ( ! empty( $rel['assets'] ) && is_array( $rel['assets'] ) ) {
			foreach ( $rel['assets'] as $asset ) {
				if ( isset( $asset['name'] ) && 'addlar.zip' === $asset['name'] && ! empty( $asset['browser_download_url'] ) ) {
					$zip_url = (string) $asset['browser_download_url'];
					break;
				}
			}
		}

		if ( '' === $zip_url ) {
			$zip_url = 'https://github.com/' . $path . '/releases/download/' . $tag . '/addlar.zip';
		}

		$releases[ $ver ] = array(
			'tag'       => $tag,
			'version'   => $ver,
			'name'      => $name,
			'published' => $published,
			'zip_url'   => $zip_url,
		);
	}

	// Always merge with fallback releases so historical versions remain accessible even if pagination cuts off
	$fallback = addlar_get_fallback_releases();
	foreach ( $fallback as $ver => $info ) {
		if ( ! isset( $releases[ $ver ] ) ) {
			$releases[ $ver ] = $info;
		}
	}

	uksort(
		$releases,
		function( $a, $b ) {
			return version_compare( $b, $a );
		}
	);

	if ( ! empty( $releases ) ) {
		set_transient( $cache_key, $releases, 2 * HOUR_IN_SECONDS );
		return $releases;
	}

	return addlar_get_fallback_releases();
}

/**
 * Switch or rollback the theme to a specified version by downloading its release archive
 * and extracting it over the theme directory.
 *
 * @param string $target_version e.g. 'v1.11.0' or '1.11.0'
 * @return array|WP_Error
 */
function addlar_switch_theme_version( $target_version ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return new WP_Error( 'forbidden', __( 'Permission denied. Administrator privileges required.', 'addlar' ) );
	}

	$target_version = trim( sanitize_text_field( $target_version ) );
	if ( empty( $target_version ) ) {
		return new WP_Error( 'invalid_version', __( 'No target version specified.', 'addlar' ) );
	}

	$ver = ltrim( $target_version, 'v' );
	$tag = 'v' . $ver;

	$releases = addlar_get_available_releases();
	$zip_url  = '';

	if ( isset( $releases[ $ver ]['zip_url'] ) ) {
		$zip_url = $releases[ $ver ]['zip_url'];
	} else {
		$repo_url = addlar_github_repo();
		$path     = $repo_url ? trim( (string) wp_parse_url( $repo_url, PHP_URL_PATH ), '/' ) : 'hellomahfuzpro/addlar-theme';
		$zip_url  = 'https://github.com/' . $path . '/releases/download/' . $tag . '/addlar.zip';
	}

	if ( ! function_exists( 'download_url' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	$download_headers = array(
		'User-Agent' => 'WordPress/ADDLAR-Theme-Updater',
	);

	$token = addlar_github_token();
	if ( $token ) {
		$download_headers['Authorization'] = 'Bearer ' . $token;
	}

	// Download release archive to temporary file
	$tmp_file = download_url( $zip_url, 300, false, array( 'headers' => $download_headers ) );
	if ( is_wp_error( $tmp_file ) ) {
		return new WP_Error(
			'download_failed',
			sprintf(
				/* translators: 1: ZIP URL, 2: Error message */
				__( 'Failed to download release archive from %1$s: %2$s', 'addlar' ),
				esc_url( $zip_url ),
				$tmp_file->get_error_message()
			)
		);
	}

	if ( ! class_exists( 'Theme_Upgrader' ) ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/theme.php';
	}

	$installed = false;
	$error_msg = '';

	// 1. Primary method: Theme_Upgrader with overwrite_package
	$skin      = new Automatic_Upgrader_Skin();
	$upgrader  = new Theme_Upgrader( $skin );
	$result    = $upgrader->install(
		$tmp_file,
		array(
			'clear_update_cache' => true,
			'overwrite_package'  => true,
		)
	);

	if ( is_wp_error( $result ) ) {
		$error_msg = $result->get_error_message();
	} elseif ( $skin->get_errors()->has_errors() ) {
		$error_msg = $skin->get_errors()->get_error_message();
	} elseif ( false === $result ) {
		$error_msg = __( 'Theme_Upgrader failed to extract package.', 'addlar' );
	} else {
		$installed = true;
	}

	// 2. Fallback method: Direct extraction via WP_Filesystem into wp-content/themes
	if ( ! $installed ) {
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		global $wp_filesystem;
		if ( $wp_filesystem ) {
			$unzip = unzip_file( $tmp_file, get_theme_root() );
			if ( ! is_wp_error( $unzip ) ) {
				$installed = true;
			} else {
				$error_msg .= ' ' . sprintf( __( 'Fallback unzip error: %s', 'addlar' ), $unzip->get_error_message() );
			}
		}
	}

	// Clean up temporary downloaded file
	if ( file_exists( $tmp_file ) ) {
		@unlink( $tmp_file );
	}

	if ( ! $installed ) {
		return new WP_Error( 'install_failed', __( 'Installation failed: ', 'addlar' ) . $error_msg );
	}

	// Ensure the active stylesheet matches our theme directory
	$theme_slug = basename( ADDLAR_DIR );
	if ( get_stylesheet() !== $theme_slug ) {
		switch_theme( $theme_slug );
	}

	// Bust and flush all caches
	wp_clean_themes_cache();

	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
		LiteSpeed_Cache_API::purge_all();
	}
	do_action( 'litespeed_purge_all' );

	flush_rewrite_rules();

	if ( function_exists( 'opcache_reset' ) ) {
		@opcache_reset();
	}

	return array(
		'success' => true,
		'version' => $ver,
		'message' => sprintf( __( 'Successfully switched theme to version %s.', 'addlar' ), $ver ),
	);
}

/* -------------------------------------------------------------------------
 * Admin Handlers & UI
 * ---------------------------------------------------------------------- */

/**
 * Handle rollback and version refresh POST actions before headers are sent.
 */
function addlar_handle_version_switch_post() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['addlar_switch_version_submit'] ) && check_admin_referer( 'addlar_version_switch_action' ) ) {
		$target = isset( $_POST['addlar_target_version'] ) ? sanitize_text_field( wp_unslash( $_POST['addlar_target_version'] ) ) : '';
		if ( 'custom' === $target && ! empty( $_POST['addlar_custom_version'] ) ) {
			$target = sanitize_text_field( wp_unslash( $_POST['addlar_custom_version'] ) );
		}

		$result = addlar_switch_theme_version( $target );
		if ( is_wp_error( $result ) ) {
			set_transient( 'addlar_rollback_error', $result->get_error_message(), 60 );
		} else {
			$ver = isset( $result['version'] ) ? $result['version'] : $target;
			set_transient( 'addlar_rollback_success', sprintf( __( 'Theme successfully switched to version %s. All asset, Elementor, and LiteSpeed caches have been cleared.', 'addlar' ), $ver ), 60 );
		}

		$redirect = wp_get_referer();
		if ( ! $redirect ) {
			$redirect = admin_url( 'tools.php?page=addlar-setup' );
		}
		wp_safe_redirect( $redirect );
		exit;
	}

	if ( isset( $_POST['addlar_refresh_releases'] ) && check_admin_referer( 'addlar_version_switch_action' ) ) {
		delete_transient( 'addlar_github_releases' );
		addlar_get_available_releases( true );
		set_transient( 'addlar_rollback_success', __( 'Version list refreshed from GitHub.', 'addlar' ), 60 );

		$redirect = wp_get_referer();
		if ( ! $redirect ) {
			$redirect = admin_url( 'tools.php?page=addlar-setup' );
		}
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'admin_init', 'addlar_handle_version_switch_post' );

/**
 * Register Appearance → Theme Rollback menu item.
 */
function addlar_register_rollback_menu() {
	add_theme_page(
		__( 'Theme Rollback', 'addlar' ),
		__( 'Theme Rollback', 'addlar' ),
		'manage_options',
		'addlar-rollback',
		'addlar_rollback_page_render'
	);
}
add_action( 'admin_menu', 'addlar_register_rollback_menu' );

/**
 * Render the dedicated Appearance → Theme Rollback page.
 */
function addlar_rollback_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'ADDLAR Theme Rollback & Version Switcher', 'addlar' ) . '</h1>';
	addlar_render_rollback_ui( 'appearance' );
	echo '</div>';
}

/**
 * Reusable renderer for the theme rollback UI.
 *
 * @param string $context 'tools' or 'appearance'
 */
function addlar_render_rollback_ui( $context = 'tools' ) {
	$success_notice = get_transient( 'addlar_rollback_success' );
	if ( $success_notice ) {
		delete_transient( 'addlar_rollback_success' );
		echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Success:', 'addlar' ) . '</strong> ' . esc_html( $success_notice ) . '</p></div>';
	}

	$error_notice = get_transient( 'addlar_rollback_error' );
	if ( $error_notice ) {
		delete_transient( 'addlar_rollback_error' );
		echo '<div class="notice notice-error is-dismissible"><p><strong>' . esc_html__( 'Error:', 'addlar' ) . '</strong> ' . esc_html( $error_notice ) . '</p></div>';
	}

	$releases = addlar_get_available_releases();
	$repo     = addlar_github_repo();
	$enabled  = addlar_updates_enabled();

	?>
	<table class="widefat striped" style="max-width:820px;margin-bottom:16px;">
		<tbody>
			<tr>
				<td style="width:220px;"><strong><?php esc_html_e( 'Installed version', 'addlar' ); ?></strong></td>
				<td><span style="display:inline-block;padding:3px 10px;background:#e2231a;color:#fff;font-weight:700;border-radius:3px;">v<?php echo esc_html( ADDLAR_VERSION ); ?></span></td>
			</tr>
			<tr>
				<td><strong><?php esc_html_e( 'Update repository', 'addlar' ); ?></strong></td>
				<td>
					<?php if ( $repo ) : ?>
						<a href="<?php echo esc_url( $repo ); ?>" target="_blank" rel="noopener noreferrer"><code><?php echo esc_html( $repo ); ?></code></a>
					<?php else : ?>
						<em><?php esc_html_e( 'not configured', 'addlar' ); ?></em>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td><strong><?php esc_html_e( 'Automatic updates', 'addlar' ); ?></strong></td>
				<td>
					<?php if ( $enabled ) : ?>
						<span style="color:#007017;font-weight:600;">&#10004; <?php esc_html_e( 'Enabled — updates appear under Dashboard → Updates and Appearance → Themes', 'addlar' ); ?></span>
					<?php else : ?>
						<span style="color:#b32d2e;font-weight:600;">&#10008; <?php esc_html_e( 'Disabled — no repository configured', 'addlar' ); ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td><strong><?php esc_html_e( 'Active theme folder', 'addlar' ); ?></strong></td>
				<td><code><?php echo esc_html( basename( ADDLAR_DIR ) ); ?></code></td>
			</tr>
		</tbody>
	</table>

	<div style="background:#fff;border-left:4px solid #e2231a;padding:14px 18px;margin:18px 0;max-width:820px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
		<h3 style="margin:0 0 6px;"><?php esc_html_e( 'Switch or Rollback to Any Version', 'addlar' ); ?></h3>
		<p style="margin:0 0 8px;font-size:13px;"><?php esc_html_e( 'Select any published version from the list below to downgrade or switch to it immediately. The theme will download the official release package from GitHub and replace the theme files on disk safely.', 'addlar' ); ?></p>
		<p style="margin:0;font-size:12px;color:#666;"><strong><?php esc_html_e( 'Note:', 'addlar' ); ?></strong> <?php esc_html_e( 'All page designs, posts, and Elementor data in your database remain untouched. If you downgrade to test an earlier layout, you can upgrade back to the latest release at any time through Dashboard → Updates or by using this switcher.', 'addlar' ); ?></p>
	</div>

	<form method="post" style="margin-bottom:24px;">
		<?php wp_nonce_field( 'addlar_version_switch_action' ); ?>
		<table class="form-table" role="presentation" style="max-width:820px;">
			<tbody>
				<tr>
					<th scope="row" style="width:200px;">
						<label for="addlar_target_version"><?php esc_html_e( 'Target Version', 'addlar' ); ?></label>
					</th>
					<td>
						<select name="addlar_target_version" id="addlar_target_version" style="min-width:340px;font-family:monospace;" onchange="var c = document.getElementById('addlar_custom_version_wrap'); if(c) c.style.display = (this.value === 'custom' ? 'block' : 'none');">
							<?php foreach ( $releases as $ver => $r ) : ?>
								<?php
								$is_current = ( $r['version'] === ADDLAR_VERSION );
								$is_newer   = version_compare( $r['version'], ADDLAR_VERSION, '>' );
								$badge      = $is_current ? ' [CURRENT]' : ( $is_newer ? ' [UPGRADE]' : ' [ROLLBACK]' );
								?>
								<option value="<?php echo esc_attr( $r['tag'] ); ?>" <?php selected( $is_current, true ); ?>>
									<?php echo esc_html( sprintf( '%-8s %s %s', $r['tag'], $badge, $r['name'] ) ); ?>
								</option>
							<?php endforeach; ?>
							<option value="custom"><?php esc_html_e( '— Enter custom version / tag… —', 'addlar' ); ?></option>
						</select>

						<div id="addlar_custom_version_wrap" style="display:none;margin-top:10px;">
							<input type="text" name="addlar_custom_version" placeholder="e.g. v1.11.0" class="regular-text" style="font-family:monospace;">
							<p class="description"><?php esc_html_e( 'Enter an exact GitHub release tag (e.g. v1.11.0 or 1.11.0).', 'addlar' ); ?></p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit" style="padding-top:0;">
			<button type="submit" name="addlar_switch_version_submit" class="button button-primary" onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to switch the theme version? Theme files on disk will be replaced with the selected release package.', 'addlar' ) ); ?>');">
				<?php esc_html_e( 'Install / Switch Version', 'addlar' ); ?>
			</button>
			<button type="submit" name="addlar_refresh_releases" class="button button-secondary" style="margin-left:8px;">
				<?php esc_html_e( 'Refresh Version List from GitHub', 'addlar' ); ?>
			</button>
		</p>
	</form>

	<h3><?php esc_html_e( 'All Published Releases', 'addlar' ); ?></h3>
	<table class="widefat striped" style="max-width:820px;margin-bottom:24px;">
		<thead>
			<tr>
				<th style="width:100px;"><?php esc_html_e( 'Version', 'addlar' ); ?></th>
				<th><?php esc_html_e( 'Release Title / Summary', 'addlar' ); ?></th>
				<th style="width:110px;"><?php esc_html_e( 'Date', 'addlar' ); ?></th>
				<th style="width:100px;"><?php esc_html_e( 'Status', 'addlar' ); ?></th>
				<th style="width:140px;"><?php esc_html_e( '1-Click Action', 'addlar' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $releases as $ver => $r ) : ?>
				<?php
				$is_current = ( $r['version'] === ADDLAR_VERSION );
				$is_newer   = version_compare( $r['version'], ADDLAR_VERSION, '>' );
				?>
				<tr>
					<td><code><strong><?php echo esc_html( $r['tag'] ); ?></strong></code></td>
					<td><?php echo esc_html( $r['name'] ); ?></td>
					<td><?php echo esc_html( $r['published'] ? $r['published'] : '—' ); ?></td>
					<td>
						<?php if ( $is_current ) : ?>
							<span style="color:#007017;font-weight:700;">&#9679; <?php esc_html_e( 'Active', 'addlar' ); ?></span>
						<?php elseif ( $is_newer ) : ?>
							<span style="color:#2271b1;font-weight:600;"><?php esc_html_e( 'Newer', 'addlar' ); ?></span>
						<?php else : ?>
							<span style="color:#d63638;font-weight:600;"><?php esc_html_e( 'Older', 'addlar' ); ?></span>
						<?php endif; ?>
					</td>
					<td>
						<?php if ( $is_current ) : ?>
							<em><?php esc_html_e( 'Currently running', 'addlar' ); ?></em>
						<?php else : ?>
							<form method="post" style="margin:0;display:inline;">
								<?php wp_nonce_field( 'addlar_version_switch_action' ); ?>
								<input type="hidden" name="addlar_target_version" value="<?php echo esc_attr( $r['tag'] ); ?>">
								<button type="submit" name="addlar_switch_version_submit" class="button button-small <?php echo $is_newer ? 'button-primary' : ''; ?>" onclick="return confirm('<?php echo esc_js( sprintf( __( 'Switch theme to version %s? Theme files on disk will be replaced.', 'addlar' ), $r['tag'] ) ); ?>');">
									<?php echo esc_html( $is_newer ? __( 'Upgrade', 'addlar' ) : __( 'Rollback', 'addlar' ) ); ?>
								</button>
							</form>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php
}

/* -------------------------------------------------------------------------
 * WP-CLI Commands
 * ---------------------------------------------------------------------- */

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

	/**
	 * List all published releases available for rollback/switch.
	 *
	 * ## EXAMPLES
	 *
	 *     wp addlar versions
	 *     wp addlar versions --refresh
	 */
	WP_CLI::add_command(
		'addlar versions',
		function ( $args, $assoc_args ) {
			$force    = isset( $assoc_args['refresh'] );
			$releases = addlar_get_available_releases( $force );

			if ( empty( $releases ) ) {
				WP_CLI::error( 'No releases found.' );
				return;
			}

			WP_CLI::line( WP_CLI::colorize( '%GInstalled version:%n v' . ADDLAR_VERSION ) );
			WP_CLI::line( str_repeat( '-', 70 ) );

			foreach ( $releases as $r ) {
				$status = '';
				if ( $r['version'] === ADDLAR_VERSION ) {
					$status = WP_CLI::colorize( '%G[CURRENT]%n' );
				} elseif ( version_compare( $r['version'], ADDLAR_VERSION, '>' ) ) {
					$status = WP_CLI::colorize( '%C[NEWER]%n' );
				} else {
					$status = WP_CLI::colorize( '%Y[OLDER]%n' );
				}

				WP_CLI::line( sprintf( '%-10s %-12s %-12s %s', $r['tag'], $r['published'] ? $r['published'] : '—', $status, $r['name'] ) );
			}
		}
	);

	/**
	 * Downgrade or switch the theme to any specified version.
	 *
	 * ## EXAMPLES
	 *
	 *     wp addlar rollback v1.11.0
	 *     wp addlar rollback 1.12.0
	 */
	WP_CLI::add_command(
		'addlar rollback',
		function ( $args ) {
			if ( empty( $args[0] ) ) {
				WP_CLI::error( 'Please specify a version to switch to. Example: wp addlar rollback v1.11.0' );
				return;
			}

			$target = $args[0];
			WP_CLI::log( 'Switching theme to version ' . $target . '...' );

			$result = addlar_switch_theme_version( $target );
			if ( is_wp_error( $result ) ) {
				WP_CLI::error( $result->get_error_message() );
			} else {
				WP_CLI::success( $result['message'] );
			}
		}
	);
}
