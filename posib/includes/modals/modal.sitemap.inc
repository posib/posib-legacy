<? $sLang = is_array( UtilsData::getInstance()->get( ':config:lang' ) ) ? ( Utils::getInstance()->globals->session( 'lang' ) ?: Utils::getInstance()->getDefaultLanguage() ) : null; ?>
<? if( Utils::getInstance()->globals->post( 'success' ) ): ?>
	<div class="posib-success-block">
		<img src="/posib/static/icons/tick.png" alt="OK" />
		L'opération a bien été effectuée.
	</div>
<? unset( $_POST[ 'success' ] ); ?>
<? endif; ?>
<? if( Utils::getInstance()->globals->post( 'error' ) ): ?>
	<div class="posib-warning-block">
		<img src="/posib/static/icons/exclamation.png" alt="(!)" />
		Une erreur a eu lieu. Veuillez réessayer.
	</div>
<? unset( $_POST[ 'error' ] ); ?>
<? endif; ?>
<div class="posib-reorder-info posib-reorder-error posib-warning-block">
	<img src="/posib/static/icons/exclamation.png" alt="(!)" />
		Une erreur a eu lieu lors de la sauvegarde. Veuillez réessayer.
</div>
<div class="posib-reorder-info posib-reorder-success posib-success-block">
	<img src="/posib/static/icons/tick.png" alt="OK" />
	Sauvegarde effectuée.
</div>
<div class="posib-reorder-info posib-reorder-pending posib-info-block">
	<img src="/posib/static/icons/hourglass.png" alt="OK" />
	Sauvegarde en cours...
</div>
<ul class="posib-sitemap">
	<? foreach( $pages as $aPageInfos ): ?>
		<li>
			<? if( $aPageInfos[ 'url' ] == Utils::getInstance()->getDirectoryIndex( ROOT ) ): ?>
				<img src="/posib/static/icons/home-medium.png" class="posib-icon" />
			<? else: ?>
				<img src="/posib/static/icons/document-text-image.png" class="posib-icon" />
			<? endif; ?>
			<span class="posib-page-name" title="nom de la page dans le menu"><?=$sLang ? ( ( is_array( $aPageInfos[ 'name' ] ) && isset( $aPageInfos[ 'name' ][ $sLang ] ) ) ? $aPageInfos[ 'name' ][ $sLang ] : ( is_array( $aPageInfos[ 'name' ] ) ? $aPageInfos[ 'url' ] : $aPageInfos[ 'name' ] ) ) : $aPageInfos[ 'name' ]; ?></span>
			<span class="posib-page-url" title="adresse de la page"><?=$aPageInfos[ 'url' ]; ?></span>
			<span class="posib-page-template" title="modèle de la page"><?=$aPageInfos[ 'template' ]; ?></span>
			<span class="posib-tools">
				<a href="javascript:void(0);" class="posib-document-tools-edit" title="éditer la page">
					<img src="/posib/static/icons/pencil.png" />
				</a>
				<? if( $aPageInfos[ 'url' ] != Utils::getInstance()->getDirectoryIndex( ROOT ) ): ?>
					<a href="javascript:void(0);" title="supprimer la page" class="posib-document-tools-delete">
						<img src="/posib/static/icons/cross.png" />
					</a>
				<? endif; ?>
				<a href="javascript:void(0);" class="posib-document-tools-move" title="changer l'ordre des pages">
					<img src="/posib/static/icons/arrow-move.png" />
				</a>
			</span>
		</li>
	<? endforeach; ?>
	<li class="posib-page-add">
		<img class="posib-icon" src="/posib/static/icons/document--plus.png" />
		<span class="posib-page-name">Ajouter une page</span>
	</li>
</ul>
<div class="posib-edit-page">
	<div class="posib-field-group posib-edit-page-name">
		<label for="page">
			nom de la page :
		</label>
		<div class="posib-input">
			<input placeholder="nom de la page" type="text" class="posib-xlarge" name="page" id="page" value="" autocomplete="off" />
		</div>
	</div>
	<div class="posib-field-group posib-edit-page-url">
		<label for="url">
			adresse de la page :
		</label>
		<div class="posib-input">
			<input placeholder="adresse_page.html" type="text" class="posib-xlarge" name="url" id="url" value="" autocomplete="off" />
		</div>
	</div>
	<div class="posib-field-group posib-edit-page-template">
		<label for="template">
			modèle de page :
		</label>
		<div class="posib-input">
			<select name="template" id="template">
				<? foreach( Utils::getInstance()->getTemplates( ROOT ) as $sTemplateFile ): ?>
					<option value="<?=$sTemplateFile; ?>"><?=$sTemplateFile; ?></option>
				<? endforeach; ?>
			</select>
			<div class="help-inline">
				<p>La page nouvellement créée utilisera le design et aura les mêmes zones éditables que la page de votre choix.</p>
			</div>
		</div>
	</div>
</div>
<div class="posib-mono-confirm">
	<p>Êtes-vous sûr de vouloir supprimer la page <span></span> ?</p>
	<p>Cette action ne peut être annulée.</p>
	<div>
		<a href="javascript:void(0);" class="posib-mono-confirm-discard">
			annuler
		</a>
		<a href="javascript:void(0);" class="posib-mono-confirm-confirm">
			oui, supprimer la page
		</a>
	</div>
</div>
