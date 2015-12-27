<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/short_brick.php
 */

class ShortBrick extends Brick {

	public function __set( $sName, $mValue ) {
		switch( $sName ) {
			case 'content':
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
				'content' => array(
					time() => $sNodeContent ?: 'Lorem ipsum...'
				)
			)
		);
	} // _create

	protected function _render() {
		DOMParser::emptyNodeContent( $this->_oNode );
		$this->_oNode->appendChild( new DOMText( str_replace( '&amp;', '&', $this->content ) ) );
	} // _render

	protected $_sType = Brick::TYPE_SHORT;

} // class::ShortBrick
