<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /modules/admin.php - main module for admin
 */

global $wout, $utils;

function admin_middleware() {
	global $wout, $utils;
	if( !$utils->globals->session( 'connected' ) )
		$wout->redirect( '/admin/connect.html' );
	else
		return true;
} // admin_middleware

$wout->get( '/admin/', 'admin_middleware', function() use( $wout, $utils ) {
	$wout->redirect( '/admin/' . $utils->getDirectoryIndex( ROOT ) );
} );

$wout->get( '/admin/connect.html', function() use( $wout, $utils ) {
	$oParser = new DOMParser( $utils->getDirectoryIndex( ROOT ), false, $utils->getDefaultLanguage() );
	$oParser->displayWithConnectBox( $utils->globals->has( 'error' ) );
} );

$wout->post( '/admin/', function() use( $wout, $utils ) {
	if( $utils->globals->has( 'connect' , 'post' ) ) {
		if( $utils->globals->has( 'error', 'post' ) )
			usleep( 200000 );
		$oUsers = Users::getInstance();
		if( $oUsers->isRoot( $utils->globals->post( 'login' ), $utils->globals->post( 'password' ) ) ) {
			$utils->globals->session( 'connected', true );
			$utils->globals->session( 'root', true );
		} else if( $oUsers->isBrandingAdmin( $utils->globals->post( 'login' ), $utils->globals->post( 'password' ) ) ) {
			$utils->globals->session( 'connected', true );
			$utils->globals->session( 'admin', true );
		} else if( $oUsers->isRegularUser( $utils->globals->post( 'login' ), $utils->globals->post( 'password' ) ) ) {
			$utils->globals->session( 'connected', true );
		} else
			$wout->redirect( '/admin/connect.html?error' );
		$wout->redirect( '/admin/' );
	} else
		$wout->redirect( '/admin/connect.html' );
} );

