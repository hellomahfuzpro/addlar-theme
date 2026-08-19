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
		// Real LinkedIn campaign graphics — 7 of the 22 real products have one
		// (2 more exist as multi-slide PDF carousels, not single images, and
		// aren't wired up yet). The other 15 products fall back to their
		// category's stock photo — see addlar_product_hero_image().
		'product-7375'  => 'products/7375.png',
		'product-7395'  => 'products/7395.png',
		'product-9100'  => 'products/9100.png',
		'product-9200'  => 'products/9200.png',
		'product-9312'  => 'products/9312.png',
		'product-9342'  => 'products/9342.png',
		'product-z2612' => 'products/z2612.png',
		// A second free-license stock photo per category, so one product page
		// doesn't repeat the exact same image for its hero, banner and tile
		// sections — see addlar_category_image_slots_secondary().
		'engine-oil-2'   => 'engine-oil-2.jpg',
		'driveline-2'    => 'driveline-2.jpg',
		'marine-2'       => 'marine-2.jpg',
		'industrial-2'   => 'industrial-2.jpg',
		'metalworking-2' => 'metalworking-2.jpg',
		'components-2'   => 'components-2.jpg',
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

/** Bare product code => image slot, for the 7 products with a real campaign graphic. */
function addlar_product_photo_slots() {
	return array(
		'7375'   => 'product-7375',
		'7395'   => 'product-7395',
		'9100'   => 'product-9100',
		'9200'   => 'product-9200',
		'9312'   => 'product-9312',
		'9342'   => 'product-9342',
		'Z 2612' => 'product-z2612',
	);
}

/** Category name => the homepage's stock-photo slot for that family. */
function addlar_category_image_slots() {
	return array(
		'Engine Oil Additive' => 'engine-oil',
		'Driveline'           => 'driveline',
		'Marine'              => 'marine',
		'Industrial'          => 'industrial',
		'Metal Working Fluid' => 'metalworking',
		'Lubricant Component' => 'components',
	);
}

/**
 * Category name => a second, different stock-photo slot for that family —
 * used for a product page's banner/tile sections so the page doesn't show
 * its hero photo two or three more times further down. Free-license photos
 * (Unsplash), same "placeholder now, client can replace later" convention
 * as the original 6 category photos.
 */
function addlar_category_image_slots_secondary() {
	return array(
		'Engine Oil Additive' => 'engine-oil-2',
		'Driveline'           => 'driveline-2',
		'Marine'              => 'marine-2',
		'Industrial'          => 'industrial-2',
		'Metal Working Fluid' => 'metalworking-2',
		'Lubricant Component' => 'components-2',
	);
}

/**
 * Resolve one product's hero image: its own real campaign graphic if it has
 * one (7 of the 22 do — see addlar_product_photo_slots()), otherwise its
 * category's stock photo, so every product page gets a real, on-brand image
 * rather than a blank space.
 *
 * @param string $code     Bare product code.
 * @param string $category Category name.
 * @return array Elementor media value, or empty array.
 */
function addlar_product_hero_image( $code, $category ) {
	$photo_slots = addlar_product_photo_slots();
	if ( isset( $photo_slots[ $code ] ) ) {
		$img = addlar_seed_image( $photo_slots[ $code ] );
		if ( ! empty( $img['id'] ) ) {
			return $img;
		}
	}

	$cat_slots = addlar_category_image_slots();
	if ( isset( $cat_slots[ $category ] ) ) {
		return addlar_seed_image( $cat_slots[ $category ] );
	}

	return array();
}

/**
 * A product page's second image (banner/tile sections) — always the
 * category's secondary stock photo, regardless of whether the product has
 * its own real hero graphic, so a product with a real campaign photo still
 * gets visual variety further down the page instead of repeating it.
 *
 * @param string $category Category name.
 * @return array Elementor media value, or empty array.
 */
function addlar_product_secondary_image( $category ) {
	$cat_slots = addlar_category_image_slots_secondary();
	if ( isset( $cat_slots[ $category ] ) ) {
		return addlar_seed_image( $cat_slots[ $category ] );
	}
	return array();
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
	$description = addlar_product_category_description( $name );

	$existing = term_exists( $name, 'addlar_product_category' );
	if ( $existing ) {
		$term_id = (int) ( is_array( $existing ) ? $existing['term_id'] : $existing );
		// Fill the description in on re-seed too — the archive hero uses it
		// as its subtitle, and terms created by earlier versions have none.
		if ( $description ) {
			wp_update_term( $term_id, 'addlar_product_category', array( 'description' => $description ) );
		}
		return $term_id;
	}

	$created = wp_insert_term( $name, 'addlar_product_category', array( 'description' => $description ) );
	return is_wp_error( $created ) ? 0 : (int) $created['term_id'];
}

/**
 * One-line description per product family, used as the category archive
 * hero's subtitle (the terms otherwise have no description, which left
 * that hero with generic filler).
 *
 * These describe what each family *is* and which sub-types it covers —
 * both facts already present in the Product Finder catalogue and the PDS
 * set. Nothing here asserts a performance characteristic.
 *
 * @param string $name Category name.
 * @return string
 */
