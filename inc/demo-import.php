<?php
/**
 * Page seeder — builds the homepage as Elementor Containers.
 *
 * Every top-level element is an `elType: container` holding one widget
 * directly (no section/column), full width with zero gap and padding, because
 * our widgets are full-bleed and manage their own rhythm. See skill gotcha #2.
 *
 * Bundled images are sideloaded into the Media Library so the client can swap
 * any of them; the slot->attachment map is cached so re-runs don't duplicate.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ADDLAR_SEED_CACHE', 'addlar_seeded_images' );

/** 7-hex Elementor-style element id. */
function addlar_uid() {
	return substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 7 );
}

/** Give repeater rows the `_id` Elementor expects. */
function addlar_rep( array $rows ) {
	return array_map(
		function ( $r ) {
			$r['_id'] = addlar_uid();
			return $r;
		},
		$rows
	);
}

/**
 * One full-width container per widget.
 *
 * @param array $widgets array( array('type'=>'addlar_hero','settings'=>array()), … )
 * @return array
 */
function addlar_build_tree( array $widgets ) {
	$tree = array();
	foreach ( $widgets as $w ) {
		$tree[] = array(
			'id'       => addlar_uid(),
			'elType'   => 'container',
			'settings' => array(
				'content_width' => 'full',
				'flex_gap'      => array( 'unit' => 'px', 'size' => 0, 'column' => '0', 'row' => '0' ),
				'padding'       => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
			),
			'isInner'  => false,
			'elements' => array(
				array(
					'id'         => addlar_uid(),
					'elType'     => 'widget',
					'widgetType' => $w['type'],
					'settings'   => isset( $w['settings'] ) ? $w['settings'] : array(),
					'elements'   => array(),
				),
			),
		);
	}
	return $tree;
}

/** Persist a built tree to a page. */
function addlar_save_page( $page_id, array $tree, $is_front = true ) {
	update_option( 'elementor_experiment-container', 'active' );

	update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $tree ) ) );
	update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
	// Keeps the theme header/footer while letting Elementor go full width.
	update_post_meta( $page_id, '_wp_page_template', 'elementor_header_footer' );

	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		update_post_meta( $page_id, '_elementor_version', ELEMENTOR_VERSION );
	}

	if ( $is_front ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_id );
	}

	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}

/* -------------------------------------------------------------------------
 * Media
 * ---------------------------------------------------------------------- */

/**
 * Bundled image slots. Sideloaded from the theme itself, so this works on an
 * offline install and needs no third-party CDN.
 *
 * @return array slot => filename
 */
function addlar_image_slots() {
	return apply_filters( 'addlar_image_slots', array(
		'hero-poster'  => 'hero-v3-poster.jpg',
		'app-poster'   => 'hero-poster.jpg',
		'about'        => 'about-plant.jpg',
		'engine-oil'   => 'engine-oil.jpg',
		'driveline'    => 'driveline.jpg',
		'marine'       => 'marine.jpg',
		'industrial'   => 'industrial.jpg',
		'metalworking' => 'metalworking.jpg',
		'components'   => 'components.jpg',
		'cta'          => 'cta-oil.jpg',
		'numbers-bg'   => 'numbers-bg.jpg',
		'li-1'         => 'li-1.jpg',
		'li-2'         => 'li-2.jpg',
		'li-3'         => 'li-3.jpg',
		'mark'         => 'addlar-mark.png',
		'mark-white'   => 'addlar-mark-white.png',
		'logo'         => 'logo.png',
	) );
}

/**
 * Video slots. Not shipped inside the theme zip by default (they are large and
 * would be re-downloaded on every update), so they are resolved in order:
 * cache -> already in the Media Library -> bundled in assets/video/ ->
 * a remote URL. Anything missing just falls back to the poster image.
 *
 * @return array slot => filename
 */
function addlar_video_slots() {
	return apply_filters( 'addlar_video_slots', array(
		'hero-video' => 'hero-v3.mp4',
		'app-video'  => 'hero.mp4',
	) );
}

/**
 * Optional remote sources for the videos, keyed by slot. Empty by default —
 * set via the filter (or drop the files into assets/video/) if you want the
 * seeder to fetch them.
 *
 * @return array slot => absolute URL
 */
function addlar_video_remote_sources() {
	return apply_filters( 'addlar_video_remote_sources', array() );
}

