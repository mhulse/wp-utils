<?php

/**
 * Template Name: YouTube
 *
 * Example username: comedycentral
 * Example playlist: EL3WQJcBtrVZi8NqTuJb2BKQ
 *
 * @package mah
 */

?>

<?php include_once(get_template_directory() . '/classes/mah/class.mah_youtube.php'); ?>

<?php if ((isset($_GET['username']) && ( ! empty($_GET['username']))) || (isset($_GET['playlist']) && ( ! empty($_GET['playlist'])))): ?>
	
	<?php
		
		$youtube = new mah_youtube(
			array(
				'username' => ((isset($_GET['username'])) ? $_GET['username'] : ''),
				'force' => ((isset($_GET['force'])) ? TRUE : FALSE),
				'items' => ((isset($_GET['items'])) ? $_GET['items'] : 10),
				'playlist' => ((isset($_GET['playlist'])) ? $_GET['playlist'] : ''),
			)
		);
		
		$videos = $youtube->videos();
		
	?>
	
	<?php
	/*
	<pre>
		<?=print_r($youtube->videos())?>
	</pre>
	*/
	?>
	
	<?php for($i = 0, $total = count($videos); $i < $total; $i++): ?>
		
		<?php $video = $videos[$i]; ?>
		
		<article>
			<div class="fix">
				<img class="imgr" src="<?=$video['image']['mqdefault']?>" width="90" alt="">
				<h1 class="h5"><a href="<?=$video['link']?>"><?=$video['title']?></a></h1>
				<?php if ($video['description']): ?>
					<p><?=$video['description']?></p>
				<?php endif; ?>
			</div>
		</article>
		
		<?php if (($i + 1) != $total): ?>
			<hr>
		<?php endif; ?>
		
	<?php endfor; ?>
	
<?php endif; ?>
