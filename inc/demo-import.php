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

/**
 * A card's category taxonomy term link, or its homepage-anchor fallback if
 * the term doesn't exist yet (theme not re-seeded since Phase 2, or no
 * product in that family yet) — so the homepage never links to a 404.
 *
 * @param string $term_name Taxonomy term name, e.g. "Engine Oil Additive".
 * @param string $fallback  Anchor to use if the term isn't found.
 * @return string
 */
function addlar_product_category_link( $term_name, $fallback ) {
	if ( ! taxonomy_exists( 'addlar_product_category' ) ) {
		return $fallback;
	}
	$term = get_term_by( 'name', $term_name, 'addlar_product_category' );
	if ( ! $term || is_wp_error( $term ) ) {
		return $fallback;
	}
	$link = get_term_link( $term );
	return is_wp_error( $link ) ? $fallback : $link;
}

/** Product cards with their images. */
function addlar_product_cards() {
	$cards = array(
		array( 'slot' => 'engine-oil',   'cat' => 'Automotive',      'title' => 'Engine Oil Additives', 'sub' => 'Heavy Duty · Passenger Car · Motorcycle',   'count' => '22 products', 'link' => array( 'url' => addlar_product_category_link( 'Engine Oil Additive', '#finder' ) ) ),
		array( 'slot' => 'driveline',    'cat' => 'Transmission',    'title' => 'Driveline Additives',  'sub' => 'Gear · ATF · Manual · Off-Road',             'count' => '6 products',  'link' => array( 'url' => addlar_product_category_link( 'Driveline', '#finder' ) ) ),
		array( 'slot' => 'marine',       'cat' => 'Marine',          'title' => 'Marine Additives',     'sub' => 'Trunk Piston · System · Cylinder Oil',       'count' => '3 products',  'link' => array( 'url' => addlar_product_category_link( 'Marine', '#finder' ) ) ),
		array( 'slot' => 'industrial',   'cat' => 'Industrial',      'title' => 'Industrial Additives', 'sub' => 'Gear · Grease · Hydraulic · Slideway',       'count' => '8 products',  'link' => array( 'url' => addlar_product_category_link( 'Industrial', '#finder' ) ) ),
		array( 'slot' => 'metalworking', 'cat' => 'Metalworking',    'title' => 'Metalworking Fluids',  'sub' => 'Neat Cutting · Soluble Oil',                 'count' => '6 products',  'link' => array( 'url' => addlar_product_category_link( 'Metal Working Fluid', '#finder' ) ) ),
		array( 'slot' => 'components',   'cat' => 'Building Blocks', 'title' => 'Lubricant Components', 'sub' => 'Detergents · Dispersants · VII · AO & more', 'count' => '30 products', 'link' => array( 'url' => addlar_product_category_link( 'Lubricant Component', '#packages' ) ) ),
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

/* -------------------------------------------------------------------------
 * Phase 2 — products, the product Theme Builder template, and stub pages
 * ---------------------------------------------------------------------- */

/**
 * Look up (or create) the addlar_product_category term for a category name,
 * so addlar_seed_products() can assign the right term without a separate
 * manual taxonomy-setup step.
 *
 * @param string $name Category name, e.g. "Engine Oil Additive".
 * @return int Term id, or 0 on failure.
 */
function addlar_ensure_product_category_term( $name ) {
	$existing = term_exists( $name, 'addlar_product_category' );
	if ( $existing ) {
		return (int) ( is_array( $existing ) ? $existing['term_id'] : $existing );
	}
	$created = wp_insert_term( $name, 'addlar_product_category' );
	return is_wp_error( $created ) ? 0 : (int) $created['term_id'];
}

/**
 * Create/update one addlar_product post per entry in addlar_products_data()
 * (inc/products-data.php — the 22 real, PDS-documented products). Idempotent
 * by code (post_name = sanitize_title($code)): re-running updates the same
 * posts instead of duplicating them, same "safe to re-run" contract as
 * addlar_seed_homepage().
 *
 * @return int Number of products seeded.
 */
function addlar_seed_products() {
	$data  = addlar_products_data();
	$count = 0;

	foreach ( $data as $code => $p ) {
		$slug     = sanitize_title( $code );
		$existing = get_page_by_path( $slug, OBJECT, 'addlar_product' );
		$post_id  = $existing ? $existing->ID : 0;

		$post_args = array(
			'post_type'   => 'addlar_product',
			'post_title'  => $p['title'],
			'post_name'   => $slug,
			'post_status' => 'publish',
		);

		if ( $post_id ) {
			$post_args['ID'] = $post_id;
			wp_update_post( $post_args );
		} else {
			$post_id = wp_insert_post( $post_args );
		}
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		$term_id = addlar_ensure_product_category_term( $p['category'] );
		if ( $term_id ) {
			wp_set_object_terms( $post_id, array( $term_id ), 'addlar_product_category' );
		}

		$raw = array(
			'_addlar_code'                  => $code,
			'_addlar_subcategory'           => isset( $p['subcategory'] ) ? $p['subcategory'] : '',
			'_addlar_doc_code'              => isset( $p['doc_code'] ) ? $p['doc_code'] : '',
			'_addlar_spec_string'           => isset( $p['spec_string'] ) ? $p['spec_string'] : '',
			'_addlar_description'           => isset( $p['description'] ) ? $p['description'] : '',
			'_addlar_applications_text'     => isset( $p['applications_text'] ) ? $p['applications_text'] : '',
			'_addlar_performance_note'      => isset( $p['performance_note'] ) ? $p['performance_note'] : '',
			'_addlar_performance_headers'   => isset( $p['performance_headers'] ) ? $p['performance_headers'] : '',
			'_addlar_performance_rows_text' => isset( $p['performance_rows_text'] ) ? $p['performance_rows_text'] : '',
			'_addlar_approvals_text'        => isset( $p['approvals_text'] ) ? $p['approvals_text'] : '',
			'_addlar_formulation_label'     => isset( $p['formulation_label'] ) ? $p['formulation_label'] : '',
			'_addlar_formulation_text'      => isset( $p['formulation_text'] ) ? $p['formulation_text'] : '',
			'_addlar_properties_text'       => isset( $p['properties_text'] ) ? $p['properties_text'] : '',
			'_addlar_viscosity_note'        => isset( $p['viscosity_note'] ) ? $p['viscosity_note'] : '',
		);
		foreach ( $raw as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		addlar_render_all_product_fragments( $post_id );

		$count++;
	}

	return $count;
}

/**
 * The single-product Theme Builder template's widget list.
 *
 * First cut of this used Elementor's native Post Title / Featured Image /
 * Text Editor / HTML / Posts widgets, bound via Dynamic Tags. Live testing
 * showed the real problem with that approach: none of those native widgets
 * get this theme's `.adl` scope wrapper (only Addlar_Base_Widget subclasses
 * do, via open_section()/close_section() — see header.php's comment on why
 * `.adl` is deliberately NOT opened around page content generally), so they
 * rendered with zero theme CSS, and the native Posts widget's default skin
 * doesn't match the design at all. Rebuilt entirely on custom widgets
 * (widgets/class-product-spec-header.php, class-product-fragment.php,
 * class-related-products.php) that read the current product's post meta
 * directly via PHP at render time — simpler than Dynamic Tags and
 * self-styling by construction.
 *
 * @return array
 */
function addlar_product_template_widgets() {
	$fragment = function ( $key ) {
		return array( 'type' => 'addlar_product_fragment', 'settings' => array( 'fragment' => $key ) );
	};

	return array(
		array( 'type' => 'addlar_product_spec_header', 'settings' => array() ),
		$fragment( 'description' ),
		$fragment( 'applications' ),
		$fragment( 'performance' ),
		$fragment( 'approvals' ),
		$fragment( 'formulation' ),
		$fragment( 'properties' ),
		$fragment( 'viscosity' ),
		array(
			'type'     => 'addlar_related_products',
			'settings' => array( 'mode' => 'current', 'heading' => __( 'Related Products', 'addlar' ) ),
		),
		array(
			'type'     => 'addlar_closing_cta',
			'settings' => array( 'bg' => addlar_seed_image( 'cta' ) ),
		),
	);
}

/**
 * The category archive Theme Builder template's widget list: a term-aware
 * intro (name + description, pulled at render time) and the same related-
 * products grid widget in "archive" mode.
 *
 * @return array
 */
function addlar_category_archive_template_widgets() {
	return array(
		array(
			'type'     => 'addlar_page_intro',
			'settings' => array( 'use_archive_term' => 'yes', 'eyebrow' => __( 'Product Category', 'addlar' ) ),
		),
		array(
			'type'     => 'addlar_related_products',
			'settings' => array( 'mode' => 'archive', 'heading' => '', 'count' => 24 ),
		),
	);
}

/** Persist an Elementor tree to a Theme Builder library template (not a page). */
function addlar_save_template( $template_id, array $tree ) {
	update_option( 'elementor_experiment-container', 'active' );
	update_post_meta( $template_id, '_elementor_data', wp_slash( wp_json_encode( $tree ) ) );
	update_post_meta( $template_id, '_elementor_edit_mode', 'builder' );
	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		update_post_meta( $template_id, '_elementor_version', ELEMENTOR_VERSION );
	}
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}

/**
 * Find a post by its exact title, scoped to one post type — a small
 * WP_Query wrapper used instead of `get_page_by_title()`, which core
 * deprecated in WP 6.2.
 *
 * @param string $title     Exact post title.
 * @param string $post_type Post type.
 * @return WP_Post|null
 */
function addlar_find_post_by_title( $title, $post_type ) {
	$found = get_posts( array(
		'post_type'      => $post_type,
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'title'          => $title,
		'no_found_rows'  => true,
	) );
	return $found ? $found[0] : null;
}

/**
 * Create/update one elementor_library Theme Builder template and set its
 * type/condition meta. Shared by the single-product and category-archive
 * seeders below — same post-type, same taxonomy assignment, same meta keys,
 * only the title/template-type/condition/widget-tree differ.
 *
 * The exact `_elementor_conditions` format used here
 * (`"include/{location}[/{sub_id}]"`) matches Elementor Pro's own Theme
 * Builder condition storage, but — like the rest of Theme Builder — can't be
 * confirmed from this environment. Open Theme Builder → the template's
 * "Edit Conditions" once after seeding and check it shows the condition
 * seeded here; if it doesn't, set it manually there (one-time, and it'll
 * stick) or use the JSON export on the Tools page to hand this template's
 * content to a fresh template you build the condition for yourself.
 *
 * @param string $title      Template title, also used to find it on re-seed.
 * @param string $type       '_elementor_template_type' value, e.g. 'single'.
 * @param array  $conditions '_elementor_conditions' value.
 * @param array  $widgets    Widget list for addlar_build_tree().
 * @return array template_id + message
 */
function addlar_seed_theme_builder_template( $title, $type, array $conditions, array $widgets ) {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return array(
			'template_id' => 0,
			'message'     => __( 'Elementor is not active — activate it first, then seed.', 'addlar' ),
		);
	}

	$existing    = addlar_find_post_by_title( $title, 'elementor_library' );
	$template_id = $existing ? $existing->ID : 0;

	if ( ! $template_id ) {
		$template_id = wp_insert_post( array(
			'post_type'   => 'elementor_library',
			'post_title'  => $title,
			'post_status' => 'publish',
		) );
		if ( is_wp_error( $template_id ) || ! $template_id ) {
			return array(
				'template_id' => 0,
				'message'     => is_wp_error( $template_id ) ? $template_id->get_error_message() : __( 'Could not create the template.', 'addlar' ),
			);
		}
	}

	if ( taxonomy_exists( 'elementor_library_type' ) ) {
		wp_set_object_terms( $template_id, $type, 'elementor_library_type' );
	}

	update_post_meta( $template_id, '_elementor_template_type', $type );
	update_post_meta( $template_id, '_elementor_conditions', $conditions );

	$tree = addlar_build_tree( $widgets );
	addlar_save_template( $template_id, $tree );

	return array(
		'template_id' => $template_id,
		/* translators: %s: template title */
		'message'     => sprintf( __( '%s template seeded.', 'addlar' ), $title ),
	);
}

/**
 * Seed the single-product Elementor Theme Builder template, conditioned to
 * the addlar_product post type.
 *
 * @return array template_id + message
 */
function addlar_seed_product_theme_builder_template() {
	return addlar_seed_theme_builder_template(
		'ADDLAR Product — Single',
		'single',
		array( 'include/post_type/addlar_product' ),
		addlar_product_template_widgets()
	);
}

/**
 * Seed the category archive Elementor Theme Builder template, conditioned
 * to the addlar_product_category taxonomy. This is what fixes "category
 * page shows not exist" together with the flush_rewrite_rules() call in
 * addlar_seed_products_and_pages() — the archive URL 404s until WordPress's
 * rewrite rules are regenerated after a new taxonomy is registered, quite
 * apart from whether a template exists for it.
 *
 * @return array template_id + message
 */
function addlar_seed_category_archive_theme_builder_template() {
	return addlar_seed_theme_builder_template(
		'ADDLAR Product — Category Archive',
		'archive',
		array( 'include/taxonomy/addlar_product_category' ),
		addlar_category_archive_template_widgets()
	);
}

/**
 * The Products landing page (`/products/`) — a normal seeded Page reusing
 * the existing Addlar_Widget_ProductGrid, whose 6 tile links now point to
 * the real category archive URLs (see addlar_product_category_link()).
 *
 * @return array page_id + message
 */
function addlar_seed_products_overview_page() {
	$existing = get_page_by_path( 'products', OBJECT, 'page' );
	$page_id  = $existing ? $existing->ID : 0;

	$args = array(
		'post_type'   => 'page',
		'post_title'  => __( 'Products', 'addlar' ),
		'post_name'   => 'products',
		'post_status' => 'publish',
	);
	if ( $page_id ) {
		$args['ID'] = $page_id;
		wp_update_post( $args );
	} else {
		$page_id = wp_insert_post( $args );
	}
	if ( is_wp_error( $page_id ) || ! $page_id ) {
		return array(
			'page_id' => 0,
			'message' => is_wp_error( $page_id ) ? $page_id->get_error_message() : __( 'Could not create the Products page.', 'addlar' ),
		);
	}

	$tree = addlar_build_tree( array(
		array(
			'type'     => 'addlar_product_grid',
			'settings' => array(
				'cards'      => addlar_rep( addlar_product_cards() ),
				'mark'       => addlar_seed_image( 'mark' ),
				'promo_link' => array( 'url' => home_url( '/contact-us/' ) ),
			),
		),
	) );
	addlar_save_page( $page_id, $tree, false );

	return array( 'page_id' => $page_id, 'message' => __( 'Products overview page seeded.', 'addlar' ) );
}

/** One minimal placeholder page: heading, one line, the closing CTA. */
function addlar_seed_stub_page( $slug, $title, $lede ) {
	$existing = get_page_by_path( $slug, OBJECT, 'page' );
	$page_id  = $existing ? $existing->ID : 0;

	$args = array(
		'post_type'   => 'page',
		'post_title'  => $title,
		'post_name'   => $slug,
		'post_status' => 'publish',
	);
	if ( $page_id ) {
		$args['ID'] = $page_id;
		wp_update_post( $args );
	} else {
		$page_id = wp_insert_post( $args );
	}
	if ( is_wp_error( $page_id ) || ! $page_id ) {
		return 0;
	}

	$tree = addlar_build_tree( array(
		array(
			'type'     => 'addlar_page_intro',
			'settings' => array( 'title' => $title, 'lede' => $lede ),
		),
		array(
			'type'     => 'addlar_closing_cta',
			'settings' => array( 'bg' => addlar_seed_image( 'cta' ) ),
		),
	) );
	addlar_save_page( $page_id, $tree, false );

	return $page_id;
}

/**
 * About Us, Contact Us and Ask the Expert as minimal placeholder pages —
 * real URLs claimed now, full content a later pass (confirmed scope). Blog
 * is WP's native "Posts page" mechanism: a page assigned via
 * `page_for_posts`, no Elementor content and no posts to seed — the
 * existing index.php fallback already renders an empty state.
 *
 * @return array slug => page_id
 */
function addlar_seed_stub_pages() {
	$ids = array();

	$ids['about-us'] = addlar_seed_stub_page(
		'about-us',
		__( 'About Us', 'addlar' ),
		__( 'ADDLAR’s full story is coming to this page shortly. In the meantime, explore our product range or get in touch.', 'addlar' )
	);
	$ids['contact-us'] = addlar_seed_stub_page(
		'contact-us',
		__( 'Contact Us', 'addlar' ),
		__( 'Full contact details are coming to this page shortly. In the meantime, reach us below.', 'addlar' )
	);
	$ids['ask-the-expert'] = addlar_seed_stub_page(
		'ask-the-expert',
		__( 'Ask the Expert', 'addlar' ),
		__( 'Have a formulation question? This page will soon let you ask our technical team directly. In the meantime, get in touch below.', 'addlar' )
	);

	$blog = get_page_by_path( 'blog', OBJECT, 'page' );
	$blog_id = $blog ? $blog->ID : 0;
	if ( ! $blog_id ) {
		$blog_id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_title'  => __( 'Blog', 'addlar' ),
			'post_name'   => 'blog',
			'post_status' => 'publish',
		) );
	}
	if ( $blog_id && ! is_wp_error( $blog_id ) ) {
		update_option( 'page_for_posts', $blog_id );
	}
	$ids['blog'] = is_wp_error( $blog_id ) ? 0 : $blog_id;

	return $ids;
}

