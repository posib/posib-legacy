<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /modules/public.php - main module for public
 */

global $wout, $utils;

$wout->get( '/', function() use( $wout, $utils ) {
    $sIndexFile = $utils->getDirectoryIndex( ROOT );
    if( is_null( $sIndexFile ) )
        $wout->callError( 404 );
    else {
        $sDefaultLang = $utils->getDefaultLanguage();
        if( $sDefaultLang ) {
            $wout->redirect( '/' . $sDefaultLang . '/' . $sIndexFile );
        } else {
            $wout->redirect( '/' . $sIndexFile );
        }
    }
} );

$wout->get( '/robots.txt', function() use( $wout, $utils ) {
    header( 'Content-type: text/plain' );
    echo 'User-agent: *' . "\n";
    echo 'Disallow: /admin' . "\n";
    echo 'Disallow: /form' . "\n";
    echo 'Sitemap: http://' . $utils->globals->server( 'http_host' ) . '/sitemap.xml' . "\n";
    die();
} );

// referencing shit (thanks to Sencyb !)
$wout->get( '/google:id.html', function( $sID ) {
    if( file_exists( ROOT . 'google' . $sID . '.html' ) ) {
        header( 'Content-type: text/plain' );
        die( file_get_contents( ROOT . 'google' . $sID . '.html' ) );
    } else
        $wout->callError( 404 );
} );

$wout->get( '/download/:id/:filename', function( $sRef, $sFileName ) use( $wout, $utils ) {
    $aRefererInfos = parse_url( $utils->globals->server( 'http_referer' ) );
    $oBrick = new FileBrick( str_replace( '/', '', $aRefererInfos[ 'path' ] ), $sRef );
    header( 'Content-Transfer-Encoding: binary' );
    header( 'Expires: 0' );
    header( 'Cache-Control: no-cache, must-revalidate' );
    header( 'Pragma: no-cache' );
    header( 'Content-Type: application/force-download; name="' . basename( $oBrick->name ) . '"' );
    header( 'Content-Length: ' . filesize( $oBrick->path ) );
    header( 'Content-Disposition: attachment; filename="' . basename( $oBrick->name ) . '"' );
    readfile( $oBrick->path );
    die();
} );

$wout->get( '/:lang/download/:id/:filename', function( $sLang, $sRef, $sFileName ) use( $wout, $utils ) {
    $aRefererInfos = parse_url( $utils->globals->server( 'http_referer' ) );
    $oBrick = new FileBrick( str_replace( '/' . $sLang . '/', '', $aRefererInfos[ 'path' ] ), $sRef, $sLang );
    header( 'Content-Transfer-Encoding: binary' );
    header( 'Expires: 0' );
    header( 'Cache-Control: no-cache, must-revalidate' );
    header( 'Pragma: no-cache' );
    header( 'Content-Type: application/force-download; name="' . basename( $oBrick->name ) . '"' );
    header( 'Content-Length: ' . filesize( $oBrick->path ) );
    header( 'Content-Disposition: attachment; filename="' . basename( $oBrick->name ) . '"' );
    readfile( $oBrick->path );
    die();
} );

