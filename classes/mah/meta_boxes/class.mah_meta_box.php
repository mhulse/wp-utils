<?php

/**
 * Theme post/page meta box.
 *
 * This code creates post/page-specific options for ads and paywall.
 *
 * // In your `functions.php` file:
 * include_once('classes/mah/meta_boxes/class.mah_meta_box.php');
 *
 * @see http://codex.wordpress.org/Function_Reference/add_meta_box
 * @see http://wp.tutsplus.com/tutorials/plugins/how-to-create-custom-wordpress-writemeta-boxes/
 * @see http://wptutsplus.s3.amazonaws.com/028_CustomWordPressMetaBoxes/meta-box.txt
 * @see http://wordpress-hackers.1065353.n5.nabble.com/Why-pass-by-reference-td41806.html
 * @see http://wordpress.stackexchange.com/a/98553/32387
 * @see http://wordpress.org/extend/ideas/topic/add-meta-box-to-multiple-post-types
 * @see https://github.com/Horttcore/WordPress-Subtitle
 * @see http://trepmal.com/filter_hook/default_hidden_meta_boxes/
 */

//--------------------------------------------------------------------

# Avoid direct calls to this file:
if ( ! function_exists('add_action')) {
	
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
	
}

//--------------------------------------------------------------------

class Mah_Meta_Box
{
	
	public function __construct() {
		
		# Hook a callback to `add_meta_boxes` function:
		add_action(
			'add_meta_boxes', // The name of the action to which $function_to_add is hooked.
			array(            // The name of the function you wish to be hooked.
				$this,        // This class ...
				'add',        // ... method name.
			)
		);
		
		# Hook a callback to `save_post` function:
		add_action(
			'save_post',
			array(
				$this,
				'save',
			)
		);
		
		# Hide this meta box by default:
		add_filter(
			'default_hidden_meta_boxes', // The name of the existing Filter to Hook the `$function_to_add` argument to.
			array(                       // The name of the function to be called when the custom Filter is applied.
				$this,                   // This class ...
				'hide',                  // ... method name.
			),
			10,                          // Used to specify the order in which the functions associated with a particular action are executed.
			2                            // The number of arguments the function(s) accept(s).
		);
		
		# Remove from the "Custom Fields" metabox:
		add_filter(
			'is_protected_meta',
			array(
				$this,
				'meta',
			),
			20,
			2
		);
		
	}
	
	/**
	 * Make the meta for storing thumbnails protected so it doesn't show in the "Custom Fields" metabox.
	 *
	 * @see https://github.com/voceconnect/multi-post-thumbnails
	 * @see http://stackoverflow.com/a/7351422/922323
	 */
	
	public function meta($protected, $meta_key) {
		
		if (apply_filters('mah_unprotect_meta', FALSE)) {
			
			return $protected;
			
		}
		
		if ($meta_key == 'post_target' || $meta_key == 'post_paywall') {
			
			$protected = TRUE;
			
		}
		
		return $protected;
		
	}
	
	/**
	 * @see http://aaron.jorb.in/blog/2011/05/thoughts-on-post-meta-boxes-in-wordpress/
	 * @see http://trepmal.com/2011/03/31/change-which-meta-boxes-are-shown-or-hidden-by-default/
	 */
	
	public function hide($hidden, $screen) {
		
		$hidden[] = 'mah_meta_box';
		
		return $hidden;
		
	}
	
	public function add($post_type) {
		
		# Allowed post types to show meta box:
		$post_types = array(
			'post',
			'page'
		);
		
		# Add a meta box to the administrative interface:
		if (in_array($post_type, $post_types)) {
			
			add_meta_box(
				'mah_meta_box',  // HTML `id` attribute of the edit screen section.
				'Theme options', // Title of the edit screen section, visible to user.
				array(           // Function that prints out the HTML for the edit screen section.
					$this,
					'render',
				),
				$post_type,      // The type of Write screen on which to show the edit screen section.
				'side',          // The part of the page where the edit screen section should be shown.
				'low'            // The priority within the context where the boxes should show.
			);
			
		}
		
	}
	
	/**
	 * Callback that displays the meta box HTML.
	 *
	 * @see http://wordpress.org/support/topic/difference-between-get_post_custom-and-get_post_custom_values-and-get_post_meta
	 */
	
