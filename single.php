<?php
/**
 * Single blog post.
 *
 * Dark hero (breadcrumb, category, title, meta), a readable measure for
 * the article body, then the same red closing band the rest of the site
 * ends on. `.post-body` styling lives in theme.css — WordPress content can
 * contain any block, so it needs real element styling rather than the
 * homepage's fixed section components.
 *
 * @package Addlar
 */

get_header();

while ( have_posts() ) {
	the_post();

	$blog_id  = (int) get_option( 'page_for_posts' );
	$blog_url = $blog_id ? get_permalink( $blog_id ) : home_url( '/' );
	$cats     = get_the_category();
	?>
	<div class="adl">

		<section class="prod-hero">
			<div class="prod-hero-wedge" aria-hidden="true"></div>
			<div class="wrap prod-hero-crumbs">
				<nav class="crumbs crumbs-dark crumbs-bare" aria-label="<?php esc_attr_e( 'Breadcrumb', 'addlar' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'addlar' ); ?></a>
					<span class="sep" aria-hidden="true">&rsaquo;</span>
					<a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( $blog_id ? get_the_title( $blog_id ) : __( 'Insights', 'addlar' ) ); ?></a>
					<span class="sep" aria-hidden="true">&rsaquo;</span>
					<span class="cur"><?php the_title(); ?></span>
				</nav>
			</div>
			<div class="wrap prod-hero-grid">
				<div class="prod-hero-copy">
					<?php if ( $cats ) : ?>
						<span class="eyebrow"><?php echo esc_html( $cats[0]->name ); ?></span>
					<?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<div class="post-meta">
						<?php echo esc_html( get_the_date() ); ?>
						<span class="sep" aria-hidden="true">&middot;</span>
						<?php echo esc_html( get_the_author() ); ?>
					</div>
				</div>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="prod-hero-media reveal">
						<?php the_post_thumbnail( 'large', array( 'class' => 'prod-hero-img' ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<main class="section">
			<div class="wrap" style="max-width:760px;">
				<article <?php post_class( 'post-body' ); ?>>
					<?php the_content(); ?>
				</article>

				<?php
				wp_link_pages( array(
					'before' => '<div class="post-pages">',
					'after'  => '</div>',
				) );
				?>

				<?php $tags = get_the_tags(); ?>
				<?php if ( $tags ) : ?>
					<ul class="chip-list" style="margin-top:32px;">
						<?php foreach ( $tags as $tag ) : ?>
							<li class="chip"><a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="post-nav">
					<?php previous_post_link( '%link', '&larr; ' . esc_html__( 'Previous', 'addlar' ) ); ?>
					<a href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'All posts', 'addlar' ); ?></a>
					<?php next_post_link( '%link', esc_html__( 'Next', 'addlar' ) . ' &rarr;' ); ?>
				</div>
			</div>
		</main>

		<section class="cta">
			<div class="wrap">
				<div class="reveal">
					<span class="eyebrow" style="color:#fff"><?php esc_html_e( 'Talk to a formulator', 'addlar' ); ?></span>
					<h2><?php esc_html_e( 'Have a question on this?', 'addlar' ); ?></h2>
					<p><?php esc_html_e( 'Our technical team answers formulation, treat rate and specification questions directly.', 'addlar' ); ?></p>
					<a class="btn btn-white" href="<?php echo esc_url( home_url( '/ask-the-expert/' ) ); ?>"><?php esc_html_e( 'Ask the expert →', 'addlar' ); ?></a>
				</div>
			</div>
		</section>

	</div>
	<?php
}

get_footer();
