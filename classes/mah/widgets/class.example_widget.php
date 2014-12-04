<?php

include_once('class.mah_widget_setup.php');

/**
 * @see http://codex.wordpress.org/Widgets_API
 */

class Mah_Example_Widget extends WP_Widget
{
	
	const TEXTDOMAIN = 'widget_textdomain'; // For translations.
	
	/**
	 * Register widget with WordPress.
	 */
	
	public function __construct() {
		
		parent::__construct(
			
			'my-awesome-widget', // Base ID.
			__('Awesome Widget!', self::TEXTDOMAIN), // Name.
			array(
				'classname' => __CLASS__,
				'description' => __('This widget kicks ass!', self::TEXTDOMAIN),
			) // Arguments.
			
		);
		
		$styles_admin = array(
			
			array(
				'handle' => 'somehandle',
				'src' => 'path/to/source',
			),
			
			array(
				'handle' => 'someotherhandle',
				'src' => 'path/to/source',
			),
			
		);
		
		$scripts_admin = array(
			
			array(
				'handle' => 'scrpthandle',
				'src' => 'path/to/source',
				'deps' => array(
					'jquery',
				),
			),
			
		);
		
		$styles = array(
			
			array(
				'handle' => 'frontstyle',
				'src' => 'path/to/src',
			),
			
		);
		
		$scripts = array(
			
			array(
				'handle' => 'scrpthandle',
				'src' => 'path/to/source',
			),
			
		);
		
		new Mah_Widget_Setup(
			__CLASS__,
			$styles_admin,
			$scripts_admin,
			$styles,
			$scripts
		);
		
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param $args { array } Widget arguments.
	 * @param $instance { array } Saved values from database.
	 */
	
	public function widget($args, $instance) {
		
		locate_template(
			
			array(
				'some-template.php'
			),
			TRUE
			
		);
		
	}
	
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param $new_instance { array } Values just sent to be saved.
	 * @param $old_instance { array } Previously saved values from database.
	 *
	 * @return { array } Updated safe values to be saved.
	 */
	
	public function update($new_instance, $old_instance) {
		
		// ...
		
	}
	
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param $instance { array } Previously saved values from database.
	 */
	
	public function form($instance) {
		
		// ...
		
	}
	
}
//new Mah_Example_Widget();
