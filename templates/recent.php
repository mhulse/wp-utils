<?php
	
	/**
	 * Template Name: Latest entries
	 *
	 * @package mah
	 */
	
?>

<?php if (isset($_GET['blog']) && ( ! empty($_GET['blog']))): ?>
	
	<?php if (strtolower($_GET['blog']) == 'all'): ?>
		
		<?php # Most recent posts from all blogs: ?>
		
		<?php $items = ((isset($_GET['items']) && is_numeric($_GET['items'])) ? $_GET['items'] : 10); ?>
		
		<?php $posts = mah_recent_ms_posts($items); ?>
		
		<?php foreach ($posts as $post): ?>
			
			<li><span><a href="<?=get_blog_details($post->blog_id)->siteurl?>"><?=get_blog_details($post->blog_id)->blogname?></a>:</span> <a href="<?=$post->permalink?>"><?=$post->post_title?></a></li>
			
		<?php endforeach; ?>
		
	<?php else: ?>
		
		<?php # Most recent posts from a specific blog: ?>
		
		<?php $qs = get_id_from_blogname($_GET['blog']); ?>
		
		<?php if (is_numeric($qs)): ?>
			
			<?php switch_to_blog($qs, true); ?>
				
				<?php $items = ((isset($_GET['items']) && is_numeric($_GET['items'])) ? $_GET['items'] : 10); ?>
				
				<?php $posts = get_posts(array('numberposts' => $items,)); ?>
				
				<?php foreach ($posts as $post): ?>
					
					<?php setup_postdata($post); ?>
					
					<li><a href="<?=the_permalink()?>"><?=the_title()?></a></li>
					
				<?php endforeach; ?>
				
				<?php wp_reset_postdata(); ?>
				
			<?php restore_current_blog(); ?>
			
		<?php endif; ?>
		
	<?php endif; ?>
	
<?php else: ?>
	
	<?php # List of most recent blogs: ?>
	
	<?php $exclude = array(1,); ?>
	
	<?php $items = ((isset($_GET['items']) && is_numeric($_GET['items'])) ? $_GET['items'] : 5); ?>
	
	<?php foreach (mah_get_last_updated($items) as $blog): ?>
		
		<?php if ( ! in_array($blog['blog_id'], $exclude)): ?>
			
			<li><a href="http://<?=$blog['domain']?><?=$blog['path']?>"><?=get_blog_option($blog['blog_id'], 'blogname')?></a></li>
			
		<?php endif; ?>
		
	<?php endforeach; ?>
	
<?php endif; ?>
