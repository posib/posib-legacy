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
<ul class="posib-root-users">
	<? if( is_array( UtilsData::getInstance()->get( ':users' ) ) ): ?>
		<? foreach( UtilsData::getInstance()->get( ':users' ) as $sUser=>$sPassword ): ?>
			<li>
				<img class="posib-icon" src="/posib/static/icons/user.png" />
				<span class="posib-user-name"><?=$sUser ?></span>
				<span class="posib-tools">
					<a href="javascript:void(0);" class="posib-user-tools-edit">
						<img src="/posib/static/icons/pencil.png" />
					</a>
					<a href="javascript:void(0);" class="posib-user-tools-delete">
						<img src="/posib/static/icons/cross.png" />
					</a>
				</span>
			</li>
		<? endforeach; ?>
	<? endif; ?>
	<li class="posib-user-add">
		<img class="posib-icon" src="/posib/static/icons/user--plus.png" />
		<span class="posib-user-name">Ajouter un utilisateur</span>
	</li>
</ul>
<div class="posib-mono-confirm">
	<p>Êtes-vous sûr de vouloir supprimer l'utilisateur <span></span> ?</p>
	<p>Cette action ne peut être annulée.</p>
	<div>
		<a href="javascript:void(0);" class="posib-mono-confirm-discard">
			annuler
		</a>
		<a href="javascript:void(0);" class="posib-mono-confirm-confirm">
			oui, supprimer l'utilisateur
		</a>
	</div>
</div>
<div class="posib-edit-user">
	<div class="posib-field-group">
		<label for="login">
			login :
		</label>
		<div class="posib-input">
			<input placeholder="login de l'utilisateur" type="text" class="posib-xlarge" name="login" id="login" value="" autocomplete="off" />
		</div>
	</div>
	<div class="posib-field-group">
		<label for="password">
			mot de passe :
		</label>
		<div class="posib-input">
			<input placeholder="mot de passe de l'utilisateur" type="text" class="posib-xlarge" name="password" id="password" value="" autocomplete="off" />
			<div class="help-inline">
				<p>Entrez un mot de passe pour remplacer le mot de passe existant.</p>
			</div>
		</div>
	</div>
</div>
