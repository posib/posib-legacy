<span>
	<?=$brick->setLocale(); ?>
	<?=strftime( $brick->getPropertyWhen( 'format', $iTime ), $brick->getPropertyWhen( 'datetime', $iTime ) ); ?>
</span>