/**
 * Run every Phase 2 seeder in the order that satisfies their dependencies:
 * products (and their category terms) before the overview page needs those
 * terms' URLs, stub pages before nav-defaults.php's fallback links point at
 * them.
 *
 * @return array message
 */
function addlar_seed_products_and_pages() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return array( 'message' => __( 'Elementor is not active — activate it first, then seed.', 'addlar' ) );
	}

	$product_count   = addlar_seed_products();
	$single_template = addlar_seed_product_theme_builder_template();
	$archive_template = addlar_seed_category_archive_theme_builder_template();
	$overview        = addlar_seed_products_overview_page();
	$stubs           = addlar_seed_stub_pages();

	// New CPT + taxonomy rewrite rules (and the category archive URLs they
	// enable) only take effect once WordPress's rewrite rules are
	// regenerated — this is what fixes a fresh category archive 404ing even
	// though the term and template both exist.
	flush_rewrite_rules();

	$message = sprintf(
		/* translators: %d: number of products seeded */
		__( '%d products seeded.', 'addlar' ),
		$product_count
	) . ' ' . $single_template['message'] . ' ' . $archive_template['message'];

	if ( $overview['page_id'] ) {
		$message .= ' <a href="' . esc_url( get_permalink( $overview['page_id'] ) ) . '">' . esc_html__( 'View Products page', 'addlar' ) . '</a>';
	}

	return array( 'message' => $message, 'stub_ids' => $stubs, 'overview_id' => $overview['page_id'] );
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

	$products_notice = '';
	if ( isset( $_POST['addlar_seed_products'] ) && check_admin_referer( 'addlar_seed_products_action' ) ) {
		$result           = addlar_seed_products_and_pages();
		$products_notice  = $result['message'];
	}

	$flush_notice = '';
	if ( isset( $_POST['addlar_flush'] ) && check_admin_referer( 'addlar_flush_action' ) ) {
		flush_rewrite_rules();
		$flush_notice = __( 'Permalinks flushed.', 'addlar' );
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
	echo '</form>';

	echo '<hr>';

	if ( $products_notice ) {
		echo '<div class="notice notice-success"><p>' . wp_kses_post( $products_notice ) . '</p></div>';
	}

	echo '<p>' . esc_html__( 'Builds the 22 real product pages (from the client’s Product Data Sheets), the product single and category archive Theme Builder templates, the Products landing page, and the About Us / Contact Us / Ask the Expert / Blog stub pages. Also flushes permalinks, so a new category archive URL works immediately instead of 404ing until Settings → Permalinks is re-saved by hand.', 'addlar' ) . '</p>';
	echo '<p><strong>' . esc_html__( 'Safe to re-run', 'addlar' ) . '</strong> — ' . esc_html__( 'products and pages are matched by slug and updated in place, not duplicated.', 'addlar' ) . '</p>';

	echo '<form method="post">';
	wp_nonce_field( 'addlar_seed_products_action' );
	submit_button( __( 'Seed products + pages', 'addlar' ), 'primary', 'addlar_seed_products' );
	echo '</form>';

	echo '<hr>';

	if ( $flush_notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $flush_notice ) . '</p></div>';
	}
	echo '<p>' . esc_html__( 'If a category archive URL 404s on its own (a new taxonomy\'s rewrite rules not yet regenerated), flush permalinks without re-seeding anything else.', 'addlar' ) . '</p>';
	echo '<form method="post">';
	wp_nonce_field( 'addlar_flush_action' );
	submit_button( __( 'Flush permalinks', 'addlar' ), 'secondary', 'addlar_flush' );
	echo '</form>';

	echo '<hr>';

	echo '<h2>' . esc_html__( 'Export Theme Builder templates', 'addlar' ) . '</h2>';
	echo '<p>' . esc_html__( 'Download either seeded template as an Elementor-importable .json file. Use Elementor\'s own Templates → Saved Templates → Import to bring it in as a fresh template, then set its display condition by hand there — that\'s the reliable way to fix a condition if the one seeded here isn\'t right, or to hand a starting point to someone editing this in the Elementor UI directly.', 'addlar' ) . '</p>';

	$single_id  = addlar_find_template_id_by_title( 'ADDLAR Product — Single' );
	$archive_id = addlar_find_template_id_by_title( 'ADDLAR Product — Category Archive' );

	echo '<p>';
	if ( $single_id ) {
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=addlar_export_template&template_id=' . $single_id ), 'addlar_export_template' );
		echo '<a class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'Export product single template', 'addlar' ) . '</a> ';
	} else {
		esc_html_e( 'Product single template not seeded yet.', 'addlar' );
	}
	echo '</p><p>';
	if ( $archive_id ) {
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=addlar_export_template&template_id=' . $archive_id ), 'addlar_export_template' );
		echo '<a class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'Export category archive template', 'addlar' ) . '</a>';
	} else {
		esc_html_e( 'Category archive template not seeded yet.', 'addlar' );
	}
	echo '</p>';

	echo '</div>';
}