$wout->get( '/admin/:page', 'admin_middleware', function( $sPageName ) use( $wout, $utils ) {
	$oParser = new DOMParser( $sPageName, true, $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
	if( $utils->globals->has( 'stored_data', 'session' ) ) {
		$oParser->display( $utils->globals->session( 'stored_data' ) );
		unset( $_SESSION[ 'stored_data' ] );
	} else
		$oParser->display();
	die();
} );

$wout->get( '/admin/langswitch/:lang.html', 'admin_middleware', function( $sLang ) use( $wout, $utils ) {
	if( in_array( $sLang, $utils->data->get( ':config:lang', array() ) ) )
		$utils->globals->session( 'lang', $sLang );
	else
		$utils->globals->session( 'lang', $utils->getDefaultLanguage() );
	$sReferer = str_replace( 'http://' . $utils->globals->server( 'http_host' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
	$wout->redirect( $sReferer );
} );

$wout->post( '/admin/save.:type.html', 'admin_middleware', function( $sType ) use( $wout, $utils ) {
	$sPage = $utils->globals->post( 'page' );
	switch( $sType ) {
		case Brick::TYPE_TIME:
			$oBrick = new TimeBrick( $sPage, $utils->globals->post( 'ref' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
			$oBrick->datetime = $utils->globals->post( 'datetime' );
			$oBrick->format = $utils->globals->post( 'format' );
			break;

		case Brick::TYPE_SHORT:
			$oBrick = new ShortBrick( $sPage, $utils->globals->post( 'ref' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
			$oBrick->content = $utils->globals->post( 'content' );
			break;

		case Brick::TYPE_RICH:
			$oBrick = new RichBrick( $sPage, $utils->globals->post( 'ref' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
			$oBrick->content = $utils->globals->post( 'content' );
			break;

		case Brick::TYPE_FILE:
			$oBrick = new FileBrick( $sPage, $utils->globals->post( 'ref' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
			if( $utils->array_access( $utils->globals->files( 'file' ), 'error' ) != 4 ) {
				if( $utils->array_access( $utils->globals->files( 'file' ), 'error' ) > 0 )
					throw new ErrorException( 'Error during upload !' ); // TODO
				$oFile = CMSFile::factory( $utils->globals->files( 'file' ) );
				$oFile->basename = $utils->genUID();
				if( !$oFile->saveTo( dirname( DATA_PATH ) . '/' ) )
					throw new ErrorException( 'Error during saving file !' ); // TODO
				$oBrick->file = $oFile->filename;
				$oBrick->size = $oFile->human_size;
				$oBrick->name = $oFile->name;
			}
			$oBrick->label = $utils->globals->post( 'label' );
			break;

		case Brick::TYPE_IMAGE:
			$oBrick = new ImageBrick( $sPage, $utils->globals->post( 'ref' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
			if( $utils->array_access( $utils->globals->files( 'src' ), 'error' ) != 4 ) {
				if( $utils->array_access( $utils->globals->files( 'src' ), 'error' ) > 0 )
					throw new ErrorException( 'Error during upload !' ); // TODO
				$oFile = CMSFile::factory( $utils->globals->files( 'src' ) );
				$oFile->basename = $utils->genUID();
				if( !$oFile->saveTo( dirname( DATA_PATH ) . '/' ) )
					throw new ErrorException( 'Error during saving file !' ); // TODO
				if( $oFile->isImage() ) {
					$oFile->resize( $oBrick->width, $oBrick->height, CMSImage::RESIZE_CROP );
					$oFile->save();
				}
				$oBrick->src = $oFile->filename;
			}
			if( $utils->globals->post( 'legend' ) )
				$oBrick->legend = $utils->globals->post( 'legend' );
			if( $utils->globals->post( 'description' ) )
				$oBrick->description = $utils->globals->post( 'description' );
			$aNewGalleryOrder = $utils->globals->post( 'gallery_order' ) ? explode( ',', $utils->globals->post( 'gallery_order' ) ) : array();
			$bHasChanged = false;
			$aGalFiles = array();
			if( is_array( $utils->globals->post( 'gal' ) ) ) {
				foreach( $utils->globals->post( 'gal' ) as $sKey => $aImageInfos ) {
					$sPropertyName = 'gal_' . $sKey;
					$oBrick->$sPropertyName = $aImageInfos[ 'src' ];
					if( isset( $aImageInfos[ 'legend' ] ) ) {
						$sPropertyName = 'gal_' . $sKey . '_legend';
						$oBrick->$sPropertyName = $aImageInfos[ 'legend' ];
					}
					if( isset( $aImageInfos[ 'description' ] ) ) {
						$sPropertyName = 'gal_' . $sKey . '_description';
						$oBrick->$sPropertyName = $aImageInfos[ 'description' ];
					}
				}
				$bHasChanged = true;
			}
			if( is_array( $utils->array_access( $utils->globals->files( 'gal' ), 'name' ) ) ) {
				foreach( $utils->array_access( $utils->globals->files( 'gal' ), 'name' ) as $sKey => $sName ) {
					$aGalFiles[ $sKey ] = array(
						'name' => $utils->array_access( $utils->array_access( $utils->globals->files( 'gal' ), 'name' ), $sKey ),
						'type' => $utils->array_access( $utils->array_access( $utils->globals->files( 'gal' ), 'type' ), $sKey ),
						'tmp_name' => $utils->array_access( $utils->array_access( $utils->globals->files( 'gal' ), 'tmp_name' ), $sKey ),
						'error' => $utils->array_access( $utils->array_access( $utils->globals->files( 'gal' ), 'error' ), $sKey ),
						'size' => $utils->array_access( $utils->array_access( $utils->globals->files( 'gal' ), 'size' ), $sKey )
					);
				}
				foreach( $aGalFiles as $sKey => $aGalFile ) {
					if( $aGalFile[ 'error' ] != 4 ) {
						if( $aGalFile[ 'error' ] > 0 )
							throw new ErrorException( 'Error during upload !' ); // TODO
						$oFile = CMSFile::factory( $aGalFile );
						$oFile->basename = $utils->genUID();
						if( !$oFile->saveTo( dirname( DATA_PATH ) . '/' ) )
							throw new ErrorException( 'Error during saving file !' ); // TODO
						if( $oFile->isImage() ) {
							$oFile->resize( $utils->data->get( ':config:image:resize:width', 960 ), $utils->data->get( ':config:image:resize:height', 720 ), CMSImage::RESIZE_FIT );
							$oFile->save();
						}
						$sPropertyName = 'gal_' . $sKey;
						$oBrick->$sPropertyName = $oFile->filename;
					}
				}
				$bHasChanged = true;
			}
			if( $bHasChanged && $aNewGalleryOrder !== $oBrick->gallery_order )
				$oBrick->gallery_order = $aNewGalleryOrder;
			break;

		case Brick::TYPE_MAP:
			$oBrick = new MapBrick( $sPage, $utils->globals->post( 'ref' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
			$oBrick->zoom = $utils->globals->post( 'zoom' );
			$oBrick->lat = $utils->globals->post( 'lat' );
			$oBrick->lng = $utils->globals->post( 'lng' );
			$oBrick->marker_lat = $utils->globals->post( 'marker_lat' );
			$oBrick->marker_lng = $utils->globals->post( 'marker_lng' );
			break;

		case Brick::TYPE_FORM:
			$oBrick = new FormBrick( $sPage, $utils->globals->post( 'ref' ), $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() );
			$oBrick->target = $utils->globals->post( 'target' );
			break;

		default:
			throw new UnexpectedValueException( 'Unknown Brick type "' . $sType . '" !' );
			break;
	}
	if( !$oBrick->save() )
		throw new ErrorException( "Can't save !" );
	if( file_exists( DOMParser::getCachePathFor( $sPage ) ) )
		unlink( DOMParser::getCachePathFor( $sPage ) );
	$wout->redirect( $sPage );
} );

$wout->get( '/admin/root/users/delete/:user/', 'admin_middleware', function( $sUser ) use( $wout, $utils ) {
	$aUsers = $utils->data->get( ':users' );
	if( isset( $aUsers[ $sUser ] ) )
		unset( $aUsers[ $sUser ] );
	$utils->data->set( ':users', $aUsers );
	$utils->data->save();
	$sReferer = str_replace( 'http://' . $utils->globals->server( 'http_host' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
	$utils->globals->session( 'stored_data', array(
		'modal' => array(
			'url' => '/ajax/root.users.html',
			'data' => array(
				'success' => true
			)
		)
	) );
	$wout->redirect( $sReferer );
} );

$wout->post( '/admin/root.save.users.html', 'admin_middleware', function() use( $wout, $utils ) {
	$aUsers = $utils->data->get( ':users' );
	if( trim( $utils->globals->post( 'login' ) ) == '' || trim( $utils->globals->post( 'password' ) ) == '' ) {
		$utils->globals->session( 'stored_data', array(
			'modal' => array(
				'url' => '/ajax/root.users.html',
				'data' => array(
					'error' => true
				)
			)
		) );
	} else {
		$aUsers[ trim( $utils->globals->post( 'login' ) ) ] = sha1( trim( $utils->globals->post( 'password' ) ) );
		$utils->data->set( ':users', $aUsers );
		$utils->data->save();
		$utils->globals->session( 'stored_data', array(
			'modal' => array(
				'url' => '/ajax/root.users.html',
				'data' => array(
					'success' => true
				)
			)
		) );
	}
	$sReferer = str_replace( 'http://' . $utils->globals->server( 'http_host' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
	$wout->redirect( $sReferer );
} );

$wout->post( '/admin/root.save.config.html', 'admin_middleware', function() use( $wout, $utils ) {
	foreach( $utils->globals->post() as $sKey => $sValue ) {
		switch( $sKey ) {
			case ':config:image:resize:width':
			case ':config:image:resize:height':
				if( intval( $sValue ) )
					$utils->data->set( $sKey, intval( $sValue ) ?: null );
				break;
		}
	}
	if( is_array( $utils->globals->post( ':config:lang' ) ) ) {
		$utils->data->set( ':config:lang', $utils->globals->post( ':config:lang' ) );
	} else {
		$utils->data->remove( ':config:lang' );
	}
	$utils->data->set( ':config:public:disable_js', $utils->globals->post( ':config:public:disable_js', false ) !== false );
	$utils->data->set( ':config:public:enable_sitemap', $utils->globals->post( ':config:public:enable_sitemap', false ) !== false );
	$utils->data->save();
	$utils->globals->session( 'stored_data', array(
		'modal' => array(
			'url' => '/ajax/root.config.html',
			'data' => array(
				'success' => true
			)
		)
	) );
	$sReferer = str_replace( 'http://' . $utils->globals->server( 'http_host' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
	$utils->clearCache();
	$wout->redirect( $sReferer );
} );

$wout->post( '/admin/root.save.brand.html', 'admin_middleware', function() use( $wout, $utils ) {
	$utils->data->set( UtilsData::SP . 'config' . UtilsData::SP . 'brand', $utils->globals->post( 'brand' ) ?: Branding::BRAND_POSIB );
	$utils->data->save();
	$utils->globals->session( 'stored_data', array(
		'modal' => array(
			'url' => '/ajax/root.brand.html',
			'data' => array(
				'success' => true
			)
		)
	) );
	$sReferer = str_replace( 'http://' . $utils->globals->server( 'http_host' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
	$utils->clearCache();
	$wout->redirect( $sReferer );
} );

$wout->post( '/admin/infos.save.html', 'admin_middleware', function() use( $wout, $utils ) {
	$sRef = $utils->globals->post( 'ref' );
	$sLang = is_array( $utils->data->get( ':config:lang' ) ) ? ( ':' . ( $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() ) ) : null ;
	$utils->data->set( ':pages:' . $sRef . ':title' . $sLang, $utils->globals->post( 'title' ) );
	$utils->data->set( ':pages:' . $sRef . ':keywords' . $sLang, $utils->globals->post( 'keywords' ) );
	$utils->data->set( ':pages:' . $sRef . ':description' . $sLang, $utils->globals->post( 'description' ) );
	$utils->data->save();
	if( file_exists( DOMParser::getCachePathFor( $sRef ) ) )
		unlink( DOMParser::getCachePathFor( $sRef ) );
	$sReferer = str_replace( 'http://' . $utils->globals->server( 'http_host' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
	$wout->redirect( $sReferer );
} );

$wout->post( '/admin/sitemap.save.html', 'admin_middleware', function() use( $wout, $utils ) {
	$sNewPageName = $utils->globals->post( 'page' );
	$sNewPageURL = $utils->globals->post( 'url' );
	$sNewPageTemplate = $utils->globals->post( 'template' ) ?: null;
	$sLang = is_array( $utils->data->get( ':config:lang' ) ) ? ( ':' . ( $utils->globals->session( 'lang' ) ?: $utils->getDefaultLanguage() ) ) : null ;
	if( substr( $sNewPageURL, -5 ) != '.html' && substr( $sNewPageURL, -4 ) != '.htm' )
		$sNewPageURL .= '.html';
	if( in_array( $sNewPageURL, array_keys( $utils->data->get( ':pages' ) ) ) && $sNewPageName == $utils->data->get( ':pages:' . $sNewPageURL . ':name' . $sLang ) ) {
		$utils->globals->session( 'stored_data', array(
			'modal' => array(
				'url' => '/ajax/sitemap.html',
				'data' => array(
					'error' => true
				)
			)
		) );
	} else {
		$utils->data->set( ':pages:' . $sNewPageURL . ':name' . $sLang, $sNewPageName );
		if( !is_null( $sNewPageTemplate ) )
			$utils->data->set( ':pages:' . $sNewPageURL . ':template', $sNewPageTemplate );
		$utils->data->set( ':sitemap', array_keys( $utils->data->get( ':pages' ) ) );
		$utils->data->save();
		$utils->globals->session( 'stored_data', array(
			'modal' => array(
				'url' => '/ajax/sitemap.html',
				'data' => array(
					'success' => true
				)
			)
		) );
	}
	$sReferer = str_replace( 'http://' . $utils->globals->server( 'http_host' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
	$wout->redirect( $sReferer );
} );

$wout->get( '/admin/sitemap/:page/delete.html', 'admin_middleware', function( $sPageToDelete ) use( $wout, $utils ) {
	$aSitemap = $utils->data->get( ':pages' );
	if( isset( $aSitemap[ $sPageToDelete ] ) )
		unset( $aSitemap[ $sPageToDelete ] );
	$utils->data->set( ':pages', $aSitemap );
	$utils->data->set( ':sitemap', array_keys( $utils->data->get( ':pages' ) ) );
	$utils->data->save();
	$utils->globals->session( 'stored_data', array(
		'modal' => array(
			'url' => '/ajax/sitemap.html',
			'data' => array(
				'success' => true
			)
		)
	) );
	$sReferer = str_replace( 'http://' . $utils->globals->server( 'http_host' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
	$wout->redirect( $sReferer );
} );

$wout->get( '/admin/restore/:timestamp/:type/:ref/:page', 'admin_middleware', function( $iTime, $sType, $sRef, $sPage ) use( $wout, $utils ) {
	switch( $sType ) {
		case 'short': $sBrickClass = 'ShortBrick'; break;
		case 'rich': $sBrickClass = 'RichBrick'; break;
		case 'image': $sBrickClass = 'ImageBrick'; break;
		case 'map': $sBrickClass = 'MapBrick'; break;
		case 'form': $sBrickClass = 'FormBrick'; break;
		case 'list': $sBrickClass = 'ListBrick'; break;
		case 'time': $sBrickClass = 'TimeBrick'; break;
	}
	$oBrick = new $sBrickClass( $sPage, $sRef, $utils->globals->session( 'lang', $utils->getDefaultLanguage() ) );
	$oBrick->restoreAt( $iTime );
	if( !$oBrick->save() )
		throw new ErrorException( "Can't save !" );
	if( file_exists( DOMParser::getCachePathFor( $sPage ) ) )
		unlink( DOMParser::getCachePathFor( $sPage ) );
	$wout->redirect( '/admin/' . $sPage );
} );

$wout->get( '/admin/list/:ref/element/add/:page', 'admin_middleware', function( $sListRef, $sPage ) use( $wout, $utils ) {
	$oListBrick = new ListBrick( $sPage, $sListRef, $utils->getDefaultLanguage() );
	$aListBrickContent = $oListBrick->content;
	$aListBrickContent[] = Utils::genUID();
	$oListBrick->content = $aListBrickContent;
	$utils->globals->session( 'stored_data', array(
		'modal' => array(
			'url' => '/ajax/list.manager.html',
			'data' => array(
				( $oListBrick->save() ? 'success' : 'error' ) => true,
				'page' => $sPage,
				'list' => $sListRef
			)
		)
	) );
	$wout->redirect( '/admin/' . $sPage );
} );

$wout->get( '/admin/list/:ref/element/:element/delete/:page', 'admin_middleware', function( $sListRef, $iElementIndex, $sPage ) use( $wout, $utils ) {
	$oListBrick = new ListBrick( $sPage, $sListRef, $utils->getDefaultLanguage() );
	$aListBrickContent = $oListBrick->content;
	array_splice( $aListBrickContent, $iElementIndex, 1 );
	$oListBrick->content = $aListBrickContent;
	$utils->globals->session( 'stored_data', array(
		'modal' => array(
			'url' => '/ajax/list.manager.html',
			'data' => array(
				( $oListBrick->save() ? 'success' : 'error' ) => true,
				'page' => $sPage,
				'list' => $sListRef
			)
		)
	) );
	$wout->redirect( '/admin/' . $sPage );
} );

$wout->get( '/admin/exit/', function() use( $wout ) {
	session_destroy();
	unset( $_SESSION );
	$_SESSION = array();
	$wout->redirect( '/' );
} );