$wout->post( '/form/:ref/send.html', function( $sRef ) use( $wout, $utils ) {
    $sReferer = str_replace( 'http://' . $utils->globals->server( 'server_name' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
    $oBrick = new FormBrick( str_replace( '/', '', $sReferer ), $sRef );
    $_SESSION[ 'target_form' ] = $sRef;
    $_SESSION[ 'send_operation' ] = $oBrick->send( $utils->globals->post() );
    $sReferer = str_replace( 'http://' . $utils->globals->server( 'server_name' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
    $wout->redirect( $sReferer );
} );

$wout->post( '/:lang/form/:ref/send.html', function( $sLang, $sRef ) use( $wout, $utils ) {
    $sReferer = str_replace( 'http://' . $utils->globals->server( 'server_name' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
    $oBrick = new FormBrick( str_replace( '/' . $sLang . '/', '', $sReferer ), $sRef, $sLang );
    $_SESSION[ 'target_form' ] = $sRef;
    $_SESSION[ 'send_operation' ] = $oBrick->send( $utils->globals->post() );
    $sReferer = str_replace( 'http://' . $utils->globals->server( 'server_name' ) . '/', '/', $utils->globals->server( 'http_referer' ) );
    $wout->redirect( $sReferer );
} );

$wout->get( '/:lang/:page', function( $sLang, $sPageName ) use( $wout, $utils ) {
    if( sizeof( $utils->data->get( ':config:lang', array() ) ) == 0 )
        $wout->redirect( '/' . $sPageName );
    if( !in_array( $sLang, $utils->data->get( ':config:lang', array() ) ) )
        return $wout->callError( 404 );
    $oParser = new DOMParser( $sPageName, false, $sLang );
    if( !$oParser->exists() ) {
        $aSitemap = $utils->getTemplates();
        $bChanges = false;
        foreach( $aSitemap as $sPage ) {
            if( is_null( $utils->data->get( UtilsData::SP . 'pages' . UtilsData::SP . $sPage ) ) ) {
                $utils->data->set( UtilsData::SP . 'pages' . UtilsData::SP . $sPage . UtilsData::SP . 'name', $sPage );
                $utils->data->set( UtilsData::SP . 'pages' . UtilsData::SP . $sPage . UtilsData::SP . 'template', $sPage );
                $bChanges = true;
            }
        }
        if( $bChanges ) {
            $utils->data->save();
            $oParser = new DOMParser( $sPageName, false, $sLang );
            if( !$oParser->exists() ) {
                return $wout->callError( 404 );
            }
        } else
            return $wout->callError( 404 );
    }
    $oParser->display();
    die();
} );

$wout->get( '/:page', function( $sPageName ) use( $wout, $utils ) {
    if( strlen( $sPageName ) == 2 ) { // $sPageName is LANG CODE
        $sIndexFile = $utils->getDirectoryIndex( ROOT );
        if( is_null( $sIndexFile ) )
            $wout->callError( 404 );
        else
            $wout->redirect( '/' . $sPageName . '/' . $sIndexFile );
    } else {
        $sDefaultLang = $utils->getDefaultLanguage();
        if( $sDefaultLang ) {
            $wout->redirect( '/' . $sDefaultLang . '/' . $sPageName );
        } else {
            $oParser = new DOMParser( $sPageName );
            if( !$oParser->exists() ) {
                $aSitemap = $utils->getTemplates();
                $bChanges = false;
                foreach( $aSitemap as $sPage ) {
                    if( is_null( $utils->data->get( UtilsData::SP . 'pages' . UtilsData::SP . $sPage ) ) ) {
                        $utils->data->set( UtilsData::SP . 'pages' . UtilsData::SP . $sPage . UtilsData::SP . 'name', $sPage );
                        $utils->data->set( UtilsData::SP . 'pages' . UtilsData::SP . $sPage . UtilsData::SP . 'template', $sPage );
                        $bChanges = true;
                    }
                }
                if( $bChanges ) {
                    $utils->data->save();
                    $oParser = new DOMParser( $sPageName, false, $sLang );
                    if( !$oParser->exists() ) {
                        return $wout->callError( 404 );
                    }
                } else
                    return $wout->callError( 404 );
            }
            $oParser->display();
            die();
        }
    }
} );

$wout->get( '/:lang/', function( $sLang ) use( $wout, $utils ) {
    if( strlen( $sLang ) !== 2 ) {
        $wout->callError( 404 );
    } else {
        $aAvailableLangs = $utils->data->get( ':config:lang', array() );
        $sIndexFile = $utils->getDirectoryIndex( ROOT );
        $wout->redirect( '/' . ( in_array( $sLang, $aAvailableLangs ) ? $sLang : $utils->getDefaultLanguage() ) . '/' . $sIndexFile );
    }
} );
