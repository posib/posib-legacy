<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/image_brick.php
 */

class ImageBrick extends Brick {

	const PLACEHOLDER_PROVIDER = 'http://placehold.it/';

	public function __get( $sName ) {
		switch( $sName ) {
			case 'gallery_paths':
				return $this->_getGallery( '/' .str_replace( ROOT, '', dirname( DATA_PATH ) ) . '/' );
				break;

			case 'gallery':
				return $this->_getGallery();
				break;

			case 'path':
				if( substr( $this->src, 0, 4 ) == 'http' )
					return $this->src;
				else
					return '/' .str_replace( ROOT, '', dirname( DATA_PATH ) ) . '/' . $this->src;
				break;

			default:
				return parent::__get( $sName );
				break;
		}
	} // __get

	public function __construct( $sURLRef, $mNodeOrRef, $sLang = null ) {
		parent::__construct( $sURLRef, $mNodeOrRef, $sLang = null );
		if( is_null( $this->gallery_order ) )
			$this->_updateDataFormat();
	} // __construct

	protected function _create() {
		list( $iWidth, $iHeight ) = $this->_getImageDimensions();
		$iRefTime = time();
		return array(
			'static' => array(
				'width' => $iWidth,
				'height' => $iHeight
			),
			'dynamic' => array(
				'gallery_order' => array(
					$iRefTime => array()
				),
				'src' => array(
					$iRefTime => $this->_oNode->hasAttribute( 'src' ) ? $this->_oNode->getAttribute( 'src' ) : ( self::PLACEHOLDER_PROVIDER . $iWidth . 'x' . $iHeight )
				)
			)
		);
	} // _create

	protected function _render() {
		$this->_oNode->setAttribute( 'src', $this->path );
		if( sizeof( $this->gallery ) > 0 )
			$this->_oNode->setAttribute( 'data-gallery-images', urlencode( json_encode( $this->gallery_paths ) ) );
	} // _render

	protected function _getPropertyWhen( $sProperty, $iTimestamp, $bExact = false ) {
		switch( $sProperty ) {
			case 'path':
				$sSrc = parent::_getPropertyWhen( 'src', $iTimestamp, $bExact );
				return ( substr( $sSrc, 0, 4 ) == 'http' ) ? $sSrc : '/' .str_replace( ROOT, '', dirname( DATA_PATH ) ) . '/' . $sSrc;
				break;

			case 'gallery':
				$aGallery = array();
				$aGalleryOrder = parent::_getPropertyWhen( 'gallery_order', $iTimestamp, $bExact );
				if( is_array( $aGalleryOrder ) && sizeof( $aGalleryOrder ) ) {
					foreach( $aGalleryOrder as $sKey ) {
						$sPropertyName = 'gal_' . $sKey;
						$sPropertyValue = parent::_getPropertyWhen( $sPropertyName, $iTimestamp, $bExact );
						if( !is_null( $sPropertyValue ) )
							$aGallery[] = $sPropertyValue;
					}
				}
				return $aGallery;
				break;

			default:
				return parent::_getPropertyWhen( $sProperty, $iTimestamp, $bExact );
				break;
		}
	} // _getPropertyWhen

	protected $_sType = Brick::TYPE_IMAGE;

	private function _getImageDimensions() {
		$iWidth = $this->_oNode->hasAttribute( 'width' ) ? intval( $this->_oNode->getAttribute( 'width' ) ) : null;
		$iHeight = $this->_oNode->hasAttribute( 'height' ) ? intval( $this->_oNode->getAttribute( 'height' ) ) : null;
		if( is_null( $iWidth ) || is_null( $iHeight ) ) {
			try {
				$aDimensions = @getimagesize( $this->_oNode->getAttribute( 'src' ) );
				list( $iWidth, $iHeight ) = $aDimensions;
			} catch( Exception $oException ) {
				throw new PosibException( 'Unable to compute dimensions of image !' );
			}
		}
		if( is_null( $iWidth ) || is_null( $iHeight ) ) {
			if( $this->_oNode->hasAttribute( 'src' ) ) {
				$sSrc = $this->_oNode->getAttribute( 'src' );
				if( strpos( $sSrc, self::PLACEHOLDER_PROVIDER ) !== false ) {
					$aDimensions = explode( 'x' , str_replace( self::PLACEHOLDER_PROVIDER, '', $sSrc ) );
					if( sizeof( $aDimensions ) > 1 && intval( $aDimensions[ 0 ] ) && intval( $aDimensions[ 1 ] ) ) {
						return array( intval( $aDimensions[ 0 ] ), intval( $aDimensions[ 1 ] ) );
					} elseif( sizeof( $aDimensions ) == 1 && intval( $aDimensions[ 0 ] ) ) {
						return array( intval( $aDimensions[ 0 ] ), intval( $aDimensions[ 0 ] ) );
					} else
						throw new PosibException( 'Unable to compute dimensions of image !' );
				} else
					throw new PosibException( 'Unable to compute dimensions of image !' );
			} else
				throw new PosibException( 'Unable to compute dimensions of image !' );
		}
		return array( $iWidth, $iHeight );
	} // _getImageDimensions

	private function _getGallery( $sURLPrefix = null ) {
		if( is_null( $this->_aGallery ) ) {
			$this->_aGallery = array();
			foreach ( $this->gallery_order as $sKey ) {
				$sPropertyName = 'gal_' . $sKey;
				$this->_aGallery[] = $this->$sPropertyName;
			}
		}
		if( is_null( $sURLPrefix ) )
			return $this->_aGallery;
		else {
			if( is_null( $this->_aGalleryPaths ) ) {
				$aGalleryPaths = array();
				foreach( $this->_aGallery as $sPath )
					$aGalleryPaths[] = $sURLPrefix . $sPath;
				$this->_aGalleryPaths = $aGalleryPaths;
			}
			return $this->_aGalleryPaths;
		}
	} // _getGallery

	private function _updateDataFormat() {
		$aGallery = array();
		$i = -1;
		$utils = Utils::getInstance();
		do {
			$sPropertyName = 'gal' . ++$i;
			if( !is_null( $this->$sPropertyName ) )
				$aGallery[] = $sPropertyName;
		} while( !is_null( $this->$sPropertyName ) );
		$aGalleryOrder = array();
		if( sizeof( $aGallery ) ) {
			foreach( $aGallery as $sPropertyName ) {
				$sUID = $utils->genUID();
				$sNewPropertyName = 'gal_' . $sUID;
				$this->_aData[ 'dynamic' ][ $sNewPropertyName ] = $this->_aData[ 'dynamic' ][ $sPropertyName ];
				$aGalleryOrder[] = $sUID;
				unset( $this->_aData[ 'dynamic' ][ $sPropertyName ] );
			}
		}
		$this->gallery_order = $aGalleryOrder;
		$this->save();
	} // _updateDataFormat

	private $_aGallery;
	private $_aGalleryPaths;

} // class::ImageBrick
