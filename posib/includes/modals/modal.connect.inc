<?php if( $error ): ?>
	<div class="posib-block-error">
		<strong>erreur: login et/ou mot de passe incorrect !</strong>
	</div>
<?php endif; ?>

<?php if( $error ): ?>
	<input type="hidden" name="error" value="1" />
<?php endif; ?>

<div class="posib-field-group">
	<label for="login">
		login :
	</label>
	<div class="posib-input">
		<input type="text" autofocus="autofocus" autocomplete="off" id="login" name="login" placeholder="votre login" class="posib-xlarge" />
	</div>
</div>
<div class="posib-field-group">
	<label for="password">
		mot de passe :
	</label>
	<div class="posib-input">
		<input type="password" id="password" name="password" placeholder="votre mot de passe" class="posib-xlarge" />
	</div>
</div>
