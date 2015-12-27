<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/rich_brick.php
 */

class RichBrick extends Brick {

	public function __set( $sName, $mValue ) {
		switch( $sName ) {
			case 'content':
				parent::__set( $sName, $this->_cleanContent( $mValue ) );
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
					time() => $sNodeContent ? '<p>' . $sNodeContent . '</p>' : '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>'
				)
			)
		);
	} // _create

	protected function _render( $bAdminMode = false ) {
		$sContent = $this->content;
		if( $bAdminMode ) {
			$oTMPDocument = new DOMDocument();
			$oTMPDocument->loadXML( '<root>' . str_replace( '&', '&amp;', $sContent ) . '</root>' );
			if( $oTMPDocument->firstChild->childNodes->length == 1 && $oTMPDocument->firstChild->childNodes->item( 0 )->nodeType == XML_TEXT_NODE ) {
				$oTMPDocument->loadXML( '<root><p>' . $sContent . '</p></root>' );
			}
			foreach( $oTMPDocument->firstChild->childNodes as $oChildNode ) {
				if( $oChildNode->nodeType == XML_TEXT_NODE )
					continue;
				if( $oChildNode->nodeName == 'div' ) {
					$oChildNode->parentNode->removeChild( $oChildNode );
					continue;
				}
				foreach( $this->_oNode->attributes as $oOriginalNodeAttribute )
					$oChildNode->setAttribute( $oOriginalNodeAttribute->name, $oOriginalNodeAttribute->value );
				// $oChildNode->setAttribute( 'data-brick', $this->ref );
				$oChildNode->setAttribute( 'data-brick-parsed', 'yes' );
				DOMParser::addClassTo( 'posib-editable', $oChildNode );
			}
			$sContent = null;
			DOMParser::getNodeContent( $oTMPDocument->firstChild, $sContent );
		}
		DOMParser::replaceNodeWithContent( str_replace( '& ', '&amp; ', $this->_cleanContent( $sContent ) ), $this->_oNode );
	} // _render

	protected $_sType = Brick::TYPE_RICH;

	private function _cleanContent( $sContent ) {
		// cleaning spaces
		$sContent = str_replace( '&nbsp;', ' ', $sContent );

		// cleaning <div>
		$sContent = str_replace( array( '<div>', '</div>' ), array( '<p>', '</p>' ), $sContent );

		// cleaning single <p>
		$sContent = str_replace( '<p />', '', $sContent );

		// cleaning <br>
		$sContent = str_replace( '<br>', '<br />', $sContent );

		// cleaning single <p><br /></p>
		$sContent = str_replace( '<p><br /></p>', '', $sContent );

		// cleaning style attributes
		$sContent = preg_replace( '/\sstyle="[^"]+"/', '', $sContent );

		// cleaning whitespaces
		$sContent = str_replace( "\n", '', $sContent );
		$sContent = str_replace( "\t", '', $sContent );

		return $sContent;
	} // _cleanContent

} // class::RichBrick
