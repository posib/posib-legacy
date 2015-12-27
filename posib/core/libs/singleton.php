<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/tools/singleton.php - singleton-pattern implementation
 */

class Singleton {

	final public static function getInstance() {
		$sClass = get_called_class();
		if( !isset( self::$_aInstances[ $sClass ] ) )
			self::$_aInstances[ $sClass ] = new $sClass;
		return self::$_aInstances[ $sClass ];
	} // getInstance

	final public function __clone() {
		throw new \BadMethodCallException( "Le clonage d'un singleton n'est pas autorisé." );
	} // __clone

	protected function __construct() {} // __construct

	private static $_aInstances = array();

} // class::Singleton
