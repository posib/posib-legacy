<div class="posib-reset posib-modal posib-modal-<?=str_replace( '.', '-', $sModalType ); ?>" data-modal-type="<?=$sModalType ?>">
	<form method="post" action="<?=$sFormAction ?>"<?=$bIsUpload ? ' enctype="multipart/form-data"' : '' ?>>
		<header>
			<a href="javascript:void(0);" class="posib-modal-close">×</a>
			<h1>
				<img alt="" src="/posib/static/icons/<?=$sTitleIcon ?>.png" />
				<?=$sTitle; ?>
			</h1>
		</header>
		<fieldset>
			<? include( POSIB . 'includes/modals/modal.' . $sContentTemplate . '.inc' ); ?>
		</fieldset>
		<? if( $bHistoryManager || $bListManager ): ?>
			<section class="posib-modals-tools">
				<? if( $bHistoryManager ): ?>
					<a href="javascript:void(0);" class="posib-modal-restore">
						<img alt="" src="/posib/static/icons/clock-history-frame.png" />
						Historique des versions
					</a>
				<? endif; ?>
				<? if( $bListManager ): ?>
					<a href="javascript:void(0);" class="posib-modal-list" data-list-ref="<?=$brick->listRef ?>">
						<img alt="" src="/posib/static/icons/category.png" />
						Gestion de la liste
					</a>
				<? endif; ?>
			</section>
		<? endif; ?>
		<footer>
			<?
				$oBranding = Branding::getInstance();
				$oBranding->brand = UtilsData::getInstance()->get( UtilsData::SP . 'config' . UtilsData::SP . 'brand', Branding::BRAND_POSIB );
			?>
			<div class="posib-branding" id="brand_<?=$oBranding->brand ?>">
				<a href="<?=$oBranding->url ?>" rel="external" class="modal-copyright">
					<?=$oBranding->name ?>
				</a>
			</div>

			<? if( $bHasButtons ): ?>
				<div class="posib-controls">
					<input type="submit" class="posib-submit" id="connect" name="connect" value="<?=$sSubmitValue ?>" />
					<a href="javascript:void(0);" class="posib-back">retour</a>
				</div>
			<? endif; ?>
		</footer>
	</form>
</div>
