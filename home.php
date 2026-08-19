<?php
/**
 * Blog listing.
 *
 * A coded template rather than an Elementor page: the posts loop is
 * inherently dynamic, and WordPress's "Posts page" setting takes over the
 * assigned page's content anyway, so there is nothing for Elementor to
 * edit here.
 *
 * Two clearly separated bands, because the page mixes two different kinds
 * of thing and a reader should never have to guess which is which:
 *   1. "From LinkedIn" — the same featured posts as the homepage, sourced
 *      from addlar_linkedin_posts() so the two can't drift apart. These
 *      leave the site, so they open in a new tab and say so.
 *   2. "Articles" — posts published here in WordPress, on a soft ground to
 *      separate the band visually as well as by label.
 *
 * @package Addlar
 */

get_header();

$blog_id = (int) get_option( 'page_for_posts' );
$title   = $blog_id ? get_the_title( $blog_id ) : __( 'Insights', 'addlar' );
$li_url  = addlar_mod( 'addlar_linkedin_url' );
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
				<p class="lead"><?php esc_html_e( 'Formulation notes, specification updates and technical discussion from our chemists — here and on LinkedIn.', 'addlar' ); ?></p>
				<div class="prod-hero-btns">
					<a class="btn btn-red" href="<?php echo esc_url( home_url( '/ask-the-expert/' ) ); ?>"><?php esc_html_e( 'Ask the expert →', 'addlar' ); ?></a>
					<?php if ( $li_url ) : ?>
						<a class="btn btn-white" href="<?php echo esc_url( $li_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Follow on LinkedIn', 'addlar' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<?php /* -------------------------------------------------- LinkedIn */ ?>
	<?php $li_posts = function_exists( 'addlar_linkedin_posts' ) ? addlar_linkedin_posts() : array(); ?>
	<?php if ( $li_posts ) : ?>
		<section class="section">
			<div class="wrap">
				<span class="eyebrow"><?php esc_html_e( 'From LinkedIn', 'addlar' ); ?></span>
				<h2 class="title band-title"><?php esc_html_e( 'Discussion on our showcase page.', 'addlar' ); ?></h2>
				<p class="band-note"><?php esc_html_e( 'These open on LinkedIn in a new tab.', 'addlar' ); ?></p>

				<div class="li-grid">
					<?php foreach ( $li_posts as $p ) : ?>
						<a class="licard reveal" href="<?php echo esc_url( $p['url'] ); ?>" target="_blank" rel="noopener">
							<div class="imgwrap">
								<img src="<?php echo esc_url( addlar_linkedin_image_url( $p ) ); ?>" alt="" loading="lazy">
							</div>
							<div class="body">
								<span class="kind"><?php echo esc_html( $p['kind'] ); ?></span>
								<h3><?php echo esc_html( $p['title'] ); ?></h3>
								<p><?php echo esc_html( $p['text'] ); ?></p>
								<div class="go">
									<span><?php esc_html_e( 'Read on LinkedIn', 'addlar' ); ?></span>
									<span class="arw">&rarr;</span>
								</div>
							</div>
						</a>
					<?php endforeach; ?>
				</div>

				<?php if ( $li_url ) : ?>
					<div class="li-follow reveal">
						<div class="lf-l">
							<div class="lf-ic"><?php addlar_icon_linkedin(); ?></div>
							<div>
								<h3><?php esc_html_e( 'Follow ADDLAR on LinkedIn', 'addlar' ); ?></h3>
								<p><?php esc_html_e( 'Formulation notes, specification updates and product releases.', 'addlar' ); ?></p>
							</div>
						</div>
						<a class="btn btn-red" href="<?php echo esc_url( $li_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Follow the showcase →', 'addlar' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php /* -------------------------------------------------- Articles */ ?>
	<section class="section soft">
		<div class="wrap">
			<span class="eyebrow"><?php esc_html_e( 'Articles', 'addlar' ); ?></span>
			<h2 class="title band-title"><?php esc_html_e( 'Published on this site.', 'addlar' ); ?></h2>

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
									<span><?php esc_html_e( 'Read article', 'addlar' ); ?></span>
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
				<p class="band-note band-empty">
					<?php esc_html_e( 'No articles published yet — the first one will appear here. In the meantime, the discussion above is running on LinkedIn.', 'addlar' ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

</div>
<?php
get_footer();