/**
 * Find an attachment already in the Media Library by its file name.
 *
 * This is what stops a re-import: the seed cache can be cleared (or the option
 * lost on a migration) while the file is still sitting in Uploads. Without
 * this check every re-run would add another copy of a 7MB video.
 *
 * @param string $filename e.g. 'hero-v3.mp4'.
 * @return int Attachment ID, or 0.
 */
function addlar_existing_attachment( $filename ) {
	if ( '' === $filename ) {
		return 0;
	}

	$found = get_posts( array(
		'post_type'              => 'attachment',
		'post_status'            => 'inherit',
		'posts_per_page'         => 1,
		'fields'                 => 'ids',
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
		'meta_query'             => array( // phpcs:ignore WordPress.DB.SlowDBQuery -- one-off setup routine.
			array(
				'key'     => '_wp_attached_file',
				'value'   => $filename,
				'compare' => 'LIKE',
			),
		),
	) );

	return $found ? (int) $found[0] : 0;
}

/** Remember a resolved slot so later runs skip the lookup entirely. */
function addlar_cache_slot( $slot, $id ) {
	$cache          = get_option( ADDLAR_SEED_CACHE, array() );
	$cache[ $slot ] = (int) $id;
	update_option( ADDLAR_SEED_CACHE, $cache );
}

/** Elementor media value for an attachment id. */
function addlar_media_value( $id ) {
	$id = (int) $id;
	return $id ? array( 'id' => $id, 'url' => wp_get_attachment_url( $id ) ) : array();
}

/**
 * Resolve a slot without importing anything: cache, then the Media Library.
 *
 * @param string $slot     Slot key.
 * @param string $filename Expected file name.
 * @return array Elementor media value, or empty array.
 */
function addlar_resolve_existing( $slot, $filename ) {
	$cache = get_option( ADDLAR_SEED_CACHE, array() );
	if ( ! empty( $cache[ $slot ] ) && get_post( $cache[ $slot ] ) ) {
		return addlar_media_value( $cache[ $slot ] );
	}

	$existing = addlar_existing_attachment( $filename );
	if ( $existing ) {
		addlar_cache_slot( $slot, $existing );
		return addlar_media_value( $existing );
	}

	return array();
}

/**
 * Import a video, reusing anything already present.
 *
 * @param string $slot Slot key.
 * @return array Elementor media value, or empty array.
 */
