<?php
/**
 * Header chrome — mirrors the mockup's fixed header + mobile panel.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php // Gates the reveal animation so content is never hidden when JS is off. ?>
<script>document.documentElement.classList.add('js');</script>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="adl">

	<header id="hdr">
		<div class="wrap nav">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				if ( has_custom_logo() ) {
					$logo_id = get_theme_mod( 'custom_logo' );
					echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) );
				} else {
					echo esc_html( get_bloginfo( 'name' ) );
				}
				?>
			</a>

			<?php
			// Falls back to the designed navigation until a menu is assigned,
			// so a fresh install is never left with an empty header.
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location'  => 'primary',
					'container'       => 'nav',
					'container_class' => 'navlinks',
					'items_wrap'      => '%3$s',
					'depth'           => 2,
					'walker'          => new Addlar_Nav_Walker(),
				) );
			} else {
				addlar_render_default_primary();
			}
			?>

			<div class="navcta">
				<?php
				$linkedin = addlar_mod( 'addlar_linkedin_url' );
				if ( $linkedin ) :
					?>
					<a class="ln" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'ADDLAR on LinkedIn', 'addlar' ); ?>">
						<?php addlar_icon_linkedin(); ?>
					</a>
				<?php endif; ?>

				<?php
				$cta_label = addlar_mod( 'addlar_cta_label' );
				$cta_link  = addlar_mod( 'addlar_cta_link' );
				if ( $cta_label ) :
					?>
					<a class="btn btn-red" href="<?php echo esc_url( $cta_link ); ?>"><?php echo esc_html( $cta_label ); ?></a>
				<?php endif; ?>

				<button class="burger" id="burger" aria-label="<?php esc_attr_e( 'Menu', 'addlar' ); ?>" aria-controls="mobnav" aria-expanded="false">
					<span></span><span></span><span></span>
				</button>
			</div>
		</div>
	</header>

	<div class="mobnav" id="mobnav">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => '',
				'items_wrap'     => '%3$s',
				'depth'          => 1,
				'walker'         => new Addlar_Mobile_Nav_Walker(),
			) );
		} else {
			addlar_render_default_mobile();
		}
		if ( $cta_label ) :
			?>
			<a class="btn btn-red" href="<?php echo esc_url( $cta_link ); ?>"><?php echo esc_html( $cta_label ); ?></a>
		<?php endif; ?>
	</div>

</div><?php // /.adl — closed here on purpose. Each widget emits its own .adl
// wrapper, so page content (including any Elementor widget the client adds
// later) stays outside our reset. ?>