function addlar_product_category_description( $name ) {
	$map = array(
		'Engine Oil Additive' => __( 'Ready-to-blend engine oil packages across heavy duty, passenger car and motorcycle service — graded to API, ACEA, ILSAC and JASO targets.', 'addlar' ),
		'Driveline'           => __( 'Gear, ATF, manual transmission and off-road packages, including multi-purpose chemistries that cover automotive and industrial gear duty from one platform.', 'addlar' ),
		'Marine'              => __( 'Trunk piston, system and cylinder oil packages, dosed by target base number for distillate and residual-fuelled marine engines.', 'addlar' ),
		'Industrial'          => __( 'Gear, grease, hydraulic and slideway packages for industrial fluid duty, including ashless extreme-pressure and anti-wear chemistries.', 'addlar' ),
		'Metal Working Fluid' => __( 'Neat cutting and soluble oil additive packages for severe metalworking operations — hobbing, broaching, tapping, drilling and forming.', 'addlar' ),
		'Lubricant Component' => __( 'Individual building blocks — detergents, dispersants, anti-wear and friction modifiers, antioxidants, pour point depressants and viscosity index improvers.', 'addlar' ),
	);

	return isset( $map[ $name ] ) ? $map[ $name ] : '';
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

		// Only structural meta — everything else lives in the page's own
		// Elementor widget settings now (see addlar_product_template_widgets()),
		// so the client edits product content on the Elementor canvas rather
		// than in a custom-fields metabox. These two are the exception because
		// the Finder and related-products queries look products up by them.
		update_post_meta( $post_id, '_addlar_code', $code );
		update_post_meta( $post_id, '_addlar_subcategory', isset( $p['subcategory'] ) ? $p['subcategory'] : '' );

		// Clear the content meta the previous (metabox-driven) versions wrote,
		// so an upgraded install isn't left carrying a stale duplicate of every
		// value that now lives in Elementor.
		foreach ( array(
			'_addlar_doc_code', '_addlar_spec_string', '_addlar_description',
			'_addlar_applications_text', '_addlar_performance_note',
			'_addlar_performance_headers', '_addlar_performance_rows_text',
			'_addlar_approvals_text', '_addlar_formulation_label',
			'_addlar_formulation_text', '_addlar_properties_text',
			'_addlar_viscosity_note', '_addlar_performance_table_html',
			'_addlar_properties_table_html', '_addlar_applications_html',
			'_addlar_approvals_html', '_addlar_formulation_html',
		) as $stale ) {
			delete_post_meta( $post_id, $stale );
		}

		$hero = addlar_product_hero_image( $code, $p['category'] );
		if ( ! empty( $hero['id'] ) ) {
			set_post_thumbnail( $post_id, (int) $hero['id'] );
		}

		// Each product is its own standalone, individually Elementor-editable
		// page (client's explicit request) — not one shared Theme Builder
		// template. Re-seeding overwrites manual edits, same "safe to re-run,
		// but re-running replaces the layout" contract as the homepage.
		if ( did_action( 'elementor/loaded' ) ) {
			$tree = addlar_build_tree( addlar_product_template_widgets( $code, $p ) );
			addlar_save_page( $post_id, $tree, false );
		}

		$count++;
	}

	return $count;
}

/**
 * Elementor only offers "Edit with Elementor" on post types it's been told
 * support it. Forced here (rather than left to Elementor Settings → Post
 * Types, a manual step someone could forget) so every addlar_product post
 * is individually editable the moment products are seeded.
 */
function addlar_enable_elementor_for_products() {
	$supported = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	if ( ! in_array( 'addlar_product', (array) $supported, true ) ) {
		$supported[] = 'addlar_product';
		update_option( 'elementor_cpt_support', $supported );
	}
}
add_action( 'init', 'addlar_enable_elementor_for_products', 20 );

/**
 * Each product's widget list — seeded onto every addlar_product post
 * individually, not into one shared Theme Builder template.
 *
 * **All content is baked into the widgets' own settings here**, at seed
 * time, from the real PDS data in inc/products-data.php. Nothing is read
 * from post meta at render time and there is no custom-fields metabox any
 * more: the client asked to "use standalone elementor widget inputs", so
 * every headline, chip, table row and card on a product page is a normal
 * Elementor control they can click and edit on the canvas.
 *
 * Visually this is built from the homepage's own component vocabulary
 * rather than lookalikes, because the gap the client kept flagging was
 * that product pages didn't read as the same site:
 *   - `addlar_product_hero`  — dark ground + red wedge, same weight as the
 *                              homepage hero (not a white text header)
 *   - `addlar_spec_cards`    — the Package Grid's hex-icon cards
 *   - `addlar_stat_band`     — the homepage's big-number band, reused as-is
 *   - `addlar_trust_strip`   — the homepage's certification strip, reused
 *   - `addlar_image_banner`  — full-bleed photo band
 *   - `addlar_related_products` / `addlar_closing_cta` — unchanged
 *
 * Sections whose data a given product genuinely lacks are simply not
 * added (KC420 has no approvals; Z 2612 has no performance table), rather
 * than seeded as empty blocks.
 *
 * @param string $code Bare product code.
 * @param array  $p    That product's row from addlar_products_data().
 * @return array
 */
