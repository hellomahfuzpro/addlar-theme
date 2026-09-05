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
$person   = addlar_mod( 'addlar_contact_person' );
$role     = addlar_mod( 'addlar_contact_title' );
$mobile   = addlar_mod( 'addlar_phone_mobile' );
$office   = addlar_mod( 'addlar_phone_office' );
$address  = addlar_mod( 'addlar_address' );
?>

<div class="adl">
	<footer id="contact">
		<div class="wrap">
			<!-- Corporate Brief Lockup -->
			<div class="foot-banner">
				<div class="foot-brand-col">
					<div class="rchem-lockup">
						<span class="rchem-r">R</span>
						<div class="rchem-text">
							<span class="rchem-main">Rchemie</span>
							<span class="rchem-sub">International</span>
						</div>
					</div>
					<?php if ( $person || $role ) : ?>
						<div class="foot-contact-card">
							<?php if ( $person ) : ?>
								<h4 class="f-person"><?php echo esc_html( $person ); ?></h4>
							<?php endif; ?>
							<?php if ( $role ) : ?>
								<span class="f-role"><?php echo esc_html( $role ); ?></span>
							<?php endif; ?>
							<ul class="f-details">
								<?php if ( $mobile ) : ?>
									<li><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $mobile ) ); ?>"><?php echo esc_html( $mobile ); ?></a></li>
								<?php endif; ?>
								<?php if ( $email ) : ?>
									<li><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
								<?php endif; ?>
								<?php if ( $office ) : ?>
									<li><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $office ) ); ?>"><?php echo esc_html( $office ); ?></a></li>
								<?php endif; ?>
								<?php if ( $address ) : ?>
									<li><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> <?php echo esc_html( $address ); ?></li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>

				<div class="foot-badge-col">
					<div class="anniversary-badge">
						<span class="anniv-sub">Thank you for</span>
						<div class="anniv-num-wrap">
							<span class="anniv-20">20</span>
							<span class="anniv-tag-yr">YEARS</span>
							<span class="anniv-26">2026</span>
						</div>
						<div class="anniv-lock">
							<span class="anniv-tag">Depending on us...</span>
							<span class="anniv-brand">ADDLAR</span>
						</div>
					</div>
				</div>
			</div>

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
