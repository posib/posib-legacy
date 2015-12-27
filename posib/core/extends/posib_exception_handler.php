<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/extends/posib_exception_handler.php
 */

function posib_exception_handler( Exception $oPosibException ) {
	$oPosibException->render();
	die();
} // posib_exception_handler

set_exception_handler( 'posib_exception_handler' );