function addlar_product_template_widgets( $code, array $p ) {
	$category  = isset( $p['category'] ) ? $p['category'] : '';
	$sub       = isset( $p['subcategory'] ) ? $p['subcategory'] : '';
	$mark      = addlar_seed_image( 'mark-white' );
	$secondary = addlar_product_secondary_image( $category );
	$eyebrow   = trim( $category . ( $sub ? ' · ' . $sub : '' ) );

	$widgets = array();

	/* ---------------------------------------------------- hero (+ crumbs) *
	 * The breadcrumb lives inside the hero rather than as a separate strip
	 * above it: the site header is `position: fixed`, so a thin first
	 * element renders underneath it and disappears. The hero carries the
	 * header offset, so a breadcrumb inside it is always visible. */
	$chips = array();
	foreach ( addlar_product_hero_chips( $p ) as $chip ) {
		$chips[] = array( 'text' => $chip );
	}

	$widgets[] = array(
		'type'     => 'addlar_product_hero',
		'settings' => array(
			'anchor'            => 'top',
			'eyebrow'           => $eyebrow,
			'title'             => $p['title'],
			'subtitle'          => addlar_product_field( $p, 'description' ),
			'doc_code'          => addlar_product_field( $p, 'doc_code' ),
			'image'             => addlar_product_hero_image( $code, $category ),
			'mark'              => $mark,
			'chips'             => addlar_rep( $chips ),
			'show_crumbs'       => 'yes',
			'crumb_parent'      => __( 'Products', 'addlar' ),
			'crumb_parent_link' => array( 'url' => '/products/' ),
			'btn1_text'         => __( 'Request data sheet →', 'addlar' ),
			'btn1_link'         => array( 'url' => '/contact-us/' ),
			'btn2_text'         => __( 'Talk to a formulator', 'addlar' ),
			'btn2_link'         => array( 'url' => '/ask-the-expert/' ),
		),
	);

	/* -------------------------------------------------------- spec cards */
	$cards = addlar_product_spec_cards( $p );
	if ( $cards ) {
		$widgets[] = array(
			'type'     => 'addlar_spec_cards',
			'settings' => array(
				'anchor'  => '',
				'soft'    => 'yes',
				'columns' => count( $cards ) >= 3 ? '3' : '2',
				'eyebrow' => __( 'Why this package', 'addlar' ),
				'title'   => __( 'Key performance benefits.', 'addlar' ),
				'lede'    => '',
				'cards'   => addlar_rep( $cards ),
			),
		);
	}

	/* ------------------------------------------------- performance table */
	$perf_headers = addlar_product_field( $p, 'performance_headers' );
	$perf_rows    = addlar_product_field( $p, 'performance_rows_text' );
	if ( $perf_headers && $perf_rows ) {
		$widgets[] = array(
			'type'     => 'addlar_spec_table',
			'settings' => array(
				'anchor'  => '',
				'soft'    => '',
				'eyebrow' => __( 'Treat rates', 'addlar' ),
				'title'   => __( 'Performance levels.', 'addlar' ),
				'note'    => addlar_product_field( $p, 'performance_note' ),
				'headers' => $perf_headers,
				'rows'    => $perf_rows,
			),
		);
	}

	/* ------------------------------------------------------- applications *
	 * Rendered with the homepage Applications section's dark icon list, not
	 * a row of chips — matching how the same kind of content looks on the
	 * homepage. Applications are short phrases, so each becomes a title
	 * with no second line rather than inventing supporting copy. */
	$applications = addlar_product_line_list( addlar_product_field( $p, 'applications_text' ) );
	if ( $applications ) {
		$app_items = array();
		foreach ( $applications as $app ) {
			$app_items[] = array( 'icon' => 'gear', 'title' => $app, 'text' => '' );
		}
		$widgets[] = array(
			'type'     => 'addlar_icon_list',
			'settings' => array(
				'anchor'  => '',
				'columns' => '1',
				'eyebrow' => __( "Where it works", 'addlar' ),
				'title'   => __( 'Applications.', 'addlar' ),
				'lede'    => '',
				// The hex-clipped image on the left is the homepage
				// Applications section's signature; without it the list reads
				// as a plain bullet column.
				'image'   => $secondary,
				'drop'    => $mark,
				'caption' => __( 'Additive science — in application', 'addlar' ),
				'items'   => addlar_rep( $app_items ),
			),
		);
	}

	/* ------------------------------------------------------ at a glance *
	 * The homepage's big-number band, background photo and all. */
	$glance = addlar_product_glance_stats( $p );
	if ( count( $glance ) >= 2 ) {
		// Products with no Applications section never show the hexagon
		// treatment otherwise, so it moves onto the numbers band for them.
		$staged = empty( $applications );
		$widgets[] = array(
			'type'     => 'addlar_stat_band',
			'settings' => array(
				'anchor'        => '',
				'stage_image'   => $staged ? $secondary : array(),
				'stage_drop'    => $staged ? $mark : array(),
				'stage_caption' => $staged ? __( 'Additive science — in application', 'addlar' ) : '',
				'eyebrow' => __( 'Product at a glance', 'addlar' ),
				'title'   => sprintf( /* translators: %s: product name */ __( '%s by the numbers.', 'addlar' ), $p['title'] ),
				'bg'      => $secondary,
				'columns' => (string) count( $glance ),
				'stats'   => addlar_rep( array_map( function ( $st ) {
					return array( 'count' => $st['count'], 'prefix' => '', 'suffix' => '', 'comma' => '', 'label' => $st['label'] );
				}, $glance ) ),
			),
		);
	}

	/* --------------------------------------------------------- approvals */
	$approval_items = addlar_product_approval_strip_items( $p );
	if ( $approval_items ) {
		$widgets[] = array(
			'type'     => 'addlar_trust_strip',
			'settings' => array( 'items' => addlar_rep( $approval_items ) ),
		);
	}

	/* ------------------------------------------------------- formulation */
	$formulation_rows = addlar_product_formulation_rows( $p );
	if ( $formulation_rows ) {
		$widgets[] = array(
			'type'     => 'addlar_spec_table',
			'settings' => array(
				'anchor'  => '',
				'soft'    => '',
				'eyebrow' => __( 'Worked example', 'addlar' ),
				'title'   => __( 'Formulation example.', 'addlar' ),
				'note'    => addlar_product_field( $p, 'formulation_label' ),
				'headers' => __( 'Component | Value', 'addlar' ),
				'rows'    => $formulation_rows,
			),
		);
	}

	/* -------------------------------------------------- typical properties */
	$properties_rows = addlar_product_properties_rows( $p );
	if ( $properties_rows ) {
		$widgets[] = array(
			'type'     => 'addlar_spec_table',
			'settings' => array(
				'anchor'  => '',
				'soft'    => 'yes',
				'eyebrow' => __( 'Lab data', 'addlar' ),
				'title'   => __( 'Typical properties.', 'addlar' ),
				'note'    => '',
				'headers' => __( 'Test | Method | Value', 'addlar' ),
				'rows'    => $properties_rows,
			),
		);
	}

	/* --------------------------------------------------- viscosity grades */
	$viscosity = addlar_product_line_list( addlar_product_field( $p, 'viscosity_note' ) );
	if ( $viscosity ) {
		$widgets[] = array(
			'type'     => 'addlar_chip_list',
			'settings' => array(
				'anchor'  => '',
				'soft'    => '',
				'eyebrow' => __( 'Coverage', 'addlar' ),
				'title'   => __( 'Viscosity grades.', 'addlar' ),
				'items'   => implode( "\n", $viscosity ),
				'style'   => 'solid',
			),
		);
	}

	/* -------------------------------------------------------- closing CTA *
	 * The red photographic band, same as the homepage and About Us. An
	 * earlier pass used a compact black bar here; the red band is the
	 * stronger close, and its spec chips carry the product's own
	 * specification rather than generic copy.
	 *
	 * The photo-tile pair and Related Products grid that used to sit above
	 * this were both removed on request. */
	$cta_maps = array();
	foreach ( array_slice( addlar_product_hero_chips( $p, 3 ), 0, 3 ) as $chip ) {
		$cta_maps[] = array( 'name' => $p['title'], 'spec' => $chip );
	}

	$widgets[] = array(
		'type'     => 'addlar_closing_cta',
		'settings' => array(
			'anchor'   => '',
			'bg'       => addlar_seed_image( 'cta' ),
			'eyebrow'  => __( 'Choose wisely — choose ADDLAR', 'addlar' ),
			'title'    => sprintf( /* translators: %s: product name */ __( 'Formulating with %s?', 'addlar' ), $p['title'] ),
			'text'     => __( 'Request the full data sheet, a sample, or formulation support from our technical team.', 'addlar' ),
			'btn_text' => __( 'Talk to our team →', 'addlar' ),
			'btn_link' => array( 'url' => '/contact-us/' ),
			'maps'     => addlar_rep( $cta_maps ),
		),
	);

	return $widgets;
}

