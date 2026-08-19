<?php
/**
 * Product data parsing + HTML-fragment rendering.
 *
 * Elementor Pro's "Post Custom Field" Dynamic Tag only reads a single scalar
 * meta value — it cannot iterate a repeater the way ACF Pro would. Rather
 * than add ACF Pro as a new paid dependency just for two tables, the admin
 * metabox (inc/products-metabox.php) stores the tables as line-based,
 * delimiter-separated text (the same convention addlar_parse_finder_rows()
 * already uses), and the functions below turn that text into ready-to-display
 * HTML at save time. The Theme Builder template then binds an HTML widget to
 * the pre-rendered meta key via a plain Dynamic Tag — no repeater needed.
 *
 * The 22 real PDS documents this schema was built from turned out far more
 * heterogeneous than a single fixed table shape: some products have no
 * performance table at all (raw components, dosed by a flat % range, e.g.
 * KC420/Z 2612), some use 2 columns (grease/hydraulic/gear — treat-rate only,
 * no TBN), one uses a formulation recipe instead of a table (9200), and
 * several rows carry long OEM-approval lists that render far better as a
 * flat chip list than crammed into a table cell. Every renderer below is
 * written to be optional/blank-tolerant for exactly that reason — a missing
 * section renders nothing, and a blank table cell renders "—", never a
 * fabricated value.
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
 * @param string $text Raw textarea value.
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
 * Split a plain "one item per line" textarea into a trimmed, blank-filtered
 * list. Used for applications and approvals, which are flat lists, not
 * tables.
 *
 * @param string $text Raw textarea value.
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
 * Render the performance/treat-rate table as HTML, or '' if there is
 * nothing to show — several documented products (raw components dosed by a
 * flat percentage, not a graded performance level) legitimately have no
 * table here at all.
 *
 * @param string $headers_line Pipe-delimited column headers, e.g. "Level|Treat Rate %|TBN".
 * @param string $rows_text    One row per line, same column count as the headers.
 * @param string $note         Optional label above the table, e.g. "Multigrade" or "Automotive".
 * @return string
 */
function addlar_render_performance_table_html( $headers_line, $rows_text, $note = '' ) {
	$headers = array_filter( array_map( 'trim', explode( '|', (string) $headers_line ) ), function ( $h ) {
		return '' !== $h;
	} );
	$rows = addlar_product_table_rows( $rows_text );

	if ( ! $headers || ! $rows ) {
		return '';
	}

	$out = '';
	if ( '' !== trim( (string) $note ) ) {
		$out .= '<p class="spec-table-note">' . esc_html( $note ) . '</p>';
	}

	$out .= '<table class="spec-table spec-table-performance"><thead><tr>';
	foreach ( $headers as $h ) {
		$out .= '<th>' . esc_html( $h ) . '</th>';
	}
	$out .= '</tr></thead><tbody>';

	foreach ( $rows as $cells ) {
		$out .= '<tr>';
		foreach ( $headers as $i => $h ) {
			$cell = isset( $cells[ $i ] ) ? trim( $cells[ $i ] ) : '';
			$out .= '<td>' . ( '' !== $cell ? esc_html( $cell ) : '&#8212;' ) . '</td>';
		}
		$out .= '</tr>';
	}
	$out .= '</tbody></table>';

	return $out;
}

/**
 * Render the typical-properties table: Test | Method | Value | Unit rows.
 * Not every product reports the same set of tests (e.g. an "ashless"
 * additive has no Calcium/Phosphorus/Zinc/Sulphated Ash rows to report), so
 * the row set itself is whatever the metabox textarea contains — nothing is
 * assumed. A row whose Value cell is blank in the source PDS renders "—",
 * matching the document rather than inventing a number.
 *
 * @param string $text One row per line: Test | Method | Value | Unit.
 * @return string
 */
