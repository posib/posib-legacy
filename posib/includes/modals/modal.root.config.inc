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

<div class="posib-field-group posib-config-field-group">
	<label for=":config:image:resize:width">
		Dimensions des images zoomées :
	</label>
	<div class="posib-input">
		<input type="text" class="posib-number" name=":config:image:resize:width" id=":config:image:resize:width" value="<?=UtilsData::getInstance()->get( ':config:image:resize:width' ); ?>" placeholder="960" autocomplete="off" /> &times; <input type="text" class="posib-number" name=":config:image:resize:height" id=":config:image:resize:height" value="<?=UtilsData::getInstance()->get( ':config:image:resize:height' ); ?>" placeholder="720" autocomplete="off" />
	</div>
</div>

<div class="posib-field-group posib-config-field-group">
	<label for=":config:public:enable_sitemap">
		Activer le mode multipages :
	</label>
	<div class="posib-input">
		<input type="checkbox" name=":config:public:enable_sitemap" id=":config:public:enable_sitemap" value="1"<? if( UtilsData::getInstance()->get( ':config:public:enable_sitemap', false ) ): ?> checked="checked"<? endif; ?> />
	</div>
</div>

<div class="posib-field-group posib-config-field-group">
	<label>
		Mode multi-langues :
	</label>
	<ul class="posib-input posib-multilang-choice">
		<? foreach( array( 'fr', 'nl', 'en', 'de', 'es', 'it' ) as $sPossibleLang ): ?>
			<li>
				<label>
					<input type="checkbox" name=":config:lang[]" value="<?=$sPossibleLang; ?>"<?=( in_array( $sPossibleLang , UtilsData::getInstance()->get( ':config:lang', array() ) ) ) ? ' checked="checked"' : '' ?> />
					<img alt="<?=$sPossibleLang; ?>" src="/posib/static/icons/flags/<?=$sPossibleLang; ?>.png" />
				</label>
			</li>
		<? endforeach; ?>
	</ul>
	<div class="help-block">
		<p>
			Cochez les langues à activer. Si aucune langue n'est cochée, le site fonctionnera avec une seule langue.
		</p>
	</div>
</div>

<div class="posib-field-group posib-config-field-group">
	<label for=":config:public:disable_js">
		Désactiver les javascripts public :
	</label>
	<div class="posib-input">
		<input type="checkbox" name=":config:public:disable_js" id=":config:public:disable_js" value="1"<? if( UtilsData::getInstance()->get( ':config:public:disable_js', false ) ): ?> checked="checked"<? endif; ?> />
	</div>
	<div class="help-block">
		<p>
			En cochant cette case, vous aurez à gérer vous-même les galeries cliquables.
		</p>
	</div>
</div>