/**
 * The category archive Theme Builder template's widget list: a term-aware
 * intro (name + description, pulled at render time) and the same related-
 * products grid widget in "archive" mode.
 *
 * @return array
 */
function addlar_category_archive_template_widgets() {
	$mark = addlar_seed_image( 'mark-white' );
	return array(
		// The dark hero doubles as the archive header and carries the
		// breadcrumb inside it — a standalone breadcrumb strip here would
		// render underneath the fixed site header and be invisible.
		array(
			'type'     => 'addlar_product_hero',
			'settings' => array(
				'anchor'            => 'top',
				'use_archive_term'  => 'yes',
				'eyebrow'           => __( 'Product Category', 'addlar' ),
				'title'             => __( 'Product Category', 'addlar' ),
				'subtitle'          => __( 'Every ADDLAR package in this family, with its documented treat rates, approvals and lab data.', 'addlar' ),
				'doc_code'          => '',
				'image'             => addlar_seed_image( 'industrial-2' ),
				'mark'              => $mark,
				'chips'             => array(),
				'show_crumbs'       => 'yes',
				'crumb_parent'      => __( 'Products', 'addlar' ),
				'crumb_parent_link' => array( 'url' => '/products/' ),
				'btn1_text'         => __( 'Request a data sheet →', 'addlar' ),
				'btn1_link'         => array( 'url' => '/contact-us/' ),
				'btn2_text'         => __( 'Talk to a formulator', 'addlar' ),
				'btn2_link'         => array( 'url' => '/ask-the-expert/' ),
			),
		),
		array(
			'type'     => 'addlar_related_products',
			'settings' => array( 'mode' => 'archive', 'heading' => '', 'count' => 24, 'mark' => $mark ),
		),
		array(
			'type'     => 'addlar_closing_cta',
			'settings' => array( 'bg' => addlar_seed_image( 'cta' ) ),
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
 * Trash the "ADDLAR Product — Single" Theme Builder template from the
 * previous iteration of this build, if it's still there.
 *
 * Theme Builder's "single" condition template takes precedence over a
 * post's own individual `_elementor_data` — so if that old shared template
 * (and its `include/post_type/addlar_product` condition) is left active,
 * it silently overrides every product's new standalone page and nothing
 * from addlar_seed_products()'s per-post seeding would ever actually show.
 * Trashed rather than force-deleted, so it's recoverable if this is wrong
 * for some install.
 *
 * @return bool True if a stale template was found and trashed.
 */
function addlar_remove_stale_product_template() {
	$post = addlar_find_post_by_title( 'ADDLAR Product — Single', 'elementor_library' );
	if ( ! $post ) {
		return false;
	}
	wp_trash_post( $post->ID );
	return true;
}

/**
 * 'elementor' (default) or 'coded' — whether the category archive should
 * use the Theme Builder template or always fall through to the coded
 * `taxonomy-addlar_product_category.php` template. A settings toggle
 * (Tools → ADDLAR setup) rather than something only fixable in code: the
 * taxonomy not appearing in Elementor's Theme Builder condition picker is a
 * real, reported problem that can't be fully diagnosed or guaranteed fixed
 * without a live Elementor install, so this gives a guaranteed-working
 * escape hatch regardless of whether the taxonomy-registration fix in
 * inc/products-cpt.php actually resolves it.
 *
 * @return string
 */
function addlar_category_template_mode() {
	$mode = get_option( 'addlar_category_template_mode', 'elementor' );
	return 'coded' === $mode ? 'coded' : 'elementor';
}

/**
 * Seed the category archive Elementor Theme Builder template, conditioned
 * to the addlar_product_category taxonomy. This is what fixes "category
 * page shows not exist" together with the flush_rewrite_rules() call in
 * addlar_seed_products_and_pages() — the archive URL 404s until WordPress's
 * rewrite rules are regenerated after a new taxonomy is registered, quite
 * apart from whether a template exists for it.
 *
 * When addlar_category_template_mode() is 'coded', the template's condition
 * is cleared instead of set, so Elementor never intercepts the archive URL
 * and WordPress's normal template hierarchy reaches
 * taxonomy-addlar_product_category.php — the guaranteed-working path.
 *
 * @return array template_id + message
 */
function addlar_seed_category_archive_theme_builder_template() {
	if ( 'coded' === addlar_category_template_mode() ) {
		$result = addlar_seed_theme_builder_template(
			'ADDLAR Product — Category Archive',
			'archive',
			array(), // No condition — Elementor never claims this URL.
			addlar_category_archive_template_widgets()
		);
		$result['message'] = __( 'Category archive: using the coded template (Theme Builder disabled in settings).', 'addlar' );
		return $result;
	}

	$result             = addlar_seed_theme_builder_template(
		'ADDLAR Product — Category Archive',
		'archive',
		array( 'include/taxonomy/addlar_product_category' ),
		addlar_category_archive_template_widgets()
	);
	$result['message'] .= ' ' . __( 'If "Product Categories" doesn\'t appear as a condition option in Elementor, switch to the coded template below instead.', 'addlar' );
	return $result;
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
			'type'     => 'addlar_product_hero',
			'settings' => array(
				'anchor'            => 'top',
				'use_archive_term'  => '',
				'eyebrow'           => __( 'Product Range', 'addlar' ),
				'title'             => __( 'Every lubrication challenge.', 'addlar' ),
				'subtitle'          => __( 'Six additive families plus complementary products — each engineered for a specific world of machinery, and documented down to the treat rate.', 'addlar' ),
				'doc_code'          => '',
				'image'             => addlar_seed_image( 'engine-oil-2' ),
				'mark'              => addlar_seed_image( 'mark-white' ),
				'chips'             => addlar_rep( array(
					array( 'text' => __( 'API · ACEA · ILSAC · JASO', 'addlar' ) ),
					array( 'text' => __( '22 documented products', 'addlar' ) ),
					array( 'text' => __( 'Automotive · Marine · Industrial', 'addlar' ) ),
				) ),
				'show_crumbs'       => 'yes',
				'crumb_parent'      => '',
				'crumb_parent_link' => array( 'url' => '' ),
				'btn1_text'         => __( 'Find your additive →', 'addlar' ),
				'btn1_link'         => array( 'url' => '#finder' ),
				'btn2_text'         => __( 'Talk to a formulator', 'addlar' ),
				'btn2_link'         => array( 'url' => '/ask-the-expert/' ),
			),
		),
		array(
			'type'     => 'addlar_product_grid',
			'settings' => array(
				'anchor'     => 'families',
				'cards'      => addlar_rep( addlar_product_cards() ),
				'mark'       => addlar_seed_image( 'mark' ),
				'promo_link' => array( 'url' => home_url( '/contact-us/' ) ),
			),
		),
		// The homepage's three-step Finder, reused here — this is the page
		// people land on to choose a product, so it belongs on it.
		array(
			'type'     => 'addlar_product_finder',
			'settings' => array( 'anchor' => 'finder', 'soft' => '' ),
		),
		array(
			'type'     => 'addlar_closing_cta',
			'settings' => array( 'bg' => addlar_seed_image( 'cta' ) ),
		),
	) );
	addlar_save_page( $page_id, $tree, false );

	return array( 'page_id' => $page_id, 'message' => __( 'Products overview page seeded.', 'addlar' ) );
}

