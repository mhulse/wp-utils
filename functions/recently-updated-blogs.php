<?php

/**
 * Get a list of the most recently updated blogs in a multisite install.
 *
 * A copy of WP's `get_last_updated()`, exept I've added ability to filter
 * and removed the need to pass so many args.
 *
 * <?php foreach (mah_get_last_updated() as $blog): ?>
 *     
 *     <?php switch_to_blog($blog['blog_id'], true); ?>
 *     
 *     <?php $details = get_blog_details($blog['blog_id']); ?>
 * 
 *     <a href="<?=$details->siteurl?>"><?=$details->blogname?></a>
 * 
 *     <?php $latest_post = get_posts('numberposts=1'); ?>
 *     
 *     <?php foreach ($latest_post as $post): ?>
 *         
 *         <?php setup_postdata($post); ?>
 *         
 *         ...
 *         
 *     <?php endforeach; ?>
 *     
 *     <?php wp_reset_postdata(); ?>
 *     
 *     <?php restore_current_blog(); ?>
 *     
 * <?php endforeach; ?>
 *
 * @todo Move `$ignore` values to more global location?
 *
 * @see https://gist.github.com/mhulse/5718743
 * @see http://wpseek.com/get_last_updated/
 * @see http://codex.wordpress.org/WPMU_Functions/get_last_updated
 * @see http://wordpress-hackers.1065353.n5.nabble.com/WP-3-5-2-multisite-How-to-use-NOT-IN-in-wpdb-prepare-tp41812.html
 */

function mah_get_last_updated($quantity = 40, $start = 0, $ignore = array('1', '19', '21',)) {
	
	global $wpdb;
	
	$ignore = implode(', ', array_map('absint', $ignore));
	
	return $wpdb->get_results($wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND blog_id NOT IN ($ignore) AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' AND last_updated != '0000-00-00 00:00:00' ORDER BY last_updated DESC limit %d, %d", $wpdb->siteid, $start, $quantity), ARRAY_A);
	
}
