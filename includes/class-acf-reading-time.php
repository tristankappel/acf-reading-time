<?php
/**
 * The core plugin class.
 *
 * @package ACF_Reading_Time
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core plugin class that wires together the components.
 */
class ACF_Reading_Time {

	/**
	 * Settings handler.
	 *
	 * @var ACF_Reading_Time_Settings
	 */
	protected $settings;

	/**
	 * Shortcode handler.
	 *
	 * @var ACF_Reading_Time_Shortcode
	 */
	protected $shortcode;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->settings  = new ACF_Reading_Time_Settings();
		$this->shortcode = new ACF_Reading_Time_Shortcode( $this->settings );
	}

	/**
	 * Register all hooks and run the plugin.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'init', array( $this, 'load_textdomain' ) );

		$this->settings->register();
		$this->shortcode->register();
	}

	/**
	 * Load the plugin text domain for translations.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'acf-reading-time',
			false,
			dirname( plugin_basename( ACF_READING_TIME_FILE ) ) . '/languages'
		);
	}
}
