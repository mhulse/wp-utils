<?php

/**
 * Get latest posts from multisite blogs.
 *
 * <?php $posts = mah_recent_ms_posts(10); ?>
 * 
 * <?php foreach ($posts as $post): ?>
 *      
 *     <a href="<?=get_blog_details($post->blog_id)->siteurl?>"><?=get_blog_details($post->blog_id)->blogname?></a>: <a href="<?=$post->permalink?>"><?=$post->post_title?></a>
 *     
 * <?php endforeach; ?>
 *
 * @todo Move `$ignore` values to more global location?
 *
 * @see http://wordpress.stackexchange.com/q/5001/32387
 * @see http://wordpress.stackexchange.com/a/49027/32387
 * @see https://gist.github.com/mhulse/5718743
 * @see http://snipplr.com/view/65413/
 * @see http://wordpress-hackers.1065353.n5.nabble.com/WP-3-5-2-multisite-How-to-use-NOT-IN-in-wpdb-prepare-tp41812.html
 */

function mah_recent_ms_posts($count = 10, $ignore = array('1', '19', '21',)) {
	
	global $wpdb, $table_prefix;
	
	$ignore = implode(', ', array_map('absint', $ignore));
	$rows = NULL;
	$tables = array();
	$query = '';
	$i = 0;
	$posts = array();
	$post = NULL;
	
	$rows = $wpdb->get_results($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs WHERE blog_id NOT IN ($ignore) AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0'", 0), ARRAY_A);
	
	if ($rows) {
		
		foreach ($rows as $row) {
			
			$tables[$row['blog_id']] = $wpdb->get_blog_prefix($row['blog_id']) . 'posts';
			
		}
		
		if (count($tables)) {
			
			foreach ($tables as $blog_id => $table) {
				
				if ($i) {
					
					$query .= ' UNION ';
					
				}
				
				$query .= " (SELECT ID, post_date, $blog_id as `blog_id` FROM $table WHERE post_status = 'publish' AND post_type = 'post')";
				
				$i++;
				
			}
			
			$query .= " ORDER BY post_date DESC LIMIT 0, $count;";
			
			$rows = $wpdb->get_results($query);
			
			if ($rows) {
				
				foreach ($rows as $row) {
					
					$post = get_blog_post($row->blog_id, $row->ID);
					$post->blog_id = $row->blog_id;
					$post->row_id =$row->ID;
					$post->permalink = get_blog_permalink($row->blog_id, $row->ID);
					
					$posts[] = $post;
					
				}
				
				return $posts;
				
			}
			
		}
		
	}
	
}
