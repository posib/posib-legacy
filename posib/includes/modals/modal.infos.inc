<? $sLang = is_array( UtilsData::getInstance()->get( ':config:lang' ) ) ? ( ':' . Utils::getInstance()->globals->session( 'lang' ) ?: Utils::getInstance()->getDefaultLanguage() ) : null; ?>
<input type="hidden" name="ref" value="<?=$ref ?>" />

<div class="posib-field-group">
	<label for="infos_title">
		Titre de la page :
	</label>
	<div class="posib-input">
		<input type="text" class="posib-xlarge" name="title" id="infos_title" value="<?=UtilsData::getInstance()->get( ':pages:' . $ref . ':title' . $sLang ); ?>" placeholder="titre de la page" />
	</div>
</div>

<div class="posib-field-group">
	<label for="infos_keywords">
		Mots-clés :
	</label>
	<div class="posib-input">
		<textarea type="text" class="posib-xlarge" name="keywords" id="infos_keywords" placeholder="mot1, mot2, mot3"><?=UtilsData::getInstance()->get( ':pages:' . $ref . ':keywords' . $sLang ); ?></textarea>
	</div>
</div>

<div class="posib-field-group">
	<label for="infos_desc">
		Description :
	</label>
	<div class="posib-input">
		<textarea class="posib-xlarge" name="description" id="infos_desc" placeholder="texte de description de la page"><?=UtilsData::getInstance()->get( ':pages:' . $ref . ':description' . $sLang ); ?></textarea>
	</div>
</div>
