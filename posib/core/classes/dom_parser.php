<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/dom_parser.php
 */

class DOMParser extends DOMDocument {

	public static function getCachePathFor( $sURL, $sLang = null ) {
		return dirname( DATA_PATH ) . '/' . md5( $sURL . $sLang ) . '.cached';
	} // getCachePathFor

	public static function emptyNodeContent( DOMNode $oNode ) {
		while( $oNode->childNodes->length > 0 )
			$oNode->removeChild( $oNode->childNodes->item( 0 ) );
	} // emptyNodeContent

	public static function replaceNodeContent( $sContent, DOMNode $oNode ) {
		DOMParser::emptyNodeContent( $oNode );
		$oTMPContentDocument = new DOMDocument();
		$oTMPContentDocument->loadXML( '<root>' . $sContent . '</root>' );
		foreach( $oTMPContentDocument->firstChild->childNodes as $oChildNode ) {
			$oInsertedNode = $oNode->ownerDocument->importNode( $oChildNode, true );
			$oNode->appendChild( $oInsertedNode );
		}
	} // replaceNodeContent

	public static function replaceNodeWithContent( $sContent, DOMNode $oNode ) {
		$oTMPContentDocument = new DOMDocument();
		$oTMPContentDocument->loadXML( '<root>' . $sContent . '</root>' );
		foreach( $oTMPContentDocument->firstChild->childNodes as $oChildNode ) {
			$oInsertedNode = $oNode->ownerDocument->importNode( $oChildNode, true );
			$oNode->parentNode->insertBefore( $oInsertedNode, $oNode );
		}
		$oNode->parentNode->removeChild( $oNode );
	} // replaceNodeWithContent

	public static function prependToNode( $sContent, DOMNode $oNode ) {
		$oTMPDocument = new DOMDocument();
		$oTMPDocument->loadXML( $sContent );
		$oInsertedNode = $oNode->ownerDocument->importNode( $oTMPDocument->firstChild, true );
		$oNode->insertBefore( $oInsertedNode, $oNode->firstChild );
	} // prependToNode

	public static function appendToNode( $sContent, DOMNode $oNode ) {
		$oTMPDocument = new DOMDocument();
 		$oTMPDocument->loadXML( $sContent );
		$oInsertedNode = $oNode->ownerDocument->importNode( $oTMPDocument->firstChild, true );
		$oNode->appendChild( $oInsertedNode );
	} // appendToNode

 	public static function getNodeContent( DOMNode $oNode, &$sNodeContent="" ) {
 	    $oNodesList = $oNode->childNodes;
 	    for( $j=0; $j < $oNodesList->length; $j++ ) {
 	        $oCurrentNode = $oNodesList->item( $j );
 	        $sNodeName = $oCurrentNode->nodeName;
 	        if( $oCurrentNode->nodeType == XML_TEXT_NODE ) {
				$sNodeContent .= trim( $oCurrentNode->wholeText ) ?: ' ';
 	        } else {
				$sNodeContent .= '<' . $sNodeName;
				$oCurrentNodeAttributes = $oCurrentNode->attributes;
				foreach( $oCurrentNodeAttributes as $oAttribute )
					$sNodeContent .= ' ' . $oAttribute->nodeName . '="' . $oAttribute->nodeValue . '"';
 	            if( $sNodeName == 'br' ) {
 	            	$sNodeContent .= " />";
					continue;
				}
				$sNodeContent .= '> ';
 	            self::getNodeContent( $oCurrentNode, $sNodeContent );
 	            $sNodeContent .= '</' . $sNodeName . '>';
 	        }
 	    }
 	} // getNodeContent

