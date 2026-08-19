<?php
/**
 * Seed-time product helpers + Product Finder catalogue derivation.
 *
 * Product content is authored in each page's own Elementor widgets, not in
 * post meta (client's request: "instead of using custom fields use
 * standalone elementor widget inputs"). So the helpers here run **once, at
 * seed time**: they turn the raw PDS data in inc/products-data.php into
 * ready-made Elementor widget settings. After seeding, nothing on the front
 * end calls them — the page renders straight from `_elementor_data`, and
 * the client edits it in Elementor.
 *
 * That's a deliberate simplification of the previous pipeline (metabox
 * textareas → save_post → pre-rendered HTML in meta → a widget echoing it),
 * which had four moving parts where one now does.
 *
 * The 22 real PDS documents are heterogeneous: some products have no
 * performance table at all (raw components dosed by a flat % range, e.g.
 * KC420/Z 2612), some use 2 columns rather than 3, one uses a formulation
 * recipe instead of a table (9200), and several carry long OEM-approval
 * lists. Every helper below returns an empty result rather than a
 * placeholder when a product genuinely lacks that data, and the seeder
 * skips the corresponding section entirely.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'ADDLAR_TESTING' ) ) {
	exit;
}

/**
 * Split "one row per line, columns separated by |" text into a clean array
 * of arrays. Blank lines are skipped; each cell is trimmed. A blank cell
 * (two consecutive `|` characters, or nothing after the last `|`) is kept as
 * an empty string rather than dropped, so column position never shifts.
 *
 * @param string $text Raw value.
 * @return array
 */
function addlar_product_table_rows( $text ) {
	$rows  = array();
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );

	foreach ( $lines as $line ) {
		if ( '' === trim( $line ) ) {
			continue;
		}
		$rows[] = array_map( 'trim', explode( '|', $line ) );
	}

	return $rows;
}

/**
 * Split a plain "one item per line" value into a trimmed, blank-filtered
 * list. Used for applications and approvals, which are flat lists.
 *
 * @param string $text Raw value.
 * @return array
 */
function addlar_product_line_list( $text ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
	$lines = array_map( 'trim', $lines );

	return array_values( array_filter( $lines, function ( $l ) {
		return '' !== $l;
	} ) );
}

/**
 * Read a key from a product's data array (inc/products-data.php) without
 * repeating an isset() ternary at every call site.
 *
 * @param array  $p   Product data row.
 * @param string $key Key.
 * @return string
 */
function addlar_product_field( array $p, $key ) {
	return isset( $p[ $key ] ) ? (string) $p[ $key ] : '';
}

/**
 * "Key Performance Benefits" cards for the product hero's card row —
 * icon + short title + supporting text, ready for Addlar_Widget_SpecCards.
 *
 * Every card restates a fact already present in this product's own
 * transcribed PDS data (a real application, the real spec string, a real
 * count of approvals or performance levels) — never an invented performance
 * claim. The icon is a direct 1:1 mapping from which field the fact came
 * from, not a guess.
 *
 * @param array $p     Product data row.
 * @param int   $limit Maximum cards.
 * @return array Each: array( icon, lab, title, text ).
 */
function addlar_product_spec_cards( array $p, $limit = 3 ) {
	$cards = array();

	$spec = trim( addlar_product_field( $p, 'spec_string' ) );
	if ( $spec ) {
		$cards[] = array(
			'icon'  => 'shield',
			'lab'   => __( 'Specification', 'addlar' ),
			'title' => __( 'Meets global standards', 'addlar' ),
			'text'  => $spec,
		);
	}

	$rows = addlar_product_table_rows( addlar_product_field( $p, 'performance_rows_text' ) );
	if ( $rows ) {
		$cards[] = array(
			'icon'  => 'layers',
			'lab'   => __( 'Cascade', 'addlar' ),
			/* translators: %d: number of real graded performance levels in this product's PDS */
			'title' => sprintf( _n( '%d performance level', '%d performance levels', count( $rows ), 'addlar' ), count( $rows ) ),
			'text'  => __( 'One package cascades across multiple grades, simplifying blending inventory.', 'addlar' ),
		);
	}

	$approvals = addlar_product_line_list( addlar_product_field( $p, 'approvals_text' ) );
	if ( $approvals ) {
		$cards[] = array(
			'icon'  => 'globe',
			'lab'   => __( 'Approvals', 'addlar' ),
			/* translators: %d: number of real OEM/industry approvals listed in this product's PDS */
			'title' => sprintf( _n( '%d OEM approval', '%d OEM approvals', count( $approvals ), 'addlar' ), count( $approvals ) ),
			'text'  => __( 'Independently aligned with leading OEM and industry performance targets.', 'addlar' ),
		);
	}

	$applications = addlar_product_line_list( addlar_product_field( $p, 'applications_text' ) );
	if ( $applications ) {
		$cards[] = array(
			'icon'  => 'gear',
			'lab'   => __( 'Applications', 'addlar' ),
			/* translators: %d: number of real application/use-case lines in this product's PDS */
			'title' => sprintf( _n( '%d documented application', '%d documented applications', count( $applications ), 'addlar' ), count( $applications ) ),
			'text'  => implode( ' · ', array_slice( $applications, 0, 3 ) ),
		);
	}

	$viscosity = trim( addlar_product_field( $p, 'viscosity_note' ) );
	if ( $viscosity ) {
		$cards[] = array(
			'icon'  => 'viscosity',
			'lab'   => __( 'Viscometry', 'addlar' ),
			'title' => __( 'Multiple viscosity grades', 'addlar' ),
			'text'  => __( 'Formulated to cover a broad grade spread from a single package.', 'addlar' ),
		);
	}

	return array_slice( $cards, 0, $limit );
}

