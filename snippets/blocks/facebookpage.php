<?php
snippet('consent-gate', [
	'vendor' => 'facebook',
	'content' => Fendinger\ConsentGate::getFacebookpageHtml($block->src()),
]);
