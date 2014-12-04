<?php
	
	/**
	 * Template Name: Test
	 *
	 * @package mah
	 */
	
?>

<?php if (have_posts()): ?>
	
	<?php while (have_posts()): ?>
		
		<?=the_post()?>
		
		<?php the_content(); ?>
		
	<?php endwhile; ?>
	
<?php endif; ?>

<hr>

<?php
	
	/*
	global $wpdb;
	$ignore = implode(',', array('1', '19', '21',));
	echo $ignore;
	$start = 0;
	$quantity = 40;
	echo '<pre>';
	print_r($wpdb->get_results($wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND blog_id NOT IN ($ignore) AND public = '1' AND mature = '0' AND spam = '0' AND deleted = '0' AND last_updated != '0000-00-00 00:00:00' ORDER BY last_updated DESC limit %d, %d", $wpdb->siteid, $start, $quantity), ARRAY_A));
	echo '</pre>';
	*/
	
	/*
	global $wpdb;
	$ignore = implode(',', array('1', '19', '21',));
	echo '<pre>';
	$rows = $wpdb->get_results($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs WHERE blog_id NOT IN (%d) AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0'", $ignore), ARRAY_A);
	print_r($rows);
	echo '</pre>';
	*/
	
	/*
	global $wpdb;
	$ignore = implode(', ', array_map('absint', array('1', '19', '21',)));
	echo '<pre>';
	$rows = $wpdb->get_results($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs WHERE blog_id NOT IN ($ignore) AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0'", 0), ARRAY_A);
	print_r($rows);
	echo '</pre>';
	*/
	
?>

<?php
	
	/*
	//delete_option('mah_options');
	$options = get_option('mah_options');
	
	print_r($options);
	*/
	
?>

<?php
/*
[scrape name="blogs-most-recent-10-60" uri="http://site.com/most-recent/?blog=all" interval="60" force="yes" meta="no"]
<ul><?=mah_scrape('test-scrape', 'http://site.com/most-recent/?blog=all', 60)?></ul>
*/
?>
