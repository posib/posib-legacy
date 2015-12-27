<input type="hidden" name="ref" value="<?=$brick->ref ?>" />
<input type="hidden" name="page" value="<?=$brick->page ?>" />

<div class="posib-field-group">
	<label for="target">
		Destinataire(s) :
	</label>
	<div class="posib-input">
		<input type="text" class="posib-xlarge" name="target" id="target" value="<?=$brick->target; ?>" autofocus="autofocus" autocomplete="off" />
		<div class="help-inline">
			Entrez les emails destinataires du formulaire, séparées par des virgules.
		</div>
	</div>
</div>
