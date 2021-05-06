<?php 

/**
 * Add filter on init
*/
add_action('init', 'dualine_init_action'); 
function dualine_init_action() {
	if(!is_admin() && is_user_logged_in()) {
		add_filter( 'pre_option_page_on_front', 'dualine_filter_pre_option_page_on_front', 10, 1 ); 
	}
}

/**
 * dualine_filter_pre_option_page_on_front
 * Change homepage for logged in users
*/
function dualine_filter_pre_option_page_on_front( $return_zero ) { 
	$dualine_loggedin_homepage = get_option('dualine_loggedin_homepage'); 
	
	if($dualine_loggedin_homepage)
		return $dualine_loggedin_homepage; 
	else $return_zero; 
}; 

/**
 * dualine_whitelist_reading_options
 * Add extra setting on the reading page
*/
add_filter( 'whitelist_options', 'dualine_whitelist_reading_options');
function dualine_whitelist_reading_options($whitelist_options) {	
	$whitelist_options['reading'][] = 'dualine_loggedin_homepage'; 
	// print_r($whitelist_options['reading']); wp_die(); 
	return $whitelist_options; 
}

/**
 * dualine_reading_options
 * Setup the settings fields
*/
add_action('admin_init', 'dualine_reading_options');
function dualine_reading_options(){
	add_settings_field(
		'dualine_loggedin_homepage',
		'Home page for members (Logged In Users)',
		'dualine_callback_for_loggedin_homepage_field',
		'reading',
		'default',
		array( 'label_for' => 'dualine_loggedin_homepage' )
	);
}

/**
 * dualine_callback_for_loggedin_homepage_field
 * Setup the dropdown with pages list
*/
function dualine_callback_for_loggedin_homepage_field($args){
	echo wp_dropdown_pages( array( 
		'name' => 'dualine_loggedin_homepage', 
		'echo' => 0, 
		'show_option_none' => __( '&mdash; Select &mdash;' ), 
		'option_none_value' => '0', 
		'selected' => get_option( 'dualine_loggedin_homepage' ) 
	)); 
}

?>