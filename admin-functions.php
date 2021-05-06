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
			$ask_later = get_option('dualine_review_notice') ? get_option('dwh_review_notice') : 0;
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
	
	$review_notice = get_option('dualine_review_notice');
	// review_notice - numeric counter for multiplying 14 days
	$review_notice =  (isset($review_notice) AND !empty($review_notice)) ? $review_notice : 1; 
	
	$install_date_object = DateTime::createFromFormat('Y-m-d H:i:s', $install_date);
	$today = DateTime::createFromFormat('U', current_time('U')); 
	$diff = $today->diff($install_date_object); 	
	
	if($review_notice!=-1) {
		if($diff->days >= 14*$review_notice) {
			echo '<div class="notice notice-success">
				<h2 style="margin:0.5em 0;">You have been using Dualine for a while now, please, would you take your time to leave us a review?- <span style="color:green;">Dualine</span></h2>
				<p>'.__( 'The plugin that allows you to have 2 Homepages on your website, one for visitors and guests and another for members and logged in users.', 'dualine' ).'
				<br><br>
				<a class="button-primary" href="https://wordpress.org/plugins/dualine/reviews">Leave A Review Now</a>
				&nbsp;<a class="button-link dwh-dismissable" data-btn="ask-later" href="#">Ask Me Again Later</a> |
				<a class="button-link dwh-dismissable" data-btn="ask-never" href="#">Don Not Show Again</a></p>
			</div>';		
		}
	}
}

/**
 * Show a notice to anyone who has just updated this plugin
 * This notice shouldn't display to anyone who has just installed the plugin for the first time
 */
function dualine_display_update_notice() {
	// Check the transient to see if we've just updated the plugin
	if( get_transient( 'dualine_updated' ) ) {
		echo '<div class="notice notice-success is-dismissible">
			<h2 style="margin:0.5em 0;">WooHoo! You are awesome!! Thanks for updating - <span style="color:green;">Dualine</span></h2>
			<p>
			'.__( 'The plugin that allows you to have 2 Homepages on your website, one for visitors and guests and another for members and logged in users.', 'dualine' ).'
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
			<h2 style="margin:0.5em 0;">WooHoo! You are awesome!! Thanks for installing - <span style="color:green;">Dualine</span></h2>
			<p>
			'.sprintf(__( 'Have 2 Homepages on your website, one for visitors and guests and another for members and logged in users.<a href="%s">Configure</a>', 'dualine' ), admin_url('options-reading.php')).'
			</p>
		</div>';
		
		// Delete the transient so we don't keep displaying the activation message
		delete_transient( 'dualine_activated' );
	}
}
add_action( 'admin_notices', 'dualine_display_install_notice' );

?>