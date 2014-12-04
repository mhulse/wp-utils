<?php

/**
 * Generic sidebar widget that contains a title and text field.
 *
 * See "Text widget class" in `/wp-includes/default-widgets.php`.
 *
 * @todo Option to make this a single-instance widget.
 * @todo Need constant that allows user to pick template base location.
 * @todo Make more like WPAlchemy.
 *
 * @see http://wordpress.stackexchange.com/questions/32103
 * @see http://wordpress.stackexchange.com/a/1834/32387
 * @see http://wordpress.stackexchange.com/questions/104077
 * @see https://github.com/farinspace/wpalchemy
 */

abstract class Mah_Base_Widget_1 extends WP_Widget
{
	
	protected $template = array();
	
	public function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = $new_instance['text']; // Unfiltered HTML.
		
		return $instance;
		
	}
	
	public function form($instance) {
		
		$instance = wp_parse_args((array) $instance, array('title' => '', 'text' => '',));
		
		$title = strip_tags($instance['title']);
		$text = esc_textarea($instance['text']);
		
		?>
		
		<p>
			<label for="<?=$this->get_field_id('title')?>">Title:</label>
			<input type="text" name="<?=$this->get_field_name('title')?>" id="<?=$this->get_field_id('title')?>" class="widefat" value="<?=esc_attr($title)?>">
		</p>
		
		<textarea rows="16" cols="20" name="<?=$this->get_field_name('text')?>" id="<?=$this->get_field_id('text')?>" class="widefat"><?=$text?></textarea>
		
		<?php
		
	}
	
	/**
	 * @see http://wordpress.stackexchange.com/a/4471/32387
	 */
	
	public function widget($args, $instance) {
		
		extract($args);
		
		$title = apply_filters(
			'widget_title',                                        // The name (`$tag`) of the filter hook.
			(empty($instance['title']) ? '' : $instance['title']), // The value which the filters hooked to `$tag` may modify.
			$instance,                                             // Additional variable passed to the filter functions.
			$this->id_base                                         // IBID.
		);
		
		$text = apply_filters(
			'widget_text',
			(empty($instance['text']) ? '' : $instance['text']),
			$instance
		);
		
		if ($text != '') {
			
			echo $text;
			
		} else {
			
			# Remember: Arguments will be available to included template.
			if ( ! empty($this->template)) include(locate_template($this->template));
			
		}
		
	}
	
}
