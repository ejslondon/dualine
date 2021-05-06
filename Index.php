<?php
/*
Plugin Name: Dualine
Plugin URI: http://www.ejslondon.com
Description: The easiest way to add 2 homepages to your website!
Author URI: http://www.ejslondon.com
Author: EJS London
Version: 1.0.0
*/
define( 'DUALINE_VERSION', '0.0.1' );
define( 'DUALINE_REQUIRED_WP_VERSION', '4.7' );
define( 'DUALINE_PLUGIN', __FILE__ );
define( 'DUALINE_PLUGIN_BASENAME', plugin_basename( DUALINE_PLUGIN ) );
define( 'DUALINE_PLUGIN_NAME', trim( dirname( DUALINE_PLUGIN_BASENAME ), '/' ) );
define( 'DUALINE_PLUGIN_DIR', untrailingslashit( dirname( DUALINE_PLUGIN ) ) );
define( 'DUALINE_PLUGIN_DOCS_DIR', DUALINE_PLUGIN_DIR . '/docs' );
define( 'DUALINE_PLUGIN_URL', plugins_url( '' , __FILE__ ) );

require_once( DUALINE_PLUGIN_DOCS_DIR . '/admin-functions.php' );
require_once( DUALINE_PLUGIN_DOCS_DIR . '/dualine-option-field.php' );

/////////////////////////////////////
// Activation Hook
/////////////////////////////////////
register_activation_hook(__FILE__, 'dualine_on_activate'); 
function dualine_on_activate() {
	// Empty Activation Hook
	update_option('dualine_install_date', current_time('Y-m-d H:i:s')); 	
	set_transient( 'dualine_activated', 1 );
}

/////////////////////////////////////
// Deactivation Hook
/////////////////////////////////////
register_deactivation_hook(__FILE__, 'dualine_on_deactivate'); 
function dualine_on_deactivate() {
	// Empty Deactivation Hook
}

/////////////////////////////////////
// Uninstall Hook
/////////////////////////////////////
register_uninstall_hook(__FILE__, 'dualine_on_uninstall'); 
function dualine_on_uninstall() {
	// Empty Activation Hook
	delete_option('dualine_install_date');
	delete_option('dualine_review_notice');
}

/**
 * Set Plugin URL Path (SSL/non-SSL)
 * @param  string - $path
 * @return string - $url 
 * Return https or non-https URL from path
 */
function dualine_plugin_url( $path = '' ) {
	$url = plugins_url( $path, DUALINE_PLUGIN );
	if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}
	return $url;
}

/////////////////////////////////////////
// Enqueue Admin Scripts
/////////////////////////////////////////
add_action('admin_enqueue_scripts', 'dualine_admin_scripts');
function dualine_admin_scripts($hook_suffix) {
	wp_register_script('custom-admin', dualine_plugin_url( '/docs/custom-admin.js')); 
	wp_enqueue_script('custom-admin');	
}
?>