 	public static function addClassTo( $sNewClass, $mNodes ) {
 		$aNodes = is_array( $mNodes ) ? $mNodes : array( $mNodes );
 		foreach( $aNodes as $oNode ) {
 			if( $oNode->hasAttribute( 'class' ) ) {
 				$sCurrentClassString = $oNode->getAttribute( 'class' );
 				$aCurrentClasses = explode( ' ', $sCurrentClassString );
 				if( in_array( $sNewClass, $aCurrentClasses ) )
 					return;
 				$oNode->setAttribute( 'class', $sCurrentClassString . ' ' . $sNewClass );
 			} else
 				$oNode->setAttribute( 'class', $sNewClass );
 		}
 	} // addClassTo

 	public static function findBricks( $oNode = null ) {
 		$aBricks = array();
 		foreach( $oNode->childNodes as $oChildNode ) {
 			if( $oChildNode->hasAttributes() ) {
 				if( $oChildNode->hasAttribute( 'data-brick' ) ) {
 					$aBricks[] = $oChildNode;
 					if( !$oChildNode->hasAttribute( 'data-brick-parsed' ) )
 						continue;
 				}
 			}
 			if( $oChildNode->hasChildNodes() )
 				$aBricks = array_merge( $aBricks, self::findBricks( $oChildNode ) );
 		}
 		return $aBricks;
 	} // findBricks

	public function __construct( $sURLPath, $bAdminMode = false, $sLang = null ) {
		parent::__construct();
		$this->_sURL = $sURLPath;
		$this->_sCachePath = self::getCachePathFor( $this->_sURL, $sLang );
		$this->_bAdminMode = $bAdminMode;
		$this->_sDataPath = UtilsData::SP . 'pages' . UtilsData::SP . $this->_sURL . UtilsData::SP;
		$this->_sPath = UtilsData::getInstance()->get( $this->_sDataPath . 'template' );
		$this->_sLang = in_array( $sLang , UtilsData::getInstance()->get( UtilsData::SP . 'config' . UtilsData::SP . 'lang', array() ) ) ? $sLang : null;
	} // __construct

	public function display( $aAdditionalData = null, $bSkipCache = false ) {
		if( $bSkipCache || !$this->_existInCache() || $this->_bAdminMode ) {
			$this->_init();
			$this->_parseLangBricks();
			$this->_parseBricks();
			$this->_parseMenuBricks();
			$this->_fillPageInfos();
			if( $this->_bAdminMode ) {
				$this->_adminModeManipulations();
			} else {
				$this->_transformLinks();
				if( !UtilsData::getInstance()->get( UtilsData::SP . 'config' . UtilsData::SP . 'public' . UtilsData::SP . 'disable_js', false ) )
					$this->_appendTo( '<script src="/posib/static/js/public.js"></script>', $this->_getTag( 'body' ) );
			}
			if( $aAdditionalData )
				$this->_appendTo( '<script>var posib_autoload_data = ' . json_encode( $aAdditionalData ) . ';</script>', $this->_getTag( 'body' ) );
			if( !$bSkipCache && !$this->_bAdminMode )
				$this->_storeInCache();
			echo $this->_getHTMLCode();
		} else
			echo $this->_getFromCache();
	} // display

	public function displayWithConnectBox( $bError = false ) {
		$this->_init();
		self::addClassTo( 'posib-connect', $this->_getTag( 'body' ) );
		if( $bError )
			self::addClassTo( 'posib-connect-error', $this->_getTag( 'body' ) );
		$this->_appendTo( '<link rel="stylesheet" href="/posib/static/css/styles.css" />', $this->_getTag( 'head' ) );
		$this->_appendTo( '<div class="posib-backdrop"></div>', $this->_getTag( 'body' ) );
		$this->_appendTo( '<script src="/posib/static/js/connect.js"></script>', $this->_getTag( 'body' ) );
		$this->_prependTo( file_get_contents( POSIB . 'includes/ie-warning.inc' ), $this->_getTag( 'body' ) );
		$this->display( null, true );
	} // displayWithConnectBox

	public function exists() {
		return file_exists( $this->_sPath );
	} // exists

