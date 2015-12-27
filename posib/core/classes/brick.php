<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/brick.php
 */

abstract class Brick {

    const TYPE_SHORT = 'short';
    const TYPE_RICH = 'rich';
    const TYPE_MAP = 'map';
    const TYPE_FORM = 'form';
    const TYPE_IMAGE = 'image';
    const TYPE_LIST = 'list';
    const TYPE_AUDIO = 'audio';
    const TYPE_TIME = 'time';
    const TYPE_FILE = 'file';

    const VERSION_HISTORY_SIZE = 10;

    public function __get( $sName ) {
        // first, we search in static, then dynamic
        if( $sName == 'ref' ) {
            return $this->_sRef;
        } elseif( $sName == 'page' ) {
            return $this->_sURLRef;
        } elseif( $sName == 'type' ) {
            return $this->_sType;
        } elseif( array_key_exists( $sName, $this->_aData[ 'static' ] ) ) {
            return $this->_aData[ 'static' ][ $sName ];
        } elseif( array_key_exists( $sName, $this->_aData[ 'dynamic' ] ) ) {
            return $this->_getVersionnedProperty( $sName );
        } else
            return null;
    } // __get

    public function __set( $sName, $mValue ) {
        if( $sName == 'ref' || $sName == 'page' || array_key_exists( $sName, $this->_aData[ 'static' ] ) ) {
            throw new ErrorException( 'You cannot modify the "' . $sName . '" property of a ' . get_called_class() . ' !' );
        } elseif( !array_key_exists( $sName, $this->_aData[ 'dynamic' ] ) )
            $this->_aData[ 'dynamic' ][ $sName ] = array();
        if( sizeof( $this->_aData[ 'dynamic' ][ $sName ] ) == Brick::VERSION_HISTORY_SIZE ) {
            ksort( $this->_aData[ 'dynamic' ][ $sName ] ); // probably useless, but...
            reset( $this->_aData[ 'dynamic' ][ $sName ] );
            unset( $this->_aData[ 'dynamic' ][ $sName ][ key( $this->_aData[ 'dynamic' ][ $sName ] ) ] );
        }
        $this->_aData[ 'dynamic' ][ $sName ][ time() ] = $mValue;
    } // __set

    public function isInAList() {
        return isset( $this->_aData[ 'static' ][ 'listRef' ] );
    } // isPartOfAList

    public function save( $bForce = false ) {
        return $this->_save( $bForce );
    } // save

    public function render( $bAdminMode = false, $sLang = null ) {
        if( $bAdminMode && $this->_oNode->hasAttribute( 'data-brick-parsed' ) )
            return false;
        $this->_render( $bAdminMode );
        if( $bAdminMode ) {
            if( !$this->_oNode->hasAttribute( 'data-brick-parsed' ) )
                $this->_oNode->setAttribute( 'data-brick-parsed', 'yes' );
            DOMParser::addClassTo( 'posib-editable', $this->_oNode );
        } else
            $this->_clean();
        return true;
    } // render

    public function getTimelineSteps() {
        return $this->_getTimelineSteps();
    } // getTimelineSteps

    public function getPropertyWhen( $sProperty, $iTimestamp, $bExact = false ) {
        return $this->_getPropertyWhen( $sProperty, $iTimestamp, $bExact );
    } // getPropertyWhen

    public function restoreAt( $iTime ) {
        $this->_restoreAt( $iTime );
    } // restoreAt

    final public static function getBrickFromNode( $sURLRef, DOMNode $oNode, $sLang = null ) {
        $sBrickType = self::_getBrickType( $oNode->nodeName );
        switch( $sBrickType ) {
            case self::TYPE_SHORT:
                return new ShortBrick( $sURLRef, $oNode, $sLang );
            case self::TYPE_RICH:
                return new RichBrick( $sURLRef, $oNode, $sLang );
            case self::TYPE_MAP:
                return new MapBrick( $sURLRef, $oNode, $sLang );
            case self::TYPE_FORM:
                return new FormBrick( $sURLRef, $oNode, $sLang );
            case self::TYPE_IMAGE:
                return new ImageBrick( $sURLRef, $oNode, $sLang );
            case self::TYPE_LIST:
                return new ListBrick( $sURLRef, $oNode, $sLang );
            case self::TYPE_AUDIO:
                return new AudioBrick( $sURLRef, $oNode, $sLang );
            case self::TYPE_TIME:
                return new TimeBrick( $sURLRef, $oNode, $sLang );
            case self::TYPE_FILE:
                return new FileBrick( $sURLRef, $oNode, $sLang );
        }
    } // getBrickFromNode

