<?php

/**
 * Custom widgets.
 *
 * // In your `functions.php` file:
 * include_once('classes/mhulse/widgets/widgets.php');
 *
 * @todo Add "Mah_" prefix to widget classes?
 */

include_once('class.medium_rectangle_1.php');
include_once('class.most_popular_1.php');
include_once('class.civic_science_1.php');
include_once('class.social_1.php');
include_once('class.blogger_1.php');
include_once('class.medium_rectangle_2.php');

/*
include_once('class.foo_widget.php');

$foo_test_widget = new Foo_Widget(
	array(
		'id_base' => 'foo_test_widget',
		'name' => 'Foo Test Widget'
	)
);
*/

/*
include_once('class.foo_widget.php');

class Foo_Widget_X extends Foo_Widget {
	
	function __construct() {
		
		parent::__construct(
			array(
				'id_base' => 'foo_test_widget',
				'name' => 'Foo Test Widget',
				'widget_class' => __CLASS__,
			)
		);
		
	}
}

new Foo_Widget_X();
*/
