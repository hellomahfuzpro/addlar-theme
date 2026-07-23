<?php
/**
 * 404.
 *
 * @package Addlar
 */
get_header();
?>
<div class="adl"><main class="section center"><div class="wrap">
	<span class="eyebrow"><?php esc_html_e( 'Error 404', 'addlar' ); ?></span>
	<h2 class="title"><?php esc_html_e( 'That page could not be found.', 'addlar' ); ?></h2>
	<p class="lead"><?php esc_html_e( 'The page you are looking for may have moved or no longer exists.', 'addlar' ); ?></p>
	<p style="margin-top:28px"><a class="btn btn-red" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'addlar' ); ?> &rarr;</a></p>
</div></main></div>
<?php
get_footer();