	private function _init() {
		if( !$this->_bLoaded ) {
			if( !$this->exists() )
				throw new PosibException( 'Error: template file "' . $this->_sPath . '" doesn\'t exists !' );
			$this->_sFileContent = file_get_contents( ROOT . $this->_sPath );
			$this->loadXML( $this->_sFileContent );
			$this->_bLoaded = true;
		}
	} // _init

	private function _existInCache() {
		return file_exists( $this->_sCachePath );
	} // _existInCache

	private function _getFromCache() {
		return file_get_contents( $this->_sCachePath );
	} // _getFromCache

	private function _storeInCache() {
		file_put_contents( $this->_sCachePath, $this->_getHTMLCode() );
	} // _storeInCache

	private function _getHTMLCode() {
		return '<!DOCTYPE html>' . "\n" . $this->_saveXHTML();
	} // _getHTMLCode

	private function _transformLinks() {
		// TODO : use regex for parsing / filtering
		if( !is_null( $this->_sLang ) ) {
			foreach( $this->getElementsByTagName( 'a' ) as $oLinkNode ) {
				$sHref = $oLinkNode->getAttribute( 'href' );
				if( substr( $sHref, 0, 7 ) == '/admin/' )
					continue;
				if( $oLinkNode->hasAttribute( 'data-preserve-link' ) ) {
					$oLinkNode->removeAttribute( 'data-preserve-link' );
					continue;
				}
				if( $sHref == '/' )
					$oLinkNode->setAttribute( 'href', '/' . $this->_sLang . '/' );
				else if( substr( $sHref, 0, 1 ) == '/' || substr( $sHref, -5 ) == '.html' ) {
					if( in_array( substr( $sHref, 1, 3 ), UtilsData::getInstance()->get( UtilsData::SP . 'config' . UtilsData::SP . 'lang', array() ) ) )
						continue;
					else {
						$oLinkNode->setAttribute( 'href', '/' . $this->_sLang . $sHref );
					}
				}
			}
		}
	} // _transformLinks

	private function _adminModeManipulations() {
		$this->_getTag( 'body' )->setAttribute( 'data-posib-ref', $this->_sURL );
		self::addClassTo( 'posib-admin', $this->_getTag( 'body' ) );
		$this->_appendTo( '<meta name="system.name" value="' . Branding::getInstance()->name . '" />', $this->_getTag( 'head' ) );
		$this->_appendTo( '<meta name="system.version" value="' . Branding::getInstance()->version . '" />', $this->_getTag( 'head' ) );
		$this->_appendTo( '<link rel="stylesheet" href="/posib/static/css/styles.css" />', $this->_getTag( 'head' ) );
		$this->_appendTo( '<div class="posib-backdrop"></div>', $this->_getTag( 'body' ) );
		$this->_appendTo( '<div class="posib-editable-hover"><div><strong>Zone éditable</strong><small><span>1</span><sup>er</sup> élément de la liste</small><span>cliquez pour éditer</span></div></div>', $this->_getTag( 'body' ) );
		$this->_appendTo( '<script src="/posib/static/js/admin.js"></script>', $this->_getTag( 'body' ) );
		$this->_transformLinksInAdminMode();
		$this->_generateToolbar();
	} // _adminModeManipulations

	private function _transformLinksInAdminMode() {
		foreach( $this->getElementsByTagName( 'a' ) as $oLinkNode ) {
			if( substr( $oLinkNode->getAttribute( 'href' ), 0, 7 ) == '/admin/' )
				continue;
			if( $oLinkNode->getAttribute( 'href' ) == '/' )
				$oLinkNode->setAttribute( 'href', '/admin/' );
			else if( substr( $oLinkNode->getAttribute( 'href' ), 0, 1 ) == '/' || substr( $oLinkNode->getAttribute( 'href' ), -5 ) == '.html' ) {
				if( substr( $oLinkNode->getAttribute( 'href' ), 0, 1 ) == '/' )
					$oLinkNode->setAttribute( 'href', '/admin' . $oLinkNode->getAttribute( 'href' ) );
				else
					$oLinkNode->setAttribute( 'href', '/admin/' . $oLinkNode->getAttribute( 'href' ) );
			}
		}
	} // _transformLinksInAdminMode

