<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/extends/posib_exception.php
 */

class PosibException extends Exception {

	public function __construct( $sMessage ) {
		return parent::__construct( $sMessage );
	} // __construct

	public function render() {
		include( POSIB . 'includes/exception.inc' );
	} // render

} // class::PosibException
