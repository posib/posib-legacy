<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/map_brick.php
 */

class MapBrick extends Brick {

	public function __get( $sName ) {
		switch( $sName ) {
			case 'lat':
				return parent::__get( 'lat' ) ?: $this->_fDefaultLat;
				break;

			case 'marker_lat':
				return parent::__get( 'marker_lat' ) ?: $this->_fDefaultLat;
				break;

			case 'lng':
				return parent::__get( 'lng' ) ?: $this->_fDefaultLng;
				break;

			case 'marker_lng':
				return parent::__get( 'marker_lng' ) ?: $this->_fDefaultLng;
				break;

			case 'zoom':
				return parent::__get( 'zoom' ) ?: $this->_iDefaultZoom;
				break;

			default:
				return parent::__get( $sName );
				break;
		}
	} // __get

	public function __set( $sName, $mValue ) {
		switch( $sName ) {
			case 'zoom':
				if( !is_numeric( $mValue ) )
					return;
				$mValue = intval( $mValue );
				if( 0 > $mValue || $mValue > 21 )
					return;
				parent::__set( 'zoom', $mValue );
				break;

			case 'lat':
			case 'marker_lat':
				if( !is_numeric( $mValue ) )
					return;
				$mValue = floatval( $mValue );
				if( -90 > $mValue || $mValue > 90 )
					return;
				parent::__set( $sName, $mValue );
				break;

			case 'lng':
			case 'marker_lng':
				if( !is_numeric( $mValue ) )
					return;
				$mValue = floatval( $mValue );
				if( -180 > $mValue || $mValue > 180 )
					return;
				parent::__set( $sName, $mValue );
				break;

			default:
				parent::__set( $sName, $mValue );
				break;
		}
	} // __set

	protected function _create() {
		$iCurrentTime = time();
		return array(
			'static' => array(),
			'dynamic' => array(
				'lat' => array( $iCurrentTime => $this->_fDefaultLat ),
				'lng' => array( $iCurrentTime => $this->_fDefaultLng ),
				'zoom' => array( $iCurrentTime => $this->_iDefaultZoom ),
				'marker_lat' => array( $iCurrentTime => $this->_fDefaultLat ),
				'marker_lng' => array( $iCurrentTime => $this->_fDefaultLng )
			)
		);
	} // _create

	protected function _render() {
		if( !$this->_oNode->hasAttribute( 'id' ) )
			$this->_oNode->setAttribute( 'id', 'map_' . Utils::getInstance()->genUID() );
		$this->_sID = $this->_oNode->getAttribute( 'id' );
		if( !self::$_bLibsAlreadyLoaded ) {
			$this->_addLibScript();
			self::$_bLibsAlreadyLoaded = true;
		}
		$this->_addMapScript();
	} // _render

	protected $_sType = Brick::TYPE_MAP;

	private function _getDefaultAddress() {
		DOMParser::getNodeContent( $this->_oNode, $sNodeContent );
		return strip_tags( Utils::getInstance()->br2nl( $sNodeContent ) );
	} // _getDefaultAddress

	private function _addLibScript() {
		$oTMPDocument = new DOMDocument();
 		$oTMPDocument->loadXML( '<script src="http://maps.google.com/maps/api/js?sensor=false"> </script>' );
		$oInsertedNode = $this->_oNode->ownerDocument->importNode( $oTMPDocument->firstChild, true );
		$this->_oNode->ownerDocument->getElementsByTagName( 'body' )->item( 0 )->appendChild( $oInsertedNode );
	} // _addLibScript

	private function _addMapScript() {
		$oTMPDocument = new DOMDocument();
 		$oTMPDocument->loadXML( '<script>
				var gmap_' . $this->_sID . ' = new google.maps.Map( document.getElementById("' . $this->_sID . '"), {
					mapTypeId: "roadmap",
					zoom: ' . $this->zoom . ',
					scrollwheel: false,
					center: new google.maps.LatLng( ' . $this->lat . ' , ' . $this->lng . ' )
				} );
				var marker_' . $this->_sID . ' = new google.maps.Marker( {
					position: new google.maps.LatLng( ' . $this->marker_lat . ', ' . $this->marker_lng . ' ),
					map: gmap_' . $this->_sID . ',
				} );
			</script>' );
		$oInsertedNode = $this->_oNode->ownerDocument->importNode( $oTMPDocument->firstChild, true );
		$this->_oNode->ownerDocument->getElementsByTagName( 'body' )->item( 0 )->appendChild( $oInsertedNode );
	} // _addMapScript

	private $_sID;
	private static $_bLibsAlreadyLoaded = false;

	private $_fDefaultLat = 50.4;
	private $_fDefaultLng = 4.4333;
	private $_iDefaultZoom = 7;

} // class::MapBrick
