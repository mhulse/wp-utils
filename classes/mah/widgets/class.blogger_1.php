<?php

include_once('class.mah_widget_setup.php');
include_once('class.mah_base_widget_1.php');

class Blogger_1 extends Mah_Base_Widget_1
{
	
	public function __construct() {
		
		$this->template = array('includes/widgets/blogger-1.php');
		
		parent::__construct(
			
			'blogger_1',
			'Blogger 1',
			array(
				'classname' => __CLASS__,
				'description' => 'The lead blogger\'s bio widget.',
			)
			
		);
		
		new Mah_Widget_Setup(
			__CLASS__
		);
		
	}
	
}

new Blogger_1();
