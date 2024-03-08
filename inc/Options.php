<?php
/**
 * Plugin options class.
 *
 * @package wp-plugin
 * @since 1.0
 */

namespace RelatedRandomPosts;

class Options {
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
		add_action( 'admin_menu', array( $this, 'rrp_add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'rrp_settings_init' ) );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function rrp_add_admin_menu() {
		add_options_page(
			'Related Posts',
			'Related Posts',
			'manage_options',
			'related-random-posts',
			array( $this, 'rrp_options_page' )
		);
	}

	/**
	 * Options page callback.
	 *
	 * @return void
	 */
	public function rrp_options_page() {
		?>
		<form action='options.php' method='post'>

			<h2>A Related Random Posts</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php
	}

	/**
	 * Add settings.
	 *
	 * @return void
	 */
	public function rrp_settings_init() {
		register_setting( 'pluginPage', 'rrp_settings' );

		add_settings_section(
			'rrp_pluginPage_section',
			__( 'Settings', 'rrposts' ),
			array( $this, 'rrp_settings_section_callback' ),
			'pluginPage'
		);

		add_settings_field(
			'rrp_text_field_0',
			__( 'API Key', 'rrposts' ),
			array( $this, 'rrp_text_field_0_render' ),
			'pluginPage',
			'rrp_pluginPage_section'
		);
	}

	/**
	 * Settings section callback.
	 *
	 * @return void
	 */
	public function rrp_settings_section_callback() {
		echo __( 'This section description', 'rrposts' );
	}

	/**
	 * Text field render.
	 *
	 * @return void
	 */
	public function rrp_text_field_0_render() {
		$options = get_option( 'rrp_settings' );
		?>
		<input type='text' name='rrp_settings[rrp_text_field_0]' value='<?php echo $options['rrp_text_field_0']??''; ?>'>
		<?php
	}


	/**
	 * The instance of this class.
	 *
	 * @return Options
	 */
	public static function get_instance(): Options {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance ?? new self();
	}
}

