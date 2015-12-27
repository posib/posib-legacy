<input type="hidden" name="ref" value="<?=$brick->ref ?>" />
<input type="hidden" name="page" value="<?=$brick->page ?>" />
<input type="hidden" name="gallery_order" value="" />
<div id="posib-input-container">
	<? if( $brick->legend ): ?>
		<input type="hidden" name="legend" value="<?=$brick->legend ?>" />
	<? endif; ?>
	<? if( $brick->description ): ?>
		<input type="hidden" name="description" value="<?=$brick->description ?>" />
	<? endif; ?>
	<? if( sizeof( $brick->gallery ) > 0 ): ?>
		<? foreach( $brick->gallery as $i => $sImagePath ): ?>
			<? $sKey = $brick->gallery_order[ $i ]; ?>
			<?  $sLegendKey = 'gal_' . $sKey . '_legend';
				if( $brick->$sLegendKey ): ?>
					<input type="hidden" name="gal[<?=$sKey ?>][legend]" value="<?=$brick->$sLegendKey ?>" />
			<? endif; ?>
			<?  $sDescriptionKey = 'gal_' . $sKey . '_description';
				if( $brick->$sDescriptionKey ): ?>
					<input type="hidden" name="gal[<?=$sKey ?>][description]" value="<?=$brick->$sDescriptionKey ?>" />
			<? endif; ?>
		<? endforeach; ?>
	<? endif; ?>
</div>

<section class="posib-image-editing">
	<div class="posib-image-editing-section posib-main-image">
		<h4>Image principale</h4>
		<div class="posib-image-box" data-ref="MAIN">
			<img src="<?= $brick->path ?>" <?= $brick->width > $brick->height ? 'height' : 'width' ?>="150" />
			<img src="/posib/static/icons/balloon-ellipsis.png" class="posib-image-tool posib-image-infos" title="modifier les informations de l'image" />
			<img src="/posib/static/icons/image-zoom.png" class="posib-image-tool posib-image-zoom" data-gallery-image="<?= $brick->path ?>" title="aperçu de l'image" />
			<input type="file" name="src" accept="image/*" />
		</div>
	</div>

	<div class="posib-image-editing-section posib-gallery-images">
		<h4>Images affichées au clic</h4>
		<ul>
			<? if( sizeof( $brick->gallery ) > 0 ): ?>
				<? foreach( $brick->gallery as $i => $sImagePath ): ?>
					<? $sKey = $brick->gallery_order[ $i ]; ?>
					<? if( !is_null( $sImagePath ) ): ?>
						<li class="posib-image-box" data-ref="<?=$sKey; ?>">
							<img src="<?='/' .str_replace( ROOT, '', dirname( DATA_PATH ) ) . '/' . $sImagePath; ?>" height="75" />
							<img src="/posib/static/icons/balloon-ellipsis.png" class="posib-image-tool posib-image-infos" title="modifier les informations de l'image" />
							<img src="/posib/static/icons/image-zoom.png" class="posib-image-tool posib-image-zoom" data-gallery-image="<?='/' .str_replace( ROOT, '', dirname( DATA_PATH ) ) . '/' . $sImagePath; ?>" title="aperçu de l'image" />
							<img src="/posib/static/icons/arrow-move.png" class="posib-image-tool posib-image-move" title="déplacer l'image" />
							<img src="/posib/static/icons/cross.png" class="posib-image-tool posib-image-remove" title="supprimer l'image" />
							<input type="file" name="gal[<?=$sKey; ?>]" accept="image/*" />
							<input type="text" name="gal[<?=$sKey; ?>][src]" value="<?=$sImagePath; ?>" />
						</li>
					<? endif; ?>
				<? endforeach; ?>
			<? else: ?>
				<? $sKey = Utils::getInstance()->genUID(); ?>
				<li class="posib-image-box" data-ref="<?=$sKey; ?>">
					<img src="http://placehold.it/75x75" height="75" />
					<img src="/posib/static/icons/balloon-ellipsis.png" class="posib-image-tool posib-image-infos" title="modifier les informations de l'image" />
					<img src="/posib/static/icons/image-zoom.png" class="posib-image-tool posib-image-zoom" data-gallery-image="http://placehold.it/500x500" title="aperçu de l'image" />
					<img src="/posib/static/icons/arrow-move.png" title="déplacer l'image" class="posib-image-tool posib-image-move" />
					<img src="/posib/static/icons/cross.png" title="supprimer l'image" class="posib-image-tool posib-image-remove" />
					<input type="file" name="gal[<?=$sKey; ?>]" accept="image/*" />
				</li>
			<? endif; ?>
			<li class="posib-add-gallery-image" title="ajouter une image">
				<span>+</span>
			</li>
		</ul>
	</div>

	<p class="help-block">
		Cliquez sur une image pour la remplacer par une image de votre disque dur.<br />
		<strong>Attention:</strong>
		Si vos images originales sont particulièrement volumineuses, le processus de redimension peut éventuellement être difficile pour le serveur sur plusieurs images en simultané.<br />
		Si vous utilisez de grosses images, envoyez-les en plusieurs fois.
	</p>

	<div class="posib-upload-waiter">
		<img alt="wait..." src="/posib/static/images/wait.gif" />
		<p>
			<strong>Vos fichiers sont en cours d'envoi et de traitement par le serveur.</strong>
			<small>En fonction de la taille de vos fichiers et de la qualité de votre connexion internet, cette procédure peut mettre un peu de temps. Veuillez patienter et ne pas recharger la page.</small>
		</p>
	</div>
</section>

<section class="posib-image-informations">
	<div class="posib-image-preview">
		<div class="posib-image-box">
			<img src="http://placehold.it/75x75" height="75" />
		</div>
	</div>
	<div class="posib-field-group">
		<label for="legend">
			Titre de l'image :
		</label>
		<div class="posib-input">
			<input type="text" class="posib-xlarge" name="legend" id="legend" value="" autofocus="autofocus" autocomplete="off" />
		</div>
	</div>
	<div class="posib-field-group">
		<label for="description">
			Description de l'image :
		</label>
		<div class="posib-input">
			<input type="text" class="posib-xlarge" name="description" id="description" value="" autocomplete="off" />
		</div>
	</div>
</section>
