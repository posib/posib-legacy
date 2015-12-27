<dl class="history-manager">
	<? foreach( $brick->getTimelineSteps() as $iTime ): ?>
		<dt>
			<span><?= date( "d/m/y H:i", $iTime ); ?></span>
		</dt>
		<dd>
			<div class="posib-history-content">
				<? include( POSIB . 'includes/modals/modal.' . $sStepTemplate . '.inc' ); ?>
			</div>
			<div class="posib-history-tools">
				<a href="/admin/restore/<?=$iTime; ?>/<?=$brick->type; ?>/<?=$brick->ref; ?>/<?=$brick->page; ?>">restaurer à cette date</a>
			</div>
		</dd>
	<? endforeach; ?>
</dl>
