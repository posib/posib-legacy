<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/list_brick.php
 */

class ListBrick extends Brick {

	public function __get( $sName ) {
		switch( $sName ) {
			case 'size':
				return sizeof( parent::__get( 'content' ) );
				break;

			default:
				return parent::__get( $sName );
				break;
		}
	} // __get

	protected function _create() {
		return array(
			'static' => array(),
			'dynamic' => array(
				'content' => array(
					time() => array(
						Utils::genUID()
					)
				)
			)
		);
	} // _create

	public function render( $bAdminMode = false, $sLang = null ) {
		if( $bAdminMode && $this->_oNode->hasAttribute( 'data-brick-parsed' ) )
			return false;
		$this->_render( $bAdminMode );
		if( $bAdminMode && !$this->_oNode->hasAttribute( 'data-brick-parsed' ) )
			$this->_oNode->setAttribute( 'data-brick-parsed', 'yes' );
		else
			$this->_clean();
		return true;
	} // render

	protected function _render() {
		$this->_bTableMode = strtolower( $this->_oNode->nodeName ) == 'table';
		$oListElementNode = $this->_bTableMode ? $this->_oNode->getElementsByTagName( 'tr' )->item( 0 ) : $this->_oNode->getElementsByTagName( 'li' )->item( 0 );
		$sCode = '';
		foreach( $this->content as $iIndex => $sListElementKey ) {
			$oClonedListElementNode = $oListElementNode->cloneNode( true );
			foreach( DOMParser::findBricks( $oClonedListElementNode ) as $oSubBrickNode ) {
				$oSubBrickNode->setAttribute( 'data-brick', $this->ref . '.' . $sListElementKey . '.' . $oSubBrickNode->getAttribute( 'data-brick' ) );
				$oSubBrickNode->setAttribute( 'data-list-ref', $this->ref );
				$oSubBrickNode->setAttribute( 'data-list-index', $iIndex + 1 );
			}
			DOMParser::getNodeContent( $oClonedListElementNode, $sClonedListElementNodeContent );
			if( $this->_bTableMode ) {
				$sCode .= '<tr>' . $sClonedListElementNodeContent . '</tr>';
			} else {
				$sCode .= '<li>' . $sClonedListElementNodeContent . '</li>';
			}
			unset( $oClonedListElementNode, $sClonedListElementNodeContent );
		}
		DOMParser::replaceNodeContent( $sCode, $this->_oNode );
		$this->_clean();
	} // _render

	protected $_sType = Brick::TYPE_LIST;
	protected $_bTableMode = false;

} // class::ListBrick
