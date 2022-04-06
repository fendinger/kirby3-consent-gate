<div class="consent-gate-container" data-consent-needed="gate" data-vendor="<?= strtolower(option('fendinger.consent-gate.vendors')[$vendor]['name']) ?>" data-consent-processed="true">
	<div class="consent-gate" data-role="consent-gate">
		<div class="consent-gate-content"></div>
		<strong><?= tt('fendinger.consent-gate.title', ['vendor' => option('fendinger.consent-gate.vendors')[$vendor]['name']]) ?></strong>
		<div class="consent-gate-vendor">
			<?= option('fendinger.consent-gate.vendors')[$vendor]['logo'] ?>
			<span class="consent-gate-vendor-name"><?= option('fendinger.consent-gate.vendors')[$vendor]['name'] ?></span>
		</div>
		<p class="consent-gate-text">
		<?= tt('fendinger.consent-gate.consent', ['vendor' => option('fendinger.consent-gate.vendors')[$vendor]['name']]) ?>
		</p>
		<div class="consent-gate-toggle" data-role="consent-gate-toggle">
			<div class="toggle-off">
				<svg class="icon consent-gate-icon-toggle-off" xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 0 24 24" width="48"><path d="M0 0h24v24H0z" fill="none"></path><path d="M17 7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h10c2.76 0 5-2.24 5-5s-2.24-5-5-5zM7 15c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"></path></svg>
				<span><?= tt('fendinger.consent-gate.toggle_on', ['vendor' => option('fendinger.consent-gate.vendors')[$vendor]['name']]) ?></span>
			</div>
			<div class="toggle-on">
				<a><?= tt('fendinger.consent-gate.toggle_off', ['vendor' => option('fendinger.consent-gate.vendors')[$vendor]['name']]) ?></a>
				<a data-role="consent-gate-privacy" class="consent-gate-privacy" href="<?= option('fendinger.consent-gate.vendors')[$vendor]['privacy'] ?>" target="_blank"><?= tt('fendinger.consent-gate.privacy', ['vendor' => option('fendinger.consent-gate.vendors')[$vendor]['name']]) ?></a>
			</div>
		</div>
		<p class="consent-gate-text">
		<?= tt('fendinger.consent-gate.agreement', ['vendor' => option('fendinger.consent-gate.vendors')[$vendor]['name']]) ?><br> <?= tt('fendinger.consent-gate.more', ['vendor' => option('fendinger.consent-gate.vendors')[$vendor]['name']]) ?> <a data-role="consent-gate-privacy" class="consent-gate-privacy" href="<?= option('fendinger.consent-gate.vendors')[$vendor]['privacy'] ?>" title="<?= tt('fendinger.consent-gate.privacy', ['vendor' => option('fendinger.consent-gate.vendors')[$vendor]['name']]) ?>" target="_blank"><?= tt('fendinger.consent-gate.privacy', ['vendor' => option('fendinger.consent-gate.vendors')[$vendor]['name']]) ?></a>.
		</p>
	</div>
	<script data-role="consent-gate-template" data-content=""></script>
	<script data-role="consent-gate-content-template" data-content="<?= htmlentities('<div class="oembed-content">' . $content . '</div>') ?>"></script>
</div>
<?php
	if (!c::get('fendinger/consent-gate/assets')) {
		echo css('media/plugins/fendinger/consent-gate/consent-gate.css?v=1');
		echo js('media/plugins/fendinger/consent-gate/consent-gate.js?v=1');
		c::set('fendinger/consent-gate/assets', true);
	}
?>