/**
 * Find-or-create a Page by slug, build its Elementor tree from a widget
 * list, and save it — the shared boilerplate behind every non-homepage
 * page seeder (Products overview, About Us, Contact Us, Ask the Expert).
 *
 * @param string $slug    Page slug.
 * @param string $title   Page title.
 * @param array  $widgets Widget list for addlar_build_tree().
 * @return int Page ID, or 0 on failure.
 */
function addlar_seed_page( $slug, $title, array $widgets ) {
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

	$tree = addlar_build_tree( $widgets );
	addlar_save_page( $page_id, $tree, false );

	return $page_id;
}

/**
 * About Us — built from the client's own copy (Drive:
 * `Content/About Us Page.docx`), not placeholder text. Structure follows
 * the client's Web Design blueprint for this page: intro, an image band of
 * the industries ADDLAR serves, the "ADDLAR Advantage" section, three
 * reasons to choose Rchemie (reusing the existing Why List widget), and a
 * closing CTA into Contact Us.
 *
 * The source doc states the HQ is in Dubai; the theme's own Customizer
 * default (set in Phase 1, still live) says Sharjah. That conflict is
 * flagged, not silently resolved — see the Phase 2 chat notes — so this
 * page's prose uses the doc's own wording verbatim rather than editing the
 * client's copy, while every other page keeps reading the already-approved
 * addlar_mod('addlar_address').
 *
 * @return int Page ID.
 */
function addlar_seed_about_page() {
	$mark = addlar_seed_image( 'mark-white' );

	$industries = array(
		array( 'slot' => 'engine-oil',   'caption' => __( 'Automotive', 'addlar' ) ),
		array( 'slot' => 'driveline',    'caption' => __( 'Driveline', 'addlar' ) ),
		array( 'slot' => 'marine',       'caption' => __( 'Marine', 'addlar' ) ),
		array( 'slot' => 'industrial',   'caption' => __( 'Industrial', 'addlar' ) ),
		array( 'slot' => 'metalworking', 'caption' => __( 'Metalworking', 'addlar' ) ),
		array( 'slot' => 'components',   'caption' => __( 'Lubricant Components', 'addlar' ) ),
	);
	$industry_items = array();
	foreach ( $industries as $row ) {
		$industry_items[] = array( 'image' => addlar_seed_image( $row['slot'] ), 'caption' => $row['caption'], 'link' => array( 'url' => '' ) );
	}

	return addlar_seed_page( 'about-us', __( 'About Us', 'addlar' ), array(
		array(
			'type'     => 'addlar_product_hero',
			'settings' => array(
				'anchor'            => 'top',
				'eyebrow'           => __( '20 Years of Chemical Expertise', 'addlar' ),
				'title'             => __( 'About ADDLAR', 'addlar' ),
				'subtitle'          => __( 'Rchemie International has spent two decades as a chemical powerhouse and trusted global supply chain partner. ADDLAR is its flagship brand of high-performance lubricant additive packages.', 'addlar' ),
				'doc_code'          => '',
				'image'             => addlar_seed_image( 'about' ),
				'mark'              => $mark,
				'chips'             => addlar_rep( array(
					array( 'text' => __( 'Founded 2006', 'addlar' ) ),
					array( 'text' => __( 'API · ACEA · ILSAC · JASO', 'addlar' ) ),
					array( 'text' => __( '25+ countries served', 'addlar' ) ),
				) ),
				'show_crumbs'       => 'yes',
				'crumb_parent'      => '',
				'crumb_parent_link' => array( 'url' => '' ),
				'btn1_text'         => __( 'Explore the range →', 'addlar' ),
				'btn1_link'         => array( 'url' => '/products/' ),
				'btn2_text'         => __( 'Get in touch', 'addlar' ),
				'btn2_link'         => array( 'url' => '/contact-us/' ),
			),
		),
		array(
			'type'     => 'addlar_rich_text',
			'settings' => array(
				'eyebrow' => __( 'Who we are', 'addlar' ),
				'title'   => __( 'Now powering the world of lubricants.', 'addlar' ),
				'body'    => '<p>' . esc_html__( 'For over two decades, Rchemie International has stood as a premier chemical powerhouse and a trusted supply chain partner globally. Headquartered in Dubai, United Arab Emirates, we have dedicated the last 20 years to supporting the growth, development, and operational efficiency of heavy industries across the UAE and beyond.', 'addlar' ) . '</p>'
					. '<p>' . esc_html__( 'ADDLAR is Rchemie\'s flagship brand of high-performance lubricant additive packages. It represents the pinnacle of our chemical engineering capabilities — designed to meet the rapidly evolving, high-stress demands of modern automotive, industrial, and marine machinery.', 'addlar' ) . '</p>',
			),
		),
		array(
			'type'     => 'addlar_spec_cards',
			'settings' => array(
				'anchor'  => 'why-rchemie',
				'soft'    => 'yes',
				'columns' => '3',
				'eyebrow' => __( 'Why Rchemie', 'addlar' ),
				'title'   => __( 'Why leading industries choose us.', 'addlar' ),
				'lede'    => __( 'Our reputation is built on three unwavering pillars of corporate excellence.', 'addlar' ),
				'cards'   => addlar_rep( array(
					array(
						'icon'  => 'shield',
						'lab'   => __( 'Quality', 'addlar' ),
						'title' => __( 'Uncompromising quality assurance', 'addlar' ),
						'text'  => __( 'Every batch undergoes exhaustive testing to guarantee absolute batch-to-batch consistency and peak operational safety.', 'addlar' ),
					),
					array(
						'icon'  => 'people',
						'lab'   => __( 'Expertise', 'addlar' ),
						'title' => __( 'Elite technical expertise', 'addlar' ),
						'text'  => __( 'Seasoned chemical professionals and engineers offering technical guidance, custom formulation consultation and application support.', 'addlar' ),
					),
					array(
						'icon'  => 'globe',
						'lab'   => __( 'Service', 'addlar' ),
						'title' => __( 'Reliable service & agile supply', 'addlar' ),
						'text'  => __( 'A strategic logistics hub, transparent communication and dependable, on-time global delivery.', 'addlar' ),
					),
				) ),
			),
		),
		array(
			'type'     => 'addlar_icon_list',
			'settings' => array(
				'anchor'  => '',
				'columns' => '1',
				'eyebrow' => __( 'The ADDLAR advantage', 'addlar' ),
				'title'   => __( 'Comprehensive package. Complexity, simplified.', 'addlar' ),
				'lede'    => __( 'All-in-one additive packages that integrate multiple critical chemical components into a single, perfectly balanced formulation.', 'addlar' ),
				'items'   => addlar_rep( array(
					array( 'icon' => 'detergent', 'title' => __( 'Detergents & Dispersants', 'addlar' ), 'text' => __( 'Engineered to prevent sludge buildup and maintain pristine engine cleanliness.', 'addlar' ) ),
					array( 'icon' => 'wear', 'title' => __( 'Anti-Wear Agents & Friction Modifiers', 'addlar' ), 'text' => __( 'A resilient chemical barrier minimising mechanical drag and metal-on-metal wear.', 'addlar' ) ),
					array( 'icon' => 'antiox', 'title' => __( 'High-Tier Antioxidants', 'addlar' ), 'text' => __( 'Resist thermal breakdown, extending oil life under extreme temperatures.', 'addlar' ) ),
					array( 'icon' => 'shield', 'title' => __( 'Global Compliance by Design', 'addlar' ), 'text' => __( 'Tested to meet or exceed API, ACEA, ILSAC and JASO benchmarks.', 'addlar' ) ),
				) ),
			),
		),
		array(
			'type'     => 'addlar_stat_band',
			'settings' => array(
				'anchor'  => 'numbers',
				'eyebrow' => __( 'ADDLAR by the numbers', 'addlar' ),
				'title'   => __( 'Scale you can formulate against.', 'addlar' ),
				'bg'      => addlar_seed_image( 'numbers-bg' ),
				'columns' => '5',
			),
		),
		array(
			'type'     => 'addlar_image_grid',
			'settings' => array(
				'soft'    => 'yes',
				'style'   => 'caption',
				'columns' => '6',
				'mark'    => $mark,
				'eyebrow' => __( 'Industries we serve', 'addlar' ),
				'title'   => __( 'Wherever fluids do critical work', 'addlar' ),
				'lede'    => '',
				'items'   => addlar_rep( $industry_items ),
			),
		),
		array(
			'type'     => 'addlar_closing_cta',
			'settings' => array(
				'anchor'   => 'partner',
				'bg'       => addlar_seed_image( 'cta' ),
				'eyebrow'  => __( 'Partner with us today', 'addlar' ),
				'title'    => __( 'Discover how ADDLAR can elevate your formulations', 'addlar' ),
				'text'     => __( 'Looking for specific formulations, technical support, or ready to partner with our team? We would love to hear from you.', 'addlar' ),
				'btn_text' => __( 'Get in touch →', 'addlar' ),
				'btn_link' => array( 'url' => '/contact-us/' ),
				'maps'     => array(),
			),
		),
	) );
}

