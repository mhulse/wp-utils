<?php

/**
 * Theme settings page.
 *
 * // In your `functions.php` file:
 * include_once('classes/mah/class.mah_settings.php');
 *
 * @see http://codex.wordpress.org/Creating_Options_Pages#Example_.232
 * @see http://codex.wordpress.org/Function_Reference/add_options_page
 * @see http://codex.wordpress.org/Roles_and_Capabilities#Additional_Admin_Capabilities
 * @see https://gist.github.com/mfields/4678999
 */

class Mah_Settings
{
	
	public function __construct() {
		
		if (is_admin()) {
			
			add_action('admin_menu', array($this, 'add_options_page'));
			add_action('admin_init', array($this, 'admin_init'));
			
		}
		
	}
	
	public function add_options_page() {
		
		add_options_page(
			'Theme options page', // The text to be displayed in the title tags of the page when the menu is selected.
			'Theme',              // The text to be used for the menu.
			'manage_options',     // The capability required for this menu to be displayed to the user.
			'theme',              // The slug name to refer to this menu by (should be unique for this menu).
			array(                // The function to be called to output the content for this page.
				$this,
				'create_options_page',
			)
		);
		
	}
	
	public function create_options_page() {
		
		?>
			
			<div class="wrap">
				
				<?=screen_icon()?>
				
				<h2>Options</h2>
				
				<form method="post" action="options.php">
					
					<?=settings_fields('mah_settings')?>
					
					<?=do_settings_sections('mah')?>
					
					<?php submit_button(); ?>
					
				</form>
				
			</div>
			
		<?php
		
	}
	
	public function admin_init() {
		
		register_setting(
			'mah_settings', // A settings group name (must match `settings_fields()` above).
			'mah_options',  // The name of an option to sanitize and save. 
			array(          // A callback function that sanitizes the option's value.
				$this,
				'mah_options_validate',
			)
		);
		
		//--------------------------------------------------------------------------
		//
		// Register field group:
		//
		//--------------------------------------------------------------------------
		
		add_settings_section(
			'general',        // String for use in the 'id' attribute of tags.
			'',               // Title of the section.
			'__return_false', // Function that fills the section with the desired content.
			'mah'             // The menu page on which to display this section.
		);
		
		//--------------------------------------------------------------------------
		//
		// Register fields:
		//
		//--------------------------------------------------------------------------
		
		#
		# STEP #1: Add a field.
		#
		
		add_settings_field(
			'username',       // String for use in the 'id' attribute of tags.
			'Primary author', // Title of the field.
			array(            // Function that fills the field with the desired inputs as part of the larger form.
				$this,        // This class ...
				'username',   // ... the function to call.
			),
			'mah',            // The menu page on which to display this field.
			'general'         // The section of the settings page in which to show the box.
		);
		
		add_settings_field(
			'attachment_id',
			'Blog image',
			array(
				$this,
				'attachment_id',
			),
			'mah',
			'general'
		);
		
		add_settings_field(
			'category',
			'Blog category',
			array(
				$this,
				'category',
			),
			'mah',
			'general'
		);
		
		add_settings_field(
			'paywall',
			'Paywall?',
			array(
				$this,
				'paywall',
			),
			'mah',
			'general'
		);
		
	}
	
	function mah_options_validate($input) {
		
		$output = array();
		
		#
		# STEP #2: Validate your field.
		#
		
		if (isset($input['username']) && ( ! empty($input['username']))) {
			
			$output['username'] = wp_filter_nohtml_kses($input['username']);
			
		}
		
		if (isset($input['attachment_id']) && ( ! empty($input['attachment_id']))) {
			
			$output['attachment_id'] = absint($input['attachment_id']);
			
		}
		
		if (isset($input['category']) && ( ! empty($input['category']))) {
			
			$output['category'] = wp_filter_nohtml_kses($input['category']);
			
		}
		
		if (isset($input['paywall'])) {
			
			$output['paywall'] = 'yes';
			
		}
		
		return apply_filters('mah_options_validate', $output, $input);
		
	}
	
	#
	# STEP #3: Generate your field's HTML.
	#
	
	public function username() {
		
		$options = $this->get_options();
		
		?>
			
			<input type="text" id="username" name="mah_options[username]" value="<?=$options['username'];?>"> <label for="username">Input an author's <code>username</code>.</label>
			
		<?php
		
	}
	
	public function attachment_id() {
		
		$options = $this->get_options();
		
		?>
			
			<input type="text" id="attachment_id" name="mah_options[attachment_id]" value="<?=$options['attachment_id'];?>"> <label for="attachment_id">Input an image's <code>attachment_id</code>.</label>
			
		<?php
		
	}
	
	public function category() {
		
		$options = $this->get_options();
		
		?>
			
			<input type="text" id="category" name="mah_options[category]" value="<?=$options['category'];?>"> <label for="category">Specify the <code>category</code> of this blog.</label>
			
		<?php
		
	}
	
	public function paywall() {
		
		$options = $this->get_options();
		
		?>
			
			<label for="paywall"><input type="checkbox" id="paywall" name="mah_options[paywall]" <?=checked('yes', $options['paywall'])?>> Paywall enabled for this blog?</label>
			
		<?php
		
	}
	
	public function get_options() {
		
		$saved = (array) get_option('mah_options');
		
		#
		# STEP #4: Finally, add field to the below array.
		#
		
		$defaults = array(
			'username'      => '',
			'attachment_id' => '',
			'category'      => '',
			'paywall'       => '',
		);
		
		$defaults = apply_filters('mah_options', $defaults);
		
		$options = wp_parse_args($saved, $defaults);
		
		$options = array_intersect_key($options, $defaults);
		
		return $options;
		
	}
	
}

$mah_settings = new Mah_Settings();
