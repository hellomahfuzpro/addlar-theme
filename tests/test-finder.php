<?php
/**
 * Unit test for addlar_parse_finder_rows() — checks the parsed catalogue
 * matches the FINDER object from the approved static mockup.
 */

define( 'ADDLAR_TESTING', true );
require __DIR__ . '/../inc/finder-data.php';

$rows = array(
	array( 'name' => 'Engine Oil Additive', 'lines' => "Heavy Duty: 7750, 7889, 7730, 7883, 7732, 7706, 7616, 7511\nPassenger Car: 7465, 7395, 7392, 7157, 7158, 7152, 7135, 7125, 7116, 7107, 7009\nMotorcycle: 9312, 9342, 9295" ),
	array( 'name' => 'Driveline', 'lines' => "Automotive Gear: KC561, KC562, KC563\nATF: KC631\nManual Transmission: KC564\nOff-Road: 9630" ),
	array( 'name' => 'Marine', 'lines' => "Trunk Piston: 9100\nSystem Oil: 9200\nCylinder Oil: 9300" ),
	array( 'name' => 'Industrial', 'lines' => "Gear: KC561, KC562, KC563, KC565\nGrease: KC311\nHydraulic: KC521, KC523\nSlideway: KC566" ),
	array( 'name' => 'Metal Working Fluid', 'lines' => "Neat Cutting: KC410, KC415, KC415A, KC20, KC426\nSoluble Oil: KC710" ),
	array( 'name' => 'Lubricant Component', 'lines' => "Detergents: 2063, 2230, 2240, 2340, 2130\nDispersants: 2417, 2422, 2443, 2569\nAnti-wear & Friction Modifier: 2604, 2610, 2611, 2641, 2651, 5883, 2995\nAnti Oxidants: 2907, 2935\nPour Point Depressants: 224, 226\nViscosity Index Improvers: 2500, 8081, 8084\nSpeciality Component: KC720, KC721, KC810, KC820" ),
	array( 'name' => 'Complementary', 'lines' => "Brake Fluid: Custom blend\nCustomised Solutions: Made to spec" ),
	// Robustness cases that should NOT appear in the output:
	array( 'name' => '', 'lines' => "Ignored: 123" ),
	array( 'name' => 'Empty Category', 'lines' => "" ),
	array( 'name' => 'No Colon', 'lines' => "just some text" ),
);

$data = addlar_parse_finder_rows( $rows );

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
		$ok ? '' : sprintf( "     expected: %s\n     actual:   %s\n", wp_j( $expected ), wp_j( $actual ) )
	);
}
function wp_j( $v ) { return json_encode( $v ); }

// Structure
check( '7 categories parsed (3 malformed rows dropped)', count( $data ), 7 );
check( 'category order preserved', array_keys( $data )[0], 'Engine Oil Additive' );

// The exact assertion from the plan's verification section
check( 'Industrial -> Hydraulic', $data['Industrial']['Hydraulic'], array( 'KC521', 'KC523' ) );

// Counts that must match the mockup's FINDER object
check( 'Engine Oil -> Heavy Duty count', count( $data['Engine Oil Additive']['Heavy Duty'] ), 8 );
check( 'Engine Oil -> Passenger Car count', count( $data['Engine Oil Additive']['Passenger Car'] ), 11 );
check( 'Lubricant Component sub-categories', count( $data['Lubricant Component'] ), 7 );

// Sub-category names containing an ampersand must survive
check( 'ampersand in sub-category name', isset( $data['Lubricant Component']['Anti-wear & Friction Modifier'] ), true );

// Multi-word codes with spaces must not be split
check( 'multi-word code kept intact', $data['Complementary']['Brake Fluid'], array( 'Custom blend' ) );

// Malformed rows dropped
check( 'unnamed category dropped', isset( $data[''] ), false );
check( 'empty category dropped', isset( $data['Empty Category'] ), false );
check( 'colon-less category dropped', isset( $data['No Colon'] ), false );

// Totals must match the mockup's FINDER object exactly
// (counted from index.html: 7 categories / 25 sub-categories / 74 codes).
$total    = 0;
$subcount = 0;
foreach ( $data as $subs ) {
	$subcount += count( $subs );
	foreach ( $subs as $codes ) { $total += count( $codes ); }
}
check( 'total sub-categories', $subcount, 25 );
check( 'total product codes', $total, 74 );

printf( "\n%d passed, %d failed\n", $pass, $fail );
exit( $fail ? 1 : 0 );
