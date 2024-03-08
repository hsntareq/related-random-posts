<?php
/**
 * Plugin Name: A Related Random Posts
 * Plugin URI: https://www.related-random-posts.com
 * Version: 1.0
 * Author: Hasan Tareq
 * Author URI: https://github.com/hsntareq
 * License: GPLv2 or later
 * Text Domain: related-random-posts
 * Description: This plugin will show related random posts under each post.
 *
 * @package wp-plugin
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'RRP_PLUGIN_FILE' ) ) {
	define( 'RRP_PLUGIN_FILE', __FILE__ );
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * FILEPATH: /wp-content/plugins/related-random-posts/related-random-posts.php
 *
 * Initializes the Related Random Posts plugin by creating an instance of the PluginMain class.
 *
 * @since 1.0.0
 */
RelatedRandomPosts\PluginMain::get_instance();
