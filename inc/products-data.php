<?php
/**
 * The 22 real, PDS-documented ADDLAR products.
 *
 * Transcribed directly from the client's own Product Data Sheets (Drive:
 * `Product Information/Product Data Sheet/`), read in full — not sourced
 * from the marketing catalogue or inferred. Kept as a plain data array,
 * separate from the seeding mechanics in demo-import.php, the same split
 * already used for addlar_why_rows() / addlar_product_cards() elsewhere in
 * this theme.
 *
 * A deliberate, explicit gap: most `properties_text` rows below carry a
 * blank Method column. Values (Test, Value) are transcribed directly from
 * each PDS; the ASTM/ISO method code for most rows was not re-verified
 * character-for-character against the source PDF text captured during the
 * read pass, and this theme's standing rule is "flag, don't guess" on lab
 * data — so Method renders as "—" rather than risk printing a wrong standard
 * number next to a client's real spec. The handful of Method values that
 * are filled in (e.g. KC311, KC420) were explicitly confirmed against the
 * source text during the read. Whoever finishes wiring these up in the
 * admin metabox can fill the rest in per-row against the PDF without
 * touching any other field.
 *
 * Column conventions (matching inc/products-metabox.php's textareas):
 * - performance_rows_text: one row per line, `|`-separated, matching performance_headers.
 * - properties_text: one row per line, `Test | Method | Value` (Method left blank — see above).
 * - applications_text / approvals_text: one item per line.
 * - formulation_text: one `Component: value` per line.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'ADDLAR_TESTING' ) ) {
	exit;
}

/**
 * @return array Indexed by bare product code.
 */