	private function _generateToolbar() {
		$oBranding = Branding::getInstance();
		$oBranding->brand = UtilsData::getInstance()->get( UtilsData::SP . 'config' . UtilsData::SP . 'brand', Branding::BRAND_POSIB );
		$sToolbarCode = '<div class="posib-toolbar">
							<div class="posib-branding" id="brand_' . $oBranding->brand . '">';
		$sToolbarCode .= '		<a href="' . $oBranding->url . '" rel="external">
									' . $oBranding->name . '
								</a>';
		$sToolbarCode .= ' 	</div>
							<div class="posib-tools">';
		$aAvailableLangs = UtilsData::getInstance()->get( ':config:lang' );
		if( is_array( $aAvailableLangs ) ) {
			$sCurrentLang = Utils::getInstance()->globals->session( 'lang' ) ?: Utils::getInstance()->getDefaultLanguage();
			$sToolbarCode .= '	<a href="javascript:void(0);" id="posib-lang" title="langue d\'édition">
									<img alt="langue d\'édition" src="/posib/static/icons/flags/' . $sCurrentLang . '.png" />
								</a>';
			$sToolbarCode .= '  <ul id="posib-lang-switcher">';
			foreach( $aAvailableLangs as $sLangName ) {
				$sTarget = $sLangName == $sCurrentLang ? ( 'javascript:void(0);" class="posib-lang-switcher-toggle' ) : ( '/admin/langswitch/' . $sLangName . '.html' );
				$sToolbarCode .= '  <li>
										<a href="' . $sTarget . '">
											<img alt="' . $sLangName . '" src="/posib/static/icons/flags/' . $sLangName . '.png" />
										</a>
									</li>';
			}
			$sToolbarCode .= '  </ul>';
		}
		if( Utils::getInstance()->globals->session( 'root' ) || Utils::getInstance()->globals->session( 'admin' ) ) {
			$sToolbarCode .= '	<a href="javascript:void(0);" rel="root.config" title="éditer paramètres de configuration">
									<img alt="éditer paramètres de configuration" src="/posib/static/icons/hammer-screwdriver.png" />
								</a>';
			$sToolbarCode .= '	<a href="javascript:void(0);" rel="root.users" title="gestion des utilisateurs">
									<img alt="gestion des utilisateurs" src="/posib/static/icons/users.png" />
								</a>';
		}
		if( Utils::getInstance()->globals->session( 'root' ) ) {
			$sToolbarCode .= '	<a href="javascript:void(0);" rel="root.brand" title="gestion du branding">
									<img alt="gestion du branding" src="/posib/static/icons/stamp.png" />
								</a>';
		}
		if( Utils::getInstance()->globals->session( 'root' ) || UtilsData::getInstance()->get( UtilsData::SP . 'config' . UtilsData::SP . 'public' . UtilsData::SP . 'enable_sitemap', false ) ) {
			$sToolbarCode .= '		<a href="javascript:void(0);" rel="sitemap" title="modifier les pages">
										<img alt="modifier les pages" src="/posib/static/icons/sitemap-image.png" />
									</a>';
		}
		$sToolbarCode .= '		<a href="javascript:void(0);" rel="infos" title="éditer infos de la page">
									<img alt="éditer infos du site" src="/posib/static/icons/document-hf-select.png" />
								</a>
								<a href="javascript:void(0);" rel="about" title="à propos de...">
									<img alt="à propos de..." src="/posib/static/icons/infocard.png" />
								</a>
								<a href="/admin/exit/" title="quitter le mode admin">
									<img alt="quitter le mode admin" src="/posib/static/icons/door-open-in.png" />
								</a>
							</div>
						</div>';
		$this->_appendTo( $sToolbarCode, $this->_getTag( 'body' ) );
	} // _generateToolbar

