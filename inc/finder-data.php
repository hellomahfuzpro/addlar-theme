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
