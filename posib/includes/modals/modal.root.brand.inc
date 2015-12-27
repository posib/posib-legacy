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
<div class="posib-edit-brand">
	<div class="posib-field-group">
		<label for="brand">
			Branding :
		</label>
		<div class="posib-input">
			<select name="brand" id="brand">
				<? foreach( Branding::getInstance()->brands as $sBrand ): ?>
					<option value="<?=$sBrand; ?>"<?=( UtilsData::getInstance()->get( UtilsData::SP . 'config' . UtilsData::SP . 'brand' ) === $sBrand ) ? ' selected="selected"' : '' ?>><?=$sBrand; ?></option>
				<? endforeach; ?>
			</select>
		</div>
	</div>
</div>
