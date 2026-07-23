<?php
/**
 * Shared base for every ADDLAR section widget.
 *
 * Gives each widget the panel category plus the small render helpers that
 * reproduce the mockup's markup (eyebrow / h2.title / p.lead, buttons, icons,
 * and the `.adl` scope wrapper).
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

abstract class Addlar_Base_Widget extends Widget_Base {

	public function get_categories() {
		return array( 'addlar' );
	}

	public function get_icon() {
		return 'eicon-parallax';
	}

	/**
	 * Open the scope wrapper + section element.
	 *
	 * Every widget is full-bleed and owns its own <section>; the seeder wraps
	 * each in a zero-padding full-width Elementor container.
	 *
	 * @param string $classes Section classes, e.g. 'section soft'.
	 * @param string $id      Optional anchor id.
	 */
	protected function open_section( $classes = 'section', $id = '' ) {
		printf(
			'<div class="adl"><section class="%1$s"%2$s>',
			esc_attr( $classes ),
			$id ? ' id="' . esc_attr( $id ) . '"' : ''
		);
	}

	protected function close_section() {
		echo '</section></div>';
	}

	/** eyebrow + h2.title + p.lead, matching the mockup. */
	protected function render_heading( $eyebrow, $title, $lede ) {
		if ( $eyebrow ) {
			printf( '<span class="eyebrow">%s</span>', esc_html( $eyebrow ) );
		}
		if ( $title ) {
			printf( '<h2 class="title">%s</h2>', wp_kses_post( $title ) );
		}
		if ( $lede ) {
			printf( '<p class="lead">%s</p>', wp_kses_post( $lede ) );
		}
	}

	/** Reusable eyebrow/title/lede controls. */
	protected function add_heading_controls( $eyebrow = '', $title = '', $lede = '' ) {
		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'addlar' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $eyebrow,
		) );
		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'default' => $title,
		) );
		$this->add_control( 'lede', array(
			'label'   => __( 'Lede', 'addlar' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => $lede,
		) );
	}

	/**
	 * Image from an Elementor MEDIA control, or nothing when unset.
	 *
	 * @param array  $image Elementor media value.
	 * @param string $alt   Alt text.
	 * @param string $class Optional class.
	 * @param string $size  Registered image size.
	 */
	protected function render_media( $image, $alt = '', $class = '', $size = 'large' ) {
		$url = '';
		if ( ! empty( $image['id'] ) ) {
			$url = wp_get_attachment_image_url( (int) $image['id'], $size );
		}
		if ( ! $url && ! empty( $image['url'] ) ) {
			$url = $image['url'];
		}
		if ( ! $url ) {
			return;
		}
		printf(
			'<img src="%1$s" alt="%2$s" loading="lazy"%3$s>',
			esc_url( $url ),
			esc_attr( $alt ),
			$class ? ' class="' . esc_attr( $class ) . '"' : ''
		);
	}

	/** Resolve a MEDIA control to a plain URL. */
	protected function media_url( $image, $size = 'large' ) {
		if ( ! empty( $image['id'] ) ) {
			$url = wp_get_attachment_image_url( (int) $image['id'], $size );
			if ( $url ) {
				return $url;
			}
		}
		return ! empty( $image['url'] ) ? $image['url'] : '';
	}

	/** Button from a text + Elementor URL control. */
	protected function render_button( $text, $link, $style = 'btn-red' ) {
		if ( empty( $text ) ) {
			return;
		}
		$url      = ! empty( $link['url'] ) ? $link['url'] : '#';
		$target   = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
		$nofollow = ! empty( $link['nofollow'] ) ? ' rel="nofollow"' : '';
		printf(
			'<a class="btn %1$s" href="%2$s"%3$s%4$s>%5$s</a>',
			esc_attr( $style ),
			esc_url( $url ),
			esc_attr( $target ) ? $target : '',
			esc_attr( $nofollow ) ? $nofollow : '',
			esc_html( $text )
		);
	}

	/**
	 * Inline line-icon SVG.
	 *
	 * Paths come from inc/icons.php and are theme-authored (not user input),
	 * so raw echo is safe here.
	 *
	 * @param string $key   Icon slug.
	 * @param string $extra Extra attributes for the <svg> tag.
	 */
	protected function render_icon( $key, $extra = '' ) {
		printf(
			'<svg viewBox="0 0 24 24" aria-hidden="true"%1$s>%2$s</svg>',
			$extra ? ' ' . $extra : '', // phpcs:ignore WordPress.Security.EscapeOutput -- theme-authored attribute string.
			addlar_icon_path( $key ) // phpcs:ignore WordPress.Security.EscapeOutput -- theme-authored SVG path data.
		);
	}
}
