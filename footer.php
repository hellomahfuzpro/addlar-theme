<?php
/**
 * Footer chrome — four columns, social row, red-ruled bottom bar.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$columns = array(
	'footer-1' => addlar_mod( 'addlar_foot_head_1' ),
	'footer-2' => addlar_mod( 'addlar_foot_head_2' ),
	'footer-3' => addlar_mod( 'addlar_foot_head_3' ),
);

$linkedin = addlar_mod( 'addlar_linkedin_url' );
$youtube  = addlar_mod( 'addlar_youtube_url' );
$email    = addlar_mod( 'addlar_email' );
$website  = addlar_mod( 'addlar_website' );
$mark     = addlar_mod( 'addlar_footer_mark' );
?>

<div class="adl">
	<footer id="contact">
		<div class="wrap">
			<?php if ( $mark ) : ?>
				<div class="foot-mark">
					<img src="<?php echo esc_url( $mark ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				</div>
			<?php endif; ?>

			<div class="foot-grid">
				<?php foreach ( $columns as $location => $heading ) : ?>
					<div class="foot-col">
						<?php if ( $heading ) : ?>
							<h5><?php echo esc_html( $heading ); ?></h5>
						<?php endif; ?>
						<?php
						// Designed defaults until a menu is assigned.
						if ( has_nav_menu( $location ) ) {
							wp_nav_menu( array(
								'theme_location' => $location,
								'container'      => '',
								'items_wrap'     => '<ul>%3$s</ul>',
								'depth'          => 1,
								'walker'         => new Addlar_Footer_Nav_Walker(),
							) );
						} else {
							addlar_render_default_footer_column( $location );
						}
						?>
					</div>
				<?php endforeach; ?>

				<div class="foot-col">
					<?php $social_head = addlar_mod( 'addlar_foot_head_4' ); ?>
					<?php if ( $social_head ) : ?>
						<h5><?php echo esc_html( $social_head ); ?></h5>
					<?php endif; ?>

					<?php if ( $linkedin || $youtube ) : ?>
						<div class="socials">
							<?php if ( $linkedin ) : ?>
								<a class="soc" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'LinkedIn', 'addlar' ); ?>"><?php addlar_icon_linkedin(); ?></a>
							<?php endif; ?>
							<?php if ( $youtube ) : ?>
								<a class="soc" href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'YouTube', 'addlar' ); ?>"><?php addlar_icon_youtube(); ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $email || $website ) : ?>
						<ul style="margin-top:20px">
							<?php if ( $email ) : ?>
								<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
							<?php endif; ?>
							<?php if ( $website ) : ?>
								<li><a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener"><?php echo esc_html( preg_replace( '#^https?://#', '', untrailingslashit( $website ) ) ); ?></a></li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="foot-bottom">
			<div class="wrap">
				<div class="copy">
					<?php echo wp_kses_post( addlar_mod( 'addlar_copyright' ) ); ?>
				</div>
				<div class="legal">
					<?php
					if ( has_nav_menu( 'legal' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'legal',
							'container'      => '',
							'items_wrap'     => '%3$s',
							'depth'          => 1,
							'walker'         => new Addlar_Mobile_Nav_Walker(),
						) );
					} else {
						addlar_render_default_legal();
					}
					?>
				</div>
			</div>
		</div>
	</footer>
</div>

<?php wp_footer(); ?>
</body>
</html>
