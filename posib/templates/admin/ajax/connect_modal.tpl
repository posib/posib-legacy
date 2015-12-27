<div class="modal">
	<form method="post" action="/admin/">
		<div class="modal-header">
			<a href="/" class="close">×</a>
			<h3>
				<img alt="" src="/posib/static/icons/jar-label.png" />
				Connexion à l'interface d'administration
			</h3>
		</div>
		<div class="modal-body">
			<?php if($error): ?>
				<div class="alert-message block-message error">
					<strong>erreur: login et/ou mot de passe incorrect !</strong>
				</div>
			<?php endif; ?>
			<?php if($error): ?>
				<input type="hidden" name="error" value="<?= $error ?>" />
			<?php endif; ?>

			<input type="hidden" name="referer" value="<?= $referer ?>" />

			<div class="clearfix">
				<label for="login">
					login:
				</label>
				<div class="input">
					<input type="text" autofocus="autofocus" autocomplete="off" id="login" name="login" placeholder="votre login" class="xlarge" />
				</div>
			</div>

			<div class="clearfix">
				<label for="password">
					mot de passe:
				</label>
				<div class="input">
					<input type="password" id="password" name="password" placeholder="votre mot de passe" class="xlarge" />
				</div>
			</div>

			<!--[if lt IE 9]>
				<div class="alert-message block-message error ie">
					<strong>Attention ! </strong><br /><br />
					Votre navigateur (Internet Explorer) présente de sérieuses lacunes en terme de sécurité et de performances, dues à son obsolescence.<br />
					En conséquence, l'outil d'administration de votre site devrait être utilisable normalement mais de manière <em>moins optimale qu'avec un navigateur récent</em> (<a href="http://www.google.com/chrome?hl=fr">Chrome</a>, <a href="http://www.mozilla-europe.org/fr/firefox/">Firefox</a>, <a href="http://www.apple.com/fr/safari/download/">Safari</a>, &hellip;)
				</div>
			<![endif]-->
		</div>
		<div class="modal-footer">
			<a href="<?= ( Data::get( 'branding_id' ) ) ? Data::get( 'branding_url' ) : 'http://www.posib.be' ?>" rel="external" class="modal-copyright" id="<?= ( Data::get( 'branding_id' ) ) ? Data::get( 'branding_id' ) : 'posib' ?>">
				<?= ( Data::get( 'branding_id' ) ) ? Data::get( 'branding_name' ) : 'posib.' ?>
			</a>

			<input type="submit" id="connect" name="connect" class="btn success" value="connexion" />
			<a href="/" class="btn secondary back">retour</a>
		</div>
	</form>
</div>
