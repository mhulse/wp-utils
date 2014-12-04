<?php

include_once('class.mah_widget_setup.php');
include_once('class.mah_base_widget_1.php');

class Social_1 extends Mah_Base_Widget_1
{
	
	public function __construct() {
		
		$this->template = array('includes/widgets/social-1.php');
		
		parent::__construct(
			
			'social_1',
			'Social 1',
			array(
				'classname' => __CLASS__,
				'description' => 'Social widget for bloggers.',
			)
			
		);
		
		new Mah_Widget_Setup(
			__CLASS__
		);
		
	}
	
}

new Social_1();
