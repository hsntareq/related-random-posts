<?php
/**
 * Frontend Class
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
		add_filter( 'rendom_post_thumb_size', array( $this, 'rrp_rendom_post_thumb_size' ) );
		add_filter( 'rrp_excerpt_text', array( $this, 'rrp_excerpt_text_callback' ) );
		add_filter( 'rrp_post_number', array( $this, 'rrp_rendom_post_count' ) );
	}

	/**
	 * Excerpt text callback.
	 *
	 * @param mixed $excerpt is the Excerpt text.
	 *
	 * @return string
	 */
	public function rrp_excerpt_text_callback( $excerpt ) {
		return wp_trim_words( $excerpt, 10, '...' );
	}

	/**
	 * Random post thumbnail size.
	 *
	 * @return array
	 */
	public function rrp_rendom_post_thumb_size() {
		return array( 150, 150 );
	}

	/**
	 * Random post count.
	 *
	 * @return int
	 */
	public function rrp_rendom_post_count() {
		// Rendom number of posts.
		return wp_rand( 2, 5 );
	}

	/**
	 * Custom excerpt length.
	 *
	 * @param mixed $length is the Excerpt length.
	 *
	 * @return int
	 */
	public function rrp_custom_excerpt_length( $length ) {
		return 12;
	}


	/**
	 * Post thumbnail fallback html.
	 *
	 * @param mixed $html is the post thumbnail html.
	 *
	 * @return [type]
	 */
	public function rrp_post_thumbnail_fallback( $html ) {
		$no_image = esc_url( apply_filters( 'rendom_post_no_image', RRP_PLUGIN_ASSETS . '/images/no-image.jpg' ) );
		$rrp_alt  = __( 'fallback thumbnail' );
		if ( empty( $html ) ) {
			return "<img src='{$no_image}' alt='{$rrp_alt}' />";
		}
		return $html;
	}

	/**
	 * Return the related posts object.
	 *
	 * @return object
	 */
	public function rrp_get_related_posts() {
		$current_post    = get_the_ID();
		$post_categories = wp_get_post_categories( $current_post, array( 'fields' => 'ids' ) );
		$post_count      = apply_filters( 'rrp_post_number', 2 );
		$args            = array(
			'category__in'   => $post_categories,
			'post_status'    => 'publish',
			'posts_per_page' => esc_attr( $post_count ), // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
			'orderby'        => 'rand',
		);
		$related_posts   = new \WP_Query( $args );
		return $related_posts;
	}

	/**
	 * Return the related posts html.
	 *
	 * @param  string $content is the WordPress post body html content.
	 * @param  object $related_posts is the WordPress query object.
	 * @param  array  $exclude is the array of post ids to exclude.
	 * @return string
	 */
	public function rrp_get_related_posts_html( $content, $related_posts, $exclude = array() ) {
		$html  = $content;
		$html .= '<div class="rrp-wrap">';
		$html .= '<div class="rrp-block-title">';
		$html .= '<h2>' . __( 'Related Posts', 'rendom-post' ) . '</h2>';
		$html .= '<button class="rrp-toggler"></button>';
		$html .= '</div>';
		$html .= '<div class="rrp-posts">';
		if ( $related_posts->have_posts() ) {
			while ( $related_posts->have_posts() ) {
				$related_posts->the_post();
				if ( ! in_array( get_the_ID(), $exclude, true ) ) {
					$html .= '<div class="rrp-item">';
					$html .= '<div class="rrp-thumb"><a href="' . get_the_permalink() . '">';
					$html .= '<figure>' . get_the_post_thumbnail( get_the_ID(), apply_filters( 'rendom_post_thumb_size', 'medium' ) ) . '</figure>';
					$html .= '</a></div>';
					$html .= '<div class="rrp-content">';
					$html .= '<h5 class="rrp-title"><a href="' . get_the_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h5>';
					$html .= '<div class="rrp-excerpt">';
					$html .= apply_filters( 'rrp_excerpt_text', get_the_excerpt() );
					$html .= '</div>';
					$html .= '</div>';
					$html .= '</div>';
				}
			}
		}
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

	/**
	 * Adding content after post.
	 *
	 * @param  string $content is the post content.
	 * @return string
	 */
	public function rrp_add_content_after_post( $content ) {
		if ( is_single() ) {
			$related_posts      = $this->rrp_get_related_posts();
			$related_posts_html = $this->rrp_get_related_posts_html( $content, $related_posts, array( get_the_ID() ) );
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