    final public static function getBrickFromRefAndTag( $sURLRef, $sRef, $sTag, $sLang = null ) {
        $sBrickType = self::_getBrickType( $sTag );
        switch( $sBrickType ) {
            case self::TYPE_SHORT:
                return new ShortBrick( $sURLRef, $sRef, $sLang );
            case self::TYPE_RICH:
                return new RichBrick( $sURLRef, $sRef, $sLang );
            case self::TYPE_MAP:
                return new MapBrick( $sURLRef, $sRef, $sLang );
            case self::TYPE_FORM:
                return new FormBrick( $sURLRef, $sRef, $sLang );
            case self::TYPE_IMAGE:
                return new ImageBrick( $sURLRef, $sRef, $sLang );
            case self::TYPE_LIST:
                return new ListBrick( $sURLRef, $sRef, $sLang );
            case self::TYPE_AUDIO:
                return new AudioBrick( $sURLRef, $sRef, $sLang );
            case self::TYPE_TIME:
                return new TimeBrick( $sURLRef, $sRef, $sLang );
            case self::TYPE_FILE:
                return new FileBrick( $sURLRef, $sRef, $sLang );
        }
    } // getBrickFromRefAndTag

    public function __construct( $sURLRef, $mNodeOrRef, $sLang = null ) {
        $this->_sURLRef = $sURLRef;
        $this->_sLang = $sLang;
        if( is_a( $mNodeOrRef, 'DOMElement' ) ) {
            $this->_oNode = $mNodeOrRef;
            if( !$this->_oNode->hasAttribute( 'data-brick' ) )
                throw new LogicException( 'The Brick has no "data-brick" attribute !' );
            $this->_sRef = $mNodeOrRef->getAttribute( 'data-brick' );
            if( strpos( $this->_sRef, UtilsData::SP ) !== false )
                throw new PosibException( 'The "data-brick" attribute of an element cannot contain "' . UtilsData::SP . '" !' );
            $this->_load();
            if( $this->_oNode->hasAttribute( 'data-list-ref' ) && !isset( $this->_aData[ 'static' ][ 'listRef' ] ) ) {
                $this->_aData[ 'static' ][ 'listRef' ] = $this->_oNode->getAttribute( 'data-list-ref' );
                $this->save();
            }
        } else if( !is_null( $mNodeOrRef ) ) {
            $this->_sRef = $mNodeOrRef;
            $this->_load();
            if( $this->_bIsNew )
                throw new PosibException( 'The Brick reference "' . $mNodeOrRef . '" is unknown !' );
        } else {
            throw new PosibException( 'A Brick is constructed from nothing !' );
        }
    } // __construct

    protected function _load() {
        $aData = UtilsData::getInstance()->get( $this->_getDataPath() );
        if( is_null( $aData ) ) {
            $this->_aData = $this->_create();
            $bOperation = $this->_save();
            if( !$bOperation )
                throw new PosibException( 'Error during initial save of a Brick !' );
        } else {
            $this->_bIsNew = false;
            $this->_aData = $aData;
            $this->_aRawData = $aData;
        }
    } // _load

    protected function _save( $bForce = false ) {
        if( !$bForce && $this->_aData === $this->_aRawData )
            return true;
        $this->_equalizeStamps();
        UtilsData::getInstance()->set( $this->_getDataPath(), $this->_aData );
        return UtilsData::getInstance()->save( $bForce );
    } // _save

    protected function _equalizeStamps() {
        $aProperties = array_keys( $this->_aData[ 'dynamic' ] );
        if( sizeof( $aProperties ) > 1 ) {
            $iMaxStamp = 0;
            foreach( $aProperties as $sProperty ) {
                $iPropertyMaxStamp = max( array_keys( $this->_aData[ 'dynamic' ][ $sProperty ] ) );
                $iMaxStamp = ( $iPropertyMaxStamp > $iMaxStamp ) ? $iPropertyMaxStamp : $iMaxStamp ;
            }
            if( $iMaxStamp === 0 )
                throw new PosibException( 'Warning: Max timestamp computing failure when saving brick.' );
            foreach( $aProperties as $sProperty ) {
                if( isset( $this->_aData[ 'dynamic' ][ $sProperty ][ $iMaxStamp ] ) )
                    continue;
                $this->_aData[ 'dynamic' ][ $sProperty ][ $iMaxStamp ] = $this->$sProperty;
            }
        }
    } // _equalizeStamps

