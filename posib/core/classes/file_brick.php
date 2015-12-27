<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/file_brick.php
 */

class FileBrick extends Brick {

    public function __get( $sName ) {
        switch( $sName ) {
            case 'path':
                return dirname( DATA_PATH ) . '/' . $this->file;
                break;

            default:
                return parent::__get( $sName );
                break;
        }
    } // __get

    public function __set( $sName, $mValue ) {
        switch( $sName ) {
            case 'label':
                parent::__set( $sName, htmlspecialchars( $mValue ) );
                break;

            default:
                parent::__set( $sName, $mValue );
                break;
        }
    } // __set

    protected function _create() {
        DOMParser::getNodeContent( $this->_oNode, $sNodeContent );
        return array(
            'static' => array(),
            'dynamic' => array(
                'label' => array(
                    time() => $sNodeContent ?: 'Lorem ipsum...'
                ),
                'file' => array(
                    time() => '#'
                ),
                'size' => array(
                    time() => 0
                ),
                'name' => array(
                    time() => '#'
                )
            )
        );
    } // _create

    protected function _render() {
        DOMParser::emptyNodeContent( $this->_oNode );
        $this->_oNode->setAttribute( 'href', '/download/' . $this->ref . '/' . $this->file );
        $this->_oNode->appendChild( new DOMText( str_replace( '&amp;', '&', $this->label ) ) );
    } // _render

    protected $_sType = Brick::TYPE_FILE;

} // class::FileBrick
