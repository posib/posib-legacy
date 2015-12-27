<input type="hidden" name="ref" value="<?=$brick->ref ?>" />
<input type="hidden" name="page" value="<?=$brick->page ?>" />

<div class="posib-field-group">
    <label for="posib-short-label">
        Texte du lien :
    </label>
    <div class="posib-input">
        <input type="text" class="posib-xlarge" name="label" id="posib-short-label" value="<?=$brick->label; ?>" autofocus="autofocus" autocomplete="off" />
    </div>
</div>

<div class="posib-field-group">
    <label>
        Fichier :
    </label>
    <div class="posib-input">
        <input type="file" id="posib-file-input" name="file" accept="*/*" />
        <div id="posib-file-fileinfos"<?= $brick->name !== '#' ? '' : ' class="empty"' ?>>
            <strong title="<?= $brick->name !== '#' ? $brick->name : '' ?>"><?= $brick->name !== '#' ? Utils::getInstance()->centralEllipsis( $brick->name, 30 ) : '' ?></strong>
            <small><?= $brick->name !== '#' ? $brick->size : '' ?></small>
        </div>
        <a href="javascript:void(0);" id="posib-file-button">choisir un fichier</a>
    </div>
</div>

<div class="posib-upload-waiter">
    <img alt="wait..." src="/posib/static/images/wait.gif" />
    <p>
        <strong>Votre fichier est en cours d'envoi et de traitement par le serveur.</strong>
        <small>En fonction de la taille de votre fichier et de la qualité de votre connexion internet, cette procédure peut mettre un peu de temps. Veuillez patienter et ne pas recharger la page.</small>
    </p>
</div>
