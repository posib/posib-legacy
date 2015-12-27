<input type="hidden" name="ref" value="<?=$brick->ref ?>" />
<input type="hidden" name="page" value="<?=$brick->page ?>" />

<div class="posib-field-group posib-editor-field-group">
	<label for="content">
		Votre contenu :
	</label>
	<div class="posib-input">
		<div class="posib-editor">
			<div class="posib-editor-toolbar">
				<span class="posib-editor-button-group">
					<a href="javascript:void(0);" class="posib-editor-button-bold" title="mettre en gras">
						mettre en gras
					</a>
					<a href="javascript:void(0);" class="posib-editor-button-italic" title="mettre en italique">
						mettre en italique
					</a>
					<a href="javascript:void(0);" class="posib-editor-button-underline" title="souligner">
						souligner
					</a>
				</span>
				<? /*
				<span class="posib-editor-button-group">
					<a href="javascript:void(0);" class="posib-editor-button-ol" title="ajouter une liste numérotée">
						ajouter une liste numérotée
					</a>
					<a href="javascript:void(0);" class="posib-editor-button-ul" title="ajouter une liste à puces">
						ajouter une liste à puces
					</a>
				</span>
				*/ ?>
				<span class="posib-editor-button-group">
					<a href="javascript:void(0);" class="posib-editor-button-link" title="ajouter un lien">
						ajouter un lien
					</a>
					<a href="javascript:void(0);" class="posib-editor-button-unlink" title="retirer un lien">
						retirer un lien
					</a>
				</span>
				<span class="posib-editor-button-group">
					<a href="javascript:void(0);" class="posib-editor-button-removeformat" title="retirer les styles">
						retirer les styles
					</a>
				</span>
			</div>
			<div class="posib-editor-content" contenteditable="true"><?= $brick->content; ?></div>
		</div>
		<textarea name="content" id="posib-content"><?= $brick->content; ?></textarea>
	</div>
</div>

<div class="posib-field-insert-link">
	<div class="posib-field-group">
		<label for="posib-insert-link-href">
			Adresse :
		</label>
		<div class="posib-input">
			<input placeholder="adresse du lien" type="text" class="posib-xlarge" name="href" id="posib-insert-link-href" value="" autocomplete="off" />
		</div>
	</div>
	<div class="posib-field-group">
		<label for="posib-insert-link-infobulle">
			Infobulle :
		</label>
		<div class="posib-input">
			<input placeholder="infobulle sur le lien" type="text" class="posib-xlarge" name="Infobulle" id="posib-insert-link-infobulle" value="" autocomplete="off" />
		</div>
	</div>
	<div class="posib-field-group">
		<label for="posib-insert-link-target">
			Ouvrir dans :
		</label>
		<div class="posib-input">
			<select name="target" id="posib-insert-link-target">
				<option value="">Fenêtre courante</option>
				<option value="_new">Nouvelle fenêtre</option>
			</select>
		</div>
	</div>
	<div class="posib-field-insert-link-controls">
		<a href="javascript:void(0);" class="posib-field-insert-link-discard">
			annuler
		</a>
		<a href="javascript:void(0);" class="posib-field-insert-link-confirm">
			ajouter le lien
		</a>
	</div>
</div>

