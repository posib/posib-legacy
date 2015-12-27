<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/tools/utils/data.php - Data Utils class
 */

class UtilsData extends Singleton {

	const SP = ':';

	public function get( $sPath, $mDefault = null ) {
		if( !$this->_bReady )
			$this->_load();
		return $this->_get( $sPath ) ?: $mDefault;
	} // get

	public function getAll() {
		if( !$this->_bReady )
			$this->_load();
		return $this->_aData;
	} // getAll

	public function set( $sPath, $mValue ) {
		if( !$this->_bReady )
			$this->_load();
		$this->_set( $sPath, $mValue );
	} // set

	public function remove( $sPath ) {
		if( !$this->_bReady )
			$this->_load();
		$this->_remove( $sPath );
	} // remove

	public function save( $bForce = false ) {
		return $this->_save( $bForce );
	} // save

	public function init( $sDataPath ) {
		$this->_sDataPath = $sDataPath;
		$this->_bReady = false;
	} // init

	protected function _load() {
		if( is_null( $this->_sDataPath ) )
			throw new PosibException( "La classe UtilsData n'a pas été initialisée !" );
		$aData = json_decode( file_get_contents( $this->_sDataPath ), true );
		if( is_null( $aData ) )
			throw new PosibException( "Le fichier JSON des data est corrompu ou malformé !" );
		$this->_aJSONData = $aData;
		$this->_aData = $aData;
		$this->_bReady = true;
		if( empty( $aData ) )
			$this->_generateData();
	} // _load

	protected function _get( $sPath ) {
		$aPathParts = $this->_getPathParts( $sPath );
		$mCurrent = $this->_aData;
		foreach( $aPathParts as $sPathPart ) {
			if( !isset( $mCurrent[ $sPathPart ] ) )
				return null; // ¿ throw Exception ?
			$mCurrent = $mCurrent[ $sPathPart ];
		}
		return $mCurrent;
	} // _get

	protected function _set( $sPath, $mValue ) {
		$aPathParts = $this->_getPathParts( $sPath );
		$mCurrent = &$this->_aData;
		for( $i = 0; $i < sizeof( $aPathParts ); $i++ ) {
			$sPathPart = $aPathParts[ $i ];
			if( $i == ( sizeof( $aPathParts ) - 1 ) ) {
				$mCurrent[ $sPathPart ] = $mValue;
				return;
			} elseif( !isset( $mCurrent[ $sPathPart ] ) ) {
				$mCurrent[ $sPathPart ] = array();
			} elseif( $i < ( sizeof( $aPathParts ) - 1 ) && !is_array( $mCurrent[ $sPathPart ] ) ) { // overwriting
				$mCurrent[ $sPathPart ] = array();
			}
			$mCurrent = &$mCurrent[ $sPathPart ];
		}
	} // _set

	protected function _remove( $sPath ) {
		$aPathParts = $this->_getPathParts( $sPath );
		$sUnsetCode = 'unset( $this->_aData["' . implode( '"]["', $aPathParts ) . '"] );';
		eval( $sUnsetCode );
	} // _remove

	protected function _save( $bForce = false ) {
		if( $this->_aJSONData === $this->_aData && !$bForce )
			return true;
		if( file_put_contents( $this->_sDataPath, preg_replace('/\\\u000([0-9a-z]{1})/', '', json_encode( $this->_aData ) ) ) === false )
			throw new ErrorException( "Can't save file in '" . $this->_sDataPath . "' !", E_USER_ERROR );
		return true;
	} // _save

	protected function _generateData() {
		$utils = Utils::getInstance();
		$sp = self::SP;
		$aSitemap = $utils->getTemplates();
		$this->set( $sp . 'sitemap', $aSitemap );
		foreach( $aSitemap as $sPage ) {
			$this->set( $sp . 'pages' . $sp . $sPage . $sp . 'name', $sPage );
			$this->set( $sp . 'pages' . $sp . $sPage . $sp . 'template', $sPage );
		}
		$this->save();
	} // _generateData

	protected function _getPathParts( $sPath ) {
		$aPathParts = explode( UtilsData::SP, $sPath );
		if( $aPathParts[ 0 ] == '' )
			array_shift( $aPathParts );
		if( $aPathParts[ sizeof( $aPathParts ) - 1 ] == '' )
			array_pop( $aPathParts );
		return $aPathParts;
	} // _getPathParts

	protected $_sDataPath;
	protected $_aData;
	protected $_bReady = false;

} // class::UtilsGlobals
