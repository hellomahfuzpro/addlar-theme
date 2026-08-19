<?php
/**
 * Insights — LinkedIn posts as one row each, artwork left, copy right.
 *
 * The post artwork is 4:5 and the card media box matches that ratio exactly,
 * so the graphics are never cropped. Entries are editable here precisely
 * because they date quickly.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Addlar_Widget_Insights extends Addlar_Base_Widget {

	public function get_name() {
		return 'addlar_insights';
	}

	public function get_title() {
		return __( 'ADDLAR Insights (LinkedIn)', 'addlar' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	private function default_posts() {
		// Sourced from addlar_linkedin_posts() (inc/linkedin-posts.php) so the
		// homepage, the seeder and the blog page cannot drift apart.
		$posts = array();
		foreach ( addlar_linkedin_posts() as $p ) {
			$posts[] = array(
				'kind'  => $p['kind'],
				'title' => $p['title'],
				'text'  => $p['text'],
				'link'  => array( 'url' => $p['url'], 'is_external' => 'on' ),
			);
		}
		return $posts;
	}

	protected function register_controls() {

		$this->start_controls_section( 'head', array(
			'label' => __( 'Heading', 'addlar' ),
		) );

		$this->add_control( 'anchor', array(
			'label'   => __( 'Anchor id', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'insights',
		) );

		$this->add_control( 'soft', array(
			'label'        => __( 'Soft background', 'addlar' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_heading_controls(
			__( 'From LinkedIn', 'addlar' ),
			__( 'Insights from the ADDLAR desk.', 'addlar' ),
			__( 'Technical explainers, product launches and formulation guidance — published to our LinkedIn showcase.', 'addlar' )
		);

		$this->end_controls_section();

		$this->start_controls_section( 'posts_section', array(
			'label' => __( 'Posts', 'addlar' ),
		) );

		$rep = new Repeater();
		$rep->add_control( 'image', array(
			'label'       => __( 'Post artwork', 'addlar' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'Use the graphic from the post itself. 4:5 portrait is shown uncropped.', 'addlar' ),
		) );
		$rep->add_control( 'kind', array( 'label' => __( 'Category label', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'title', array( 'label' => __( 'Post title', 'addlar' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => '' ) );
		$rep->add_control( 'text', array( 'label' => __( 'Summary', 'addlar' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 4, 'default' => '' ) );
		$rep->add_control( 'link', array(
			'label'       => __( 'Post URL', 'addlar' ),
			'type'        => Controls_Manager::URL,
			'description' => __( 'Link straight to the individual post, not the showcase feed.', 'addlar' ),
			'default'     => array( 'url' => '', 'is_external' => 'on' ),
		) );

		$this->add_control( 'posts', array(
			'label'       => __( 'Posts', 'addlar' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ kind }}}',
			'default'     => $this->default_posts(),
		) );

		$this->add_control( 'cta_read', array(
			'label'   => __( 'Card link text', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Read on LinkedIn', 'addlar' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'follow', array(
			'label' => __( 'Follow panel', 'addlar' ),
		) );

		$this->add_control( 'follow_title', array( 'label' => __( 'Title', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Follow ADDLAR on LinkedIn', 'addlar' ) ) );
		$this->add_control( 'follow_text', array( 'label' => __( 'Text', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Formulation notes, specification updates and product releases.', 'addlar' ) ) );
		$this->add_control( 'follow_btn', array( 'label' => __( 'Button', 'addlar' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Follow the showcase →', 'addlar' ) ) );
		$this->add_control( 'follow_link', array(
			'label'   => __( 'Button link', 'addlar' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => 'https://www.linkedin.com/showcase/addlar-lubricant-additives/', 'is_external' => 'on' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$this->open_section(
			'yes' === $s['soft'] ? 'section soft' : 'section',
			! empty( $s['anchor'] ) ? $s['anchor'] : ''
		);
		?>
		<div class="wrap center">
			<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
		</div>
		<div class="wrap">
			<div class="li-grid">
				<?php foreach ( (array) $s['posts'] as $post ) : ?>
					<?php
					$url    = ! empty( $post['link']['url'] ) ? $post['link']['url'] : '#';
					$target = ! empty( $post['link']['is_external'] ) ? ' target="_blank" rel="noopener"' : '';
					?>
					<a class="licard reveal" href="<?php echo esc_url( $url ); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput -- fixed attribute string. ?>>
						<div class="imgwrap">
							<?php $this->render_media( $post['image'], $post['title'], '', 'full' ); ?>
						</div>
						<div class="body">
							<span class="kind"><?php echo esc_html( $post['kind'] ); ?></span>
							<h3><?php echo esc_html( $post['title'] ); ?></h3>
							<p><?php echo esc_html( $post['text'] ); ?></p>
							<div class="go">
								<span><?php echo esc_html( $s['cta_read'] ); ?></span>
								<span>&rarr;</span>
							</div>
						</div>
					</a>
				<?php endforeach; ?>
			</div>

			<?php if ( ! empty( $s['follow_title'] ) ) : ?>
				<div class="li-follow reveal">
					<div class="lf-l">
						<div class="lf-ic"><?php addlar_icon_linkedin(); ?></div>
						<div>
							<h3><?php echo esc_html( $s['follow_title'] ); ?></h3>
							<p><?php echo esc_html( $s['follow_text'] ); ?></p>
						</div>
					</div>
					<?php $this->render_button( $s['follow_btn'], $s['follow_link'], 'btn-red' ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		$this->close_section();
	}
}
