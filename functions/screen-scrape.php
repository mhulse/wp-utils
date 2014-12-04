<?php

/**
 * Screeen scrape a given URI with caching.
 *
 * <?=mah_scrape('key-name', 'http://site.com/most-recent/?blog=all&items=15', 30)?>
 *
 * @see http://wordpress.stackexchange.com/a/73659/32387
 * @see http://codex.wordpress.org/Function_Reference/get_transient
 * @see http://wordpress.stackexchange.com/a/6652/32387
 *
 * @todo Garbage collection.
 * @todo OOP.
 *
 * @param $name { string } Cache key name. Required.
 * @param $uri { string } URI to scrape. Required.
 * @param $interval { integer } Cache time in minutes. Default: `60`.
 * @param $force { boolean } Bypass cache and force scraping. Default: `FALSE`.
 * @param $meta { boolean } Dispaly HTML comments? Default: `TRUE`.
 * @return { string } Scraped content or empty string.
 */

function mah_scrape($name = '', $uri = '', $interval = 60, $force = FALSE, $meta = TRUE) {
	
	$return = '';
	$transient = FALSE;
	$expiration = NULL;
	$response = NULL;
	$body = '';
	
	if (( ! empty($name)) && ( ! empty($uri)) && is_numeric($interval)) {
		
		$transient = (($force) ? FALSE : get_transient($name));
		
		if ($transient === FALSE) {
			
			$expiration = ($interval * 60);
			if ($expiration > 31536000) $expiration = 31536000; // 60 (seconds) * 60 (minutes) * 24 (hours) * 365 (days) = 31536000 seconds/year.
			
			$response = wp_remote_request($uri);
			$code = wp_remote_retrieve_response_code($response);
			$message = wp_remote_retrieve_response_message($response);
			
			if (($code == '200') && ($message == 'OK')) {
				
				$body = trim(wp_remote_retrieve_body($response));
				
				if ($body !== '') {
					
					if ($meta) $body .= sprintf("\n\n<!-- [%s] [%d] [%s] -->", $name, $expiration, date("Ymd h:i:s", time()));
					
					set_transient($name, $body, $expiration);
					
					$return = $body;
					
				}
				
			} else {
				
				if ($meta) $return = sprintf('<!-- [%s] [%s] [%s/%s] -->', $name, $expiration, $code, $message);
				
			}
			
		} else {
			
			$return = $transient;
			
		}
		
	} else {
		
		if ($meta) $return = '<!-- [...] -->';
		
	}
	
	return $return;
	
}

//----------------------------------------------------------------------

/**
 * Shortcode for `mah_scrape`.
 *
 * Example:
 * [scrape name="unique-key" uri="http://foo.com/?params" interval="60" force="yes" meta="no"]
 *
 * @see http://mikefigueroa.com/blog/2011/06/wordpress-shortcode-no-value-attributes/
 *
 * @param $attr { array } User defined attributes in shortcode tag.
 * @return { string } Result of `mah_scrape()`.
 */

function mah_scrape_shortcode($attr) {
	
	extract(shortcode_atts(array(
		'name' => '',
		'uri' => '',
		'interval' => 60,
		'force' => 'no',
		'meta' => 'yes',
	), $attr));
	
	$force = (in_array(strtolower($force), array('yes', 'true'))) ? TRUE : FALSE;
	
	$meta = (in_array(strtolower($meta), array('no', 'false'))) ? FALSE : TRUE;
	
	return mah_scrape($name, $uri, $interval, $force, $meta);
	
}

add_shortcode('scrape', 'mah_scrape_shortcode');
