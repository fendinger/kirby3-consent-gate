<?php

$video_html = snippet('blocks/video-original', ['block' => $block], true);

if (Str::contains($video_html, 'youtu', true) === true) {
	snippet('consent-gate', [
		'vendor' => 'youtube',
		'content' => $video_html,
	]);
} else {
	if (Str::contains($video_html, 'vimeo', true) === true) {
		snippet('consent-gate', [
			'vendor' => 'vimeo',
			'content' => $video_html,
		]);
	} else {
		echo $video_html;
	}
}
