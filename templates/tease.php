<?php

/**
 * Template Name: Blog tease
 * Description: Most recent posts latest blog, with mug.
 *
 * @package mah
 */

?>

<?php $items = ((isset($_GET['items']) && is_numeric($_GET['items'])) ? $_GET['items'] : 4); ?>

<?php foreach (mah_get_last_updated(1) as $blog): ?>
	
	<?php switch_to_blog($blog['blog_id'], true); ?>
	
	<?php $details = get_blog_details($blog['blog_id']); ?>
	
	<section>
		
		<h1 class="head"><a href="http://site.com/">Blogs</a></h1>
		
		<article>
			
			<div class="force">
				
				<?php $latest_post = get_posts('numberposts=1'); ?>
				
				<?php foreach ($latest_post as $post): ?>
					
					<?php setup_postdata($post); ?>
					
					<div class="fix">
						
						<h3 class="label"><a href="<?=$details->siteurl?>"><?=$details->blogname?></a></h3>
						
						<figure style="float:right;display:inline;" class="sig" itemscope itemtype="http://schema.org/Person">
							
							<a href="<?=$details->siteurl?>" itemprop="url">
								
								<img src="<?=mah_get_avatar();?>" width="144" alt="" itemprop="image">
								
								<?php
									
									$author_first = get_the_author_meta('first_name');
									$author_last = get_the_author_meta('last_name');
									
								?>
								
								<?php if ($author_first || $author_last): ?>
									
									<figcaption itemprop="name">
										
										<?php if ($author_first): ?><span itemprop="givenName"><?=$author_first?></span><?php endif; ?>
										
										<?php if ($author_last): ?><span itemprop="familyName"><?=$author_last?></span><?php endif; ?>
										
									</figcaption>
									
								<?php endif; ?>
								
							</a>
							
						</figure> <!-- /.sig -->
						
						<header>
							
							<h1 class="h5"><a href="<?=the_permalink()?>"><?=get_the_title()?></a></h1>
							
							<?php if (has_deck()): ?>
								
								<h2 class="sh6"><?=get_deck()?></h2>
								
							<?php endif; ?>
							
						</header>
						
						<p><?=get_the_excerpt()?></p>
						
					</div> <!-- /.fix -->
					
				<?php endforeach; ?>
				
				<?php wp_reset_postdata(); ?>
				
			</div> <!-- /.force -->
			
		</article>
		
		<?php if ($items > 1): ?>
			
			<?php $latest_posts = get_posts(array('offset' => 1, 'numberposts' => ($items - 1),)); ?>
			
			<?php if ( ! empty($latest_posts)): ?>
				
				<hr>
				
				<ul class="li2">
					
					<?php foreach($latest_posts as $post): ?>
						
						<?php setup_postdata($post); ?>
						
						<li><a href="<?=the_permalink()?>"><?=the_title()?></a></li>
						
					<?php endforeach; ?>
					
				</ul>
				
			<?php endif; ?>
			
			<?php wp_reset_postdata(); ?>
			
		<?php endif; ?>
		
		<footer>
			
			<p class="jump"><a href="<?=$details->siteurl?>">More <span><?=$details->blogname?></span> »</a></p>
			
		</footer>
		
	</section>
	
<?php endforeach; ?>
