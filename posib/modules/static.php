<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /modules/static.php - main module for static
 */

global $wout, $utils;

// CSS files

$wout->get( '/posib/static/css/:file.css', function( $sFilePath ) {
	header( 'Content-Type: text/css' );
	die( file_get_contents( POSIB . 'static/css/' . $sFilePath . '.css' ) );
} );

// JS files

$wout->get( '/posib/static/js/:file.js', function( $sFilePath ) {
	header( 'Content-Type: text/javascript' );
	die( file_get_contents( POSIB . 'static/js/' . $sFilePath . '-min.js' ) );
} );

// image files

$wout->get( '/posib/static/images/:file.png', function( $sFilePath ) {
	header( 'Content-Type: image/png' );
	die( file_get_contents( POSIB . 'static/images/' . $sFilePath . '.png' ) );
} );
$wout->get( '/posib/static/images/:file.jpg', function( $sFilePath ) {
	header( 'Content-Type: image/jpeg' );
	die( file_get_contents( POSIB . 'static/images/' . $sFilePath . '.jpg' ) );
} );
$wout->get( '/posib/static/images/:file.gif', function( $sFilePath ) {
	header( 'Content-Type: image/gif' );
	die( file_get_contents( POSIB . 'static/images/' . $sFilePath . '.gif' ) );
} );

// icon files
$wout->get( '/posib/static/icons/:file.png', function( $sFilePath ) {
	header( 'Content-Type: image/png' );
	die( file_get_contents( POSIB . 'static/icons/' . $sFilePath . '.png' ) );
} );

// flags files
$wout->get( '/posib/static/icons/flags/:file.png', function( $sFilePath ) {
	header( 'Content-Type: image/png' );
	die( file_get_contents( POSIB . 'static/flags/' . $sFilePath . '.png' ) );
} );
