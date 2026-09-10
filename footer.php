<?php
/**
 * Footer chrome — Stitch Contact banner with inquiry form & deep dark industrial footer.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$linkedin = addlar_mod( 'addlar_linkedin_url' );
$youtube  = addlar_mod( 'addlar_youtube_url' );
$email    = addlar_mod( 'addlar_email' );
if ( empty( $email ) || 'info@rchemie.com' === $email ) {
	$email = 'info@addlar-rc.com';
}
$mark = addlar_mod( 'addlar_footer_mark' );
if ( empty( $mark ) ) {
	$mark = get_template_directory_uri() . '/assets/images/addlar-mark-white.png';
}
?>

<div class="adl">
	<!-- CONTACT BANNER (Red Gradient) -->
	<section class="contact-banner" id="contact">
		<!-- Atmospheric industrial graphic watermark -->
		<div class="contact-watermark" aria-hidden="true">
			<svg viewBox="0 0 200 200" fill="none" stroke="currentColor">
				<circle cx="100" cy="100" r="80" stroke-width="12" stroke-dasharray="10 15" />
				<circle cx="100" cy="100" r="50" stroke-width="8" />
				<path d="M100 20 L100 180 M20 100 L180 100" stroke-width="6" />
			</svg>
		</div>

		<div class="contact-container">
			<div class="contact-grid">
				<!-- LEFT: Details & Narrative -->
				<div class="contact-left">
					<div class="contact-intro">
						<span class="contact-badge"><?php esc_html_e( 'Direct Technical Line', 'addlar' ); ?></span>
						<h2 class="contact-title"><?php esc_html_e( 'Get in touch with us', 'addlar' ); ?></h2>
						<p class="contact-desc">
							<?php esc_html_e( "Partner with ADDLAR's formulation specialists. Reach out directly or leave a technical specification inquiry below to discuss your custom additive package requirements.", 'addlar' ); ?>
						</p>

						<div class="contact-items">
							<!-- Telephone -->
							<div class="contact-item">
								<div class="contact-item-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
								</div>
								<div class="contact-item-text">
									<span class="contact-item-lbl"><?php esc_html_e( 'Telephone', 'addlar' ); ?></span>
									<a href="tel:+971503074886" class="contact-item-val">+971 (50) 307 4886</a>
									<span class="contact-item-sub">+971 (6) 526 1816 (UAE HQ)</span>
								</div>
							</div>

							<!-- Email -->
							<div class="contact-item">
								<div class="contact-item-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
								</div>
								<div class="contact-item-text">
									<span class="contact-item-lbl"><?php esc_html_e( 'E-mail Inquiries', 'addlar' ); ?></span>
									<a href="mailto:<?php echo esc_attr( $email ); ?>" class="contact-item-val"><?php echo esc_html( $email ); ?></a>
									<span class="contact-item-sub">operations@rchemie.com</span>
								</div>
							</div>
						</div>
					</div>

					<div class="contact-hq">
						<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
						</svg>
						<span>WH# 1 J05/01 & 03, Al Hamriyah Free Zone, Sharjah, UAE</span>
					</div>
				</div>

				<!-- RIGHT: Form Card -->
				<div class="contact-right">
					<div class="contact-form-card">
						<div class="cf-header">
							<h3 class="cf-title"><?php esc_html_e( 'Leave Us a Message', 'addlar' ); ?></h3>
							<p><?php esc_html_e( 'Submit your specification request or general inquiry below.', 'addlar' ); ?></p>
						</div>

						<?php if ( isset( $_GET['addlar_sent'] ) && 'contact' === sanitize_key( wp_unslash( $_GET['addlar_sent'] ) ) ) : ?>
							<div class="form-success" style="padding:14px 18px;background:rgba(16,185,129,0.22);border:1px solid #10b981;border-radius:8px;color:#fff;margin-bottom:18px;font-size:14px;">
								<?php esc_html_e( 'Thank you! Your message has been sent successfully. Our formulation team will reach out promptly.', 'addlar' ); ?>
							</div>
						<?php elseif ( isset( $_GET['addlar_sent'] ) && 'error' === sanitize_key( wp_unslash( $_GET['addlar_sent'] ) ) ) : ?>
							<div class="form-error" style="padding:14px 18px;background:rgba(239,68,68,0.22);border:1px solid #ef4444;border-radius:8px;color:#fff;margin-bottom:18px;font-size:14px;">
								<?php esc_html_e( 'Something went wrong sending your message — please check the required fields and try again.', 'addlar' ); ?>
							</div>
						<?php endif; ?>

						<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="cf-form">
							<?php wp_nonce_field( 'addlar_contact_form', 'addlar_contact_nonce' ); ?>
							<input type="hidden" name="action" value="addlar_contact_submit">
							<input type="hidden" name="preset" value="contact">
							<input type="hidden" name="redirect_to" value="<?php echo esc_url( ( get_permalink() ? get_permalink() : home_url( '/' ) ) . '#contact' ); ?>">
							<input type="text" name="addlar_hp" value="" style="display:none;" tabindex="-1" autocomplete="off">

							<div class="cf-row">
								<div class="cf-field">
									<label for="cf-name"><?php esc_html_e( 'Your Name', 'addlar' ); ?> *</label>
									<input type="text" id="cf-name" name="name" placeholder="John Doe" class="cf-input" required>
								</div>
								<div class="cf-field">
									<label for="cf-company"><?php esc_html_e( 'Company Name', 'addlar' ); ?></label>
									<input type="text" id="cf-company" name="company" placeholder="Lubricant Formulators Inc." class="cf-input">
								</div>
							</div>

							<div class="cf-row">
								<div class="cf-field">
									<label for="cf-email"><?php esc_html_e( 'Email Address', 'addlar' ); ?> *</label>
									<input type="email" id="cf-email" name="email" placeholder="john@company.com" class="cf-input" required>
								</div>
								<div class="cf-field">
									<label for="cf-phone"><?php esc_html_e( 'Phone Number', 'addlar' ); ?></label>
									<input type="tel" id="cf-phone" name="phone" placeholder="+1 (555) 000-0000" class="cf-input">
								</div>
							</div>

							<div class="cf-field">
								<label for="cf-message"><?php esc_html_e( 'Your Message', 'addlar' ); ?></label>
								<textarea id="cf-message" name="message" rows="4" placeholder="<?php esc_attr_e( 'Specify your desired product grade (e.g., Heavy Duty Engine Oil, Driveline, Treat Rates, or TDS inquiry)...', 'addlar' ); ?>" class="cf-input cf-textarea"></textarea>
							</div>

							<div class="cf-actions">
								<!-- Mock reCAPTCHA badge -->
								<div class="cf-recaptcha">
									<div class="cf-rc-left">
										<input type="checkbox" id="footer-recaptcha" checked>
										<label for="footer-recaptcha">I'm not a robot</label>
									</div>
									<div class="cf-rc-brand">
										<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/></svg>
										<span>reCAPTCHA</span>
									</div>
								</div>

								<!-- Black Submit Button -->
								<button type="submit" class="cf-btn-submit">
									<span><?php esc_html_e( 'Send Message', 'addlar' ); ?></span>
									<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
									</svg>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- DEEP DARK INDUSTRIAL FOOTER -->
	<footer class="stitch-footer">
		<!-- Gear watermark bottom left -->
		<div class="stitch-footer-watermark" aria-hidden="true">
			<svg viewBox="0 0 200 200" fill="currentColor">
				<polygon points="100,10 180,55 180,145 100,190 20,145 20,55" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="12 6" />
				<polygon points="100,40 150,70 150,130 100,160 50,130 50,70" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="4" />
				<circle cx="100" cy="100" r="22" fill="#0B0D0F" stroke="currentColor" stroke-width="6" />
				<circle cx="100" cy="100" r="8" fill="currentColor" />
			</svg>
		</div>

		<div class="stitch-footer-inner">
			<div class="stitch-footer-grid">
				<!-- Column 1: Brand Wordmark & Intro -->
				<div class="stitch-col-brand">
					<div class="stitch-logo">
						<img src="<?php echo esc_url( $mark ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					</div>
					<p>
						<strong>ADDLAR™</strong>, engineered by Rchemie International, is the culmination of two decades of chemical formulation mastery. Formulated to provide lubricant blenders globally with superior treat rates, lighter viscometry stability, and uncompromising international standards.
					</p>
					<div class="stitch-badges">
						<span class="stitch-badge">
							<span class="stitch-badge-dot"></span>
							ISO 9001:2015 Certified
						</span>
						<span class="stitch-badge">
							UAE Blending Facility
						</span>
					</div>
				</div>

				<!-- Column 2: Quick Links -->
				<div class="stitch-col-links">
					<h4 class="stitch-head"><?php esc_html_e( 'Quick Links', 'addlar' ); ?></h4>
					<?php
					if ( has_nav_menu( 'footer-1' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'footer-1',
							'container'      => '',
							'items_wrap'     => '<ul class="stitch-links">%3$s</ul>',
							'depth'          => 1,
							'walker'         => new Addlar_Footer_Nav_Walker(),
						) );
					} else {
						?>
						<ul class="stitch-links">
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'addlar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'About Us', 'addlar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/products/' ) ); ?>"><?php esc_html_e( 'Products & Additives', 'addlar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/#applications' ) ); ?>"><?php esc_html_e( 'Applications', 'addlar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Insights & Articles', 'addlar' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'addlar' ); ?></a></li>
						</ul>
						<?php
					}
					?>
				</div>

				<!-- Column 3: Contact Details & Socials -->
				<div class="stitch-col-contact">
					<h4 class="stitch-head"><?php esc_html_e( 'Contact Details', 'addlar' ); ?></h4>
					<div class="stitch-contact-box">
						<p class="stitch-c-row">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
							<a href="tel:+97165261816">+971 6 526 1816</a>
						</p>
						<span class="stitch-c-sub">+971 50 307 4886 (Mobile)</span>

						<p class="stitch-c-row">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
							<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
						</p>
						<span class="stitch-c-sub">operations@rchemie.com</span>
					</div>

					<span class="stitch-social-title"><?php esc_html_e( 'Connect With Us', 'addlar' ); ?></span>
					<div class="stitch-social-icons">
						<a href="<?php echo esc_url( $linkedin ? $linkedin : 'https://www.linkedin.com/showcase/addlar-lubricant-additives/' ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'LinkedIn', 'addlar' ); ?>" class="stitch-soc-btn">
							<svg viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.6 1.6 0 0 0-1.6 1.6 1.6 1.6 0 0 0 1.6 1.6 1.6 1.6 0 0 0 1.6-1.6 1.6 1.6 0 0 0-1.6-1.6Z"/></svg>
						</a>
						<a href="#" aria-label="X (Twitter)" class="stitch-soc-btn">
							<svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
						</a>
						<a href="#" aria-label="Instagram" class="stitch-soc-btn">
							<svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
						</a>
						<?php if ( $youtube ) : ?>
							<a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'YouTube', 'addlar' ); ?>" class="stitch-soc-btn">
								<svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
							</a>
						<?php else : ?>
							<a href="#" aria-label="YouTube" class="stitch-soc-btn">
								<svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<!-- Column 4: Catalogue Download Box -->
				<div class="stitch-col-catalogue">
					<h4 class="stitch-head"><?php esc_html_e( 'ADDLAR™ CATALOGUE', 'addlar' ); ?></h4>
					<p class="stitch-catalogue-desc">
						<?php esc_html_e( 'Request the comprehensive', 'addlar' ); ?> <strong>ADDLAR™ Product Selection & Specification Guide</strong> (API, ACEA, ILSAC, JASO).
					</p>
					<div class="stitch-guide-card">
						<a href="<?php echo esc_url( home_url( '/products/#finder' ) ); ?>" class="stitch-btn-guide">
							<span><?php esc_html_e( 'Request Guide', 'addlar' ); ?></span>
							<svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
						</a>
						<span class="stitch-catalogue-sub"><?php esc_html_e( 'Includes treat rate & viscometry cascade guides', 'addlar' ); ?></span>
					</div>
				</div>
			</div>

			<!-- Sub-footer Bottom Bar: Privacy & Copyright -->
			<div class="stitch-bottom-bar">
				<div class="stitch-legal-links">
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
						?>
						<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">PRIVACY POLICY</a>
						<span class="stitch-legal-sep">|</span>
						<a href="<?php echo esc_url( home_url( '/terms-of-use/' ) ); ?>">TERMS OF USE</a>
						<span class="stitch-legal-sep">|</span>
						<a href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>">FAQS</a>
						<?php
					}
					?>
				</div>

				<div class="stitch-copyright">
					<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <strong>Rchemie International</strong>. <?php esc_html_e( 'All Rights Reserved.', 'addlar' ); ?></span>
				</div>
			</div>
		</div>
	</footer>
</div>

<?php wp_footer(); ?>
</body>
</html>

