<div class="help-block">
	<p>Déplacez le marqueur sur la carte et réglez le niveau de zoom pour ajuster la map.</p>
</div>

<input type="hidden" name="ref" value="<?=$brick->ref ?>" />
<input type="hidden" name="page" value="<?=$brick->page ?>" />

<input type="hidden" name="lat" id="posib-map-lat" value="<?=$brick->lat ?>" />
<input type="hidden" name="lng" id="posib-map-lng" value="<?=$brick->lng ?>" />
<input type="hidden" name="marker_lat" id="posib-marker-lat" value="<?=$brick->marker_lat ?>" />
<input type="hidden" name="marker_lng" id="posib-marker-lng" value="<?=$brick->marker_lng ?>" />
<input type="hidden" name="zoom" id="posib-map-zoom" value="<?=$brick->zoom ?>" />

<div id="posib-modal-map"></div>

<div id="posib-address-finder" class="posib-field-group">
	<div class="posib-input">
		<textarea placeholder="Entrez une adresse et cliquez sur le bouton pour centrer la map et le marqueur sur ce lieu."></textarea><input type="button" id="posib-address-finder-input" name="posib-address-finder" value="Trouver l'adresse" />
	</div>
</div>