/**
 * Short spec chips for the product hero — the spec string split on its
 * natural separators, so "API SN/CF, SL to SJ · ACEA C3/C4 · ILSAC GF-5"
 * becomes three chips instead of one long line.
 *
 * @param array $p     Product data row.
 * @param int   $limit Maximum chips.
 * @return array
 */
function addlar_product_hero_chips( array $p, $limit = 4 ) {
	$spec = trim( addlar_product_field( $p, 'spec_string' ) );
	if ( '' === $spec ) {
		return array();
	}

	$parts = preg_split( '/\s*·\s*|\s*\|\s*/u', $spec );
	$parts = array_values( array_filter( array_map( 'trim', (array) $parts ), function ( $x ) {
		return '' !== $x;
	} ) );

	return array_slice( $parts, 0, $limit );
}

/**
 * Real, non-fabricated counts for the "Product at a Glance" stat band.
 * A stat is only included when its count is greater than zero, so a sparse
 * product simply gets fewer stats rather than a padded-out one.
 *
 * @param array $p Product data row.
 * @return array Each: array( count, label ).
 */
function addlar_product_glance_stats( array $p ) {
	$stats = array();

	$applications = addlar_product_line_list( addlar_product_field( $p, 'applications_text' ) );
	if ( $applications ) {
		$stats[] = array( 'count' => (string) count( $applications ), 'label' => __( 'Documented<br>applications', 'addlar' ) );
	}

	$rows = addlar_product_table_rows( addlar_product_field( $p, 'performance_rows_text' ) );
	if ( $rows ) {
		$stats[] = array( 'count' => (string) count( $rows ), 'label' => __( 'Performance<br>levels', 'addlar' ) );
	}

	$approvals = addlar_product_line_list( addlar_product_field( $p, 'approvals_text' ) );
	if ( $approvals ) {
		$stats[] = array( 'count' => (string) count( $approvals ), 'label' => __( 'OEM &amp; industry<br>approvals', 'addlar' ) );
	}

	$properties = addlar_product_table_rows( addlar_product_field( $p, 'properties_text' ) );
	if ( $properties ) {
		$stats[] = array( 'count' => (string) count( $properties ), 'label' => __( 'Lab-documented<br>properties', 'addlar' ) );
	}

	return array_slice( $stats, 0, 4 );
}

/**
 * Turn a product's approvals list into Addlar_Widget_TrustStrip's `items`
 * shape (`strong` + `text`), so the approvals section reuses the homepage's
 * certification strip rather than a lookalike. Approval lines are
 * transcribed as `Code (context)` — e.g. "MB 228.5 (E7)" — so the leading
 * code becomes the bold lead and the parenthesised context the label.
 *
 * @param array $p     Product data row.
 * @param int   $limit Maximum items (the strip is a single row; a product
 *                     with 24 approvals would wrap into an unreadable block).
 * @return array
 */
function addlar_product_approval_strip_items( array $p, $limit = 8 ) {
	$lines = addlar_product_line_list( addlar_product_field( $p, 'approvals_text' ) );
	$items = array();

	foreach ( array_slice( $lines, 0, $limit ) as $line ) {
		if ( preg_match( '/^(.*?)\s*\(([^)]+)\)\s*$/', $line, $m ) ) {
			$items[] = array( 'strong' => trim( $m[1] ), 'text' => trim( $m[2] ) );
		} else {
			$items[] = array( 'strong' => $line, 'text' => '' );
		}
	}

	return $items;
}

/**
 * A product's typical-properties rows re-flowed for Addlar_Widget_SpecTable.
 * products-data.php stores them as `Test | Method | Value | Unit`; the table
 * shows three columns, with unit appended to the value.
 *
 * @param array $p Product data row.
 * @return string Rows text, one row per line, `|`-separated.
 */
function addlar_product_properties_rows( array $p ) {
	$out = array();

	foreach ( addlar_product_table_rows( addlar_product_field( $p, 'properties_text' ) ) as $cells ) {
		$test   = isset( $cells[0] ) ? $cells[0] : '';
		$method = isset( $cells[1] ) ? $cells[1] : '';
		$value  = isset( $cells[2] ) ? $cells[2] : '';
		$unit   = isset( $cells[3] ) ? $cells[3] : '';

		if ( '' === $test ) {
			continue;
		}
		if ( '' !== $unit ) {
			$value = trim( $value . ' ' . $unit );
		}

		$out[] = $test . ' | ' . $method . ' | ' . $value;
	}

	return implode( "\n", $out );
}

