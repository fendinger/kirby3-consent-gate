<?php

if (Str::contains($block->src(), 'goo', true) === true) {
	snippet('consent-gate', [
		'vendor' => 'googlemaps',
		'content' => Fendinger\ConsentGate::getGooglemapsHtml($block->src()),
	]);
} else {
	if (Str::contains($block->src(), 'open', true) === true) {
		snippet('consent-gate', [
			'vendor' => 'openstreetmap',
			'content' => Fendinger\ConsentGate::getOpenstreetmapHtml($block->src()),
		]);
	}
}