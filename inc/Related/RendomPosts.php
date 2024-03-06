<?php
/**
 * Frontend Class
 * version 1.0
 *
 * @package wp-plugin
 * @since 1.0
 */

namespace RelatedRandomPosts\Related;

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
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

	/**
	 * Enqueue style and script for the plugin.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_style( 'learn-plugin-style', RRP_PLUGIN_URL . '/assets/css/style.css', array(), RRP_PLUGIN_VERSION );
		wp_enqueue_script( 'learn-plugin-script', RRP_PLUGIN_URL . '/assets/js/main.js', array( 'jquery' ), RRP_PLUGIN_VERSION, true );
	}
}
