<?php

include_once('class.mah_widget_setup.php');
include_once('class.mah_base_widget_1.php');

class Medium_Rectangle_1 extends Mah_Base_Widget_1
{
	
	public function __construct() {
		
		$this->template = array('includes/widgets/medium-rectangle-1.php');
		
		parent::__construct(
			
			'medium_rectangle_1',
			'Medium Rectangle 1',
			array(
				'classname' => __CLASS__,
				'description' => 'Standard Advertising Unit',
			)
			
		);
		
		new Mah_Widget_Setup(
			__CLASS__
		);
		
	}
	
}

new Medium_Rectangle_1();