	private function _fillPageInfos() {
		$sLang = $this->_sLang ? ':' . $this->_sLang : null;
		$sTitle = Utils::getInstance()->data->get( $this->_sDataPath . 'title' . $sLang, 'TITRE DE LA PAGE' );
		$sKeywords = Utils::getInstance()->data->get( $this->_sDataPath . 'keywords' . $sLang, '' );
		$sDescription = Utils::getInstance()->data->get( $this->_sDataPath . 'description' . $sLang, '' );
		self::replaceNodeContent( htmlspecialchars( $sTitle ), $this->_getTag( 'title' ) );
		$this->_appendTo( '<meta name="keywords" content="' . htmlspecialchars( $sKeywords ) . '" />', $this->_getTag( 'head' ) );
		$this->_appendTo( '<meta name="description" content="' . htmlspecialchars( $sDescription ) . '" />', $this->_getTag( 'head' ) );
	} // _fillPageInfos

	private function _parseLangBricks() {
		$oDOMXPath = new DOMXPath( $this );
		$oLangBricksList = $oDOMXPath->query( '//*[@data-lang]' );
		foreach( $oLangBricksList as $oLangBrick ) {
			if( $this->_sLang !== $oLangBrick->getAttribute( 'data-lang' ) ) {
				$oLangBrick->parentNode->removeChild( $oLangBrick );
			} else {
				$oLangBrick->removeAttribute( 'data-lang' );
			}
		}
	} // _parseLangBricks

	private function _parseBricks() {
		while( sizeof( $this->_getBricks() ) > 0 ) {
			$iParsedBricks = 0;
			foreach( $this->_getBricks() as $oBrickNode ) {
				$oBrick = Brick::getBrickFromNode( $this->_sURL, $oBrickNode, $this->_sLang );
				$iParsedBricks += intval( $oBrick->render( $this->_bAdminMode ) );
			}
			if( $iParsedBricks == 0 )
				return;
		}
	} // _parseBricks

 	private function _getBricks() {
 		return self::findBricks( $this->_getTag( 'body' ) ) ?: array();
 	} // _getBricks

 	private function _parseMenuBricks() {
 		$aPages = Utils::getInstance()->getPages();
 		foreach( $this->_getMenuBricks() as $oMenuBrickNode ) {
			$sMenuNodeContent = '';
			foreach( $aPages as $aPageInfos ) {
				$oClonedMenuNode = $oMenuBrickNode->cloneNode( true );
				$this->_parseMenuBrick( $oClonedMenuNode, $aPageInfos[ 'url' ], $aPageInfos[ 'name' ] );
				self::getNodeContent( $oClonedMenuNode, $sMenuNodeContent );
			}
			self::replaceNodeContent( $sMenuNodeContent, $oMenuBrickNode );
			$oMenuBrickNode->removeAttribute( 'data-menu' );
		}
 	} // _parseMenuBricks

 	private function _parseMenuBrick( DOMNode $oCurrentNode, $sPagePath, $sPageName, $iLevel = 0 ) {
 		if( $oCurrentNode->nodeType == XML_ELEMENT_NODE && $this->_sPath == $sPagePath && $iLevel == 1 )
			self::addClassTo( 'active', $oCurrentNode );
		if( $oCurrentNode->nodeType == XML_ELEMENT_NODE && $oCurrentNode->hasAttribute( 'data-href' ) ) {
 			$oCurrentNode->removeAttribute( 'data-href' );
 			$oCurrentNode->setAttribute( 'href', $sPagePath );
 		}
 		if( $oCurrentNode->nodeType == XML_ELEMENT_NODE && $oCurrentNode->hasAttribute( 'data-content' ) ) {
 			$oCurrentNode->removeAttribute( 'data-content' );
 			self::replaceNodeContent( $sPageName, $oCurrentNode );
 		} elseif( $oCurrentNode->nodeType == XML_ELEMENT_NODE ) {
 			$oNodesList = $oCurrentNode->childNodes;
 			for( $j=0; $j < $oNodesList->length; $j++ ) {
 			    $oChildNode = $oNodesList->item( $j );
				$this->_parseMenuBrick( $oChildNode, $sPagePath, $sPageName, $iLevel + 1 );
 			}
 		}
 	} // _parseMenuBrick

