<?php

/*
* dualine_notice_dismissable
* Ajax action to do tasks on notice dismissable
* Require class: sp-dismissable
*/
add_action( 'wp_ajax_dualine_notice_dismissable', 'dualine_notice_dismissable' );
function dualine_notice_dismissable() {
	
	// Sanitize string for added security
	$data_btn = isset($_POST['dataBtn']) ? sanitize_text_field($_POST['dataBtn']) : '';
	
	if(empty($data_btn)) return; 
	
	$today = DateTime::createFromFormat('U', current_time('U')); 
	
	switch($data_btn) {
		case 'ask-later':
			$ask_later = get_option('dualine_review_notice') ? get_option('dualine_review_notice') : 0;
			$updated = update_option('dualine_review_notice', ++$ask_later); 
			break; 
		case 'ask-never':
			$updated = update_option('dualine_review_notice', -1); 
			break; 
	}
	
	$ajaxy = ($updated) ? 'Updated' : 'Not updated'; 
	wp_send_json_success($ajaxy); 
	wp_die(); 
}

add_action( 'admin_notices', 'dualine_admin_notices' );
function dualine_admin_notices() {
	
	// Get dualine_install_date from options
	$install_date = get_option('dualine_install_date') ? get_option('dualine_install_date') : current_time('Y-m-d H:i:s'); 
	
	$install_date_object = DateTime::createFromFormat('Y-m-d H:i:s', $install_date);
	$today = DateTime::createFromFormat('U', current_time('U')); 
	$diff = $today->diff($install_date_object); 	

/**
 * Show a notice to anyone who has just updated this plugin
 * This notice shouldn't display to anyone who has just installed the plugin for the first time
 */
function dualine_display_update_notice() {
	// Check the transient to see if we've just updated the plugin
	if( get_transient( 'dualine_updated' ) ) {
		echo '<div class="notice notice-success is-dismissible">
			<h2 style="margin:0.5em 0;">Thank you for updating  - <span style="color:#0073aa;">Dualine</span> to the latest version. we are sure you will love what is in this new version.</h2>
			<p>
			'.__( 'Dualine adds the ability for 2 homepages on your website, one for your visitors and guests and the other for your members and logged in users.', 'dualine' ).'
			</p>
		</div>';
		
		// Save dualine_install_date for already existing users (before: 1.5.3)
		if(!get_option('dualine_install_date'))
			update_option('dualine_install_date', current_time('Y-m-d H:i:s'));		
		
		delete_transient( 'dualine_updated' );
	}
}
add_action( 'admin_notices', 'dualine_display_update_notice' );

/**
 * Show a notice to anyone who has just installed the plugin for the first time
 * This notice shouldn't display to anyone who has just updated this plugin
 */
function dualine_display_install_notice() {
	// Check the transient to see if we've just activated the plugin
	if( get_transient( 'dualine_activated' ) ) {
		
		echo '<div class="notice notice-success is-dismissible">
			<h2 style="margin:0.5em 0;"> Wohoo! You Are <strong>Awesome</strong>! Thanks for installing our plugin.
			<p>
			'.sprintf(__( 'Set a homepage for visitors and guests and another one for members and logged in users. Find The Settings At: <strong>WP Admin < Settings < Reading</strong>.
			</p>
		</div>;'
		
		// Delete the transient so we don't keep displaying the activation message
		delete_transient( 'dualine_activated' );
	}
}
add_action( 'admin_notices', 'dualine_display_install_notice' );

?>