/**
 * Contact Us — real channels only (addlar_mod('addlar_email') /
 * ('addlar_address') / ('addlar_website'), already configured in Phase 1),
 * plus a working contact form (Baskar Palani's proposed field set — see
 * inc/contact-form.php). No manufacturing/sales street address is
 * fabricated; the client's Drive content only confirms a city-level HQ.
 *
 * @return int Page ID.
 */
function addlar_seed_contact_page() {
	return addlar_seed_page( 'contact-us', __( 'Contact Us', 'addlar' ), array(
		array(
			'type'     => 'addlar_product_hero',
			'settings' => array(
				'anchor'            => 'top',
				'eyebrow'           => __( 'Contact Us', 'addlar' ),
				'title'             => __( 'Get in Touch', 'addlar' ),
				'subtitle'          => __( "Have a question about ADDLAR's additive packages, need a data sheet, or want to discuss a formulation? Reach us directly below.", 'addlar' ),
				'doc_code'          => '',
				'image'             => addlar_seed_image( 'about' ),
				'mark'              => addlar_seed_image( 'mark-white' ),
				'chips'             => addlar_rep( array(
					array( 'text' => __( 'Technical support', 'addlar' ) ),
					array( 'text' => __( 'Samples & data sheets', 'addlar' ) ),
					array( 'text' => __( 'Global supply', 'addlar' ) ),
				) ),
				'show_crumbs'       => 'yes',
				'crumb_parent'      => '',
				'crumb_parent_link' => array( 'url' => '' ),
				'btn1_text'         => __( 'Send a message →', 'addlar' ),
				'btn1_link'         => array( 'url' => '#form' ),
				'btn2_text'         => __( 'Ask the expert', 'addlar' ),
				'btn2_link'         => array( 'url' => '/ask-the-expert/' ),
			),
		),
		array(
			'type'     => 'addlar_contact_info',
			'settings' => array(
				'soft'  => 'yes',
				'items' => addlar_rep( array(
					array( 'icon' => 'mail', 'label' => __( 'Email', 'addlar' ), 'value' => addlar_mod( 'addlar_email' ), 'link' => array( 'url' => 'mailto:' . addlar_mod( 'addlar_email' ) ) ),
					array( 'icon' => 'pin', 'label' => __( 'Headquarters', 'addlar' ), 'value' => addlar_mod( 'addlar_address' ), 'link' => array( 'url' => '' ) ),
					array( 'icon' => 'globe', 'label' => __( 'Parent company', 'addlar' ), 'value' => addlar_mod( 'addlar_website' ), 'link' => array( 'url' => addlar_mod( 'addlar_website' ) ) ),
				) ),
			),
		),
		array(
			'type'     => 'addlar_contact_form',
			'settings' => array( 'anchor' => 'form', 'preset' => 'contact', 'submit_label' => __( 'Send message →', 'addlar' ) ),
		),
		array(
			'type'     => 'addlar_cta_bar',
			'settings' => array(
				'icon'     => 'linkedin',
				'title'    => __( 'Follow ADDLAR on LinkedIn', 'addlar' ),
				'text'     => __( 'Formulation notes, specification updates and product releases.', 'addlar' ),
				'btn_text' => __( 'Follow the showcase →', 'addlar' ),
				'btn_link' => array( 'url' => addlar_mod( 'addlar_linkedin_url' ), 'is_external' => 'on' ),
			),
		),
	) );
}

