<?php

$image_html = snippet('blocks/image-original', ['block' => $block], true);

if (Str::contains($image_html, url(), true) === true) {
	echo $image_html;
} else {
	snippet('consent-gate', [
		'vendor' => 'unknown',
		'content' => $image_html,
	]);
}
