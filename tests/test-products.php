<?php
/**
 * Unit tests for the product data and the seed-time helpers that turn it
 * into Elementor widget settings:
 *  - addlar_default_finder_categories() carries the 4 previously-missing
 *    codes (7155, KC420, Z 2612, KC321) plus the 2 additional gaps found
 *    while reading the real PDS set (7375, 7376 were documented products
 *    missing from the Finder's Passenger Car list).
 *  - The seed-time helpers (spec cards, hero chips, glance stats, approval
 *    strip items, properties/formulation row re-flow) produce well-formed
 *    output for real products and degrade to empty — never to a fabricated
 *    placeholder — for products genuinely missing that data.
 *  - Every one of the 22 real products in products-data.php has internally
 *    consistent table data: a performance table's row column counts match
 *    its header column count (a transcription-error tripwire, not a content
 *    check — this cannot catch a wrong *value*, only a malformed *shape*).
 */

define( 'ADDLAR_TESTING', true );

// Minimal WP shims — this harness exercises the pure parsing/rendering
// functions only, never the WP-query-backed ones (addlar_product_url_map(),
// addlar_finder_catalogue_merged()'s CPT lookup), so escaping passthroughs
// are all that's needed.
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES ); }
function esc_attr( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES ); }
function esc_html__( $s, $d = null ) { return esc_html( $s ); }
function esc_attr__( $s, $d = null ) { return esc_attr( $s ); }
function __( $s, $d = null ) { return $s; }
function _n( $single, $plural, $number, $d = null ) { return 1 === (int) $number ? $single : $plural; }

require __DIR__ . '/../inc/finder-data.php';
require __DIR__ . '/../inc/icons.php';
require __DIR__ . '/../inc/products-render.php';
require __DIR__ . '/../inc/products-data.php';

$pass = 0;
$fail = 0;
function check( $label, $actual, $expected ) {
	global $pass, $fail;
	$ok = ( $actual === $expected );
	$ok ? $pass++ : $fail++;
	printf(
		"%s %s\n%s",
		$ok ? 'PASS' : 'FAIL',
		$label,
		$ok ? '' : sprintf( "     expected: %s\n     actual:   %s\n", json_encode( $expected ), json_encode( $actual ) )
	);
}
function check_true( $label, $actual ) {
	check( $label, $actual, true );
}

/* ---------------------------------------------------------- catalogue fix */

$data = addlar_parse_finder_rows( addlar_default_finder_categories() );

check_true( 'KC321 now under Industrial -> Hydraulic', in_array( 'KC321', $data['Industrial']['Hydraulic'], true ) );
check( 'Industrial -> Hydraulic still has KC521, KC523 (regression)', array_values( array_intersect( array( 'KC521', 'KC523' ), $data['Industrial']['Hydraulic'] ) ), array( 'KC521', 'KC523' ) );
check_true( 'KC420 now under Metal Working Fluid -> Neat Cutting', in_array( 'KC420', $data['Metal Working Fluid']['Neat Cutting'], true ) );
check_true( 'Z 2612 now under Lubricant Component -> Anti-wear & Friction Modifier', in_array( 'Z 2612', $data['Lubricant Component']['Anti-wear & Friction Modifier'], true ) );
check_true( '7155 now under Engine Oil Additive -> Passenger Car', in_array( '7155', $data['Engine Oil Additive']['Passenger Car'], true ) );
check_true( '7375 now under Engine Oil Additive -> Passenger Car', in_array( '7375', $data['Engine Oil Additive']['Passenger Car'], true ) );
check_true( '7376 now under Engine Oil Additive -> Passenger Car', in_array( '7376', $data['Engine Oil Additive']['Passenger Car'], true ) );

/* ------------------------------------------------ seed-time helper output */

$all = addlar_products_data();

