<?php
/**
 * Product finder catalogue parsing.
 *
 * Kept out of the widget class so it can be unit-tested without bootstrapping
 * Elementor, and reused by the seeder.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'ADDLAR_TESTING' ) ) {
	exit;
}

/**
 * The hand-maintained Finder catalogue, as Elementor repeater rows
 * (`name` + `lines`, one `Sub-category: CODE, CODE` line per line).
 *
 * Lives here (not in Addlar_Widget_ProductFinder) so both the widget's
 * control default and addlar_finder_catalogue_merged() below read the same
 * data. Four codes that have a real, documented Product Data Sheet but were
 * missing from the original client catalogue export are folded in directly:
 * `7155` (Passenger Car — sibling of 7157/7158, same treat-rate/TBN cascade
 * shape), `KC420` (Neat Cutting — sibling of KC410/KC415/KC426), `Z 2612`
 * (Anti-wear & Friction Modifier — PDS text is a ZDDP for engine/hydraulic/
 * bearing oils, matches that sub-category's siblings), and `KC321`, which the
 * Phase 2 plan flagged as lower-confidence "Industrial" but whose PDS reads
 * "Premium Anti-wear Hydraulic Oil Additive Package" (Denison HF-0, DIN
 * 51524-2/3) — confirmed Hydraulic, filed alongside KC521/KC523.
 *
 * @return array
 */
function addlar_default_finder_categories() {
	return array(
		array(
			'name'  => 'Engine Oil Additive',
			'lines' => "Heavy Duty: 7750, 7889, 7730, 7883, 7732, 7706, 7616, 7511\nPassenger Car: 7465, 7395, 7392, 7376, 7375, 7157, 7158, 7155, 7152, 7135, 7125, 7116, 7107, 7009\nMotorcycle: 9312, 9342, 9295",
		),
		array(
			'name'  => 'Driveline',
			'lines' => "Automotive Gear: KC561, KC562, KC563\nATF: KC631\nManual Transmission: KC564\nOff-Road: 9630",
		),
		array(
			'name'  => 'Marine',
			'lines' => "Trunk Piston: 9100\nSystem Oil: 9200\nCylinder Oil: 9300",
		),
		array(
			'name'  => 'Industrial',
			'lines' => "Gear: KC561, KC562, KC563, KC565\nGrease: KC311\nHydraulic: KC521, KC523, KC321\nSlideway: KC566",
		),
		array(
			'name'  => 'Metal Working Fluid',
			'lines' => "Neat Cutting: KC410, KC415, KC415A, KC20, KC426, KC420\nSoluble Oil: KC710",
		),
		array(
			'name'  => 'Lubricant Component',
			'lines' => "Detergents: 2063, 2230, 2240, 2340, 2130\nDispersants: 2417, 2422, 2443, 2569\nAnti-wear & Friction Modifier: 2604, 2610, 2611, 2641, 2651, 5883, 2995, Z 2612\nAnti Oxidants: 2907, 2935\nPour Point Depressants: 224, 226\nViscosity Index Improvers: 2500, 8081, 8084\nSpeciality Component: KC720, KC721, KC810, KC820",
		),
		array(
			'name'  => 'Complementary',
			'lines' => "Brake Fluid: Custom blend\nCustomised Solutions: Made to spec",
		),
	);
}

/**
 * Turn finder repeater rows into { category: { sub-category: [codes] } }.
 *
 * Each row is a category name plus a block of lines in the form
 * `Sub-category: CODE, CODE, CODE`. Blank lines, lines without a colon, and
 * empty code lists are skipped so a half-finished edit never breaks the widget.
 *
 * @param array $rows Repeater rows with 'name' and 'lines' keys.
 * @return array
 */
function addlar_parse_finder_rows( $rows ) {
	$data = array();

	foreach ( (array) $rows as $row ) {
		$cat = isset( $row['name'] ) ? trim( (string) $row['name'] ) : '';
		if ( '' === $cat ) {
			continue;
		}

		$subs  = array();
		$lines = preg_split( '/\r\n|\r|\n/', isset( $row['lines'] ) ? (string) $row['lines'] : '' );

		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( '' === $line || false === strpos( $line, ':' ) ) {
				continue;
			}

			list( $sub, $codes ) = explode( ':', $line, 2 );

			$sub = trim( $sub );
			if ( '' === $sub ) {
				continue;
			}

			$list = array_values(
				array_filter(
					array_map( 'trim', explode( ',', $codes ) ),
					function ( $c ) {
						return '' !== $c;
					}
				)
			);

			if ( $list ) {
				$subs[ $sub ] = $list;
			}
		}

		if ( $subs ) {
			$data[ $cat ] = $subs;
		}
	}

	return $data;
}