/**
 * Find a seeded elementor_library template's post ID by its title — used to
 * build the export links above without hardcoding a post ID that changes
 * per install.
 *
 * @param string $title Template title.
 * @return int
 */
function addlar_find_template_id_by_title( $title ) {
	$post = addlar_find_post_by_title( $title, 'elementor_library' );
	return $post ? $post->ID : 0;
}

/**
 * Build an Elementor Template Library-importable array from a stored
 * elementor_library template — the same shape Elementor's own template
 * export produces, so the downloaded file can be re-imported via
 * Elementor's own "Import Templates" screen.
 *
 * Reads `_elementor_data` with get_post_meta(), which already unslashes it
 * correctly, and re-encodes it fresh for the download. That's the right
 * side of the fence per the JSON-corruption bug written up in
 * WORDPRESS-ELEMENTOR-JSON-BUG.md: the value only needs `wp_slash()`ing
 * again if it were going back into another `update_post_meta()` call — a
 * one-way export to a downloaded file never does that, so no extra
 * (un)slashing belongs here.
 *
 * @param int $template_id elementor_library post ID.
 * @return array|null
 */
function addlar_export_template_array( $template_id ) {
	$post = get_post( $template_id );
	if ( ! $post || 'elementor_library' !== $post->post_type ) {
		return null;
	}

	$raw  = get_post_meta( $template_id, '_elementor_data', true );
	$tree = $raw ? json_decode( $raw, true ) : array();
	$type = get_post_meta( $template_id, '_elementor_template_type', true );

	return array(
		'content'       => $tree ? $tree : array(),
		'page_settings' => array(),
		'version'       => defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '0.4',
		'title'         => $post->post_title,
		'type'          => $type ? $type : 'page',
	);
}

/** Stream a seeded template as a downloadable Elementor-import .json file. */
function addlar_handle_export_template() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'addlar' ) );
	}
	check_admin_referer( 'addlar_export_template' );

	$template_id = isset( $_GET['template_id'] ) ? (int) $_GET['template_id'] : 0;
	$export      = addlar_export_template_array( $template_id );
	if ( ! $export ) {
		wp_die( esc_html__( 'Template not found.', 'addlar' ) );
	}

	$filename = sanitize_title( $export['title'] ) . '.json';

	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	echo wp_json_encode( $export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); // phpcs:ignore WordPress.Security.EscapeOutput -- raw JSON file download, not HTML output.
	exit;
}
add_action( 'admin_post_addlar_export_template', 'addlar_handle_export_template' );

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

	WP_CLI::add_command(
		'addlar seed-products',
		function () {
			$result = addlar_seed_products_and_pages();
			WP_CLI::success( $result['message'] );
		}
	);
}