// 7375: a rich product — spec string, performance table, viscosity note.
$p7375 = $all['7375'];
check_true( '7375 produces spec cards', count( addlar_product_spec_cards( $p7375 ) ) > 0 );
check_true( '7375 spec string splits into multiple hero chips', count( addlar_product_hero_chips( $p7375 ) ) > 1 );
check_true( '7375 has at least 2 glance stats', count( addlar_product_glance_stats( $p7375 ) ) >= 2 );
check_true( '7375 properties re-flow to 3-column rows', false !== strpos( addlar_product_properties_rows( $p7375 ), '|' ) );

// Properties re-flow must append the unit to the value, not drop it.
check_true(
	'properties re-flow keeps the unit with the value',
	false !== strpos( addlar_product_properties_rows( $p7375 ), '80 cSt' )
);

// KC420: no performance table and no approvals — helpers must return empty,
// never a fabricated placeholder row.
$pkc420 = $all['KC420'];
check( 'KC420 has no approval strip items', addlar_product_approval_strip_items( $pkc420 ), array() );
check( 'KC420 has no formulation rows', addlar_product_formulation_rows( $pkc420 ), '' );

// 7730: long approval list — parsed into strong/text pairs and capped so the
// single-row strip can't wrap into an unreadable block.
$p7730    = $all['7730'];
$approvals = addlar_product_approval_strip_items( $p7730 );
check_true( '7730 approvals parse into strip items', count( $approvals ) > 0 );
check_true( '7730 approval strip is capped at 8 items', count( $approvals ) <= 8 );
check( "7730 first approval's code is split from its context", $approvals[0]['strong'], 'ACEA A3/B4' );
check( "7730 first approval's context becomes the label", $approvals[0]['text'], 'E7' );

// 9200: documented as a formulation recipe rather than a treat-rate table.
check_true( '9200 produces formulation rows', false !== strpos( addlar_product_formulation_rows( $all['9200'] ), '|' ) );

/* ------------------------------------------------- products-data.php shape */

$products = addlar_products_data();
check( '22 products in products-data.php', count( $products ), 22 );

foreach ( $products as $code => $p ) {
	$headers = isset( $p['performance_headers'] ) ? array_filter( array_map( 'trim', explode( '|', $p['performance_headers'] ) ), function ( $h ) { return '' !== $h; } ) : array();
	$rows    = isset( $p['performance_rows_text'] ) ? addlar_product_table_rows( $p['performance_rows_text'] ) : array();

	if ( ! $headers || ! $rows ) {
		continue; // No performance table for this product — valid (e.g. KC420, Z 2612).
	}

	foreach ( $rows as $i => $cells ) {
		check_true( "{$code}: performance row " . ( $i + 1 ) . ' column count <= header count', count( $cells ) <= count( $headers ) );
	}
}

foreach ( $products as $code => $p ) {
	check_true( "{$code}: has a title", ! empty( $p['title'] ) );
	check_true( "{$code}: has a category", ! empty( $p['category'] ) );
	check_true( "{$code}: has a doc_code", ! empty( $p['doc_code'] ) );
}

/* ------- Every product's seeded spec cards are renderable, for real ------ *
 * Now that the helpers take a plain data array rather than a post ID, this
 * exercises the real per-product code path for all 22 products instead of
 * scanning source text: an icon key that isn't registered would render as a
 * silently-missing SVG in production, so it fails here instead. */
$valid_icons = array_keys( addlar_icon_choices() );

foreach ( $products as $code => $p ) {
	$cards = addlar_product_spec_cards( $p );
	check_true( "{$code}: produces at least one spec card", count( $cards ) > 0 );
	check_true( "{$code}: spec cards capped at 3", count( $cards ) <= 3 );

	foreach ( $cards as $i => $card ) {
		check_true( "{$code}: card " . ( $i + 1 ) . " icon '{$card['icon']}' is a registered icon", in_array( $card['icon'], $valid_icons, true ) );
		check_true( "{$code}: card " . ( $i + 1 ) . ' has a title', '' !== trim( $card['title'] ) );
	}
}

printf( "\n%d passed, %d failed\n", $pass, $fail );
exit( $fail ? 1 : 0 );
