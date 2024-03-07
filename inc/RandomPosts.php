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
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
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
