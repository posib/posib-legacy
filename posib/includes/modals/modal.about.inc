<h4 class="system_infos">
	<a href="<?=$branding->url; ?>" rel="external">
		<?=$branding->name; ?>
	</a>
	<span class="version">
		v<?=$branding->version; ?>
	</span>
    <small><?=$_SERVER[ 'SERVER_ADDR' ] ?></small>
</h4>

<dl class="changelog">
	<?=$changelog; ?>
</dl>
