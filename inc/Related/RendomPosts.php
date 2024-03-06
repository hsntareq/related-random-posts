<?php
/**
 * Frontend Class
 * version 1.0
 *
 * @package wp-plugin
 * @since 1.0
 */

namespace RelatedRandomPosts\Related;

class RendomPosts {
	private static $instance = null;
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}
	public static function get_instance(): RendomPosts {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function enqueue() {
		wp_enqueue_style( 'learn-plugin-style', RRP_PLUGIN_URL . '/assets/css/style.css' );
		wp_enqueue_script( 'learn-plugin-script', RRP_PLUGIN_URL . '/assets/js/main.js', array( 'jquery' ), '1.0', true );
	}
}