function addlar_seed_video( $slot ) {
	$slots = addlar_video_slots();
	if ( empty( $slots[ $slot ] ) ) {
		return array();
	}
	$filename = $slots[ $slot ];

	// 1. Already known or already uploaded — never fetch again.
	$existing = addlar_resolve_existing( $slot, $filename );
	if ( $existing ) {
		return $existing;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// 2. Bundled alongside the theme (works offline, no download).
	$bundled = ADDLAR_DIR . '/assets/video/' . $filename;
	if ( file_exists( $bundled ) ) {
		$tmp = wp_tempnam( $filename );
		if ( $tmp && copy( $bundled, $tmp ) ) {
			$id = media_handle_sideload( array( 'name' => $filename, 'tmp_name' => $tmp ), 0, 'ADDLAR — ' . $slot );
			if ( ! is_wp_error( $id ) ) {
				addlar_cache_slot( $slot, $id );
				return addlar_media_value( $id );
			}
			if ( file_exists( $tmp ) ) {
				wp_delete_file( $tmp );
			}
		}
	}

	// 3. Remote source, if one has been configured.
	$remote = addlar_video_remote_sources();
	if ( ! empty( $remote[ $slot ] ) ) {
		$tmp = download_url( $remote[ $slot ], 120 );
		if ( ! is_wp_error( $tmp ) ) {
			$id = media_handle_sideload( array( 'name' => $filename, 'tmp_name' => $tmp ), 0, 'ADDLAR — ' . $slot );
			if ( ! is_wp_error( $id ) ) {
				addlar_cache_slot( $slot, $id );
				return addlar_media_value( $id );
			}
			if ( file_exists( $tmp ) ) {
				wp_delete_file( $tmp );
			}
		}
	}

	// 4. Nothing available — the widget falls back to its poster image.
	return array();
}

/**
 * Import one bundled image into the Media Library (once).
 *
 * @param string $slot Slot key.
 * @return array Elementor media value, or empty array on failure.
 */
function addlar_seed_image( $slot ) {
	$slots = addlar_image_slots();
	if ( empty( $slots[ $slot ] ) ) {
		return array();
	}

	// Cache, then an existing upload of the same name — so a cleared cache
	// re-links rather than duplicating the file.
	$existing = addlar_resolve_existing( $slot, $slots[ $slot ] );
	if ( $existing ) {
		return $existing;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$file = $slots[ $slot ];
	$src  = ADDLAR_DIR . '/assets/images/' . $file;
	if ( ! file_exists( $src ) ) {
		return array();
	}

	// Copy to a temp file first: media_handle_sideload MOVES the file it is
	// given, which would otherwise delete the bundled original.
	$tmp = wp_tempnam( $file );
	if ( ! $tmp || ! copy( $src, $tmp ) ) {
		return array();
	}

	$id = media_handle_sideload(
		array(
			'name'     => $file,
			'tmp_name' => $tmp,
		),
		0,
		'ADDLAR — ' . $slot
	);

	if ( is_wp_error( $id ) ) {
		if ( file_exists( $tmp ) ) {
			wp_delete_file( $tmp );
		}
		return array();
	}

	addlar_cache_slot( $slot, $id );

	return addlar_media_value( $id );
}

/* -------------------------------------------------------------------------
 * Homepage definition
 * ---------------------------------------------------------------------- */

/** Why-list rows with their numeral fill images. */
function addlar_why_rows() {
	$imgs = array( 'engine-oil', 'driveline', 'industrial', 'metalworking', 'components', 'marine', 'about', 'cta' );
	$rows = array(
		array( 'lbl' => 'Legacy',      'title' => '20+ Years of Proven Industry Legacy', 'text' => 'Headquartered in the UAE, Rchemie brings two decades of market stability, financial reliability and trusted global supply-chain partnerships.' ),
		array( 'lbl' => 'Range',       'title' => 'Broader Spectrum of Products',         'text' => 'Beyond our core automotive line, a comprehensive portfolio spans industrial, marine and specialised fluids — consolidating your supply chain under one manufacturer.' ),
		array( 'lbl' => 'Efficiency',  'title' => 'Cascading Viscometry Versatility',     'text' => '“One fits many” — a single ADDLAR package cascades across multiple viscosity grades, maximising raw material utility and simplifying blending inventory.' ),
		array( 'lbl' => 'Modern Oils', 'title' => 'Optimized for Lighter Products',       'text' => "As the industry shifts to 0W-16, 0W-20 and 5W-20, ADDLAR's lighter packages maintain film strength and boundary lubrication in thin fluid regimes." ),
		array( 'lbl' => 'Value',       'title' => 'Distinct Treat Rate Advantage',        'text' => 'Optimal performance at lower treat rates — directly lowering blending cost per tonne without compromising finished oil performance or OEM specifications.' ),
		array( 'lbl' => 'Quality',     'title' => 'Unwavering Product Consistency',       'text' => 'Strict quality-control protocols ensure absolute batch-to-batch consistency, physical-chemical stability and predictable performance.' ),
		array( 'lbl' => 'Compliance',  'title' => 'Strict Adherence to Global Standards', 'text' => 'Application-specific packages precision-engineered to meet or exceed API, ACEA, ILSAC and JASO benchmarks.' ),
		array( 'lbl' => 'Logistics',   'title' => 'Strategic Global Logistics Hub',       'text' => "Operating from one of the world's premier shipping crossroads — agile supply chain management, minimised lead times and dependable delivery." ),
	);
	foreach ( $rows as $i => $row ) {
		$rows[ $i ]['image'] = addlar_seed_image( $imgs[ $i ] );
	}
	return $rows;
}

/** Product cards with their images. */
function addlar_product_cards() {
	$cards = array(
		array( 'slot' => 'engine-oil',   'cat' => 'Automotive',      'title' => 'Engine Oil Additives', 'sub' => 'Heavy Duty · Passenger Car · Motorcycle',   'count' => '22 products', 'link' => array( 'url' => '#finder' ) ),
		array( 'slot' => 'driveline',    'cat' => 'Transmission',    'title' => 'Driveline Additives',  'sub' => 'Gear · ATF · Manual · Off-Road',             'count' => '6 products',  'link' => array( 'url' => '#finder' ) ),
		array( 'slot' => 'marine',       'cat' => 'Marine',          'title' => 'Marine Additives',     'sub' => 'Trunk Piston · System · Cylinder Oil',       'count' => '3 products',  'link' => array( 'url' => '#finder' ) ),
		array( 'slot' => 'industrial',   'cat' => 'Industrial',      'title' => 'Industrial Additives', 'sub' => 'Gear · Grease · Hydraulic · Slideway',       'count' => '8 products',  'link' => array( 'url' => '#finder' ) ),
		array( 'slot' => 'metalworking', 'cat' => 'Metalworking',    'title' => 'Metalworking Fluids',  'sub' => 'Neat Cutting · Soluble Oil',                 'count' => '6 products',  'link' => array( 'url' => '#finder' ) ),
		array( 'slot' => 'components',   'cat' => 'Building Blocks', 'title' => 'Lubricant Components', 'sub' => 'Detergents · Dispersants · VII · AO & more', 'count' => '30 products', 'link' => array( 'url' => '#packages' ) ),
	);
	foreach ( $cards as $i => $card ) {
		$cards[ $i ]['image'] = addlar_seed_image( $card['slot'] );
		unset( $cards[ $i ]['slot'] );
	}
	return $cards;
}

/** LinkedIn insight rows with their post artwork. */
function addlar_insight_rows() {
	$rows = array(
		array(
			'slot'  => 'li-1',
			'kind'  => 'Heavy-Duty Diesel',
			'title' => 'ADDLAR on Soot Control: why modern HDEO formulation requires a balanced additive system',
			'text'  => 'Soot control is more than dispersant chemistry. Balancing soot handling, oxidation stability, detergency, anti-wear and viscosity retention across EGR-equipped, high-load duty cycles — with ADDLAR 7750, 7730 and 7706.',
			'link'  => array( 'url' => 'https://www.linkedin.com/posts/addlar-lubricant-additives_hdeo-sootcontrol-dieselengineoil-activity-7485585693746966528-uZnF', 'is_external' => 'on' ),
		),
		array(
			'slot'  => 'li-2',
			'kind'  => 'Gear Oils',
			'title' => 'ADDLAR KC562: EP chemistry for high-load gear oil formulations',
			'text'  => 'Gear oils face high torque, sliding contact and shock loading. How KC562 meets API GL-5 and GL-4 across automotive axle, transmission and industrial gear oils — verified on the FZG S-A10/16.6R/90 test.',
			'link'  => array( 'url' => 'https://www.linkedin.com/posts/addlar-lubricant-additives_%F0%9D%97%94%F0%9D%97%97%F0%9D%97%97%F0%9D%97%9F%F0%9D%97%94%F0%9D%97%A5-kc562-activity-7485944178733043713-5nDv', 'is_external' => 'on' ),
		),
		array(
			'slot'  => 'li-3',
			'kind'  => 'Base Oils',
			'title' => 'Switching from Group I to Group II or Group III base oils?',
			'text'  => "An additive package performs differently across base oil groups. Why additive strategy can't be separated from base oil strategy — across oxidation stability, solubility, low-temperature flow and seal compatibility.",
			'link'  => array( 'url' => 'https://www.linkedin.com/posts/addlar-lubricant-additives_lubricantformulation-baseoil-tribology-activity-7483771438290681857-DgMW', 'is_external' => 'on' ),
		),
	);
	foreach ( $rows as $i => $row ) {
		$rows[ $i ]['image'] = addlar_seed_image( $row['slot'] );
		unset( $rows[ $i ]['slot'] );
	}
	return $rows;
}

/**
 * Section order, matching the approved static page.
 *
 * Widgets carry their own content defaults, so only media slots and rows that
 * need real attachment ids are specified here.
 *
 * @return array
 */
function addlar_homepage_widgets() {
	$mark  = addlar_seed_image( 'mark' );
	$white = addlar_seed_image( 'mark-white' );

	return array(
		array(
			'type'     => 'addlar_hero',
			'settings' => array(
				'poster' => addlar_seed_image( 'hero-poster' ),
				'video'  => addlar_seed_video( 'hero-video' ),
			),
		),
		array( 'type' => 'addlar_trust_strip' ),
		array(
			'type'     => 'addlar_about',
			'settings' => array(
				'image' => addlar_seed_image( 'about' ),
				'mark'  => $mark,
			),
		),
		array( 'type' => 'addlar_journey' ),
		array(
			'type'     => 'addlar_why_list',
			'settings' => array( 'rows' => addlar_rep( addlar_why_rows() ) ),
		),
		array(
			'type'     => 'addlar_product_grid',
			'settings' => array(
				'cards' => addlar_rep( addlar_product_cards() ),
				'mark'  => $mark,
			),
		),
		array( 'type' => 'addlar_package_grid' ),
		array(
			'type'     => 'addlar_applications',
			'settings' => array(
				'poster' => addlar_seed_image( 'app-poster' ),
				'video'  => addlar_seed_video( 'app-video' ),
				'drop'   => $white,
			),
		),
		array(
			'type'     => 'addlar_stat_band',
			'settings' => array( 'bg' => addlar_seed_image( 'numbers-bg' ) ),
		),
		array( 'type' => 'addlar_product_finder' ),
		array(
			'type'     => 'addlar_insights',
			'settings' => array( 'posts' => addlar_rep( addlar_insight_rows() ) ),
		),
		array(
			'type'     => 'addlar_closing_cta',
			'settings' => array( 'bg' => addlar_seed_image( 'cta' ) ),
		),
	);
}

/* -------------------------------------------------------------------------
 * Runner
 * ---------------------------------------------------------------------- */

/**
 * Build (or rebuild) the homepage.
 *
 * @return array page_id + message
 */
function addlar_seed_homepage() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return array(
			'page_id' => 0,
			'message' => __( 'Elementor is not active — activate it first, then seed.', 'addlar' ),
		);
	}

	$page = get_page_by_path( 'home', OBJECT, 'page' );

	if ( ! $page ) {
		$existing = (int) get_option( 'page_on_front' );
		if ( $existing && get_post( $existing ) ) {
			$page = get_post( $existing );
		}
	}

	if ( ! $page ) {
		$page_id = wp_insert_post( array(
			'post_title'   => __( 'Home', 'addlar' ),
			'post_name'    => 'home',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
		) );
		if ( is_wp_error( $page_id ) ) {
			return array( 'page_id' => 0, 'message' => $page_id->get_error_message() );
		}
	} else {
		$page_id = $page->ID;
	}

	$tree = addlar_build_tree( addlar_homepage_widgets() );
	addlar_save_page( $page_id, $tree, true );

	// Seed the chrome images too, but never overwrite a choice already made.
	if ( ! get_theme_mod( 'addlar_footer_mark' ) ) {
		$white = addlar_seed_image( 'mark-white' );
		if ( ! empty( $white['url'] ) ) {
			set_theme_mod( 'addlar_footer_mark', $white['url'] );
		}
	}
	if ( ! get_theme_mod( 'custom_logo' ) ) {
		$logo = addlar_seed_image( 'logo' );
		if ( ! empty( $logo['id'] ) ) {
			set_theme_mod( 'custom_logo', $logo['id'] );
		}
	}

	return array(
		'page_id' => $page_id,
		/* translators: %d: number of sections */
		'message' => sprintf( __( 'Homepage seeded with %d sections.', 'addlar' ), count( $tree ) ),
	);
}

