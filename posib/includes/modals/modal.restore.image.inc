<div class="posib-main-image">
	<h4>Image principale</h4>
	<div class="posib-image-box">
		<img src="<?=$brick->getPropertyWhen( 'path', $iTime ); ?>" <?= $brick->width > $brick->height ? 'height' : 'width' ?>="120" style="<?= $brick->width > $brick->height ? 'height' : 'width' ?>: 120px;" />
		<img src="/posib/static/icons/image-zoom.png" class="posib-image-tool posib-image-zoom" data-gallery-image="<?=$brick->getPropertyWhen( 'path', $iTime ); ?>" title="aperçu de l'image" />
	</div>
</div>

<? $sBasePath = '/' . str_replace( ROOT, '', dirname( DATA_PATH ) ) . '/'; ?>
<div class="posib-gallery-images">
	<h4>Images affichées au clic</h4>
	<ul>
		<? if( sizeof( $brick->getPropertyWhen( 'gallery', $iTime ) ) > 0 ): ?>
			<? foreach ( $brick->getPropertyWhen( 'gallery', $iTime ) as $sImagePath ): ?>
				<li class="posib-image-box">
					<img src="<?=$sBasePath . $sImagePath; ?>" height="75" style="height: 75px;" />
					<img src="/posib/static/icons/image-zoom.png" class="posib-image-tool posib-image-zoom" data-gallery-image="<?=$sBasePath . $sImagePath; ?>" title="aperçu de l'image" />
				</li>
			<? endforeach; ?>
		<? endif; ?>
	</ul>
</div>
