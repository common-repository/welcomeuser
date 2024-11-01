<?php
/**
 * Add options page
 */
function nlws_wu_plugin_menu() {
	// Create a new options page
	$plugin_page = add_options_page( 'Welcome User', 'Welcome User!', 'manage_options', 'nlws-wu-options', 'nlws_wu_options');
	add_action( 'admin_head-'. $plugin_page, 'nlws_wu_admin_header' );
}