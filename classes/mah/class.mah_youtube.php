<?php

/**
 * Get recent YouTube videos based on user name.
 *
 * For an example, see /templates/youtube.php
 *
 * @todo Upgrade to API v3.
 * @todo More comments/docs for class.
 *
 * @see https://github.com/mechabyte/youtube-widget
 * @see http://stackoverflow.com/a/2068371/922323
 * @see http://wordpress-hackers.1065353.n5.nabble.com/Transient-key-best-practice-td42211.html
 * @see http://www.stumiller.me/does-wordpress-delete-expired-transients-from-the-database/
 */

class mah_youtube {
	
	private $username;
	private $playlist;
	private $force;
	private $expires;
	private $items;
	
	function __construct($args = array()) {
		
		# Get options and/or apply defaults:
		extract(
			wp_parse_args(
				$args,
				array(
					'username' => '',        // Required.
					'playlist' => '',        // Playlist ID.
					'force'    => FALSE,     // Cache by default.
					'expires'  => (60 * 60), // Default to 1 hour.
					'items'    => 10,        // Number of videos to return.
				)
			)
		);
		
		# Setup options:
		$this->username = $username;
		$this->playlist = $playlist;
		$this->force    = $force;
		$this->expires  = $expires;
		$this->items    = $items;
		
	}
	
	public function videos() {
		
		# Return value:
		$return = FALSE;
		
		# Playlist or user?
		$kind = ($this->playlist ?: $this->username); // PHP 5.3.
		
		# Must have a username OR playlist:
		if ($kind) {
			
			# Transient key (allow for cached data that varries by playlist OR username, expiration time and number of items):
			$transient = 'mah_yt' . md5($kind . '|' . $this->expires . '|' . $this->items);
			
			# Check to see if we already have cached data:
			$cached = get_transient($transient);
			
			# If we do have cached data, let's continue with that instead of grabbing it again:
			if (( ! $this->force) && $cached) {
				
				# Prepare to return existing cached data:
				$return = $cached->data;
				
			} else {
				
				# YouTube API URL that returns JSON:
				$api = 'https://gdata.youtube.com/feeds/api/%s?fields=entry(published,title,media:group(media:description(),yt:videoid,yt:duration,yt:uploaded),yt:statistics,yt:rating)&alt=json&v=2&max-results=%s';
				
				# Difference between user upload and playlist JSON API calls:
				// https://gdata.youtube.com/feeds/api/    users/USERNAME/uploads                          ?fields=entry(published,title,media:group(media:description(),yt:videoid,yt:duration),yt:statistics,yt:rating)&alt=json&v=2&max-results=20
				// https://gdata.youtube.com/feeds/api/    playlists/PL04d8o8dhm27SI-pUoSGhVVr_rpqXem45    ?fields=entry(published,title,media:group(media:description(),yt:videoid,yt:duration),yt:statistics,yt:rating)&alt=json&v=2&max-results=20
				
				# Useful playlist call that shows a bunch of fields:
				// https://gdata.youtube.com/feeds/api/playlists/PL04d8o8dhm27SI-pUoSGhVVr_rpqXem45?fields=entry&alt=json&v=2&max-results=10&&prettyprint=true
				
				# Get the videos for our user with `wp_remote_get()` (playlist will trump user uploads):
				$response = wp_remote_get(sprintf($api, (($this->playlist) ? 'playlists/' . $this->playlist : 'users/' . $this->username . '/uploads'), $this->items));
				
				# No errors and valid response code?
				if (( ! is_wp_error($response)) OR (wp_remote_retrieve_response_code($response) == 200)) {
					
					# Decode the JSON response:
					$json = json_decode(wp_remote_retrieve_body($response), TRUE);
					
					# Make sure we're working with an array:
					if (is_array($json)) {
						
						# Video array:
						$videos = array();
						
						# Grab what we want:
						$keys = $json['feed']['entry'];
						
						# Loop over the videos:
						foreach(array_keys($keys) as $key) {
							
							# Current video:
							$video = array();
							
							# Video title:
							$video['title'] = $keys[$key]['title']['$t'];
							
							# Video description:
							$video['description'] = $keys[$key]['media$group']['media$description']['$t'];
							
							# Video ID:
							$video['videoid'] = $keys[$key]['media$group']['yt$videoid']['$t'];
							
							# Video view count:
							$video['viewcount'] = number_format($keys[$key]['yt$statistics']['viewCount']);
							
							# Publish date:
							$video['published'] = $keys[$key]['published']['$t'];
							
							# Uploaded:
							$video['uploaded'] = $keys[$key]['media$group']['yt$uploaded']['$t'];
							
							# Duration:
							$video['duration'] = $this->format_time($keys[$key]['media$group']['yt$duration']['seconds']);
							
							# Likes:
							$video['numlikes'] = ( ! empty($keys[$key]['yt$rating'])) ? number_format($keys[$key]['yt$rating']['numLikes']) : 0;
							
							# Video link:
							$video['link'] = 'http://www.youtube.com/watch?v=' . $video['videoid'];
							
							# Preview image url:
							$video['image']['url'] = 'http://img.youtube.com/vi/' . $video['videoid'] . '/';
							
							# The default thumbnail image:
							$video['image']['default'] = $video['image']['url'] . 'default.jpg';
							
							# High quality version of the thumbnail:
							$video['image']['mqdefault'] = $video['image']['url'] . 'mqdefault.jpg';
							
							# Medium quality version of the thumbnail:
							$video['image']['hqdefault'] = $video['image']['url'] . 'hqdefault.jpg';
							
							# Standard definition version of the thumbnail:
							$video['image']['sddefault'] = $video['image']['url'] . 'sddefault.jpg';
							
							#  Maximum resolution version of the thumbnail:
							$video['image']['maxresdefault'] = $video['image']['url'] . 'maxresdefault.jpg';
							
							# Structure:
							/*
								$video['title']
								$video['description']
								$video['videoid']
								$video['viewcount']
								$video['published']
								$video['uploaded']
								$video['duration']
								$video['numlikes']
								$video['link']
								$video['image']['url']
								$video['image']['default']
								$video['image']['mqdefault']
								$video['image']['hqdefault']
								$video['image']['sddefault']
								$video['image']['maxresdefault']
							*/
							
							# Add current video to videos array:
							array_push($videos, $video);
							
						}
						
						# Add video data to new object:
						$cache = new stdClass();
						$cache->kind     = $kind;
						$cache->username = $this->username;
						$cache->playlist = $this->playlist;
						$cache->expires  = $this->expires;
						$cache->items    = $this->items;
						$cache->data     = $videos;
						
						# Cache video data in transient:
						set_transient($transient, $cache, $this->expires);
						
						# Return video data:
						$return = $videos;
						
					}
					
				}
				
			}
			
		}
		
		# Return videos:
		return apply_filters(get_class($this) . '_videos', $return);
		
	}
	
	private function format_time($s) {
		
		$time = round($s);
		$parts = array();
		
		while ($time >= 1) {
			
			array_unshift($parts, $time % 60);
			$time /= 60;
			
		}
		
		if ($s < 60) {
			
			// if it is seconds only, prepend "0:":
			array_unshift($parts, '0');
			
		}
		
		$last = count($parts) - 1;
		
		if ($parts[$last] < 10) {
			$parts[$last] .= '0';
		}
		
		$duration = join(':', $parts);
		
		return $duration;
		
	}
	
}
