<?php

/**
 * Add custom profile field.
 */

function mah_user_contactmethods($cm) {
	
	# Add "avatar" field:
	$cm['avatar'] = 'Avatar';
	
	return $cm;
	
}

add_filter('user_contactmethods', 'mah_user_contactmethods', 10, 1);

//----------------------------------------------------------------------

/**
 * Get custom profile field "avatar".
 *
 * <img src="<?=mah_get_avatar();?>" width="144" alt="" itemprop="image">
 *
 * @see http://core.trac.wordpress.org/browser/tags/3.6/wp-includes/pluggable.php
 */

if ( ! function_exists('mah_get_avatar')) {
	
	function mah_get_avatar($id = FALSE, $default = 'http://site.com/wp-content/uploads/YYYY/MM/default.png') {
		
		$return = '';
		
		$id = ($id) ? ((int) $id) : get_the_author_meta('ID'); // Empty string if `get_the_author_meta()` fails.
		
		$avatar = get_the_author_meta('avatar', $id); // IBID.
		
		if ( ! $avatar) $avatar = apply_filters('mah_get_avatar_default', $default);
		
		return apply_filters('mah_get_avatar', $avatar);
		
	}
	
}