function addlar_render_properties_table_html( $text ) {
	$rows = addlar_product_table_rows( $text );
	if ( ! $rows ) {
		return '';
	}

	$out = '<table class="spec-table spec-table-properties"><thead><tr><th>' . esc_html__( 'Test', 'addlar' ) . '</th><th>' . esc_html__( 'Method', 'addlar' ) . '</th><th>' . esc_html__( 'Value', 'addlar' ) . '</th></tr></thead><tbody>';

	foreach ( $rows as $cells ) {
		$test   = isset( $cells[0] ) ? trim( $cells[0] ) : '';
		$method = isset( $cells[1] ) ? trim( $cells[1] ) : '';
		$value  = isset( $cells[2] ) ? trim( $cells[2] ) : '';
		$unit   = isset( $cells[3] ) ? trim( $cells[3] ) : '';

		if ( '' === $test ) {
			continue;
		}

		$value_cell = '' !== $value ? esc_html( $value . ( '' !== $unit ? ' ' . $unit : '' ) ) : '&#8212;';

		$out .= '<tr><td>' . esc_html( $test ) . '</td><td>' . ( '' !== $method ? esc_html( $method ) : '&#8212;' ) . '</td><td>' . $value_cell . '</td></tr>';
	}
	$out .= '</tbody></table>';

	return $out;
}

/**
 * Render a flat list (applications, approvals) as chip spans. Returns '' if
 * the list is empty — most products have applications only as an implicit
 * reading of their spec string, and not every product carries an OEM
 * approvals list (raw components like Z 2612 are dosed by the formulator,
 * not independently approval-tested).
 *
 * @param string $text  One item per line.
 * @param string $class Extra class on the wrapping <ul>, e.g. "chips-approvals".
 * @return string
 */
function addlar_render_chip_list_html( $text, $class = '' ) {
	$items = addlar_product_line_list( $text );
	if ( ! $items ) {
		return '';
	}

	$out = '<ul class="chip-list ' . esc_attr( $class ) . '">';
	foreach ( $items as $item ) {
		$out .= '<li class="chip">' . esc_html( $item ) . '</li>';
	}
	$out .= '</ul>';

	return $out;
}

/**
 * Render a formulation recipe (ADDLAR 9200-style: base oil + additive +
 * pour-point depressant percentages for one finished-oil example) as a
 * definition list. This is a distinct content shape from the performance
 * table — a worked example, not a graded treat-rate — so it gets its own
 * renderer rather than being force-fit into the table schema.
 *
 * @param string $label Heading above the list, e.g. "SAE 30 (TBN 5) Formulation".
 * @param string $text  One "Component: value" per line.
 * @return string
 */
function addlar_render_formulation_html( $label, $text ) {
	$items = addlar_product_line_list( $text );
	if ( ! $items ) {
		return '';
	}

	$out = '';
	if ( '' !== trim( (string) $label ) ) {
		$out .= '<p class="spec-table-note">' . esc_html( $label ) . '</p>';
	}

	$out .= '<dl class="formulation-list">';
	foreach ( $items as $item ) {
		if ( false === strpos( $item, ':' ) ) {
			continue;
		}
		list( $k, $v ) = explode( ':', $item, 2 );
		$out .= '<div class="formulation-row"><dt>' . esc_html( trim( $k ) ) . '</dt><dd>' . esc_html( trim( $v ) ) . '</dd></div>';
	}
	$out .= '</dl>';

	return $out;
}

/**
 * Compute and save all five pre-rendered HTML fragments for one product
 * post from its raw meta fields. Shared by the admin metabox's save_post
 * hook (products-metabox.php) and the seeder (demo-import.php) so both
 * paths produce identical output from the same raw text — one rendering
 * pipeline, not two.
 *
 * @param int $post_id addlar_product post ID. Raw meta must already be saved.
 */
