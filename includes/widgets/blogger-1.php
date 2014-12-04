<?php
	
	$description = '';
	
	if ($title) {
		
		/**
		 * Use the "title" of the widget as the blogger's username.
		 */
		
		$user = get_user_by('login', $title);
		
	} else {
		
		/**
		 * Get the blogger username from the theme options.
		 * Using the default author for blog.
		 */
		
		$options = get_option('mah_options');
		
		if (is_array($options) && $options['username']) {
			
			$user = get_user_by('login', $options['username']);
			
		}
		
	}
	
	if ($user) $description = get_the_author_meta('description', $user->ID);
	
?>

<?php if ($description): ?>
	
	<section>
		
		<h1 class="head off">About &hellip;</h1>
		
		<div class="fix">
			
			<figure style="float:right;display:inline;" class="sig" itemscope itemtype="http://schema.org/Person">
				
				<?php $details = get_blog_details(); ?>
				
				<a href="<?=$details->siteurl?>" itemprop="url">
					
					<img src="<?=mah_get_avatar($user->ID);?>" width="144" alt="" itemprop="image">
					
					<?php
						
						$author_first = get_the_author_meta('first_name', $user->ID);
						$author_last = get_the_author_meta('last_name', $user->ID);
						
					?>
					
					<?php if ($author_first || $author_last): ?>
						
						<figcaption itemprop="name">
							
							<?php if ($author_first): ?><span itemprop="givenName"><?=$author_first?></span><?php endif; ?>
							
							<?php if ($author_last): ?><span itemprop="familyName"><?=$author_last?></span><?php endif; ?>
							
						</figcaption>
						
					<?php endif; ?>
					
				</a>
				
			</figure> <!-- /.sig -->
			
			<p style="font-style: italic;"><?=$description?></p>
			
		</div>
		
	</section>
	
	<hr>
	
<?php endif; ?>