/**
 * Ask the Expert — an inquiry form (client blueprint: "Inquiry Form" +
 * "Whitepaper discussion blog articles", the latter deferred to the Blog
 * pass since there's no blog content yet) pointing readers to the real
 * ADDLAR LinkedIn showcase in the meantime.
 *
 * @return int Page ID.
 */
function addlar_seed_ask_the_expert_page() {
	return addlar_seed_page( 'ask-the-expert', __( 'Ask the Expert', 'addlar' ), array(
		array(
			'type'     => 'addlar_product_hero',
			'settings' => array(
				'anchor'            => 'top',
				'eyebrow'           => __( 'Ask the Expert', 'addlar' ),
				'title'             => __( 'Ask Our Technical Team', 'addlar' ),
				'subtitle'          => __( 'Have a formulation or application question? Send it straight to our chemical engineers.', 'addlar' ),
				'doc_code'          => '',
				'image'             => addlar_seed_image( 'components-2' ),
				'mark'              => addlar_seed_image( 'mark-white' ),
				'chips'             => addlar_rep( array(
					array( 'text' => __( 'Formulation support', 'addlar' ) ),
					array( 'text' => __( 'Treat rate guidance', 'addlar' ) ),
					array( 'text' => __( 'Base oil strategy', 'addlar' ) ),
				) ),
				'show_crumbs'       => 'yes',
				'crumb_parent'      => '',
				'crumb_parent_link' => array( 'url' => '' ),
				'btn1_text'         => __( 'Ask a question →', 'addlar' ),
				'btn1_link'         => array( 'url' => '#form' ),
				'btn2_text'         => __( 'Contact us', 'addlar' ),
				'btn2_link'         => array( 'url' => '/contact-us/' ),
			),
		),
		array(
			'type'     => 'addlar_spec_cards',
			'settings' => array(
				'anchor'  => '',
				'soft'    => 'yes',
				'columns' => '3',
				'eyebrow' => __( 'What we can help with', 'addlar' ),
				'title'   => __( 'Bring us the hard questions.', 'addlar' ),
				'lede'    => '',
				'cards'   => addlar_rep( array(
					array( 'icon' => 'flask', 'lab' => __( 'Formulation', 'addlar' ), 'title' => __( 'Package selection', 'addlar' ), 'text' => __( 'Which ADDLAR package fits your target specification, base oil and viscosity spread.', 'addlar' ) ),
					array( 'icon' => 'viscosity', 'lab' => __( 'Treat rates', 'addlar' ), 'title' => __( 'Dosage & cascade', 'addlar' ), 'text' => __( 'How a single package cascades across grades, and the treat rate each level needs.', 'addlar' ) ),
					array( 'icon' => 'shield', 'lab' => __( 'Compliance', 'addlar' ), 'title' => __( 'Specifications & approvals', 'addlar' ), 'text' => __( 'Meeting API, ACEA, ILSAC and JASO targets, and the OEM approvals behind them.', 'addlar' ) ),
				) ),
			),
		),
		array(
			'type'     => 'addlar_contact_form',
			'settings' => array( 'anchor' => 'form', 'preset' => 'expert', 'submit_label' => __( 'Ask your question →', 'addlar' ) ),
		),
		array(
			'type'     => 'addlar_cta_bar',
			'settings' => array(
				'icon'     => 'linkedin',
				'title'    => __( 'Follow ADDLAR on LinkedIn', 'addlar' ),
				'text'     => __( 'Formulation notes, specification updates and product releases.', 'addlar' ),
				'btn_text' => __( 'Follow the showcase →', 'addlar' ),
				'btn_link' => array( 'url' => addlar_mod( 'addlar_linkedin_url' ), 'is_external' => 'on' ),
			),
		),
	) );
}

/**
 * Blog only, now — About Us, Contact Us and Ask the Expert are fully
 * designed pages (addlar_seed_about_page(), addlar_seed_contact_page(),
 * addlar_seed_ask_the_expert_page() below), not placeholders. Blog is WP's
 * native "Posts page" mechanism: a page assigned via `page_for_posts`, no
 * Elementor content and no posts to seed — the existing index.php fallback
 * already renders an empty state, and there's no client content for a blog
 * yet (deferred scope, unchanged).
 *
 * @return array slug => page_id
 */
