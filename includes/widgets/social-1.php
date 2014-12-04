<?php
	
	$twitter = '';
	$list = '';
	
	if ($title) {
		
		/**
		 * Use the "title" of the widget as the Twitter username.
		 */
		
		$twitter = $title;
		
		# Is it a list?
		
		if (strstr($twitter, '/')) {
			
			$pieces = explode('/', $twitter);
			
			# Get the username:
			if ( ! empty($pieces[0])) $twitter = $pieces[0];
			
			# Get the list name:
			if ( ! empty($pieces[1])) $list = $pieces[1];
			
		}
		
	} else {
		
		/**
		 * Get the Twitter username from the theme options.
		 * Using the default author for blog.
		 */
		
		$options = get_option('mah_options');
		
		if (is_array($options) && $options['username']) {
			
			$user = get_user_by('login', $options['username']);
			
			if ($user) {
				
				# Get twitter handle:
				$twitter = get_the_author_meta('twitter', $user->ID);
				
				# Remove the `@` if it exists:
				if ($twitter) $twitter = str_replace('@', '', $twitter);
				
			}
			
		}
		
	}
	
?>

<?php if ($twitter): ?>
	
	<section>
		
		<a 
			data-widget-id="1234567890"
			class="twitter-timeline"
			width="100%"
			<?php if ($list): ?>
				href="https://twitter.com/<?=$twitter?>/<?=$list?>"
				data-list-owner-screen-name="<?=$twitter?>"
				data-list-slug="<?=$list?>"
			<?php else: ?>
				href="https://twitter.com/<?=$twitter?>"
				data-screen-name="<?=$twitter?>"
			<?php endif; ?>
		>
			<?php if ($list): ?>
				Tweets from @<?=$twitter?>/<?=$list?>
			<?php else: ?>
				Tweets by @<?=$twitter?>
			<?php endif; ?>
		</a>
		
	</section>
	
	<hr>
	
<?php endif; ?>
