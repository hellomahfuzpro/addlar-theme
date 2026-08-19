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
 * **Fully re-verified against the source PDFs.** An earlier pass left the
 * Method column blank throughout rather than guess ASTM codes; every one
 * has since been read off the PDS and filled in, and `description` is now
 * the client's own DESCRIPTION paragraph rather than a summary written
 * here. That re-read also caught real transcription errors in the first
 * pass, all corrected:
 *   - 7009: kinematic viscosity was 160 cSt (that is its Base Number);
 *     the real value is 65 cSt, and density 1025 kg/m3 was missing.
 *   - Appearance was recorded as "Brown Viscous Liquid" for six products
 *     whose PDS says otherwise — KC311 (Yellow Light Viscous), KC321
 *     (Brown Clear), KC420 / KC562 / KC563 (Clear Yellow) and Z 2612
 *     (Bright & Clear).
 * Blank values that remain (9100 and 9300's viscosity and density) are
 * blank in the source PDS itself and render as "—", never invented.
 *
 * This array is the **seed source**, read once by addlar_seed_products()
 * to populate each product page's Elementor widgets. After seeding, the
 * pages are the source of truth and are edited in Elementor; re-seeding
 * regenerates them from here and overwrites those edits.
 *
 * Column conventions:
 * - performance_rows_text: one row per line, `|`-separated, matching performance_headers.
 * - properties_text: one row per line, `Test | Method | Value`.
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
			'description'  => 'A cost-effective, multipurpose additive package formulated with high-quality detergents, dispersants and anti-wear agents, suitable for both gasoline and diesel engine oil formulations. It offers outstanding thermal and oxidative stability, superior soot dispersancy and robust anti-wear protection, contributing to extended engine life. Particularly effective in engines operating under high-temperature conditions commonly found in hot climates or when using high-sulphur fuels.',
			'performance_note'      => 'Multigrade',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SL/CF-4 + 2.3% Booster | 4.80% | 7.1\nSF/CF | 3.75% | 6.1\nSF/CD | 3.75% | 6.1\nSF/CC | 3.75% | 6.1\nSE/CD & SD/CC | 3.23% | 5.2\nSC/CC | 2.43% | 3.9\nSB/CB | 1.92% | 3.1",
			'formulation_label'     => 'Monograde',
			'formulation_text'      => "SE/CD & SD/CC: 2.93% treat rate, 4.7 TBN\nSC/CC: 2.21% treat rate, 3.6 TBN\nSB/CB: 1.75% treat rate, 2.8 TBN",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 65 cSt\nDensity @ 15°C | ASTM D1298 | 1025 kg/m3\nFlash Point (COC) | ASTM D92 | 200 °C\nBase Number | ASTM D2896 | 160 mgKOH/g\nNitrogen | ASTM D3228 | 0.3 %mass\nCalcium | ASTM D5185 | 5.6 %mass\nPhosphorus | ASTM D5185 | 1.3 %mass\nZinc | ASTM D5185 | 1.5 %mass\nSulphated Ash | ASTM D874 | 22 %mass",
			'viscosity_note' => "Gasoline: 10W-30, 10W-40, 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 50\nDiesel: 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 40, 50",
		),

		'7155' => array(
			'title'        => 'ADDLAR 7155',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/7155/V1.2',
			'spec_string'  => 'API SJ/CF-4 to API SB/CB',
			'description'  => 'A cost-effective, cascade-type multifunctional additive package developed for formulating mid-tier gasoline and diesel engine oils. Suitable for both multigrade and monograde formulations using Group I and Group II base stocks, offering reliable performance at economical treat rates — a standard, versatile solution for formulators seeking balanced performance and cost-efficiency.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "API SJ/CF-4 | 5.60% | 10.1\nAPI SG/CF-4 | 5.50% | 10.0\nAPI SJ/CF | 4.50% | 8.1\nAPI SG/CF | 4.40% | 8.0\nAPI SF/CF | 3.25% | 5.9\nAPI SF/CD | 3.25% | 5.9\nAPI CF (M) | 3.05% | 5.5\nAPI SB/CD | 2.15% | 3.9\nAPI CD (M) | 2.10% | 3.8\nAPI SC/CC | 2.00% | 3.6\nAPI SB/CB | 1.35% | 2.4",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nDensity @ 15°C | ASTM D1298 | 1045 kg/m3\nFlash Point (COC) | ASTM D92 | 200 °C\nBase Number | ASTM D2896 | 180 mgKOH/g\nNitrogen | ASTM D3228 | 0.4 %mass\nCalcium | ASTM D5185 | 7.0 %mass\nPhosphorus | ASTM D5185 | 1.5 %mass\nZinc | ASTM D5185 | 1.65 %mass\nSulphated Ash | ASTM D874 | 25 %mass",
			'viscosity_note' => "Gasoline: 10W-30, 10W-40, 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 50\nDiesel: 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 40, 50",
		),

		'7157' => array(
			'title'        => 'ADDLAR 7157',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/V1.3/7157',
			'spec_string'  => 'API SL/CF-4 · CF · ACEA A3/B3',
			'description'  => 'A cascade multifunctional engine oil additive package for formulating mid-tier gasoline and diesel engine oils. Designed for multigrade and monograde engine oils with Group I and Group II base stocks at economical treat rates. It has a unique synergy of detergent, dispersant and anti-wear additives to provide excellent engine cleanliness, exceptional wear protection and resistance to both high- and low-temperature deposits.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "PSAB71 2300 | 9.50% | 13.8\nVW501.01/505.00 | 9.50% | 13.8\nMB229.1 | 8.00% | 11.6\nACEA A3/B3 | 7.30% | 10.6\nSL/CF-4 | 5.90% | 8.6\nSL/CF | 5.70% | 8.3\nSJ/CF-4 | 5.40% | 7.8\nSJ/CF | 4.60% | 6.7\nSG/CF | 4.20% | 6.1\nSG/CD | 3.10% | 4.5\nSE/CD | 2.70% | 3.9\nSC/CC | 1.90% | 2.8\nSB/CB | 1.60% | 2.3",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 75 cSt\nDensity @ 15°C | ASTM D1298 | 1016 kg/m3\nFlash Point (COC) | ASTM D92 | 206 °C\nBase Number | ASTM D2896 | 145 mgKOH/g\nNitrogen | ASTM D3228 | 0.6 %mass\nCalcium | ASTM D5185 | 4.8 %mass\nPhosphorus | ASTM D5185 | 1.35 %mass\nZinc | ASTM D5185 | 1.61 %mass\nSulphated Ash | ASTM D874 | 18 %mass",
			'viscosity_note' => "For ACEA A3/B3: 0W-30, 0W-40, 5W-30, 5W-40, 10W-40\nGasoline: 10W-30, 10W-40, 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 50\nDiesel: 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 40, 50",
		),

		'7158' => array(
			'title'        => 'ADDLAR 7158',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/V1.1/7158',
			'spec_string'  => 'API SL/CF-4 · CF · ACEA A3/B3 · JASO MA2/MB',
			'description'  => 'An advanced cascade additive system engineered for the formulation of mid-tier gasoline and diesel engine oils. Optimised for blending monograde and multigrade lubricants using Group I and Group II base stocks at cost-effective treat rates. The package integrates a highly effective balance of metallic detergents, ashless dispersants and advanced anti-wear agents, delivering outstanding piston and ring cleanliness, superior wear protection and excellent resistance to high- and low-temperature deposits. Also suitable for motorcycle engine oils meeting JASO MA2 and JASO MB.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "PSAB71 2300 | 9.50% | 13.8\nVW501.01/505.00 | 9.50% | 13.8\nMB229.1 | 8.00% | 11.6\nACEA A3/B3 | 7.30% | 10.6\nSL/CF-4 | 5.90% | 8.6\nSL/CF | 5.70% | 8.3\nSJ/CF-4 | 5.40% | 7.8\nSJ/CF | 4.60% | 6.7\nSG/CF | 4.20% | 6.1\nSG/CD | 3.10% | 4.5\nSE/CD | 2.70% | 3.9\nSC/CC | 1.90% | 2.8\nSB/CB | 1.60% | 2.3\nJASO MA2 | 6.50% | 5.7\nJASO MB | 6.50% + 0.3% RCH5830 Booster | 5.9",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 70 cSt\nDensity @ 15°C | ASTM D1298 | 1016 kg/m3\nFlash Point (COC) | ASTM D92 | 206 °C\nBase Number | ASTM D2896 | 145 mgKOH/g\nNitrogen | ASTM D3228 | 0.6 %mass\nCalcium | ASTM D5185 | 4.85 %mass\nPhosphorus | ASTM D5185 | 1.35 %mass\nZinc | ASTM D5185 | 1.61 %mass\nMolybdenum | ASTM D5185 | 0.04 %mass\nSulphated Ash | ASTM D874 | 18 %mass",
			'viscosity_note' => "For ACEA A3/B3: 0W-30, 0W-40, 5W-30, 5W-40, 10W-40\nGasoline: 10W-30, 10W-40, 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 50\nDiesel: 15W-40, 15W-50, 20W-40, 20W-50, 20W, 30, 40, 50",
		),

		'7375' => array(
			'title'        => 'ADDLAR 7375',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/V1.1/7375',
			'spec_string'  => 'API SN/CF, SL to SJ · ACEA C3/C4, A3/B4, A5/B5 · ILSAC GF-5 · JASO MA2',
			'description'  => 'A high-performance mid-SAPS engine oil additive package. It maximises engine durability by providing exceptional wear protection, extends engine life and maintains peak performance under extreme conditions. It improves fuel economy by reducing internal friction, and offers exceptional deposit control, effectively preventing sludge and deposit formation across a wide range of operating temperatures. Its outstanding detergency and dispersancy ensure superior engine cleanliness, while optimised seal compatibility works seamlessly with engine sealing materials to prevent leaks.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SN | 6.75% | 6.7\nSM | 6.00% | 5.9\nSL | 5.35% | 5.3\nSJ | 5.00% | 4.9",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 80 cSt\nDensity @ 15°C | ASTM D1298 | 975 kg/m3\nFlash Point (COC) | ASTM D92 | 190 °C\nBase Number | ASTM D2896 | 105 mgKOH/g\nNitrogen | ASTM D3228 | 1.0 %mass\nCalcium | ASTM D5185 | 3.2 %mass\nPhosphorus | ASTM D5185 | 1.11 %mass\nZinc | ASTM D5185 | 1.31 %mass\nMolybdenum | ASTM D5185 | 0.06 %mass\nSulphated Ash | ASTM D874 | 11 %mass",
			'viscosity_note' => "5W-30, 5W-40, 10W-30, 10W-40, 15W-40, 20W-40, 20W-50, 20W, 30W, 30, 40, 50",
		),

		'7376' => array(
			'title'        => 'ADDLAR 7376',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/7376/V1.1',
			'spec_string'  => 'API SN/CF to SL/CF-4 · ILSAC GF-5 · ACEA C3/C4, A3/B4 · JASO MA2',
			'description'  => 'A high-performance mid-SAPS engine oil additive package engineered to deliver superior protection in modern engines. It enhances engine durability through outstanding wear protection and ensures consistent operation under severe conditions. By reducing internal friction it improves fuel economy, and provides excellent deposit control, preventing sludge and varnish across a wide temperature range. Superior detergency and dispersancy contribute to exceptional engine cleanliness. The final formulation is designed to achieve a Base Number of 10 mg KOH/g, ensuring effective acid neutralisation and prolonged oil life.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SN | 8.0% | 10.0\nSM | 7.2% | 9.0\nSL/CF-4 | 6.0% | 7.5",
			'approvals_text' => "ACEA C3/C4 (SN)\nMB 229.51 / 229.31 (SN)\nVW 504.00 / 507.00 (SN)\nGM Dexos2 (SN)\nBMW LL-04 (SN)\nPorsche C30 (SN)\nChrysler MS-11106 (SN)\nILSAC GF-5 (SN)\nJASO MA2 (SN)\nACEA A3/B4 (SM)",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 80 cSt\nDensity @ 15°C | ASTM D1298 | 987 kg/m3\nFlash Point (COC) | ASTM D92 | 205 °C\nBase Number | ASTM D2896 | 125 mgKOH/g\nNitrogen | ASTM D3228 | 1.15 %mass\nCalcium | ASTM D4951 | 3.85 %mass\nPhosphorus | ASTM D4951 | 0.94 %mass\nZinc | ASTM D4951 | 1.06 %mass\nMolybdenum | ASTM D4951 | 0.06 %mass\nSulphated Ash | ASTM D874 | 12 %mass",
			'viscosity_note' => "5W-30, 5W-40, 10W-30, 10W-40, 15W-40, 20W-40, 20W-50, 20W, 30W, 30, 40, 50",
		),

		'7395' => array(
			'title'        => 'ADDLAR 7395',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Passenger Car',
			'doc_code'     => 'RCH/V2.1/7395',
			'spec_string'  => 'API SN/CF, SM · ILSAC GF-5 · ACEA C3/C5, A3/B4 · JASO MA2',
			'description'  => 'A high-performance mid-SAPS engine oil additive package. It maximises engine durability by providing exceptional wear protection, extends engine life and maintains peak performance under extreme conditions. It improves fuel economy by reducing internal friction, and offers exceptional deposit control across a wide range of operating temperatures. Its outstanding detergency and dispersancy ensure superior engine cleanliness, while optimised seal compatibility prevents leaks and ensures long-term reliability.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SN | 7.70% | 6.7\nSM | 7.10% | 6.2",
			'approvals_text' => "ACEA C3/C5 (SN)\nMB 229.51 / 229.31 (SN)\nVW 504.00 / 507.00 (SN)\nGM Dexos2 (SN)\nBMW LL-04 (SN)\nPorsche C30 (SN)\nChrysler MS-11106 (SN)\nILSAC GF-5 (SN)\nJASO MA2 (SN)\nACEA A3/B4 (SM)",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 115 cSt\nDensity @ 15°C | ASTM D1298 | 980 kg/m3\nFlash Point (COC) | ASTM D92 | 205 °C\nBase Number | ASTM D2896 | 90 mgKOH/g\nNitrogen | ASTM D3228 | 0.93 %mass\nCalcium | ASTM D4951 | 2.60 %mass\nPhosphorus | ASTM D4951 | 0.93 %mass\nZinc | ASTM D4951 | 1.05 %mass\nMolybdenum | ASTM D4951 | 0.05 %mass\nSulphated Ash | ASTM D874 | 10.0 %mass",
			'viscosity_note' => "5W-30, 5W-40, 10W-30, 10W-40, 15W-40, 20W-40, 20W-50, 20W, 30W, 30, 40, 50",
		),

		'7511' => array(
			'title'        => 'ADDLAR 7511',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Heavy Duty',
			'doc_code'     => 'RCH/V2.1/7511',
			'spec_string'  => 'API SF/CF to API SB/CB',
			'description'  => 'A cost-effective, multipurpose additive package formulated with high-quality detergents, dispersants and anti-wear agents, suitable for both gasoline and diesel engine oils. It imparts excellent thermal oxidation stability, effective soot dispersancy and superior anti-wear protection. Specifically developed for engines operating at elevated temperatures, particularly in hot climates and with high-sulphur fuels, it ensures outstanding engine cleanliness, effective deposit and oil oxidation control, and enhanced wear and rust protection under severe operating conditions.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "SF/CF | 3.50% | 6.30\nCF (Monograde) | 3.50% | 6.30\nSF/CD | 3.40% | 6.10\nSF/CC | 3.50% | 6.10\nSE/CD | 2.75% | 4.95\nSD/CD | 2.50% | 4.50\nCD (Monograde) | 2.45% | 4.40\nSD/CC | 2.40% | 4.30\nSC/CC | 2.05% | 3.70\nSB/CC | 1.65% | 3.00",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 55 cSt\nDensity @ 15°C | ASTM D1298 | 1042 kg/m3\nFlash Point (COC) | ASTM D92 | 206 °C\nBase Number | ASTM D2896 | 180 mgKOH/g\nNitrogen | ASTM D3228 | 0.25 %mass\nCalcium | ASTM D5185 | 6.0 %mass\nPhosphorus | ASTM D5185 | 1.35 %mass\nZinc | ASTM D5185 | 1.61 %mass",
			'viscosity_note' => "Monograde: 30, 40, 50\nMultigrade: 10W-30, 10W-40, 15W-40, 15W-50, 20W-50",
		),

		'7706' => array(
			'title'        => 'ADDLAR 7706',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Heavy Duty',
			'doc_code'     => 'RCH/V2.1/7706',
			'spec_string'  => 'ACEA E4/E5 · API CI-4 to CF-4/SJ',
			'description'  => 'A high-performance engine oil additive package specifically designed for modern diesel engines, meeting the stringent requirements of ACEA E4/E5 and API CI-4 through CF-4/SJ. It supports enhanced durability and extended oil drain intervals, and is optimised for both on-road and off-road applications. Compatible with Group I, Group II and Group III base stocks, it delivers excellent deposit and soot control, superior wear protection, and effective oil oxidation and corrosion resistance for extended service life.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "ACEA E4/E5 | 8.50% | 9.2\nAPI CH-4 | 7.80% | 8.4\nCF-4/SL | 6.00% | 6.5\nCF-4/SJ | 5.10% | 5.5",
			'approvals_text' => "API CI-4 (E4/E5)\nCaterpillar ECF-1-a (E4/E5)\nDetroit DFS 93K215 (E4/E5)\nMTU Cat 1 (E4/E5)\nVolvo VDS-2 (E4/E5)\nMAN 270 (E4/E5)\nMAN 271 (E4/E5)\nVolvo VDS-2 (CH-4)",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 85 cSt\nDensity @ 15°C | ASTM D1298 | 991 kg/m3\nFlash Point (COC) | ASTM D92 | 208 °C\nBase Number | ASTM D2896 | 108 mgKOH/g\nNitrogen | ASTM D3228 | 0.55 %mass\nCalcium | ASTM D5185 | 3.70 %mass\nPhosphorus | ASTM D5185 | 1.35 %mass\nZinc | ASTM D5185 | 1.55 %mass",
			'viscosity_note' => "For CI-4, CH-4, CF-4/SL & CF-4/SJ: 5W-30, 5W-40, 10W-30, 10W-40, 15W-40, 20W-50, 40, 50\nFor ACEA E4/E5: 5W-30, 10W-30, 10W-40, 15W-40, 40, 50",
		),

		'7730' => array(
			'title'        => 'ADDLAR 7730',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Heavy Duty',
			'doc_code'     => 'RCH/V2.1/7730',
			'spec_string'  => 'ACEA E7 · A3/B4, E5, E2, A2 · API CI-4/SL, CH-4/SJ, CG-4/SJ',
			'description'  => 'An advanced additive package engineered for formulating heavy-duty diesel engine oils. It delivers superior piston cleanliness, minimises wear on the valve mechanism and cylinder-piston assembly, and ensures corrosion protection throughout extended oil drain intervals. Optimised for Euro V and earlier engine models from global and Asian manufacturers, particularly in applications without diesel particulate filters (DPF). It provides high thermal-oxidative stability, maintains an excellent detergent-dispersant balance under high soot and sulphur conditions, and ensures full compatibility with standard seal materials.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "ACEA E7 | 11.70% | 11.10\nCH-4/SJ | 10.70% | 10.20\nCG-4/SJ | 9.10% | 8.60",
			'approvals_text' => "ACEA A3/B4 (E7)\nAPI CI-4/SL (E7)\nMB 228.5 (E7)\nMB 228.3 (E7)\nMAN 3277 (E7)\nMAN 270 (E7)\nMAN 271 (E7)\nVolvo VDS-3 (E7)\nMTU Cat 1 (E7)\nDetroit DFS 93K215 (E7)\nMack EO-N (E7)\nGlobal DHD-1 (E7)\nJASO DH-1 (E7)\nCaterpillar ECF-2 (E7)\nCummins 20078 (E7)\nCummins 20077 (E7)\nCummins 20076 (E7)\nACEA E5 (CH-4/SJ)\nRenault RLD-2 (CH-4/SJ)\nMTU Type 3 (CH-4/SJ)\nDDC DFS 93214 (CH-4/SJ)\nACEA E2 (CG-4/SJ)\nACEA A2 (CG-4/SJ)\nMB 228.0 / 228.1 / 227.0 / 227.1 (CG-4/SJ)",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 80 cSt\nDensity @ 15°C | ASTM D1298 | 970 kg/m3\nFlash Point (COC) | ASTM D92 | 208 °C\nBase Number | ASTM D2896 | 92 mgKOH/g\nNitrogen | ASTM D3228 | 0.54 %mass\nCalcium | ASTM D5185 | 3.0 %mass\nPhosphorus | ASTM D5185 | 0.90 %mass\nZinc | ASTM D5185 | 1.01 %mass\nSulphated Ash | ASTM D874 | 11.5 %mass",
			'viscosity_note' => "For CI-4, CH-4, CG-4/SL: 0W-50, 5W-40, 10W-30, 15W-40\nFor ACEA E7/E5: 10W-40, 5W-30",
		),

		'7750' => array(
			'title'        => 'ADDLAR 7750',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Heavy Duty',
			'doc_code'     => 'RCH/7750/V2.1',
			'spec_string'  => 'ACEA E11/E9 · API CK-4 · JASO DH-2',
			'description'  => 'A next-generation, low-SAPS additive package formulated for high-performance diesel engine oils meeting API CK-4, ACEA E11-2022 and leading OEM specifications. Designed for engines equipped with DPF, EGR, SCR and TWC systems, it offers exceptional protection against wear, oxidation, deposits and oil thickening under high thermal and mechanical stress. It ensures optimal engine cleanliness through advanced detergency and dispersancy, while its robust anti-wear chemistry extends component life. The formulation maintains excellent shear stability, supports long drain intervals and prevents filter clogging.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "ACEA E11/E9 | 13.50% | 10.1",
			'approvals_text' => "API CK-4\nCummins 20086\nCummins 20087\nDetroit DFS 93K222\nMB 228.31\nMAN M3775\nMAN M3575\nVolvo VDS-4\nMTU Type 2.1\nMack EO-O Premium Plus\nGlobal DHD-1\nJASO DH-2\nCaterpillar ECF-3\nRenault RLD-3\nScania Low Ash",
			'properties_text' => "Appearance | Visual | Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 156 cSt\nDensity @ 15°C | ASTM D1298 | 944 kg/m3\nFlash Point (COC) | ASTM D92 | 208 °C\nBase Number | ASTM D2896 | 74 mgKOH/g\nNitrogen | ASTM D3228 | 1.36 %mass\nCalcium | ASTM D5185 | 1.80 %mass\nMolybdenum | ASTM D4951 | 0.08 %mass\nPhosphorus | ASTM D5185 | 0.70 %mass\nZinc | ASTM D5185 | 0.77 %mass\nSulphated Ash | ASTM D874 | 6.60 %mass",
			'viscosity_note' => "For API CK-4, ACEA E11/E9: 0W-50, 5W-30, 5W-40, 10W-30, 10W-40, 15W-40",
		),

		'9100' => array(
			'title'        => 'ADDLAR 9100',
			'category'     => 'Marine',
			'subcategory'  => 'Trunk Piston',
			'doc_code'     => 'RCH/V1.1/9100',
			'spec_string'  => 'Marine Trunk Piston Engine Oil Additive Package',
			'description'  => 'Specifically designed for application in medium-speed diesel engines, and employed in the formulation of trunk piston engine oils with Total Base Numbers ranging from 12 to 40, as determined by ASTM D2896. It delivers outstanding engine cleanliness and superior wear protection in both distillate- and residual-fuelled marine engines.',
			'performance_headers'   => 'Treat Rate % | TBN',
			'performance_rows_text' => "6.00% | 12.0\n10.00% | 20.0\n15.00% | 30.0\n20.00% | 40.0",
			'properties_text' => "Appearance | Visual | Clear Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | \nDensity @ 15°C | ASTM D1298 | \nFlash Point (COC) | ASTM D92 | 200 °C\nBase Number | ASTM D2896 | 200 mgKOH/g\nCalcium | ASTM D5185 | 7.2 %mass\nPhosphorus | ASTM D5185 | 0.19 %mass\nZinc | ASTM D5185 | 0.23 %mass\nSulphated Ash | ASTM D874 | 42 %mass",
			'viscosity_note' => "30, 40, 50",
		),

		'9200' => array(
			'title'        => 'ADDLAR 9200',
			'category'     => 'Marine',
			'subcategory'  => 'System Oil',
			'doc_code'     => 'RCH/V1.1/9200',
			'spec_string'  => 'Marine System Oil Additive Package',
			'description'  => 'Specifically formulated for lubricating crankshafts, oil-cooled pistons and shaft bearings in two-stroke crosshead engines, and equally suited to intermediate and tail shaft stern tube bearings. With excellent detergency, thermal stability and anti-foaming properties, it ensures superior engine protection, while its outstanding oxidation resistance helps prevent rust and corrosion.',
			'formulation_label' => 'SAE 30 (TBN 5 mg KOH/g) Formulation',
			'formulation_text'  => "600SN Base Oil: 85.70%\n150SN Base Oil: 11.20%\nADDLAR 9200: 2.80%\nPour Point Depressant: 0.30%",
			'properties_text' => "Appearance | Visual | Clear Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 33 cSt\nDensity @ 15°C | ASTM D1298 | 1133 kg/m3\nFlash Point (COC) | ASTM D92 | 200 °C\nBase Number | ASTM D2896 | 185 mgKOH/g\nPhosphorus | ASTM D5185 | 0.95 %mass\nCalcium | ASTM D5185 | 7.30 %mass\nZinc | ASTM D5185 | 1.14 %mass\nSulphated Ash | ASTM D874 | 26 %mass",
			'viscosity_note' => "30, 40, 50",
		),

		'9300' => array(
			'title'        => 'ADDLAR 9300',
			'category'     => 'Marine',
			'subcategory'  => 'Cylinder Oil',
			'doc_code'     => 'RCH/V1.1/9300',
			'spec_string'  => 'Marine Cylinder Engine Oil Additive Package',
			'description'  => 'Specially formulated with high levels of thermally stable detergents, making it particularly suitable for marine cylinder lubricants (MCL) delivering 40, 70 and 100 TBN. When formulated, it works effectively in both low-speed and mid-speed engines.',
			'performance_headers'   => 'Treat Rate % | TBN',
			'performance_rows_text' => "12.50% | 40\n22.00% | 70\n31.50% | 100",
			'properties_text' => "Appearance | Visual | Clear Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | \nDensity @ 15°C | ASTM D1298 | \nFlash Point (COC) | ASTM D92 | 200 °C\nBase Number | ASTM D2896 | 330 mgKOH/g\nCalcium | ASTM D5185 | 12.0 %mass\nSulphated Ash | ASTM D874 | 42 %mass",
			'viscosity_note' => "30, 40, 50",
		),

		'9312' => array(
			'title'        => 'ADDLAR 9312',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Motorcycle',
			'doc_code'     => 'RCH/V1.1/9312',
			'spec_string'  => 'JASO FD/FC/FB/FA · API TC/TA · ISO-L-EGD/EGC/EGB',
			'description'  => 'A high-performance additive designed to deliver excellent cleanliness, protection and low smoke at economical treat rates. Suitable for formulating two-stroke oils used in both oil-injection and premix systems, it ensures clean combustion and reduces exhaust smoke. It helps prevent blockage of the exhaust system, extends engine life and maintains full power output, while providing strong lubricant film strength for outstanding protection against metal-to-metal wear.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "JASO FD / ISO-L-EGD | 2.50% | 2.95\nAPI TC | 2.25% | 2.65\nJASO FC / ISO-L-EGC | 2.10% | 2.45\nJASO FB / ISO-L-EGB | 1.20% | 1.40\nJASO FA / API TA | 1.10% | 1.30",
			'properties_text' => "Appearance | Visual | Clear Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 115 cSt\nDensity @ 15°C | ASTM D1298 | 961 kg/m3\nFlash Point (COC) | ASTM D92 | 200 °C\nBase Number | ASTM D2896 | 117 mgKOH/g\nCalcium | ASTM D5185 | 2.88 %mass\nSulphated Ash | ASTM D874 | 10 %mass",
			'viscosity_note' => "20",
		),

		'9342' => array(
			'title'        => 'ADDLAR 9342',
			'category'     => 'Engine Oil Additive',
			'subcategory'  => 'Motorcycle',
			'doc_code'     => 'RCH/9342/V2',
			'spec_string'  => 'API TCW3',
			'description'  => 'An ashless two-stroke engine oil additive package developed for formulating lubricants that meet NMMA TC-W3 performance standards. It provides excellent detergency, cleanliness and lubricity in outboard water-cooled two-cycle gasoline engines. Designed as an ashless additive, it minimises pre-ignition, exhaust port deposits and spark plug fouling, ensuring smooth and reliable engine performance under marine operating conditions.',
			'performance_headers'   => 'Level | Treat Rate % | TBN',
			'performance_rows_text' => "API TCW3 | 10.5% | 2.65",
			'properties_text' => "Appearance | Visual | Clear Brown Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 135 cSt\nDensity @ 15°C | ASTM D1298 | 902 kg/m3\nFlash Point (COC) | ASTM D92 | 210 °C\nBase Number | ASTM D2896 | 19.5 mgKOH/g\nNitrogen | ASTM D3228 | 0.85 %mass",
			'viscosity_note' => "20",
		),

		'KC311' => array(
			'title'        => 'ADDLAR KC311',
			'category'     => 'Industrial',
			'subcategory'  => 'Grease',
			'doc_code'     => 'RCH/V1.1/KC311',
			'spec_string'  => 'Premium Ashless EP Additive Package for Greases',
			'description'  => 'A multifunctional additive package that offers extreme pressure (EP), anti-wear (AW), yellow metal protection and steel corrosion inhibition, formulated using advanced sulfur-phosphorus chemistry. Its multifunctionality allows for inventory rationalisation and simplifies the formulation process. It delivers excellent anti-corrosion performance across a wide range of grease types, and owing to its unique chemical structure effectively inhibits copper corrosion caused by aggressive sulfur compounds while exhibiting strong EP performance.',
			'performance_headers'   => 'Weld Load Target | Treat Rate %',
			'performance_rows_text' => "200 kg | 0.40% – 0.50%\n250 kg | 0.80% – 1.00%\n315 kg | 1.50% – 2.00%",
			'properties_text' => "Appearance | Visual | Yellow Light Viscous Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 1.4 cSt\nDensity @ 15°C | ASTM D1298 | 108 kg/m3\nFlash Point (COC) | ASTM D92 | 90 °C\nSulphur | ASTM D5185 | 40 %mass\nNitrogen | ASTM D3228 | 0.27 %mass\nPhosphorus | ASTM D5185 | 900 ppm\nSolubility (5% in 150SN) | Internal | Bright and Clear",
			'viscosity_note' => "Lithium Grease, Lithium Complex Grease",
		),

		'KC321' => array(
			'title'        => 'ADDLAR KC321',
			'category'     => 'Industrial',
			'subcategory'  => 'Hydraulic',
			'doc_code'     => 'RCH/V1.1/KC321',
			'spec_string'  => 'Premium Anti-wear Hydraulic Oil Additive Package',
			'description'  => 'A high-performance additive package designed for the formulation of hydraulic fluids meeting Denison HF-0 and other current industry standards. A cost-effective, zinc-based anti-wear hydraulic additive incorporating a built-in defoamer, it provides exceptional wear protection, excellent oxidation stability, superior filterability and high contamination tolerance. It ensures extended pump durability in vane and piston systems and is compatible with a wide range of base stocks, including hydrotreated oils.',
			'performance_headers'   => 'Tier | Treat Rate %',
			'performance_rows_text' => "Top-Tier | 0.80%\nMid-Tier | 0.40%",
			'approvals_text' => "Denison HF-0 (Top-Tier)\nParker HF-0/1/2 (HV) (Top-Tier)\nMAG P70 (HV) (Top-Tier)\nDIN 51524-3 (HVLP) (Top-Tier)\nISO 11158 HV (Top-Tier)\nASTM D6158 HV (Top-Tier)\nSAE MS1004 HV (Top-Tier)\nBosch Rexroth RE 90220 (Top-Tier)\nSEB 181222 (Top-Tier)\nDIN 51524-2 (HLP) (Mid-Tier)\nParker HF-1/2 (HM) (Mid-Tier)\nEaton M-2950-S / I-286-S3 (Mid-Tier)\nMAG P68/P69 (HM) (Mid-Tier)\nISO 11158 HM (Mid-Tier)\nASTM D6158 HM (Mid-Tier)\nANSI/AGMA 9005-E02-RO (Mid-Tier)\nGM LS-2 (Mid-Tier)\nAIST 126/127 (Mid-Tier)",
			'properties_text' => "Appearance | Visual | Brown Clear Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 12 cSt\nDensity @ 15°C | ASTM D1298 | 1016 kg/m3\nFlash Point (COC) | ASTM D92 | 138 °C\nBase Number | ASTM D2896 | 65 mgKOH/g\nNitrogen | ASTM D3228 | 1.17 %mass\nCalcium | ASTM D5185 | 0.64 %mass\nPhosphorus | ASTM D5185 | 4.2 %mass\nZinc | ASTM D5185 | 5.2 %mass\nSulphur | ASTM D5185 | 8.6 %mass",
			'viscosity_note' => "ISO VG 32, 46, 68, 100, 150",
		),

		'KC420' => array(
			'title'        => 'ADDLAR KC420',
			'category'     => 'Metal Working Fluid',
			'subcategory'  => 'Neat Cutting',
			'doc_code'     => 'RCH/V1.1/KC420',
			'spec_string'  => 'Sulphurised Ester Neat Cutting Oil Additive Package',
			'description'  => 'A high-performance sulphurised ester specifically designed to provide exceptional load-bearing capability in metalworking fluids, while also exhibiting outstanding lubricity. One of its key advantages is its non-staining nature — it remains inactive towards copper, even at elevated temperatures. Ideally suited for formulating a wide range of neat cutting oils used in demanding applications such as gear hobbing, gear grinding, deep hole drilling, broaching and other severe metalworking operations.',
			'applications_text' => "Gear Hobbing\nGear Shaping and Grinding\nBroaching\nThread Cutting and Tapping\nDeep Hole Drilling (e.g. gun drilling)\nForming and Stamping (especially on tough materials like stainless steel)\nHeavy-Duty Turning and Milling\nCold Heading or Cold Forming\nSpline Rolling\nSawing Operations",
			'properties_text' => "Appearance | Visual | Clear Yellow Liquid\nKinematic Viscosity @ 40°C | ASTM D445 | 220 cSt\nDensity @ 15°C | ASTM D1298 | 998 kg/m3\nFlash Point (COC) | ASTM D92 | 140 °C\nSulphur | ASTM D5185 | 20 %mass\nCopper Corrosion (2% in 150SN, 100°C / 3h) | ASTM D130 | 1b Rating\nFour-Ball Weld Load (2% / 3% / 5% in 150SN) | ASTM D2783 | 315 / 620 / >800 kg\nSolubility (5% in 150SN) | Internal | Bright and Clear",
		),

		'KC562' => array(
			'title'        => 'ADDLAR KC562',
			'category'     => 'Driveline',
			'subcategory'  => 'Automotive Gear',
			'doc_code'     => 'RCH/V1.1/KC562',
			'spec_string'  => 'API GL-5/GL-4 · DIN 51517 Part III',
			'description'  => 'Designed to formulate high-quality gear oils that fully meet API GL-5 requirements (ASTM D7450-13). Ideal for a range of transmission and axle applications, providing excellent Extreme Pressure (EP) protection in industry-standard tests such as L-42 and L-37. The additive is easily soluble in different base stocks, helping with inventory management. It has been successfully tested in tough conditions and is an affordable, multifunctional gear oil additive suitable for both GL-4 transmissions and industrial gear oils.',
			'performance_note'      => 'Automotive',
			'performance_headers'   => 'Level | Treat Rate %',
			'performance_rows_text' => "API GL-5 | 3.90%\nAPI GL-5 + 1.0% ADDLAR KC560B (Booster, API MT-1 capable) | 3.90% + 1.0%\nAPI GL-4 | 1.50%",
			'formulation_label' => 'Industrial',
			'formulation_text'  => "DIN 51517 Part III: 0.90% treat rate",
			'approvals_text' => "ZF TE-ML 07A (GL-5)\nMAN M2342 capable (GL-5)\nChina GB 13895-1992 (GL-5)\nScania STO 1:0 (GL-5)\nBIS 1118:1992 (GL-4)\nAIST 224 (Industrial)\nAGMA 9005:E02 (Industrial)\nISO 12925-1 CKD (Industrial)\nDavid Brown S1.53.101 (Industrial)\nGM LS-2 (Industrial)\nCincinnati Machine (Gear) (Industrial)",
			'properties_text' => "Appearance | Visual | Clear Yellow Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 1.4 cSt\nDensity @ 15°C | ASTM D1298 | 1020 kg/m3\nFlash Point (COC) | ASTM D92 | 90 °C\nSulphur | ASTM D5185 | 30 %mass\nNitrogen | ASTM D3228 | 0.2 %mass\nPhosphorus | ASTM D5185 | 0.7 %mass\nSolubility (5% in 150SN) | Internal | Bright and Clear",
			'viscosity_note' => "Automotive: SAE 90, 140, 220, 250, 320, 75W-90, 80W-90, 80W-140, 85W-140, 75W-140\nIndustrial: ISO VG 100, 150, 220, 320, 460, 680, 1000",
		),

		'KC563' => array(
			'title'        => 'ADDLAR KC563',
			'category'     => 'Driveline',
			'subcategory'  => 'Automotive Gear',
			'doc_code'     => 'RCH/V1.1/KC563',
			'spec_string'  => 'API GL-5/GL-4 · DIN 51517 Part III (Odorless)',
			'description'  => 'A specially formulated odourless gear oil additive package designed for use with a wide range of base oils, including Group I, Group II, Group III, PAOs and other synthetic stocks. It offers excellent solubility and compatibility, making it highly versatile across formulations, and enables the development of gear oils meeting demanding specifications such as API GL-4, API GL-5, MT-1, AIST 224 (US Steel 224) and DIN 51517 Part III.',
			'performance_note'      => 'Automotive',
			'performance_headers'   => 'Level | Treat Rate %',
			'performance_rows_text' => "API GL-5 | 4.0%\nAPI GL-5 + 1.0% ADDLAR KC560B (Booster, API MT-1 capable) | 4.0% + 1.0%\nAPI GL-4 | 2.0%",
			'formulation_label' => 'Industrial',
			'formulation_text'  => "DIN 51517 Part III: 1.0% treat rate",
			'approvals_text' => "ZF TE-ML 07A (GL-5)\nMAN M2342 capable (GL-5)\nChina GB 13895-1992 (GL-5)\nScania STO 1:0 (GL-5)\nBIS 1118:1992 (GL-4)\nAIST 224 (Industrial)\nAGMA 9005:E02 (Industrial)\nISO 12925-1 CKD (Industrial)\nDavid Brown S1.53.101 (Industrial)\nGM LS-2 (Industrial)\nCincinnati Machine (Gear) (Industrial)",
			'properties_text' => "Appearance | Visual | Clear Yellow Liquid\nKinematic Viscosity @ 40°C | ASTM D445 | 45 cSt\nDensity @ 15°C | ASTM D1298 | 108 kg/m3\nFlash Point (COC) | ASTM D92 | 150 °C\nSulphur | ASTM D5185 | 16 %mass\nNitrogen | ASTM D3228 | 0.2 %mass\nPhosphorus | ASTM D5185 | 0.7 %mass\nSolubility (5% in 150SN) | Internal | Bright and Clear",
			'viscosity_note' => "Automotive: SAE 90, 140, 220, 250, 320, 75W-90, 80W-90, 80W-140, 85W-140, 75W-140\nIndustrial: ISO VG 100, 150, 220, 320, 460, 680, 1000",
		),

		'Z 2612' => array(
			'title'        => 'ADDLAR Z 2612',
			'category'     => 'Lubricant Component',
			'subcategory'  => 'Anti-wear & Friction Modifier',
			'doc_code'     => 'Z/V1.1/2612',
			'spec_string'  => 'Zinc Primary Alkyl Dithiophosphate (ZDDP)',
			'description'  => 'A zinc thiophosphate additive derived from thiophosphoric acid based on primary alcohols. When blended into oils it effectively inhibits oxidation, protects bearing surfaces from corrosion and reduces wear on cams and tappets. It offers excellent anti-oxidation, anti-wear and corrosion-inhibition performance, making it a versatile and comprehensive additive suitable for a wide range of applications.',
			'applications_text' => "Engine Oils (especially heavy-duty supercharged diesel)\nHydraulic Oils\nBearing Oils",
			'formulation_label' => 'Recommended Dosage',
			'formulation_text'  => "Dosage Range: 0.5% – 3.0% mass",
			'properties_text' => "Appearance | Visual | Bright & Clear Liquid\nKinematic Viscosity @ 100°C | ASTM D445 | 22 cSt\nDensity @ 15°C | ASTM D1298 | 1200 kg/m3\nFlash Point (COC) | ASTM D92 | 120 °C\nBase Number | ASTM D2896 | 145 mgKOH/g\nSulphur | ASTM D5185 | 4.8 %mass\nPhosphorus | ASTM D5185 | 9.20 %mass\nZinc | ASTM D5185 | 10.5 %mass",
		),

	);
}