/**
 * A product's formulation example as two-column table rows
 * ("Component: value" per line → `Component | value`).
 *
 * @param array $p Product data row.
 * @return string
 */
function addlar_product_formulation_rows( array $p ) {
	$out = array();

	foreach ( addlar_product_line_list( addlar_product_field( $p, 'formulation_text' ) ) as $line ) {
		if ( false === strpos( $line, ':' ) ) {
			continue;
		}
		list( $k, $v ) = explode( ':', $line, 2 );
		$out[] = trim( $k ) . ' | ' . trim( $v );
	}

	return implode( "\n", $out );
}

/**
 * code => permalink for every published addlar_product, keyed by the
 * product's bare code (`_addlar_code` meta, e.g. "7375", "KC420", "Z 2612" —
 * no "ADDLAR" prefix, matching how codes appear in the Finder's textarea
 * lists). Cached for the request since the Finder widget can render more
 * than once on a page.
 *
 * @return array
 */
function addlar_product_url_map() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}

	$map = array();

	$ids = get_posts( array(
		'post_type'      => 'addlar_product',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	) );

	foreach ( (array) $ids as $id ) {
		$code = trim( (string) get_post_meta( $id, '_addlar_code', true ) );
		if ( '' !== $code ) {
			$map[ $code ] = get_permalink( $id );
		}
	}

	return $map;
}

/**
 * Enrich addlar_parse_finder_rows()'s {category:{sub:[codes]}} output with
 * product URLs: a code with a real product page becomes ['code'=>…,
 * 'url'=>…], a code without one stays a plain string. theme.js's finder
 * renders the former as a link and the latter as plain text, so the pass-
 * through data itself (never the parser) carries the "this one's
 * documented" distinction.
 *
 * @param array $data Output of addlar_parse_finder_rows().
 * @return array
 */
function addlar_finder_enrich_with_urls( $data ) {
	$urls = addlar_product_url_map();
	if ( ! $urls ) {
		return $data;
	}

	foreach ( $data as $cat => $subs ) {
		foreach ( $subs as $sub => $codes ) {
			foreach ( $codes as $i => $code ) {
				if ( isset( $urls[ $code ] ) ) {
					$data[ $cat ][ $sub ][ $i ] = array( 'code' => $code, 'url' => $urls[ $code ] );
				}
			}
		}
	}

	return $data;
}

/**
 * The Finder catalogue as repeater rows (same shape
 * addlar_default_finder_categories() returns), with any addlar_product CPT
 * entry folded in that the hand-maintained catalogue doesn't already list
 * under its category/sub-category. This is what keeps the Finder and the
 * CPT from drifting apart again the way the original 4-code mismatch
 * happened: adding a product is enough — the Finder's default catalogue
 * picks it up next time the theme is (re)seeded.
 *
 * @return array
 */
function addlar_finder_catalogue_merged() {
	$rows = addlar_default_finder_categories();

	$ids = get_posts( array(
		'post_type'      => 'addlar_product',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	) );

	if ( ! $ids ) {
		return $rows;
	}

	// Index existing rows for quick "is this code already listed anywhere?" lookups.
	$known = array();
	foreach ( $rows as $row ) {
		$parsed = addlar_parse_finder_rows( array( $row ) );
		foreach ( $parsed as $subs ) {
			foreach ( $subs as $codes ) {
				foreach ( $codes as $code ) {
					$known[ $code ] = true;
				}
			}
		}
	}

	foreach ( $ids as $id ) {
		$code = trim( (string) get_post_meta( $id, '_addlar_code', true ) );
		if ( '' === $code || isset( $known[ $code ] ) ) {
			continue;
		}

		$terms = get_the_terms( $id, 'addlar_product_category' );
		$cat   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : __( 'Other', 'addlar' );
		$sub   = trim( (string) get_post_meta( $id, '_addlar_subcategory', true ) );
		if ( '' === $sub ) {
			$sub = $cat;
		}

		$found_row = false;
		foreach ( $rows as $ri => $row ) {
			if ( $row['name'] !== $cat ) {
				continue;
			}
			$found_row = true;

			$lines      = preg_split( '/\r\n|\r|\n/', $row['lines'] );
			$found_line = false;
			foreach ( $lines as $li => $line ) {
				if ( 0 === strpos( ltrim( $line ), $sub . ':' ) ) {
					$lines[ $li ] = rtrim( $line ) . ', ' . $code;
					$found_line   = true;
					break;
				}
			}
			if ( ! $found_line ) {
				$lines[] = $sub . ': ' . $code;
			}
			$rows[ $ri ]['lines'] = implode( "\n", $lines );
			break;
		}

		if ( ! $found_row ) {
			$rows[] = array( 'name' => $cat, 'lines' => $sub . ': ' . $code );
		}

		$known[ $code ] = true;
	}

	return $rows;
}
