<?php
/**
 * Plugin File: Related Random Posts
 * Description: This plugin will show related random posts under each post.
 * Version: 1.0
 * Author: Shamsun Naher
 * Author URI: https://www.traversymedia.com
 * License: GPLv2 or later
 *
 * @package wp-plugin
 * @since 1.0
 */

namespace RelatedRandomPosts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PluginMain {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const version = '1.0';

	/**
	 * Instance of the PluginMain class
	 *
	 * @var PluginMain|null
	 */
	private static $instance = null;

	/**
	 * Class constructor (private to enforce singleton pattern)
	 */
	private function __construct() {
		// Do initialization here
		$this->register_hooks();
	}

	/**
	 * Get the singleton instance of PluginMain
	 *
	 * @return PluginMain
	 */
	public static function get_instance(): PluginMain {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register hooks and do other setup tasks
	 */
	private function register_hooks() {

		register_activation_hook( RRP_PLUGIN_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( RRP_PLUGIN_FILE, array( $this, 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
	}

	/**
	 * Initialize the plugin
	 */
	public function init_plugin() {
		// Defining plugin constants
		$this->define_constants();

		// Instantiate the Related_Random_Posts class
		Related\RendomPosts::get_instance();
	}

	/**
	 * Function to define all constants.
	 */
	private function define_constants() {

		/**
		 * Check if the RRP_PLUGIN_VERSION constant is defined and if not, define it with the value of the 'version' property of the LearnPlugin class.
		 */
		if ( ! defined( 'RRP_PLUGIN_VERSION' ) ) {
			define( 'RRP_PLUGIN_VERSION', self::version );
		}
		/**
		 * Define the constant RRP_PLUGIN_PATH if it is not already defined.
		 * The constant represents the path to the Learn Plugin directory.
		 * It is defined as the plugin directory path without the trailing slash.
		 */
		if ( ! defined( 'RRP_PLUGIN_PATH' ) ) {
			define( 'RRP_PLUGIN_PATH', untrailingslashit( plugin_dir_path( RRP_PLUGIN_FILE ) ) );
		}

		/**
		 * Define the constant RRP_PLUGIN_URL if it is not already defined.
		 *
		 * @since 1.0.0
		 */
		if ( ! defined( 'RRP_PLUGIN_URL' ) ) {
			define( 'RRP_PLUGIN_URL', untrailingslashit( plugin_dir_url( RRP_PLUGIN_FILE ) ) );
		}

		/**
		 * Define the constant RRP_PLUGIN_ASSETS if it is not already defined.
		 * RRP_PLUGIN_ASSETS is the URL for the assets directory of the Learn Plugin.
		 *
		 * @since 1.0.0
		 */
		if ( ! defined( 'RRP_PLUGIN_ASSETS' ) ) {
			define( 'RRP_PLUGIN_ASSETS', RRP_PLUGIN_URL . '/assets' );
		}
	}

	/**
	 * Run code when the plugin is activated
	 */
	public function activate() {

		$installed = get_option( 'rrp_plugin_installed' );

		if ( ! $installed ) {
			update_option( 'rrp_plugin_installed', time() );
		}

		update_option( 'rrp_plugin_version', self::version );
	}
	/**
	 * Run code when the plugin is activated
	 */
	public function deactivate() {

		delete_option( 'rrp_plugin_installed' );
		delete_option( 'rrp_plugin_version' );
	}
}



