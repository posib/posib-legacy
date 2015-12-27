<input type="hidden" name="ref" value="<?=$brick->ref ?>" />
<input type="hidden" name="page" value="<?=$brick->page ?>" />
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
<ul class="posib-list-elements" data-list-ref="<?=$brick->ref ?>">
	<? foreach( $brick->content as $i=>$elt ): ?>
		<li data-list-element-ref="<?=$elt; ?>">
			<img class="posib-icon" src="/posib/static/icons/edit-list-item.png" />
			<span class="posib-element-name">Élément n°<span><?=$i + 1 ?></span></span>
			<span class="posib-tools">
				<!--a href="javascript:void(0);" title="Visualiser l'élément" class="posib-list-element-tools-preview">
					<img src="/posib/static/icons/magnifier-medium-left.png" />
				</a-->
				<a href="javascript:void(0);" class="posib-list-element-tools-move">
					<img src="/posib/static/icons/arrow-move.png" />
				</a>
				<a href="javascript:void(0);" title="Supprimer l'élément" class="posib-list-element-tools-delete">
					<img src="/posib/static/icons/cross.png" />
				</a>
			</span>
		</li>
	<? endforeach; ?>
	<li class="posib-list-element-add">
		<img class="posib-icon" src="/posib/static/icons/edit-list-add-item.png" />
		<span class="posib-element-name">Ajouter un élément</span>
	</li>
</ul>
<div class="posib-mono-confirm">
	<p>Êtes-vous sûr de vouloir supprimer l'élément n°<span></span> ?</p>
	<p>Cette action ne peut être annulée.</p>
	<div>
		<a href="javascript:void(0);" class="posib-mono-confirm-discard">
			annuler
		</a>
		<a href="javascript:void(0);" class="posib-mono-confirm-confirm">
			oui, supprimer l'élément
		</a>
	</div>
</div>