	public function render($post) {
		
		# Display the nonce hidden form field:
		wp_nonce_field(
			plugin_basename(__FILE__), // Action name.
			'mah_meta_box_nonce'       // Nonce name.
		);
		
		# Fetch all of the post's custom field keys and values:
		$values = get_post_custom($post->ID);
		
		# Get and validate each field value:
		$post_target = isset($values['post_target']) ? esc_attr($values['post_target'][0]) : '';
		$post_paywall = isset($values['post_paywall']) ? esc_attr($values['post_paywall'][0]) : '';
		
		# Output the HTML:
		?>
			
			<p>
				<label for="post_target">Ad</label>
				<input type="text" name="post_target" id="post_target" value="<?=$post_target?>">
			</p>
			
			<p>
				<input type="checkbox" name="post_paywall" id="post_paywall" <?=checked($post_paywall, 'on')?>>
				<label for="post_paywall">Disable paywall?</label>
			</p>
			
		<?php
		
	}
	
	/**
	 * Save our custom data when the post is saved.
	 *
	 * @see http://wordpress.stackexchange.com/a/16267/32387
	 */
	
	public function save($post_id) {
		
		# Is the current user is authorised to do this action?
		if ((isset($_POST['post_type']) && ($_POST['post_type'] == 'page') && current_user_can('edit_page', $post_id)) || current_user_can('edit_post', $post_id)) { // If it's a page, OR, if it's a post, can the user edit it? 
			
			# Stop WP from clearing custom fields on autosave:
			if ((( ! defined('DOING_AUTOSAVE')) || ( ! DOING_AUTOSAVE)) && (( ! defined('DOING_AJAX')) || ( ! DOING_AJAX))) {
				
				# Nonce verification:
				if (isset($_POST['mah_meta_box_nonce']) && (wp_verify_nonce($_POST['mah_meta_box_nonce'], plugin_basename(__FILE__)))) {
					
					# Get `post_target` text field:
					if (isset($_POST['post_target'])) {
						
						update_post_meta(
							$post_id,                 // The `ID` of the post which contains the field you will edit.
							'post_target',            // The key of the custom field you will edit.
							sanitize_text_field(      // The new value of the custom field (this sanitizes a string from user input or from the db).
								$_POST['post_target'] // String to be sanitized.
							)
						);
						
					}
					
				}
				
				# Get `post_paywall` checkbox:
				$post_paywall = (isset($_POST['post_paywall']) && $_POST['post_paywall']) ? 'on' : 'off';
				update_post_meta($post_id, 'post_paywall', $post_paywall);
				
			}
			
		}
		
	}
	
	static function get_post_target($post_id = FALSE) {
		
		$post_id = ($post_id) ? $post_id : get_the_ID();
		
		return apply_filters('the_post_target', get_post_meta($post_id, 'post_target', TRUE));
		
	}
	
	static function the_post_target() {
		
		echo get_post_target(get_the_ID());
		
	}
	
	static function get_post_paywall($post_id = FALSE) {
		
		$post_id = ($post_id) ? $post_id : get_the_ID();
		
		return apply_filters('the_post_paywall', get_post_meta($post_id, 'post_paywall', TRUE));
		
	}
	
	static function the_post_paywall() {
		
		echo get_post_paywall(get_the_ID());
		
	}
	
}

//--------------------------------------------------------------------

function call_mah_meta_box() {
	
	return new Mah_Meta_Box();
	
}

if (is_admin()) {
	
	# http://wordpress.stackexchange.com/a/33105/32387
	add_action('admin_init', 'call_mah_meta_box');
	
}

//--------------------------------------------------------------------

function has_post_target($post_id = FALSE) {
	
	if (Mah_Meta_Box::get_post_target($post_id)) return TRUE;
	
}

function get_post_target($post_id = FALSE) {
	
	return Mah_Meta_Box::get_post_target($post_id);
	
}

function the_post_target() {
	
	Mah_Meta_Box::the_post_target();
	
}

//--------------------------------------------------------------------

function has_post_paywall($post_id = FALSE) {
	
	if (Mah_Meta_Box::get_post_paywall($post_id) == 'on') return TRUE;
	
}

function get_post_paywall($post_id = FALSE) {
	
	return Mah_Meta_Box::get_post_paywall($post_id);
	
}

function the_post_paywall() {
	
	Mah_Meta_Box::the_post_paywall();
	
}
