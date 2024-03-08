<?php
/**
 * Frontend Class
 * version 1.0
 *
 * @package wp-plugin
 * @since 1.0
 */

namespace RelatedRandomPosts;

/**
 * RandomPosts Class.
 */
class RandomPosts {
	/**
	 * $instance
	 *
	 * @var null
	 */
	private static $instance = null;
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		$this->register_hooks();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	private function register_hooks() {
		add_filter( 'the_content', array( $this, 'rrp_add_content_after_post' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'rrp_post_thumbnail_fallback' ) );
		add_filter( 'excerpt_length', array( $this, 'rrp_custom_excerpt_length' ) );
	}

	/**
	 * Custom excerpt length.
	 *
	 * @param mixed $length Excerpt length.
	 *
	 * @return int
	 */
	public function rrp_custom_excerpt_length( $length ) {
		return 12;
	}


	/**
	 * Post thumbnail fallback html.
	 *
	 * @param mixed $html Post thumbnail html.
	 *
	 * @return [type]
	 */
	public function rrp_post_thumbnail_fallback( $html ) {
		if ( empty( $html ) ) {
			return '<img src="https://via.placeholder.com/150" alt="fallback thumbnail" />';
		}
		return $html;
	}

	/**
	 * Return the related posts object.
	 *
	 * @return [type]
	 */
	public function rrp_get_related_posts() {
		$post_categories = wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) );
		$post_number     = wp_rand( 2, 5 );
		$args            = array(
			'category__in'   => $post_categories,
			'post__not_in'   => array( get_the_ID() ),
			'posts_per_page' => $post_number,
			'orderby'        => 'rand',
		);
		$related_posts   = new \WP_Query( $args );
		return $related_posts;
	}

	/**
	 * Return the related posts html.
	 *
	 * @param  object $related_posts WordPress query object.
	 * @return string
	 */
	public function rrp_get_related_posts_html( $related_posts ) {
		$html  = '<div class="rrp-wrap">';
		$html .= '<div class="rrp-block-title">';
		$html .= '<h2>' . __( 'Related Posts', 'rendom-post' ) . '</h2>';
		$html .= '<button class="rrp-toggler"></button>';
		$html .= '</div>';
		$html .= '<div class="rrp-posts">';
		if ( $related_posts->have_posts() ) {
			while ( $related_posts->have_posts() ) {
				$related_posts->the_post();
				$html .= '<div class="rrp-item">';
				$html .= '<div class="rrp-thumb"><a href="' . get_the_permalink() . '">';
				$html .= '<figure>' . get_the_post_thumbnail( get_the_ID(), 'medium' ) . '</figure>';
				$html .= '</a></div>';
				$html .= '<div class="rrp-content">';
				$html .= '<h5 class="rrp-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h5>';
				$html .= '<div class="rrp-excerpt">' . get_the_excerpt() . '</div>';
				$html .= '</div>';
				$html .= '</div>';
			}
		}
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

	/**
	 * Adding content after post.
	 *
	 * @param  string $content Post content.
	 * @return string
	 */
	public function rrp_add_content_after_post( $content ) {
		if ( is_single() ) {
			$related_posts      = $this->rrp_get_related_posts();
			$related_posts_html = $this->rrp_get_related_posts_html( $related_posts );
			return $related_posts_html;
		}

		return $content;

	}


	/**
	 * The instance of this class.
	 *
	 * @return RandomPosts
	 */
	public static function get_instance(): RandomPosts {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance ?? new self();
	}
}