    protected function _create() {
        return array(
            'static' => array(),
            'dynamic' => array()
        );
    } // _create

    protected function _clean() {
        $this->_oNode->removeAttribute( 'data-brick' );
        $this->_oNode->removeAttribute( 'data-list-ref' );
        $this->_oNode->removeAttribute( 'data-brick-parsed' );
    } // _clean

    abstract protected function _render(); // _render

    protected function _getTimelineSteps() {
        if( is_null( $this->_aTimelineSteps ) ) {
            $aSteps = array();
            foreach( array_keys( $this->_aData[ 'dynamic' ] ) as $sProperty )
                $aSteps = array_merge( $aSteps, array_keys( $this->_aData[ 'dynamic' ][ $sProperty ] ) );
            $aSteps = array_unique( $aSteps );
            rsort( $aSteps );
            $this->_aTimelineSteps = $aSteps;
        }
        return $this->_aTimelineSteps;
    } // _getTimelineSteps

    protected function _getVersionnedProperty( $sName, $iTime = null, $bStrict = false ) {
        if( is_null( $iTime ) ) {
            return end( $this->_aData[ 'dynamic' ][ $sName ] );
        } else {
            // TODO
        }
    } // _getVersionnedProperty

    protected function _getPropertyWhen( $sProperty, $iTimestamp, $bExact = false ) {
        if( $bExact ) {
            // TODO
        } else {
            if( isset( $this->_aData[ 'dynamic' ][ $sProperty ][ $iTimestamp ] ) ) {
                return $this->_aData[ 'dynamic' ][ $sProperty ][ $iTimestamp ];
            } else {
                foreach( $this->_getTimelineSteps() as $iStep ) {
                    if( $iTimestamp < $iStep ) continue;
                    if( isset( $this->_aData[ 'dynamic' ][ $sProperty ][ $iStep ] ) )
                        return $this->_aData[ 'dynamic' ][ $sProperty ][ $iStep ];
                    else
                        continue;
                }
                return null;
            }
        }
    } // _getPropertyWhen

    protected function _restoreAt( $iTime ) {
        foreach( $this->_aData[ 'dynamic' ] as $sProperty=>$mValue )
            $this->$sProperty = $this->_getPropertyWhen( $sProperty, $iTime );
    } // _restoreAt

    protected function _getDataPath() {
        if( is_null( $this->_sDataPath ) ) {
            $sLangPart = is_null( $this->_sLang ) ? '' : ( UtilsData::SP . $this->_sLang ) ;
            $this->_sDataPath = UtilsData::SP . 'bricks' . $sLangPart . UtilsData::SP . $this->_sURLRef . UtilsData::SP . $this->_sRef;
        }
        return $this->_sDataPath;
    } // _getDataPath

    protected $_oNode;
    protected $_sURLRef;
    protected $_sRef;
    protected $_sDataPath;
    protected $_aRawData;
    protected $_aData;
    protected $_sType;
    protected $_bIsNew = true;
    protected $_aTimelineSteps;
    protected $_sLang;
    protected $_bParsed = false;

    private static function _getBrickType( $sNodeName ) {
        switch( strtolower( $sNodeName ) ) {
            // short
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
            case 'span':
            case 'small':
            case 'strong':
            case 'em':
            case 'b':
            case 'u':
            case 'i':
            case 'del':
            case 'figcaption':
                return self::TYPE_SHORT;

            // rich
            case 'p':
                return self::TYPE_RICH;

            // map
            case 'address':
                return self::TYPE_MAP;

            // form
            case 'form':
                return self::TYPE_FORM;

            // image
            case 'img':
                return self::TYPE_IMAGE;

            // audio
            case 'audio':
                return self::TYPE_AUDIO;

            // time
            case 'time':
                return self::TYPE_TIME;

            // file
            case 'a':
                return self::TYPE_FILE;

            // lists
            case 'ol':
            case 'ul':
            case 'table':
                return self::TYPE_LIST;

            default:
                throw new PosibException( 'The NodeName "' . $sNodeName . '" is not Brickable !' );
        }
    } // _getBrickType

} // class::Brick
