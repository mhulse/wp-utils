<?php

include_once('class.mah_widget_setup.php');
include_once('class.mah_base_widget_1.php');

class Most_Popular_1 extends Mah_Base_Widget_1
{
	
	public function __construct() {
		
		$this->template = array('includes/widgets/most-popular-1.php',);
		
		parent::__construct(
			
			'most_popular_1',
			'Most Popular',
			array(
				'classname' => __CLASS__,
				'description' => 'Most popular news, comments, other ...',
			)
			
		);
		
		new Mah_Widget_Setup(
			__CLASS__
		);
		
	}
	
}

new Most_Popular_1();
