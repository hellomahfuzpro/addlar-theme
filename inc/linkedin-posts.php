<?php
/**
 * The ADDLAR LinkedIn posts featured on the site.
 *
 * Single source of truth. These rows were previously written out twice —
 * once as the Insights widget's control defaults and again in the seeder —
 * and the blog page now needs them a third time. Defining them once here
 * means updating a post is one edit, not three that can drift apart.
 *
 * Consumed by:
 *   - Addlar_Widget_Insights  (homepage section's control defaults)
 *   - addlar_insight_rows()   (seeder, attaches imported media)
 *   - home.php                (blog page's "From LinkedIn" band)
 *
 * Override the whole set from a child theme or plugin with the
 * `addlar_linkedin_posts` filter.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array Each: slot, file, kind, title, text, url.
 */
function addlar_linkedin_posts() {
	return apply_filters( 'addlar_linkedin_posts', array(
		array(
			'slot'  => 'li-1',
			'file'  => 'li-1.jpg',
			'kind'  => __( 'Heavy-Duty Diesel', 'addlar' ),
			'title' => __( 'ADDLAR on Soot Control: why modern HDEO formulation requires a balanced additive system', 'addlar' ),
			'text'  => __( 'Soot control is more than dispersant chemistry. Balancing soot handling, oxidation stability, detergency, anti-wear and viscosity retention across EGR-equipped, high-load duty cycles — with ADDLAR 7750, 7730 and 7706.', 'addlar' ),
			'url'   => 'https://www.linkedin.com/posts/addlar-lubricant-additives_hdeo-sootcontrol-dieselengineoil-activity-7485585693746966528-uZnF',
		),
		array(
			'slot'  => 'li-2',
			'file'  => 'li-2.jpg',
			'kind'  => __( 'Gear Oils', 'addlar' ),
			'title' => __( 'ADDLAR KC562: EP chemistry for high-load gear oil formulations', 'addlar' ),
			'text'  => __( 'Gear oils face high torque, sliding contact and shock loading. How KC562 meets API GL-5 and GL-4 across automotive axle, transmission and industrial gear oils — verified on the FZG S-A10/16.6R/90 test.', 'addlar' ),
			'url'   => 'https://www.linkedin.com/posts/addlar-lubricant-additives_%F0%9D%97%94%F0%9D%97%97%F0%9D%97%97%F0%9D%97%9F%F0%9D%97%94%F0%9D%97%A5-kc562-activity-7485944178733043713-5nDv',
		),
		array(
			'slot'  => 'li-3',
			'file'  => 'li-3.jpg',
			'kind'  => __( 'Base Oils', 'addlar' ),
			'title' => __( 'Switching from Group I to Group II or Group III base oils?', 'addlar' ),
			'text'  => __( "An additive package performs differently across base oil groups. Why additive strategy can't be separated from base oil strategy — across oxidation stability, solubility, low-temperature flow and seal compatibility.", 'addlar' ),
			'url'   => 'https://www.linkedin.com/posts/addlar-lubricant-additives_lubricantformulation-baseoil-tribology-activity-7483771438290681857-DgMW',
		),
	) );
}

/**
 * Image URL for a LinkedIn post, for front-end templates.
 *
 * Read-only on purpose: prefers the copy the seeder already imported into
 * the Media Library (so a replaced image is honoured), and falls back to
 * the file bundled with the theme. It never imports anything — that would
 * be a media sideload on a visitor's page request.
 *
 * @param array $post Row from addlar_linkedin_posts().
 * @return string
 */
function addlar_linkedin_image_url( array $post ) {
	$option = defined( 'ADDLAR_SEED_CACHE' ) ? ADDLAR_SEED_CACHE : 'addlar_seeded_images';
	$cache  = get_option( $option, array() );

	if ( ! empty( $cache[ $post['slot'] ] ) ) {
		$url = wp_get_attachment_image_url( (int) $cache[ $post['slot'] ], 'medium_large' );
		if ( $url ) {
			return $url;
		}
	}

	return ADDLAR_URI . '/assets/images/' . $post['file'];
}
