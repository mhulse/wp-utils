<?php

class Foo_Widget extends WP_Widget
{
	
	private $t;
	
	public function __construct($arr = array()) { # If I don't set a default array() value, I get this notice: "Undefined variable: arr in /.../class.foo_widget.php on line 9"
		
		$this->t = $this;
		
		$args = wp_parse_args(
			$arr,
			array(
				'id_base' => '',
				'name' => '',
			)
		);
		
		extract($args, EXTR_SKIP);
		
		$widget_ops = array('classname' => __CLASS__, 'description' => __('Description goes here.'));
		$control_ops = array('width' => 400, 'height' => 350);
		
		# THIS WORKS:
		//parent::__construct('eeeeeeeeee', 'wtf son?', $widget_ops, $control_ops);
		
		# `$id_base` and `$name` are not getting passed:
		parent::__construct($id_base, $name, $widget_ops, $control_ops); # Why doesn't this work? Is it a timing thing?
		
		add_action(
			'widgets_init',
			function() {
				register_widget(get_class($this->t));
			}
		);
		
	}
	
	public function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
		
		$instance['text'] = $new_instance['text']; // Unfiltered HTML.
		
		return $instance;
		
	}
	
	public function form($instance) {
		
		$instance = wp_parse_args((array) $instance, array('text' => '',));
		
		$text = esc_textarea($instance['text']);
		
		?>
		
		<textarea rows="16" cols="20" name="<?=$this->get_field_name('text')?>" id="<?=$this->get_field_id('text')?>" class="widefat"><?=$text?></textarea>
		
		<?php
		
	}
	
	public function widget($args, $instance) {
		
		extract($args);
		
		$text = apply_filters(
			'widget_text',
			(empty($instance['text']) ? '' : $instance['text']),
			$instance
		);
		
		echo $text;
		
	}
	
}
