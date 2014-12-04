<?php

/**
 * Programatically add custom fields.
 *
 * @see http://wordpress.stackexchange.com/questions/98269/
 */

function mah_default_custom_fields() {
	
	if (isset($GLOBALS['post'])) {
		
		$post_type = get_post_type($GLOBALS['post']);
		
		if (post_type_supports($post_type, 'custom-fields')) {
			
			?>
				
				<script>
					
					// Cache:
					var $metakeyinput = jQuery('#metakeyinput'),
					    $metakeyselect = jQuery('#metakeyselect');
					
					// Does the default input field exist and is it visible?
					if ($metakeyinput.length && ( ! $metakeyinput.hasClass('hide-if-js'))) {
						
						// Hide it:
						$metakeyinput.addClass('hide-if-js'); // Using WP admin class.
						
						// ... and create the select box:
						$metakeyselect = jQuery('<select id="metakeyselect" name="metakeyselect">').appendTo('#newmetaleft');
						
						// Add the default select value:
						$metakeyselect.append('<option value="#NONE#">— Select —</option>');
						
					}
					
					// Does "Slideshow" already exist?
					if (jQuery("[value='Slideshow']").length < 1) {
						
						// Add option:
						$metakeyselect.append("<option value='Slideshow'>Slideshow</option>");
						
					}
					
					// Does "Video" already exist?
					if (jQuery("[value='Video']").length < 1) {
						
						// Add option:
						$metakeyselect.append("<option value='Video'>Video</option>");
						
					}
					
				</script>
				
			<?php
			
		}
		
	}
	
}

add_action('admin_footer-post-new.php', 'mah_default_custom_fields');
add_action('admin_footer-post.php', 'mah_default_custom_fields');
