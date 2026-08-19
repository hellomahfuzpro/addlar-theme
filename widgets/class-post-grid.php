<?php
/**
 * Blog post grid — the articles list as an Elementor widget.
 *
 * This exists so the blog page can be a normal, editable Elementor page
 * rather than a coded template. WordPress's "Posts page" setting
 * (Settings → Reading) hands the URL to `home.php` and ignores the
 * assigned page's own content entirely — so as long as that setting points
 * at /blog/, nothing you arrange in Elementor there would ever render.
 * The seeder therefore clears it and lets /blog/ be an ordinary page, with
 * this widget running the posts query.
 *
 * Renders the same `.li-grid`/`.licard` cards the homepage Insights
 * section uses, so articles and LinkedIn posts sit together consistently.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Addlar_Widget_PostGrid extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_post_grid';
	}

	public function get_title() {
		return __( 'ADDLAR Post Grid', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array(
			'label' => __( 'Content', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Articles', 'addlar' ),
		) );

		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Published on this site.', 'addlar' ),
		) );

		$this->add_control( 'count', array(
			'label'   => __( 'Posts per page', 'addlar' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 9,
			'min'     => 1,
			'max'     => 36,
		) );

		$this->add_control( 'category', array(
			'label'       => __( 'Limit to category slug', 'addlar' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'description' => __( 'Leave blank to show every category.', 'addlar' ),
		) );

		$this->add_control( 'pagination', array(
			'label'        => __( 'Show pagination', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'empty_text', array(
			'label'   => __( 'Message when there are no posts', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 3,
			'default' => __( 'No articles published yet — the first one will appear here.', 'addlar' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$count = ! empty( $s['count'] ) ? (int) $s['count'] : 9;

		// On a static page WordPress supplies the page number as `page`;
		// the /blog/page/2/ rewrite supplies `paged`. Read both so
		// pagination works whichever route the visitor arrived by.
		$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $count,
			'paged'          => $paged,
			'ignore_sticky_posts' => true,
		);
		if ( ! empty( $s['category'] ) ) {
			$args['category_name'] = sanitize_title( $s['category'] );
		}

		$q = new WP_Query( $args );

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<div class="wrap">
			<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
				<span class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $s['title'] ) ) : ?>
				<h2 class="title band-title"><?php echo esc_html( $s['title'] ); ?></h2>
			<?php endif; ?>

			<?php if ( $q->have_posts() ) : ?>
				<div class="li-grid">
					<?php
					while ( $q->have_posts() ) :
						$q->the_post();
						$cats = get_the_category();
						?>
						<a class="licard reveal" href="<?php the_permalink(); ?>">
							<div class="imgwrap">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large' ); ?>
								<?php endif; ?>
							</div>
							<div class="body">
								<span class="kind"><?php echo esc_html( $cats ? $cats[0]->name : get_the_date() ); ?></span>
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

				<?php if ( 'yes' === $s['pagination'] && $q->max_num_pages > 1 ) : ?>
					<nav class="navigation pagination">
						<div class="nav-links">
							<?php
							echo wp_kses_post( paginate_links( array(
								'base'      => trailingslashit( get_permalink() ) . 'page/%#%/',
								'format'    => '',
								'total'     => $q->max_num_pages,
								'current'   => $paged,
								'mid_size'  => 1,
								'prev_text' => __( '&larr; Newer', 'addlar' ),
								'next_text' => __( 'Older &rarr;', 'addlar' ),
							) ) );
							?>
						</div>
					</nav>
				<?php endif; ?>

			<?php elseif ( ! empty( $s['empty_text'] ) ) : ?>
				<p class="band-note band-empty"><?php echo esc_html( $s['empty_text'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		$this->close_section();
		wp_reset_postdata();
	}
}
