<?php
/**
 * Fallback template.
 *
 * @package Addlar
 */
get_header();
?>
<div class="adl"><main class="section"><div class="wrap">
<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
		<article <?php post_class(); ?>>
			<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="lead"><?php the_excerpt(); ?></div>
		</article>
		<?php
	}
	the_posts_pagination();
} else {
	echo '<p class="lead">' . esc_html__( 'Nothing found.', 'addlar' ) . '</p>';
}
?>
</div></main></div>
<?php
get_footer();