function addlar_products_data() {
	return array(

		'7009' => array(
			'title'        => 'ADDLAR 7009',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/V1.2/7009',
			'spec_string'  => 'API SL/CF-4 to API SB/CB',
			'description'  => 'PCMO & HDDEO engine oil additive package spanning a full API cascade from SL/CF-4 down to SB/CB, formulated in both multigrade and monograde treat-rate regimes.',
			'performance_note'      => 'Multigrade',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SL/CF-4 + 2.3% Booster | 4.80% | 7.1\nSF/CF | 3.75% | 6.1\nSF/CD | 3.75% | 6.1\nSF/CC | 3.75% | 6.1\nSE/CD & SD/CC | 3.23% | 5.2\nSC/CC | 2.43% | 3.9\nSB/CB | 1.92% | 3.1",
			'formulation_label'     => 'Monograde',
			'formulation_text'      => "SE/CD & SD/CC: 2.93% treat rate, 4.7 TBN\nSC/CC: 2.21% treat rate, 3.6 TBN\nSB/CB: 1.75% treat rate, 2.8 TBN",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 160 cSt\nDensity @ 15°C | | \nFlash Point (COC) | | \nBase Number | | 160 mgKOH/g\nNitrogen | | 0.3 %mass\nCalcium | | 5.6 %mass\nPhosphorus | | 1.3 %mass\nZinc | | 1.5 %mass\nSulphated Ash | | 22 %mass",
			'viscosity_note' => "Gasoline: 10W-30, 10W-40, 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 50\nDiesel: 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 40, 50",
		),

		'7155' => array(
			'title'        => 'ADDLAR 7155',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/7155/V1.2',
			'spec_string'  => 'API SJ/CF-4 to API SB/CB',
			'description'  => 'Economic-grade multifunctional engine oil additive package for mid-tier gasoline and diesel engine oils, formulated for economical treat rates across an 11-level API cascade.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "API SJ/CF-4 | 5.60% | 10.1\nAPI SG/CF-4 | 5.50% | 10.0\nAPI SJ/CF | 4.50% | 8.1\nAPI SG/CF | 4.40% | 8.0\nAPI SF/CF | 3.25% | 5.9\nAPI SF/CD | 3.25% | 5.9\nAPI CF (M) | 3.05% | 5.5\nAPI SB/CD | 2.15% | 3.9\nAPI CD (M) | 2.10% | 3.8\nAPI SC/CC | 2.00% | 3.6\nAPI SB/CB | 1.35% | 2.4",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nDensity @ 15°C | | 1045 kg/m3\nFlash Point (COC) | | 200 °C\nBase Number | | 180 mgKOH/g\nNitrogen | | 0.4 %mass\nCalcium | | 7.0 %mass\nPhosphorus | | 1.5 %mass\nZinc | | 1.65 %mass\nSulphated Ash | | 25 %mass",
			'viscosity_note' => "Gasoline: 10W-30, 10W-40, 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 50\nDiesel: 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 40, 50",
		),

		'7157' => array(
			'title'        => 'ADDLAR 7157',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/V1.3/7157',
			'spec_string'  => 'API SL/CF-4 · CF · ACEA A3/B3',
			'description'  => 'Passenger car engine oil additive package built for the ACEA A3/B3 and PSA/VW/MB OEM tier as well as a full API SL-to-SB cascade beneath it.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "PSAB71 2300 | 9.50% | 13.8\nVW501.01/505.00 | 9.50% | 13.8\nMB229.1 | 8.00% | 11.6\nACEA A3/B3 | 7.30% | 10.6\nSL/CF-4 | 5.90% | 8.6\nSL/CF | 5.70% | 8.3\nSJ/CF-4 | 5.40% | 7.8\nSJ/CF | 4.60% | 6.7\nSG/CF | 4.20% | 6.1\nSG/CD | 3.10% | 4.5\nSE/CD | 2.70% | 3.9\nSC/CC | 1.90% | 2.8\nSB/CB | 1.60% | 2.3",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 75 cSt\nDensity @ 15°C | | 1016 kg/m3\nFlash Point (COC) | | 206 °C\nBase Number | | 145 mgKOH/g\nNitrogen | | 0.6 %mass\nCalcium | | 4.8 %mass\nPhosphorus | | 1.35 %mass\nZinc | | 1.61 %mass\nSulphated Ash | | 18 %mass",
			'viscosity_note' => "For ACEA A3/B3: 0W-30, 0W-40, 5W-30, 5W-40, 10W-40\nGasoline: 10W-30, 10W-40, 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 50\nDiesel: 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 40, 50",
		),

		'7158' => array(
			'title'        => 'ADDLAR 7158',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/V1.1/7158',
			'spec_string'  => 'API SL/CF-4 · CF · ACEA A3/B3 · JASO MA2/MB',
			'description'  => 'Advanced-cascade engine oil additive package extending ADDLAR 7157’s ACEA A3/B3 and API tier down through the JASO MA2/MB motorcycle-oil standards, for both passenger car and four-stroke motorcycle applications.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "PSAB71 2300 | 9.50% | 13.8\nVW501.01/505.00 | 9.50% | 13.8\nMB229.1 | 8.00% | 11.6\nACEA A3/B3 | 7.30% | 10.6\nSL/CF-4 | 5.90% | 8.6\nSL/CF | 5.70% | 8.3\nSJ/CF-4 | 5.40% | 7.8\nSJ/CF | 4.60% | 6.7\nSG/CF | 4.20% | 6.1\nSG/CD | 3.10% | 4.5\nSE/CD | 2.70% | 3.9\nSC/CC | 1.90% | 2.8\nSB/CB | 1.60% | 2.3\nJASO MA2 | 6.50% | 5.7\nJASO MB | 6.50% + 0.3% RCH5830 Booster | 5.9",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 70 cSt\nDensity @ 15°C | | 1016 kg/m3\nFlash Point (COC) | | 206 °C\nBase Number | | 145 mgKOH/g\nNitrogen | | 0.6 %mass\nCalcium | | 4.85 %mass\nPhosphorus | | 1.35 %mass\nZinc | | 1.61 %mass\nMolybdenum | | 0.04 %mass\nSulphated Ash | | 18 %mass",
			'viscosity_note' => "For ACEA A3/B3: 0W-30, 0W-40, 5W-30, 5W-40, 10W-40\nGasoline: 10W-30, 10W-40, 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 50\nDiesel: 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 40, 50",
		),

		'7375' => array(
			'title'        => 'ADDLAR 7375',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/V1.1/7375',
			'spec_string'  => 'API SN/CF, SL to SJ · ACEA C3/C4, A3/B4, A5/B5 · ILSAC GF-5 · JASO MA2',
			'description'  => 'High-performance mid-SAPS engine oil additive package meeting the full modern API SN/ILSAC GF-5/ACEA C3-C4-A3-B4-A5-B5/JASO MA2 spread, ADDLAR’s flagship passenger car package.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SN | 6.75% | 6.7\nSM | 6.00% | 5.9\nSL | 5.35% | 5.3\nSJ | 5.00% | 4.9",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 80 cSt\nDensity @ 15°C | | 975 kg/m3\nFlash Point (COC) | | 190 °C\nBase Number | | 105 mgKOH/g\nNitrogen | | 1.0 %mass\nCalcium | | 3.2 %mass\nPhosphorus | | 1.11 %mass\nZinc | | 1.31 %mass\nMolybdenum | | 0.06 %mass\nSulphated Ash | | 11 %mass",
			'viscosity_note' => "5W-30, 5W-40, 10W-30, 10W-40, 15W-40, 20W-40, 20W-50, 20W, 30W, 30, 40, 50",
		),

		'7376' => array(
			'title'        => 'ADDLAR 7376',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/7376/V1.1',
			'spec_string'  => 'API SN/CF to SL/CF-4 · ILSAC GF-5 · ACEA C3/C4, A3/B4 · JASO MA2',
			'description'  => 'High-performance mid-SAPS engine oil additive package, sibling to ADDLAR 7375, meeting a wide OEM approval list at the SN level (ACEA C3/C4, MB, VW, GM Dexos2, BMW LL-04, Porsche, Chrysler, ILSAC GF-5, JASO MA2).',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SN | 8.0% | 10.0\nSM | 7.2% | 9.0\nSL/CF-4 | 6.0% | 7.5",
			'approvals_text' => "ACEA C3/C4 (SN)\nMB 229.51 / 229.31 (SN)\nVW 504.00 / 507.00 (SN)\nGM Dexos2 (SN)\nBMW LL-04 (SN)\nPorsche C30 (SN)\nChrysler MS-11106 (SN)\nILSAC GF-5 (SN)\nJASO MA2 (SN)\nACEA A3/B4 (SM)",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 80 cSt\nDensity @ 15°C | | 987 kg/m3\nFlash Point (COC) | | 205 °C\nBase Number | | 125 mgKOH/g\nNitrogen | | 1.15 %mass\nCalcium | | 3.85 %mass\nPhosphorus | | 0.94 %mass\nZinc | | 1.06 %mass\nMolybdenum | | 0.06 %mass\nSulphated Ash | | 12 %mass",
			'viscosity_note' => "5W-30, 5W-40, 10W-30, 10W-40, 15W-40, 20W-40, 20W-50, 20W, 30W, 30, 40, 50",
		),

		'7395' => array(
			'title'        => 'ADDLAR 7395',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/V2.1/7395',
			'spec_string'  => 'API SN/CF, SM · ILSAC GF-5 · ACEA C3/C5, A3/B4 · JASO MA2',
			'description'  => 'High-performance mid-SAPS engine oil additive package built for the low-viscosity ACEA C3/C5 tier alongside the same wide OEM approval list as ADDLAR 7376.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SN | 7.70% | 6.7\nSM | 7.10% | 6.2",
			'approvals_text' => "ACEA C3/C5 (SN)\nMB 229.51 / 229.31 (SN)\nVW 504.00 / 507.00 (SN)\nGM Dexos2 (SN)\nBMW LL-04 (SN)\nPorsche C30 (SN)\nChrysler MS-11106 (SN)\nILSAC GF-5 (SN)\nJASO MA2 (SN)\nACEA A3/B4 (SM)",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 115 cSt\nDensity @ 15°C | | 980 kg/m3\nFlash Point (COC) | | 205 °C\nBase Number | | 90 mgKOH/g\nNitrogen | | 0.93 %mass\nCalcium | | 2.60 %mass\nPhosphorus | | 0.93 %mass\nZinc | | 1.05 %mass\nMolybdenum | | 0.05 %mass\nSulphated Ash | | 10.0 %mass",
			'viscosity_note' => "5W-30, 5W-40, 10W-30, 10W-40, 15W-40, 20W-40, 20W-50, 20W, 30W, 30, 40, 50",
		),

		'7511' => array(
			'title'        => 'ADDLAR 7511',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Heavy Duty',
			'doc_code'     => 'RCH/V2.1/7511',
			'spec_string'  => 'API SF/CF to API SB/CB',
			'description'  => 'Universal engine oil additive package spanning a full API cascade in both multigrade and monograde treat-rate regimes, from SF/CF down to SB/CB.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SF/CF | 3.50% | 6.30\nCF (Monograde) | 3.50% | 6.30\nSF/CD | 3.40% | 6.10\nSF/CC | 3.50% | 6.10\nSE/CD | 2.75% | 4.95\nSD/CD | 2.50% | 4.50\nCD (Monograde) | 2.45% | 4.40\nSD/CC | 2.40% | 4.30\nSC/CC | 2.05% | 3.70\nSB/CC | 1.65% | 3.00",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 55 cSt\nDensity @ 15°C | | 1042 kg/m3\nFlash Point (COC) | | 206 °C\nBase Number | | 180 mgKOH/g\nNitrogen | | 0.25 %mass\nCalcium | | 6.0 %mass\nPhosphorus | | 1.35 %mass\nZinc | | 1.61 %mass",
			'viscosity_note' => "Monograde: 30, 40, 50\nMultigrade: 10W-30, 10W-40, 15W-40, 15W-50, 20W-50",
		),

		'7706' => array(
			'title'        => 'ADDLAR 7706',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Heavy Duty',
			'doc_code'     => 'RCH/V2.1/7706',
			'spec_string'  => 'ACEA E4/E5 · API CI-4 to CF-4/SJ',
			'description'  => 'Heavy-duty diesel engine oil additive package meeting the ACEA E4/E5 and API CI-4 tiers, with a wide OEM approval list at the top grade.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "ACEA E4/E5 | 8.50% | 9.2\nAPI CH-4 | 7.80% | 8.4\nCF-4/SL | 6.00% | 6.5\nCF-4/SJ | 5.10% | 5.5",
			'approvals_text' => "API CI-4 (E4/E5)\nCaterpillar ECF-1-a (E4/E5)\nDetroit DFS 93K215 (E4/E5)\nMTU Cat 1 (E4/E5)\nVolvo VDS-2 (E4/E5)\nMAN 270 (E4/E5)\nMAN 271 (E4/E5)\nVolvo VDS-2 (CH-4)",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 85 cSt\nDensity @ 15°C | | 991 kg/m3\nFlash Point (COC) | | 208 °C\nBase Number | | 108 mgKOH/g\nNitrogen | | 0.55 %mass\nCalcium | | 3.70 %mass\nPhosphorus | | 1.35 %mass\nZinc | | 1.55 %mass",
			'viscosity_note' => "For CI-4, CH-4, CF-4/SL & CF-4/SJ: 5W-30, 5W-40, 10W-30, 10W-40, 15W-40, 20W-50, 40, 50\nFor ACEA E4/E5: 5W-30, 10W-30, 10W-40, 15W-40, 40, 50",
		),

		'7730' => array(
			'title'        => 'ADDLAR 7730',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Heavy Duty',
			'doc_code'     => 'RCH/V2.1/7730',
			'spec_string'  => 'ACEA E7 · A3/B4, E5, E2, A2 · API CI-4/SL, CH-4/SJ, CG-4/SJ',
			'description'  => 'Heavy-duty diesel engine oil additive package with an extensive OEM approval spread at the ACEA E7 top grade, spanning MB, MAN, Volvo, MTU, Mack, Cummins, Caterpillar, JASO and Renault approvals.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "ACEA E7 | 11.70% | 11.10\nCH-4/SJ | 10.70% | 10.20\nCG-4/SJ | 9.10% | 8.60",
			'approvals_text' => "ACEA A3/B4 (E7)\nAPI CI-4/SL (E7)\nMB 228.5 (E7)\nMB 228.3 (E7)\nMAN 3277 (E7)\nMAN 270 (E7)\nMAN 271 (E7)\nVolvo VDS-3 (E7)\nMTU Cat 1 (E7)\nDetroit DFS 93K215 (E7)\nMack EO-N (E7)\nGlobal DHD-1 (E7)\nJASO DH-1 (E7)\nCaterpillar ECF-2 (E7)\nCummins 20078 (E7)\nCummins 20077 (E7)\nCummins 20076 (E7)\nACEA E5 (CH-4/SJ)\nRenault RLD-2 (CH-4/SJ)\nMTU Type 3 (CH-4/SJ)\nDDC DFS 93214 (CH-4/SJ)\nACEA E2 (CG-4/SJ)\nACEA A2 (CG-4/SJ)\nMB 228.0 / 228.1 / 227.0 / 227.1 (CG-4/SJ)",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 80 cSt\nDensity @ 15°C | | 970 kg/m3\nFlash Point (COC) | | 208 °C\nBase Number | | 92 mgKOH/g\nNitrogen | | 0.54 %mass\nCalcium | | 3.0 %mass\nPhosphorus | | 0.90 %mass\nZinc | | 1.01 %mass\nSulphated Ash | | 11.5 %mass",
			'viscosity_note' => "For CI-4, CH-4, CG-4/SL: 0W-50, 5W-40, 10W-30, 15W-40\nFor ACEA E7/E5: 10W-40, 5W-30",
		),

		'7750' => array(
			'title'        => 'ADDLAR 7750',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Heavy Duty',
			'doc_code'     => 'RCH/7750/V2.1',
			'spec_string'  => 'ACEA E11/E9 · API CK-4 · JASO DH-2',
			'description'  => 'Latest-generation heavy-duty diesel engine oil additive package meeting API CK-4 and ACEA E11/E9, with the broadest single-grade OEM approval list in the ADDLAR heavy-duty range.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "ACEA E11/E9 | 13.50% | 10.1",
			'approvals_text' => "API CK-4\nCummins 20086\nCummins 20087\nDetroit DFS 93K222\nMB 228.31\nMAN M3775\nMAN M3575\nVolvo VDS-4\nMTU Type 2.1\nMack EO-O Premium Plus\nGlobal DHD-1\nJASO DH-2\nCaterpillar ECF-3\nRenault RLD-3\nScania Low Ash",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 156 cSt\nDensity @ 15°C | | 944 kg/m3\nFlash Point (COC) | | 208 °C\nBase Number | | 74 mgKOH/g\nNitrogen | | 1.36 %mass\nCalcium | | 1.80 %mass\nMolybdenum | | 0.08 %mass\nPhosphorus | | 0.70 %mass\nZinc | | 0.77 %mass\nSulphated Ash | | 6.60 %mass",
			'viscosity_note' => "For API CK-4, ACEA E11/E9: 0W-50, 5W-30, 5W-40, 10W-30, 10W-40, 15W-40",
		),

		'9100' => array(
			'title'        => 'ADDLAR 9100',
			'category'     => 'Marine',
			'subcategory'  => 'Trunk Piston',
			'doc_code'     => 'RCH/V1.1/9100',
			'spec_string'  => 'Marine Trunk Piston Engine Oil Additive Package',
			'description'  => 'TBN-graded marine trunk piston engine oil additive package, dosed to four target base-number levels rather than an API/ACEA cascade.',
			'performance_headers'   => 'Treat Rate % | TBN',
			'performance_rows_text' => "6.00% | 12.0\n10.00% | 20.0\n15.00% | 30.0\n20.00% | 40.0",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | \nDensity @ 15°C | | \nFlash Point (COC) | | 200 °C\nBase Number | | 200 mgKOH/g\nCalcium | | 7.2 %mass\nPhosphorus | | 0.19 %mass\nZinc | | 0.23 %mass\nSulphated Ash | | 42 %mass",
			'viscosity_note' => "30, 40, 50",
		),

		'9200' => array(
			'title'        => 'ADDLAR 9200',
			'category'     => 'Marine',
			'subcategory'  => 'System Oil',
			'doc_code'     => 'RCH/V1.1/9200',
			'spec_string'  => 'Marine System Oil Additive Package',
			'description'  => 'Marine system oil additive package documented as a worked formulation recipe rather than a treat-rate table — base oils, additive and pour-point depressant blended to a target SAE grade and TBN.',
			'formulation_label' => 'SAE 30 (TBN 5 mg KOH/g) Formulation',
			'formulation_text'  => "600SN Base Oil: 85.70%\n150SN Base Oil: 11.20%\nADDLAR 9200: 2.80%\nPour Point Depressant: 0.30%",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 33 cSt\nDensity @ 15°C | | 1133 kg/m3\nFlash Point (COC) | | 200 °C\nBase Number | | 185 mgKOH/g\nPhosphorus | | 0.95 %mass\nCalcium | | 7.30 %mass\nZinc | | 1.14 %mass\nSulphated Ash | | 26 %mass",
			'viscosity_note' => "30, 40, 50",
		),

		'9300' => array(
			'title'        => 'ADDLAR 9300',
			'category'     => 'Marine',
			'subcategory'  => 'Cylinder Oil',
			'doc_code'     => 'RCH/V1.1/9300',
			'spec_string'  => 'Marine Cylinder Engine Oil Additive Package',
			'description'  => 'TBN-graded marine cylinder oil additive package, dosed to three high-alkalinity target base-number levels for cylinder lubrication duty.',
			'performance_headers'   => 'Treat Rate % | TBN',
			'performance_rows_text' => "12.50% | 40\n22.00% | 70\n31.50% | 100",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | \nDensity @ 15°C | | \nFlash Point (COC) | | 200 °C\nBase Number | | 330 mgKOH/g\nCalcium | | 12.0 %mass\nSulphated Ash | | 42 %mass",
			'viscosity_note' => "30, 40, 50",
		),

		'9312' => array(
			'title'        => 'ADDLAR 9312',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Motorcycle',
			'doc_code'     => 'RCH/V1.1/9312',
			'spec_string'  => 'JASO FD/FC/FB/FA · API TC/TA · ISO-L-EGD/EGC/EGB',
			'description'  => 'Low-ash two-stroke engine oil additive package for oil-injection and premix systems, graded across the JASO F-series and equivalent ISO-L-EG standards.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "JASO FD / ISO-L-EGD | 2.50% | 2.95\nAPI TC | 2.25% | 2.65\nJASO FC / ISO-L-EGC | 2.10% | 2.45\nJASO FB / ISO-L-EGB | 1.20% | 1.40\nJASO FA / API TA | 1.10% | 1.30",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 115 cSt\nDensity @ 15°C | | 961 kg/m3\nFlash Point (COC) | | 200 °C\nBase Number | | 117 mgKOH/g\nCalcium | | 2.88 %mass\nSulphated Ash | | 10 %mass",
			'viscosity_note' => "20",
		),

		'9342' => array(
			'title'        => 'ADDLAR 9342',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Motorcycle',
			'doc_code'     => 'RCH/9342/V2',
			'spec_string'  => 'API TCW3',
			'description'  => 'Ashless two-cycle engine oil additive package meeting API TCW3 — deliberately free of metallic (ash-forming) elements, so it has no Calcium/Phosphorus/Zinc/Sulphated Ash to report.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "API TCW3 | 10.5% | 2.65",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 135 cSt\nDensity @ 15°C | | 902 kg/m3\nFlash Point (COC) | | 210 °C\nBase Number | | 19.5 mgKOH/g\nNitrogen | | 0.85 %mass",
			'viscosity_note' => "20",
		),

		'KC311' => array(
			'title'        => 'ADDLAR KC311',
			'category'     => 'Industrial',
			'subcategory'  => 'Grease',
			'doc_code'     => 'RCH/V1.1/KC311',
			'spec_string'  => 'Premium Ashless EP Additive Package for Greases',
			'description'  => 'Ashless extreme-pressure additive package for lithium and lithium-complex greases, dosed by four-ball weld-load target rather than an API/ACEA level.',
			'performance_headers'   => 'Weld Load Target | Treat Rate %',
			'performance_rows_text' => "200 kg | 0.40% – 0.50%\n250 kg | 0.80% – 1.00%\n315 kg | 1.50% – 2.00%",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 1.4 cSt\nDensity @ 15°C | ASTM D1298 | 108 kg/m3\nFlash Point (COC) | | 90 °C\nSulphur | | 40 %mass\nNitrogen | | 0.27 %mass\nPhosphorus | | 900 ppm\nSolubility (in base grease) | | Bright and Clear",
			'viscosity_note' => "Lithium Grease, Lithium Complex Grease",
		),

		'KC321' => array(
			'title'        => 'ADDLAR KC321',
			'category'     => 'Industrial',
			'subcategory'  => 'Hydraulic',
			'doc_code'     => 'RCH/V1.1/KC321',
			'spec_string'  => 'Premium Anti-wear Hydraulic Oil Additive Package',
			'description'  => 'Anti-wear hydraulic oil additive package dosed at two tiers — a top-tier HVLP/HV formulation and a mid-tier HLP/HM formulation — each carrying its own wide OEM and ISO/DIN approval list.',
			'performance_headers'   => 'Tier | Treat Rate %',
			'performance_rows_text' => "Top-Tier | 0.80%\nMid-Tier | 0.40%",
			'approvals_text' => "Denison HF-0 (Top-Tier)\nParker HF-0/1/2 (HV) (Top-Tier)\nMAG P70 (HV) (Top-Tier)\nDIN 51524-3 (HVLP) (Top-Tier)\nISO 11158 HV (Top-Tier)\nASTM D6158 HV (Top-Tier)\nSAE MS1004 HV (Top-Tier)\nBosch Rexroth RE 90220 (Top-Tier)\nSEB 181222 (Top-Tier)\nDIN 51524-2 (HLP) (Mid-Tier)\nParker HF-1/2 (HM) (Mid-Tier)\nEaton M-2950-S / I-286-S3 (Mid-Tier)\nMAG P68/P69 (HM) (Mid-Tier)\nISO 11158 HM (Mid-Tier)\nASTM D6158 HM (Mid-Tier)\nANSI/AGMA 9005-E02-RO (Mid-Tier)\nGM LS-2 (Mid-Tier)\nAIST 126/127 (Mid-Tier)",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 12 cSt\nDensity @ 15°C | | 1016 kg/m3\nFlash Point (COC) | | 138 °C\nBase Number | | 65 mgKOH/g\nNitrogen | | 1.17 %mass\nCalcium | | 0.64 %mass\nPhosphorus | | 4.2 %mass\nZinc | | 5.2 %mass\nSulphur | | 8.6 %mass",
			'viscosity_note' => "ISO VG 32, 46, 68, 100, 150",
		),

		'KC420' => array(
			'title'        => 'ADDLAR KC420',
			'category'     => 'Metal Working Fluid',
			'subcategory'  => 'Neat Cutting',
			'doc_code'     => 'RCH/V1.1/KC420',
			'spec_string'  => 'Sulphurised Ester Neat Cutting Oil Additive Package',
			'description'  => 'Sulphurised ester additive package for neat (undiluted) cutting oils used in severe metalworking operations — dosed for the application, not graded to an API/ACEA level.',
			'applications_text' => "Gear Hobbing\nGear Shaping and Grinding\nBroaching\nThread Cutting and Tapping\nDeep Hole Drilling\nForming and Stamping\nHeavy-Duty Turning and Milling\nCold Heading / Forming\nSpline Rolling\nSawing Operations",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 40°C | ASTM D445 | 220 cSt\nDensity @ 15°C | | 998 kg/m3\nFlash Point (COC) | | 140 °C\nSulphur | | 20 %mass\nCopper Corrosion (2% in 150SN, 100°C / 3h) | ASTM D130 | 1b Rating\nFour-Ball Weld Load (2% / 3% / 5% in 150SN) | ASTM D2783 | 315 / 620 / >800 kg\nSolubility (5% in 150SN) | | Bright and Clear",
		),

		'KC562' => array(
			'title'        => 'ADDLAR KC562',
			'category'     => 'Driveline',
			'subcategory'  => 'Automotive Gear',
			'doc_code'     => 'RCH/V1.1/KC562',
			'spec_string'  => 'API GL-5/GL-4 · DIN 51517 Part III',
			'description'  => 'Multi-purpose gear oil additive package covering both automotive (API GL-5/GL-4) and industrial (DIN 51517 Part III) gear applications from a single package.',
			'performance_note'      => 'Automotive',
			'performance_headers'   => 'Level | Treat Rate %',
			'performance_rows_text' => "API GL-5 | 3.90%\nAPI GL-5 + 1.0% ADDLAR KC560B (Booster, API MT-1 capable) | 3.90% + 1.0%\nAPI GL-4 | 1.50%",
			'formulation_label' => 'Industrial',
			'formulation_text'  => "DIN 51517 Part III: 0.90% treat rate",
			'approvals_text' => "ZF TE-ML 07A (GL-5)\nMAN M2342 capable (GL-5)\nChina GB 13895-1992 (GL-5)\nScania STO 1:0 (GL-5)\nBIS 1118:1992 (GL-4)\nAIST 224 (Industrial)\nAGMA 9005:E02 (Industrial)\nISO 12925-1 CKD (Industrial)\nDavid Brown S1.53.101 (Industrial)\nGM LS-2 (Industrial)\nCincinnati Machine (Gear) (Industrial)",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 1.4 cSt\nDensity @ 15°C | | 1020 kg/m3\nFlash Point (COC) | | 90 °C\nSulphur | | 30 %mass\nNitrogen | | 0.2 %mass\nPhosphorus | | 0.7 %mass\nSolubility | | Bright and Clear",
			'viscosity_note' => "Automotive: SAE 90, 140, 220, 250, 320, 75W-90, 80W-90, 80W-140, 85W-140, 75W-140\nIndustrial: ISO VG 100, 150, 220, 320, 460, 680, 1000",
		),

		'KC563' => array(
			'title'        => 'ADDLAR KC563',
			'category'     => 'Driveline',
			'subcategory'  => 'Automotive Gear',
			'doc_code'     => 'RCH/V1.1/KC563',
			'spec_string'  => 'API GL-5/GL-4 · DIN 51517 Part III (Odorless)',
			'description'  => 'Odorless multi-purpose gear oil additive package, sibling to ADDLAR KC562, covering the same automotive (API GL-5/GL-4) and industrial (DIN 51517 Part III) gear applications at higher treat rates.',
			'performance_note'      => 'Automotive',
			'performance_headers'   => 'Level | Treat Rate %',
			'performance_rows_text' => "API GL-5 | 4.0%\nAPI GL-5 + 1.0% ADDLAR KC560B (Booster, API MT-1 capable) | 4.0% + 1.0%\nAPI GL-4 | 2.0%",
			'formulation_label' => 'Industrial',
			'formulation_text'  => "DIN 51517 Part III: 1.0% treat rate",
			'approvals_text' => "ZF TE-ML 07A (GL-5)\nMAN M2342 capable (GL-5)\nChina GB 13895-1992 (GL-5)\nScania STO 1:0 (GL-5)\nBIS 1118:1992 (GL-4)\nAIST 224 (Industrial)\nAGMA 9005:E02 (Industrial)\nISO 12925-1 CKD (Industrial)\nDavid Brown S1.53.101 (Industrial)\nGM LS-2 (Industrial)\nCincinnati Machine (Gear) (Industrial)",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 40°C | | 45 cSt\nDensity @ 15°C | | 108 kg/m3\nFlash Point (COC) | | 150 °C\nSulphur | | 16 %mass\nNitrogen | | 0.2 %mass\nPhosphorus | | 0.7 %mass\nSolubility | | Bright and Clear",
			'viscosity_note' => "Automotive: SAE 90, 140, 220, 250, 320, 75W-90, 80W-90, 80W-140, 85W-140, 75W-140\nIndustrial: ISO VG 100, 150, 220, 320, 460, 680, 1000",
		),

		'Z 2612' => array(
			'title'        => 'ADDLAR Z 2612',
			'category'     => 'Lubricant Component',
			'subcategory'  => 'Anti-wear & Friction Modifier',
			'doc_code'     => 'Z/V1.1/2612',
			'spec_string'  => 'Zinc Primary Alkyl Dithiophosphate (ZDDP)',
			'description'  => 'Zinc primary alkyl dithiophosphate — a raw anti-wear/anti-oxidant component dosed by the formulator into finished oils, not graded to an API/ACEA level itself. Good anti-oxidant performance, excellent anti-wear protection, and effectively inhibits copper-lead bearing corrosion.',
			'applications_text' => "Engine Oils (especially heavy-duty supercharged diesel)\nHydraulic Oils\nBearing Oils",
			'formulation_label' => 'Recommended Dosage',
			'formulation_text'  => "Dosage Range: 0.5% – 3.0% mass",
			'properties_text' => "Appearance | | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | | 22 cSt\nDensity @ 15°C | | 1200 kg/m3\nFlash Point (COC) | | 120 °C\nBase Number | | 145 mgKOH/g\nSulphur | | 4.8 %mass\nPhosphorus | | 9.20 %mass\nZinc | | 10.5 %mass",
		),

	);
}
