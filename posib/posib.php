<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /posib.php - main entry point
 */

define( 'POSIB_VERSION', '2.9.5' );
define( 'POSIB', __DIR__ . '/' );

include( POSIB . 'core/extends/posib_exception.php' );
include( POSIB . 'core/extends/posib_exception_handler.php' );

// check constants and file writeability
if( !version_compare( phpversion(), '5.3.0', '>' ) )
	throw new PosibException( 'Votre version de php est ' . phpversion() . ' !<br />posib. requiert au moins php 5.3.0 !' );

if( !defined( 'ROOT' ) )
	throw new PosibException( 'Error: undefined constant ROOT !' );

defined( 'DATA_PATH' ) ?: define( 'DATA_PATH', ROOT . 'contents/data.json' );
if( !file_exists( DATA_PATH ) )
	throw new PosibException( 'Error: data file doesn\'t exists at "' . DATA_PATH . '" !' );

if( !is_writeable( dirname( DATA_PATH ) ) || !is_writeable( DATA_PATH ) )
	throw new PosibException( 'Error: data file and/or his folder are not writeable !' );

// global stuffs
global $user;
static $utils;
session_start();

// include libs & tools
include( POSIB . 'core/libs/wout.phar' );
include( POSIB . 'core/tools/singleton.php' );
include( POSIB . 'core/tools/utils/strings.php' );
include( POSIB . 'core/tools/utils/globals.php' );
include( POSIB . 'core/tools/utils/data.php' );
include( POSIB . 'core/tools/utils.php' );
$utils = Utils::getInstance();
include( POSIB . 'core/extends/cms_fs.php' );
include( POSIB . 'core/extends/cms_file.php' );
include( POSIB . 'core/extends/cms_image.php' );
include( POSIB . 'core/extends/cms_mail.php' );

// include classes
$utils->load( POSIB . 'core/classes/*.php', array( POSIB . 'core/classes/brick.php' ) );

$utils->clearCache();

// init
$utils->data->init( DATA_PATH );
$wout->init();

// include modules
$utils->load( POSIB . 'modules/*.php' );

// run
$wout->run();

// die.
die();
