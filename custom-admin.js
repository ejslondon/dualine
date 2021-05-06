jQuery(document).ready(function() { // wait for page to finish loading

	//Notice dismissable
	jQuery('.dualine-dismissable').click( function(e) {
		
		e.preventDefault();
		$btnClicked = jQuery(this); 
		$parent = jQuery(this).parent(); 
		$parentBox = jQuery(this).closest('.notice'); 
		
		$parentBox.hide(); 
		
		jQuery.post(
			ajaxurl,
			{
				action : 'dualine_notice_dismissable',
				dataBtn : $btnClicked.attr('data-btn'),
			},
			function( response ) {				
				if( response.success === true ) {					
					
				}
				else {
					
				}				
			} 
		);
	});

	jQuery('.tog').change(function(e){
		if(jQuery(this).val() == 'posts') {
			jQuery('#dualine_loggedin_homepage').prop('disabled', 'disabled');
		}
		else {
			jQuery('#dualine_loggedin_homepage').removeAttr('disabled');
		}
	});
	
});