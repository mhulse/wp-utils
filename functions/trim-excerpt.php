<?php

/**
 * Removes and replaces the excerpt function.
 *
 * <p><?=get_the_excerpt()?></p>
 *
 * @todo Make as class.
 * @todo Remove empty HTML tags (http://codesnap.blogspot.com/2011/04/recursively-remove-empty-html-tags.html).
 * @see http://core.trac.wordpress.org/browser/tags/3.5.1/wp-includes/formatting.php#L2128
 * @see http://bacsoftwareconsulting.com/blog/index.php/wordpress-cat/how-to-preserve-html-tags-in-wordpress-excerpt-without-a-plugin/
 * @see http://bacsoftwareconsulting.com/blog/index.php/wordpress-cat/how-to-create-a-variable-length-excerpt-in-wordpress-without-a-plugin/
 * @see http://www.wpquestions.com/question/show/id/3683
 * @see http://wordpress.org/extend/plugins/advanced-excerpt/
 */

function mah_trim_excerpt($return = '') {
	
	# Use `1` to finish sentence, otherwise use `0`.
	$finish_sentence = 1;
	# Tags to allow in the excerpt:
	$allowed_tags = '<a><strong><b><em><i>';
	
	if ($return == '') {
		
		# Setup text:
		$text = $return;
		# Get the full content:
		$text = get_the_content('');
		# Delete all shortcode tags from the content:
		$text = strip_shortcodes($text);
		# Filter it:
		$text = apply_filters('the_content', $text);
		# From the default wp_trim_excerpt():
		$text = str_replace(']]>', ']]&gt;', $text); // Some kind of precaution against malformed CDATA in RSS feeds I suppose.
		# Remove script tags:
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
		# Allowed tags?
		$text = strip_tags($text, $allowed_tags);
		
		# Word length (depends on setting above).
		$excerpt_length = apply_filters('excerpt_length', 55); // Defaults to `55` words.
		
		# Divide the string into tokens; HTML tags, or words, followed by any whitespace:
		$tokens = array();
		preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $text, $tokens);
		
		# Itterate over word tokens:
		$word = 0;
		foreach ($tokens[0] as $token) {
			
			# Parse each token:
			if (($word >= $excerpt_length) && ( ! $finish_sentence)) {
				
				# Limit reached:
				break; // Quit the loop.
				
			}
			
			# Is token a tag?
			if ($token[0] != '<') {
				
				# Check for the end of the sentence: '.', '?' or '!':
				if (($word >= $excerpt_length) && $finish_sentence && (preg_match('/[\?\.\!]\s*$/uS', $token) == 1)) {
					
					# Limit reached!
					$return .= trim($token); // Continue until '?' '.' or '!' occur to reach the end of the sentence.
					
					break;
					
				}
				
				# Add `1` to $word:
				$word++;
				
			}
			
			# Append what's left of the token:
			$return .= $token;
			
		}
		
		# Add the excerpt suffix:
		$return .= apply_filters('excerpt_more', ' &hellip;');
		
		# Balances tags:
		$return = force_balance_tags($return);
		
		# Trim:
		$return = trim($return);
		
	}
	
	# Return the excerpt:
	return $return;
	
}

remove_all_filters('the_excerpt');
remove_all_filters('get_the_excerpt');
add_filter('get_the_excerpt', 'mah_trim_excerpt');

//----------------------------------------------------------------------

/**
 * Bonus: Remove excerpt suffix.
 */

function mah_excerpt_more($more) {
	
	global $post;
	return ' <a href="'. get_permalink($post->ID) . '">Read&nbsp;more</a>&nbsp;&hellip;';
	
}

add_filter('excerpt_more', 'mah_excerpt_more');