function addlar_render_all_product_fragments( $post_id ) {
	$get = function ( $key ) use ( $post_id ) {
		return get_post_meta( $post_id, $key, true );
	};

	update_post_meta( $post_id, '_addlar_performance_table_html', addlar_render_performance_table_html(
		$get( '_addlar_performance_headers' ),
		$get( '_addlar_performance_rows_text' ),
		$get( '_addlar_performance_note' )
	) );

	update_post_meta( $post_id, '_addlar_properties_table_html', addlar_render_properties_table_html( $get( '_addlar_properties_text' ) ) );

	update_post_meta( $post_id, '_addlar_applications_html', addlar_render_chip_list_html( $get( '_addlar_applications_text' ), 'chips-applications' ) );

	update_post_meta( $post_id, '_addlar_approvals_html', addlar_render_chip_list_html( $get( '_addlar_approvals_text' ), 'chips-approvals' ) );

	update_post_meta( $post_id, '_addlar_formulation_html', addlar_render_formulation_html(
		$get( '_addlar_formulation_label' ),
		$get( '_addlar_formulation_text' )
	) );
}

/**
 * "Key Performance Benefits" bullets for the product page's checklist box
 * (see widgets/class-product-benefits.php) — modelled on the reference
 * competitor pages the client asked to match, which lead with a short,
 * scannable benefits list above the technical tables.
 *
 * Every bullet here is a restatement of a fact already present in this
 * product's own transcribed data (a real application line, the real spec
 * string, a real count of approvals/performance levels) — never an invented
 * performance claim. Products vary in which facts they have (a raw
 * component like Z 2612 has applications but no performance table; 9342 has
 * neither approvals nor applications), so this degrades gracefully rather
 * than assuming every product has the same shape of content.
 *
 * @param int $post_id addlar_product post ID.
 * @param int $limit   Maximum bullets to return.
 * @return array
 */
function addlar_product_benefit_bullets( $post_id, $limit = 6 ) {
	$bullets = array();

	$applications = addlar_product_line_list( get_post_meta( $post_id, '_addlar_applications_text', true ) );
	foreach ( array_slice( $applications, 0, 4 ) as $app ) {
		$bullets[] = sprintf(
			/* translators: %s: an application/use-case line from the product's own PDS */
			__( 'Formulated for %s', 'addlar' ),
			$app
		);
	}

	$spec = trim( (string) get_post_meta( $post_id, '_addlar_spec_string', true ) );
	if ( $spec ) {
		/* translators: %s: the product's real specification string */
		$bullets[] = sprintf( __( 'Meets %s', 'addlar' ), $spec );
	}

	$approvals = addlar_product_line_list( get_post_meta( $post_id, '_addlar_approvals_text', true ) );
	if ( $approvals ) {
		$bullets[] = sprintf(
			/* translators: %d: number of real OEM/industry approvals listed in this product's PDS */
			_n( 'Backed by %d OEM & industry approval', 'Backed by %d OEM & industry approvals', count( $approvals ), 'addlar' ),
			count( $approvals )
		);
	}

	$rows = addlar_product_table_rows( get_post_meta( $post_id, '_addlar_performance_rows_text', true ) );
	if ( $rows ) {
		$bullets[] = sprintf(
			/* translators: %d: number of real graded performance levels in this product's PDS */
			_n( 'Available across %d performance level', 'Available across %d performance levels', count( $rows ), 'addlar' ),
			count( $rows )
		);
	}

	$viscosity = trim( (string) get_post_meta( $post_id, '_addlar_viscosity_note', true ) );
	if ( $viscosity ) {
		$bullets[] = __( 'Formulated across multiple viscosity grades', 'addlar' );
	}

	return array_slice( $bullets, 0, $limit );
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
 * through data itself (never the parser, never the metabox convention)
 * carries the "this one's documented" distinction.
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
 * happened: from here forward, adding a product via the CPT admin screen is
 * enough — the Finder's default catalogue picks it up next time the theme
 * is (re)seeded, with no parallel textarea edit required.
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