/* ------------------------------------------------------------ admin action */

function addlar_tools_page() {
	add_management_page(
		__( 'ADDLAR setup', 'addlar' ),
		__( 'ADDLAR setup', 'addlar' ),
		'manage_options',
		'addlar-setup',
		'addlar_tools_page_render'
	);
}
add_action( 'admin_menu', 'addlar_tools_page' );

function addlar_tools_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice = '';
	if ( isset( $_POST['addlar_seed'] ) && check_admin_referer( 'addlar_seed_action' ) ) {
		$result = addlar_seed_homepage();
		$notice = $result['message'];
		if ( $result['page_id'] ) {
			$notice .= ' <a href="' . esc_url( get_permalink( $result['page_id'] ) ) . '">' . esc_html__( 'View homepage', 'addlar' ) . '</a>';
		}
	}

	echo '<div class="wrap"><h1>' . esc_html__( 'ADDLAR setup', 'addlar' ) . '</h1>';

	if ( $notice ) {
		echo '<div class="notice notice-success"><p>' . wp_kses_post( $notice ) . '</p></div>';
	}

	echo '<p>' . esc_html__( 'Builds the homepage from the approved design: creates the page, imports the bundled images into the Media Library, lays out every section as an Elementor container, and sets it as the front page.', 'addlar' ) . '</p>';
	echo '<p><strong>' . esc_html__( 'Safe to re-run', 'addlar' ) . '</strong> — ' . esc_html__( 'images are imported once and reused. Re-running replaces the homepage layout, so manual edits to it will be lost.', 'addlar' ) . '</p>';

	echo '<form method="post">';
	wp_nonce_field( 'addlar_seed_action' );
	submit_button( __( 'Seed homepage', 'addlar' ), 'primary', 'addlar_seed' );
	echo '</form></div>';
}

/* ---------------------------------------------------------------- WP-CLI */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'addlar seed',
		function () {
			$result = addlar_seed_homepage();
			if ( ! $result['page_id'] ) {
				WP_CLI::error( $result['message'] );
			}
			WP_CLI::success( $result['message'] );
		}
	);
}