 	private function _getMenuBricks() {
 		if( is_null( $this->_aMenuBricks ) )
 			$this->_aMenuBricks = $this->_findMenuBricks();
 		return $this->_aMenuBricks;
 	} // _getMenuBricks

 	private function _findMenuBricks( $oNodeRef = null ) {
 		$aMenuBricks = array();
 		$oNode = $oNodeRef ?: $this->_getTag( 'body' );
 		foreach( $oNode->childNodes as $oChildNode ) {
 			if( $oChildNode->hasAttributes() ) {
 				if( $oChildNode->hasAttribute( 'data-menu' ) ) {
 					$aMenuBricks[] = $oChildNode;
 					continue;
 				}
 			}
 			if( $oChildNode->hasChildNodes() )
 				$aMenuBricks = array_merge( $aMenuBricks, $this->_findMenuBricks( $oChildNode ) );
 		}
 		return $aMenuBricks;
 	} // _findMenuBricks

	// utilities methods

	private function _getTag( $sTagName ) {
 		return $this->getElementsByTagName( $sTagName )->item( 0 );
 	} // _getTag

	private function _prependTo( $sCode, DOMNode $oRef ) {
		$oTMPDocument = new DOMDocument();
		$oTMPDocument->loadXML( $sCode );
		$oInsertedNode = $this->importNode( $oTMPDocument->firstChild, true );
		$oRef->insertBefore( $oInsertedNode, $oRef->firstChild );
 	} // _prependTo

 	private function _appendTo( $sCode, DOMNode $oRef ) {
 		$oTMPDocument = new DOMDocument();
 		$oTMPDocument->loadXML( $sCode );
		$oInsertedNode = $this->importNode( $oTMPDocument->firstChild, true );
		$oRef->appendChild( $oInsertedNode );
 	} // _appendTo

 	private function _saveXHTML( DOMNode $oNode = null ) {
		$oNode = $oNode ?: $this->getElementsByTagName( 'html' )->item( 0 );

 	    $oDocument = new DOMDocument( '1.0' );
 	    $oClone = $oDocument->importNode( $oNode->cloneNode( false ), true );
 	    $oSelfTerminate = in_array( strtolower( $oClone->nodeName ), $this->_aSelfTerminateTags );
 	    $sContent = '';

 	    if ( !$oSelfTerminate ) {
			$oClone->appendChild( new DOMText( '' ) );
			if( $oNode->childNodes ) {
				foreach( $oNode->childNodes as $oChild ) {
					$sContent .= $this->_saveXHTML( $oChild );
				}
			}
 	    }

 	    $oDocument->appendChild( $oClone );
 	    $sOutput = $oDocument->saveXML( $oClone );

 	    return $oSelfTerminate ? substr( $sOutput, 0, -2 ) . " />\n" : str_replace( '><', ">$sContent<", $sOutput );
	}

	private $_sPath;
	private $_sURL;
	private $_sCachePath;
	private $_sDataPath;
	private $_bLoaded = false;
	private $_sLang;
	private $_sFileContent;
	private $_aMenuBricks;

	private $_bAdminMode = false;

	private $_aSelfTerminateTags = array(
		'area','base','basefont','br','col','frame','hr','img','input','link','meta','param'
  	);

} // class::DOMParser
