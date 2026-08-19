<?php
/**
 * Unit tests for the Phase 2 product data:
 *  - addlar_default_finder_categories() carries the 4 previously-missing
 *    codes (7155, KC420, Z 2612, KC321) plus the 2 additional gaps found
 *    while reading the real PDS set (7375, 7376 were documented products
 *    missing from the Finder's Passenger Car list).
 *  - addlar_render_performance_table_html() / addlar_render_properties_table_html()
 *    round-trip correctly, including the blank-cell "—" behaviour.
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

/* --------------------------------------------------------- table renderers */

$perf = addlar_render_performance_table_html( 'Level | Treat Rate % | TBN', "SN | 6.75% | 6.7\nSM | 6.00% | ", 'Multigrade' );
check_true( 'performance table renders a <table>', false !== strpos( $perf, '<table class="spec-table spec-table-performance">' ) );
check_true( 'performance table blank cell renders em-dash', false !== strpos( $perf, '<td>&#8212;</td>' ) );
check_true( 'performance table note renders', false !== strpos( $perf, 'Multigrade' ) );
check( 'empty performance table returns empty string', addlar_render_performance_table_html( '', '', '' ), '' );

$props = addlar_render_properties_table_html( "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | " );
check_true( 'properties table renders a <table>', false !== strpos( $props, '<table class="spec-table spec-table-properties">' ) );
check_true( 'properties table blank value renders em-dash', false !== strpos( $props, '<td>&#8212;</td><td>&#8212;</td>' ) );

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

/* ------------- Key Performance Benefits icon keys stay valid ------------- *
 * addlar_product_benefit_bullets() (inc/products-render.php) hardcodes a
 * fixed set of icon keys ('gear', 'shield', 'globe', 'layers', 'viscosity')
 * rather than deriving them from data, so a static check of the source
 * against addlar_icon_choices() is a complete, not sampled, verification —
 * no WP post-meta mocking needed to exercise the real per-product path. */
$render_src = file_get_contents( __DIR__ . '/../inc/products-render.php' );
preg_match_all( "/'icon'\s*=>\s*'([a-z_]+)'/", $render_src, $icon_matches );
$used_icons  = array_unique( $icon_matches[1] );
$valid_icons = array_keys( addlar_icon_choices() );

check_true( 'benefit-bullet icon keys found in source', count( $used_icons ) > 0 );
foreach ( $used_icons as $icon_key ) {
	check_true( "icon key '{$icon_key}' used in products-render.php is registered in addlar_icon_choices()", in_array( $icon_key, $valid_icons, true ) );
}

printf( "\n%d passed, %d failed\n", $pass, $fail );
exit( $fail ? 1 : 0 );
