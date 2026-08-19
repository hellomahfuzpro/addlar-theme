<?php
/**
 * Blog listing.
 *
 * A coded template rather than an Elementor page: the posts loop is
 * inherently dynamic, and WordPress's "Posts page" setting takes over the
 * assigned page's content anyway, so there is nothing for Elementor to
 * edit here.
 *
 * Uses the same components as the rest of the site — the dark hero with
 * its breadcrumb, and the homepage Insights section's `.li-grid`/`.licard`
 * cards for the post list — so the blog doesn't read as a different site.
 *
 * @package Addlar
 */

get_header();

$blog_id = (int) get_option( 'page_for_posts' );
$title   = $blog_id ? get_the_title( $blog_id ) : __( 'Insights', 'addlar' );
?>
<div class="adl">

	<section class="prod-hero">
		<div class="prod-hero-wedge" aria-hidden="true"></div>
		<div class="wrap prod-hero-crumbs">
			<nav class="crumbs crumbs-dark crumbs-bare" aria-label="<?php esc_attr_e( 'Breadcrumb', 'addlar' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'addlar' ); ?></a>
				<span class="sep" aria-hidden="true">&rsaquo;</span>
				<span class="cur"><?php echo esc_html( $title ); ?></span>
			</nav>
		</div>
		<div class="wrap prod-hero-grid">
			<div class="prod-hero-copy">
				<span class="eyebrow"><?php esc_html_e( 'From the ADDLAR desk', 'addlar' ); ?></span>
				<h1><?php echo esc_html( $title ); ?></h1>
				<p class="lead"><?php esc_html_e( 'Formulation notes, specification updates and technical discussion from our chemists.', 'addlar' ); ?></p>
				<div class="prod-hero-btns">
					<a class="btn btn-red" href="<?php echo esc_url( home_url( '/ask-the-expert/' ) ); ?>"><?php esc_html_e( 'Ask the expert →', 'addlar' ); ?></a>
					<?php $li = addlar_mod( 'addlar_linkedin_url' ); ?>
					<?php if ( $li ) : ?>
						<a class="btn btn-white" href="<?php echo esc_url( $li ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Follow on LinkedIn', 'addlar' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<main class="section">
		<div class="wrap">
			<?php if ( have_posts() ) : ?>
				<div class="li-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<a class="licard reveal" href="<?php the_permalink(); ?>">
							<div class="imgwrap">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large' ); ?>
								<?php endif; ?>
							</div>
							<div class="body">
								<span class="kind">
									<?php
									$cats = get_the_category();
									echo esc_html( $cats ? $cats[0]->name : get_the_date() );
									?>
								</span>
								<h3><?php the_title(); ?></h3>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32 ) ); ?></p>
								<div class="go">
									<span><?php esc_html_e( 'Read more', 'addlar' ); ?></span>
									<span class="arw">&rarr;</span>
								</div>
							</div>
						</a>
						<?php
					endwhile;
					?>
				</div>

				<?php
				the_posts_pagination( array(
					'mid_size'  => 1,
					'prev_text' => __( '&larr; Newer', 'addlar' ),
					'next_text' => __( 'Older &rarr;', 'addlar' ),
				) );
				?>

			<?php else : ?>
				<div class="center">
					<h2 class="title"><?php esc_html_e( 'Nothing published yet.', 'addlar' ); ?></h2>
					<p class="lead"><?php esc_html_e( 'Technical notes and formulation insights will appear here. In the meantime, our latest discussion happens on LinkedIn.', 'addlar' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</main>

</div>
<?php
get_footer();