function addlar_seed_stub_pages() {
	$ids = array();

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

	addlar_remove_stale_product_template();

	$product_count     = addlar_seed_products();
	$archive_template   = addlar_seed_category_archive_theme_builder_template();
	$overview          = addlar_seed_products_overview_page();
	$stubs             = addlar_seed_stub_pages();
	$about             = addlar_seed_about_page();
	$contact           = addlar_seed_contact_page();
	$expert            = addlar_seed_ask_the_expert_page();

	// New CPT + taxonomy rewrite rules (and the category archive URLs they
	// enable) only take effect once WordPress's rewrite rules are
	// regenerated — this is what fixes a fresh category archive 404ing even
	// though the term and template both exist.
	flush_rewrite_rules();

	$message = sprintf(
		/* translators: %d: number of products seeded, each its own standalone Elementor page */
		__( '%d standalone product pages seeded.', 'addlar' ),
		$product_count
	) . ' ' . $archive_template['message'];

	if ( $overview['page_id'] ) {
		$message .= ' <a href="' . esc_url( get_permalink( $overview['page_id'] ) ) . '">' . esc_html__( 'View Products page', 'addlar' ) . '</a>';
	}

	return array(
		'message'     => $message,
		'stub_ids'    => $stubs,
		'overview_id' => $overview['page_id'],
		'about_id'    => $about,
		'contact_id'  => $contact,
		'expert_id'   => $expert,
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

	$update_notice = '';
	if ( isset( $_POST['addlar_check_updates'] ) && check_admin_referer( 'addlar_check_updates_action' ) ) {
		$checker = function_exists( 'addlar_updater' ) ? addlar_updater() : null;
		if ( ! $checker ) {
			$update_notice = __( 'Updates are switched off for this install (no repository configured).', 'addlar' );
		} else {
			// PUC caches results for hours; this forces a live request so the
			// dashboard reflects a release published moments ago.
			$checker->checkForUpdates();
			$state  = $checker->getUpdateState();
			$update = $state ? $state->getUpdate() : null;

			if ( $update && ! empty( $update->version ) && version_compare( $update->version, ADDLAR_VERSION, '>' ) ) {
				$update_notice = sprintf(
					/* translators: 1: available version, 2: installed version */
					__( 'Version %1$s is available (you have %2$s). Go to Appearance → Themes to install it.', 'addlar' ),
					$update->version,
					ADDLAR_VERSION
				);
			} else {
				/* translators: %s: installed version */
				$update_notice = sprintf( __( 'No update found — %s is the latest published release.', 'addlar' ), ADDLAR_VERSION );
			}
		}
	}

	$category_mode_notice = '';
	if ( isset( $_POST['addlar_category_mode'] ) && check_admin_referer( 'addlar_category_mode_action' ) ) {
		$mode = 'coded' === sanitize_key( wp_unslash( $_POST['addlar_category_mode'] ) ) ? 'coded' : 'elementor';
		update_option( 'addlar_category_template_mode', $mode );
		$result = addlar_seed_category_archive_theme_builder_template();
		flush_rewrite_rules();
		$category_mode_notice = $result['message'];
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

	echo '<p>' . esc_html__( 'Builds the 22 real product pages (from the client’s Product Data Sheets) as individually standalone, Elementor-editable pages. All product content — headline, spec chips, benefit cards, data tables — is written into each page’s own Elementor widgets, so it is edited on the Elementor canvas, not in a custom-fields box. Also seeds the category archive template, the Products landing page, and the About Us / Contact Us / Ask the Expert pages (Blog stays a placeholder — no client content for it yet), and flushes permalinks.', 'addlar' ) . '</p>';
	echo '<p><strong>' . esc_html__( 'Safe to re-run', 'addlar' ) . '</strong> — ' . esc_html__( 'products and pages are matched by slug and updated in place, not duplicated. Re-running replaces each product page\'s layout, so manual edits made directly in Elementor will be lost.', 'addlar' ) . '</p>';

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

	/* ------------------------------------------------------------ updates */
	echo '<h2>' . esc_html__( 'Theme updates', 'addlar' ) . '</h2>';
	if ( $update_notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $update_notice ) . '</p></div>';
	}

	$repo    = function_exists( 'addlar_github_repo' ) ? addlar_github_repo() : '';
	$enabled = function_exists( 'addlar_updates_enabled' ) && addlar_updates_enabled();

	echo '<table class="widefat striped" style="max-width:720px;margin-bottom:14px;"><tbody>';
	printf(
		'<tr><td style="width:220px;"><strong>%s</strong></td><td>%s</td></tr>',
		esc_html__( 'Installed version', 'addlar' ),
		esc_html( ADDLAR_VERSION )
	);
	printf(
		'<tr><td><strong>%s</strong></td><td>%s</td></tr>',
		esc_html__( 'Update source', 'addlar' ),
		$repo ? esc_html( $repo ) : '<em>' . esc_html__( 'not configured', 'addlar' ) . '</em>'
	);
	printf(
		'<tr><td><strong>%s</strong></td><td>%s</td></tr>',
		esc_html__( 'Status', 'addlar' ),
		$enabled
			? '<span style="color:#007017;">' . esc_html__( 'Enabled — updates appear under Appearance → Themes', 'addlar' ) . '</span>'
			: '<span style="color:#b32d2e;">' . esc_html__( 'Disabled — no updates will ever be offered', 'addlar' ) . '</span>'
	);
	printf(
		'<tr><td><strong>%s</strong></td><td><code>%s</code></td></tr>',
		esc_html__( 'Theme folder', 'addlar' ),
		esc_html( basename( ADDLAR_DIR ) )
	);
	echo '</tbody></table>';

	echo '<p>' . esc_html__( 'Updates come from GitHub Releases that have a .zip attached — pushing code alone never triggers one. WordPress only checks periodically, so use this button to check immediately after a release is published.', 'addlar' ) . '</p>';
	echo '<form method="post">';
	wp_nonce_field( 'addlar_check_updates_action' );
	submit_button( __( 'Check for updates now', 'addlar' ), 'secondary', 'addlar_check_updates' );
	echo '</form>';

	echo '<hr>';

	echo '<h2>' . esc_html__( 'Category archive template', 'addlar' ) . '</h2>';
	if ( $category_mode_notice ) {
		echo '<div class="notice notice-success"><p>' . wp_kses_post( $category_mode_notice ) . '</p></div>';
	}
	echo '<p>' . esc_html__( 'If the "Product Categories" taxonomy doesn\'t show up as an option in Elementor\'s own Theme Builder condition picker, switch this to the coded template — it doesn\'t depend on Elementor recognising the taxonomy at all and works immediately.', 'addlar' ) . '</p>';
	$current_mode = addlar_category_template_mode();
	echo '<form method="post">';
	wp_nonce_field( 'addlar_category_mode_action' );
	echo '<p>';
	echo '<label style="margin-right:20px;"><input type="radio" name="addlar_category_mode" value="elementor"' . checked( 'elementor', $current_mode, false ) . '> ' . esc_html__( 'Elementor Theme Builder (default)', 'addlar' ) . '</label>';
	echo '<label><input type="radio" name="addlar_category_mode" value="coded"' . checked( 'coded', $current_mode, false ) . '> ' . esc_html__( 'Coded template (guaranteed to work)', 'addlar' ) . '</label>';
	echo '</p>';
	submit_button( __( 'Save', 'addlar' ), 'secondary', 'addlar_save_category_mode' );
	echo '</form>';

	echo '<hr>';

	echo '<h2>' . esc_html__( 'Export the category archive template', 'addlar' ) . '</h2>';
	echo '<p>' . esc_html__( 'Each product page is now its own standalone Elementor page (open its edit screen and click "Edit with Elementor" directly — no shared template involved). The category archive is still one shared Theme Builder template, though; download it as an Elementor-importable .json below if you want to fix its display condition by hand or hand a starting point to someone editing it directly.', 'addlar' ) . '</p>';

	$archive_id = addlar_find_template_id_by_title( 'ADDLAR Product — Category Archive' );

	echo '<p>';
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
