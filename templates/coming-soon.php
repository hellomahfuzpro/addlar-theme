<?php
/**
 * Template Name: Coming Soon
 *
 * A standalone brand splash — no header or footer chrome. Assign it to a page
 * and set that page as the homepage (Settings → Reading), or assign it to the
 * seeded Home page to switch the live site into coming-soon mode. Remove the
 * template to restore the full homepage.
 *
 * Reuses the theme's tokens, buttons and contact settings, and the bundled
 * hero video, so it needs no configuration.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Editable copy — filterable so it can be changed without touching this file.
$cs_eyebrow = apply_filters( 'addlar_coming_soon_eyebrow', __( 'Launching Soon', 'addlar' ) );
$cs_heading = apply_filters( 'addlar_coming_soon_heading', __( 'Something powerful is taking shape.', 'addlar' ) );
$cs_text    = apply_filters( 'addlar_coming_soon_text', __( 'ADDLAR by Rchemie International — advanced lubricant additive technology. Our new site is on its way. In the meantime, our team is ready to talk formulations.', 'addlar' ) );

$cs_email    = function_exists( 'addlar_mod' ) ? addlar_mod( 'addlar_email' ) : get_option( 'admin_email' );
$cs_linkedin = function_exists( 'addlar_mod' ) ? addlar_mod( 'addlar_linkedin_url' ) : '';
$cs_youtube  = function_exists( 'addlar_mod' ) ? addlar_mod( 'addlar_youtube_url' ) : '';

$cs_uri     = get_template_directory_uri();
$cs_mark    = $cs_uri . '/assets/images/addlar-mark-white.png';
$cs_video   = $cs_uri . '/assets/video/hero-v3.mp4';
$cs_poster  = $cs_uri . '/assets/images/hero-v3-poster.jpg';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>document.documentElement.classList.add('js');</script>
<?php wp_head(); ?>
<style>
/* Page-specific layout. Brand colours/typography come from the theme tokens
 * loaded via wp_head(), scoped under .adl. */
.adl .cs { position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; overflow: hidden; background: var(--adl-ink); text-align: center; padding: 32px; }
.adl .cs-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: .28; }
.adl .cs::after { content: ''; position: absolute; inset: 0; background: linear-gradient(150deg, rgba(125, 15, 10, .72), rgba(13, 10, 10, .92) 72%); }
.adl .cs-inner { position: relative; z-index: 2; width: 100%; max-width: 640px; }
.adl .cs-mark { width: 62px; height: auto; margin: 0 auto 30px; display: block; }
.adl .cs .eyebrow { color: #fff; justify-content: center; }
.adl .cs h1 { font-size: clamp(32px, 6vw, 60px); color: #fff; margin: 20px 0 18px; }
.adl .cs p { font-size: 17px; line-height: 1.65; color: rgba(255, 255, 255, .8); max-width: 520px; margin: 0 auto; }
.adl .cs-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-top: 34px; }
.adl .cs-btns .btn-ghost { color: #fff; border-color: rgba(255, 255, 255, .35); }
.adl .cs-btns .btn-ghost:hover { background: #fff; color: var(--adl-ink); border-color: #fff; }
.adl .cs-social { display: flex; gap: 12px; justify-content: center; margin-top: 30px; }
.adl .cs-social a { width: 42px; height: 42px; border: 1.5px solid rgba(255, 255, 255, .35); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background .22s, border-color .22s; }
.adl .cs-social a svg { width: 18px; height: 18px; fill: #fff; }
.adl .cs-social a:hover { background: var(--adl-red); border-color: var(--adl-red); }
.adl .cs-foot { position: absolute; bottom: 24px; left: 0; right: 0; z-index: 2; text-align: center; font-size: 12.5px; letter-spacing: .04em; color: rgba(255, 255, 255, .5); }
</style>
</head>
<body <?php body_class( 'addlar-coming-soon' ); ?>>
<?php wp_body_open(); ?>

<div class="adl">
	<section class="cs">
		<video class="cs-bg" autoplay muted loop playsinline poster="<?php echo esc_url( $cs_poster ); ?>">
			<source src="<?php echo esc_url( $cs_video ); ?>" type="video/mp4">
		</video>

		<div class="cs-inner">
			<img class="cs-mark" src="<?php echo esc_url( $cs_mark ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<span class="eyebrow"><?php echo esc_html( $cs_eyebrow ); ?></span>
			<h1><?php echo esc_html( $cs_heading ); ?></h1>
			<p><?php echo esc_html( $cs_text ); ?></p>

			<div class="cs-btns">
				<?php if ( $cs_email ) : ?>
					<a class="btn btn-red" href="mailto:<?php echo esc_attr( $cs_email ); ?>"><?php esc_html_e( 'Get in touch', 'addlar' ); ?></a>
				<?php endif; ?>
				<?php if ( $cs_linkedin ) : ?>
					<a class="btn btn-ghost" href="<?php echo esc_url( $cs_linkedin ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Follow on LinkedIn', 'addlar' ); ?></a>
				<?php endif; ?>
			</div>

			<?php if ( $cs_linkedin || $cs_youtube ) : ?>
				<div class="cs-social">
					<?php if ( $cs_linkedin && function_exists( 'addlar_icon_linkedin' ) ) : ?>
						<a href="<?php echo esc_url( $cs_linkedin ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'LinkedIn', 'addlar' ); ?>"><?php addlar_icon_linkedin(); ?></a>
					<?php endif; ?>
					<?php if ( $cs_youtube && function_exists( 'addlar_icon_youtube' ) ) : ?>
						<a href="<?php echo esc_url( $cs_youtube ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'YouTube', 'addlar' ); ?>"><?php addlar_icon_youtube(); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="cs-foot">
			<?php
			/* translators: %s: year */
			echo esc_html( sprintf( __( '© %s Rchemie International', 'addlar' ), gmdate( 'Y' ) ) );
			?>
		</div>
	</section>
</div>

<?php wp_footer(); ?>
</body>
</html>
