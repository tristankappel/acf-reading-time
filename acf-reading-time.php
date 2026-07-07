<?php
/**
 * Plugin Name:       ACF Reading Time
 * Plugin URI:        https://github.com/tristankappel/acf-reading-time
 * Description:       Display the estimated reading time of a post via a shortcode. Counts the post content and all Advanced Custom Fields (ACF Pro) values automatically.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Tristan Kappel
 * Author URI:        https://tristankappel.de
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       acf-reading-time
 * Domain Path:       /languages
 *
 * @package ACF_Reading_Time
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current plugin version.
 */
define( 'ACF_READING_TIME_VERSION', '1.0.0' );

/**
 * Plugin file path.
 */
define( 'ACF_READING_TIME_FILE', __FILE__ );

/**
 * Plugin directory path.
 */
define( 'ACF_READING_TIME_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'ACF_READING_TIME_URL', plugin_dir_url( __FILE__ ) );

// Load core classes.
require_once ACF_READING_TIME_PATH . 'includes/class-acf-reading-time-calculator.php';
require_once ACF_READING_TIME_PATH . 'includes/class-acf-reading-time-settings.php';
require_once ACF_READING_TIME_PATH . 'includes/class-acf-reading-time-shortcode.php';
require_once ACF_READING_TIME_PATH . 'includes/class-acf-reading-time.php';

/**
 * Set default options on activation.
 *
 * @return void
 */
function acf_reading_time_activate() {
	$defaults = array(
		'prefix'           => 'Reading time:',
		'postfix'          => 'min read',
		'words_per_minute' => 200,
	);

	$existing = get_option( 'acf_reading_time_settings', array() );
	update_option( 'acf_reading_time_settings', wp_parse_args( $existing, $defaults ) );
}
register_activation_hook( __FILE__, 'acf_reading_time_activate' );

/**
 * Begins execution of the plugin.
 *
 * @return void
 */
function acf_reading_time_run() {
	$plugin = new ACF_Reading_Time();
	$plugin->run();
}
add_action( 'plugins_loaded', 'acf_reading_time_run' );
