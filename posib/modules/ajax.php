<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /modules/ajax.php - main module for ajax
 */

global $wout, $utils;

function ajax_admin_middleware() {
	global $utils;
	if( $utils->globals->session( 'connected' ) )
		return true;
	else
		die( null );
} // ajax_admin_middleware

$wout->post( '/ajax/connect.html', function() use( $wout, $utils ) {
	$oModal = new Modals();
	$oModal->setTitle( "Connexion à l'interface d'administration" );
	$oModal->displayConnectBox( $utils->globals->post( 'error' ) == 'true' );
} );

$wout->post( '/ajax/edit.short.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oBrick = Brick::getBrickFromRefAndTag( $utils->globals->post( 'page' ), $utils->globals->post( 'ref' ), $utils->globals->post( 'tag' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
	$oModal = new Modals();
	$oModal->setTitle( "Édition d'un texte court", "edit-small-caps" );
	$oModal->displayEditBox( Brick::TYPE_SHORT, $oBrick );
} );

$wout->post( '/ajax/edit.rich.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oBrick = Brick::getBrickFromRefAndTag( $utils->globals->post( 'page' ), $utils->globals->post( 'ref' ), $utils->globals->post( 'tag' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
	$oModal = new Modals();
	$oModal->setTitle( "Édition d'un bloc de texte", "edit-alignment" );
	$oModal->displayEditBox( Brick::TYPE_RICH, $oBrick );
} );

$wout->post( '/ajax/edit.image.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oBrick = Brick::getBrickFromRefAndTag( $utils->globals->post( 'page' ), $utils->globals->post( 'ref' ), $utils->globals->post( 'tag' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
	$oModal = new Modals();
	$oModal->setTitle( "Édition d'une image", "image" );
	$oModal->displayEditBox( Brick::TYPE_IMAGE, $oBrick, true );
} );

$wout->post( '/ajax/edit.map.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oBrick = Brick::getBrickFromRefAndTag( $utils->globals->post( 'page' ), $utils->globals->post( 'ref' ), $utils->globals->post( 'tag' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
	$oModal = new Modals();
	$oModal->setTitle( "Édition d'une Google Map", "map-pin" );
	$oModal->displayEditBox( Brick::TYPE_MAP, $oBrick );
} );

$wout->post( '/ajax/edit.form.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oBrick = Brick::getBrickFromRefAndTag( $utils->globals->post( 'page' ), $utils->globals->post( 'ref' ), $utils->globals->post( 'tag' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
	$oModal = new Modals();
	$oModal->setTitle( "Édition d'un formulaire", "application-form" );
	$oModal->displayEditBox( Brick::TYPE_FORM, $oBrick );
} );

$wout->post( '/ajax/edit.time.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oBrick = Brick::getBrickFromRefAndTag( $utils->globals->post( 'page' ), $utils->globals->post( 'ref' ), $utils->globals->post( 'tag' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
	$oModal = new Modals();
	$oModal->setTitle( "Édition d'une date", "calendar-month" );
	$oModal->displayEditBox( Brick::TYPE_TIME, $oBrick );
} );

$wout->post( '/ajax/edit.file.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oBrick = Brick::getBrickFromRefAndTag( $utils->globals->post( 'page' ), $utils->globals->post( 'ref' ), $utils->globals->post( 'tag' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
	$oModal = new Modals();
	$oModal->setTitle( "Édition d'un lien de téléchargement de fichier", "drive-download" );
	$oModal->displayEditBox( Brick::TYPE_FILE, $oBrick, true );
} );

$wout->post( '/ajax/root.brand.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oModal = new Modals();
	$oModal->setTitle( "Gestion du branding", "stamp" );
	$oModal->displayRootBox( 'brand' );
} );

$wout->post( '/ajax/root.users.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oModal = new Modals();
	$oModal->setTitle( "Gestion des utilisateurs", "users" );
	$oModal->displayRootBox( 'users' );
} );

$wout->post( '/ajax/root.config.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oModal = new Modals();
	$oModal->setTitle( "Édition de la config", "hammer-screwdriver" );
	$oModal->displayRootBox( 'config' );
} );

$wout->post( '/ajax/edit.infos.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oModal = new Modals();
	$oModal->setTitle( "Édition des infos de la page", "document-hf-select" );
	$oModal->displayInfosBox( $utils->globals->post( 'ref' ) );
} );

$wout->post( '/ajax/restore.:type.html', 'ajax_admin_middleware', function( $sType ) use( $wout, $utils ) {
	switch( $sType ) {
		case 'short': $sBrickClass = 'ShortBrick'; break;
		case 'rich': $sBrickClass = 'RichBrick'; break;
		case 'image': $sBrickClass = 'ImageBrick'; break;
		case 'map': $sBrickClass = 'MapBrick'; break;
		case 'form': $sBrickClass = 'FormBrick'; break;
		case 'list': $sBrickClass = 'ListBrick'; break;
		case 'time': $sBrickClass = 'TimeBrick'; break;
		case 'file': $sBrickClass = 'FileBrick'; break;
	}
	$sRef = $utils->globals->post( 'ref' );
	$sPage = $utils->globals->post( 'page' );
	$oModal = new Modals();
	$oModal->displayRestoreBox( $sType, new $sBrickClass( $sPage, $sRef, $utils->globals->session( 'lang', $utils->getDefaultLanguage() ) ) );
} );

$wout->post( '/ajax/sitemap.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oModal = new Modals();
	$aPages = $utils->getPages();
	$oModal->setTitle( "Édition des pages du site", "sitemap-image" );
	$oModal->displaySitemapBox( $aPages );
} );

$wout->post( '/ajax/sitemap.order.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$utils->data->set( ':sitemap', $utils->globals->post( 'order' ) );
	die( $utils->data->save() );
} );

$wout->post( '/ajax/list.manager.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oListBrick = new ListBrick( $utils->globals->post( 'page' ), $utils->globals->post( 'list' ), $utils->getDefaultLanguage() );
	$oModal = new Modals();
	$oModal->setTitle( "Gestion de la liste", "category" );
	$oModal->displayListManagerBox( $utils->globals->post( 'list' ), $oListBrick );
} );

$wout->post( '/ajax/list/:ref/list.order.html', 'ajax_admin_middleware', function( $sListRef ) use( $wout, $utils ) {
	$oListBrick = new ListBrick( $utils->globals->post( 'page' ), $sListRef, $utils->getDefaultLanguage() );
	$oListBrick->content = $utils->globals->post( 'order' );
	die( $oListBrick->save() );
} );

$wout->post( '/ajax/about.html', 'ajax_admin_middleware', function() use( $wout, $utils ) {
	$oBranding = Branding::getInstance();
	$oBranding->brand = $utils->data->get( ':config:brand', Branding::BRAND_POSIB );
	$sChangelog = file_get_contents( POSIB . 'changelog.inc' );
	$oModal = new Modals();
	$oModal->setTitle( "À propos de...", 'infocard' );
	$oModal->displayAboutBox( $oBranding, $sChangelog );
} );
