<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package ACF_Reading_Time
 */

// Exit if not called by WordPress uninstall.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'acf_reading_time_settings' );
