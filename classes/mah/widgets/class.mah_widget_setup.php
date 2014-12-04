<?php

/**
 * @see http://www.jonloomer.com/2012/06/28/create-custom-wordpress-widget/
 * @see http://wordpress.stackexchange.com/a/80433/32387
 * @see http://wordpress.stackexchange.com/a/45920/32387
 * @see http://wordpress.stackexchange.com/a/48094/32387
 */

class Oct_Widget_Setup
{
	
	private $widget_class = '';
	private $style_admin = array();
	private $script_admin = array();
	private $style = array();
	private $script = array();
	
	private $style_defaults = array(
		'handle' => '',
		'src' => '',
		'deps' => array(),
		'version' => false,
		'media' => 'all',
	);
	
	private $script_defaults = array(
		'handle' => '',
		'src' => '',
		'deps' => array(),
		'version' => false,
		'in_footer' => false,
	);
	
	public function __construct(
		$widget_class = '',
		$style_admin = array(),
		$script_admin = array(),
		$style = array(),
		$script = array()
	) {
		
		if ($style_admin) {
			
			$this->style_admin = $style_admin;
			
			add_action('admin_enqueue_scripts', array($this, 'add_styles'));
			
		}
		
		if ($script_admin) {
			
			$this->script_admin = $script_admin;
			
			add_action('admin_enqueue_scripts', array($this, 'add_scripts'));
			
		}
		
		if ($style) {
			
			$this->style = $style;
			
			add_action('wp_enqueue_scripts', array($this, 'add_styles'));
			
		}
		
		if ($script) {
			
			$this->script = $script;
			
			add_action('wp_enqueue_scripts', array($this, 'add_styles'));
			
		}
		
		if ( ! empty($widget_class)) {
			
			$this->widget_class = $widget_class;
			
			add_action(
				'widgets_init',
				array(
					$this,
					'register_widget',
				)
			);
			
		}
		
	}
	
	public function register_widget() {
		
		register_widget($this->widget_class);
		
	}
	
	public function add_styles() {
		
		return $this->add_files('style');
		
	}
	
	public function add_scripts() {
		
		return $this->add_files('script');
		
	}
	
	private function add_files($kind = '') {
		
		$return = FALSE;
		
		if ($kind) {
			
			$files = (is_admin()) ? $this->{ $kind . '_admin' } : $this->{ $kind };
			
			if ( ! empty($files)) {
				
				foreach($files as $file) {
					
					$args = wp_parse_args($file, $this->{ $kind . '_defaults' });
					
					call_user_func_array(
						
						('wp_enqueue_' . $kind),
						array(
							$args['handle'],
							$args['src'],
							$args['deps'],
							$args['version'],
							((array_key_exists('media', $args)) ? $args['media'] : $args['in_footer']),
						)
						
					);
					
				}
				
				$return = TRUE;
				
			}
			
		}
		
		return $return;
		
	}
	
}
