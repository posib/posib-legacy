<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/tools/utils.php - Utils class : big ol' singleton-class with globals access and utility stuffs
 */

class Utils extends Singleton {

	public function __get( $sName ) {
		switch( $sName ) {
			case 'strings':
				return UtilsStrings::getInstance();
				break;
			case 'globals':
				return UtilsGlobals::getInstance();
				break;
			case 'data':
				return UtilsData::getInstance();
				break;
			default:
				return null;
				break;
		}
	} // __get

	public function trace() {
		$aAttributes = func_get_args();
		if( !is_array( $aAttributes ) )
			die( 'nothing to debug' );
		$aBacktrace = debug_backtrace();
		$aBacktrace = reset( $aBacktrace );
		echo '<div style="border: 1px solid #ffc266; background: #ffffcc; padding: 2px 5px; margin: 5px; font-size: 11px; font-family: Verdana;">';
			echo '<strong style="color: #ff944c;"><small>trace: ' . str_replace( $_SERVER[ 'DOCUMENT_ROOT' ], '', $aBacktrace[ 'file' ] ) . ' ln. ' . $aBacktrace[ 'line' ] . '</small></strong>';
		for( $i=0; $i<count( $aAttributes ); $i++ ) {
			if( $i > 0 )
				echo '<hr style="height: 1px; border: 0; background: #ffd699" />';
			echo '<pre>';
				var_dump( $aAttributes[ $i ] );
			echo '</pre>';
		}
		echo '</div>';
	} // trace

	public function clearCache() {
		if( is_array( glob( dirname( DATA_PATH ) . '/*.cached' ) ) )
			foreach( glob( dirname( DATA_PATH ) . '/*.cached' ) as $sCacheFile )
				unlink( $sCacheFile );
	} // clearCache

	public function load( $mPaths, $aOrderPaths = array() ) {
		$aFiles = ( is_array( $mPaths ) && sizeof( $mPaths ) ) ? $mPaths : glob( $mPaths );
		if( sizeof( $aOrderPaths ) ) {
			foreach( $aOrderPaths as $sOrderedPath ) {
				$iIndex = array_search( $sOrderedPath, $aFiles );
				if( $iIndex !== false ) {
					include( $sOrderedPath );
					unset( $aFiles[ $iIndex ] );
				}
			}
		}
		if( sizeof( $aFiles ) )
			foreach( $aFiles as $sFilePath )
				include( $sFilePath );
	} // load

	public function genUID() {
		return substr( md5( uniqid() ), 0, 8 );
	} // genUID

	public function now( $iDecalage = 0 ) {
		return time() + $iDecalage;
	} // now

	public function br2nl( $sStr ) {
		return preg_replace( '(<br[[:space:]]*/?[[:space:]]*>)', chr(13).chr(10), $sStr );
	} // br2nl

	public function centralEllipsis( $sText, $iWrapSize = 80 ) {
		if( strlen( $sText ) <= $iWrapSize ) {
		    return $sText;
		}
		$iLimit = round( $iWrapSize / 2 ) - 1;
		return substr( $sText, 0, $iLimit ) . "<span>&hellip;</span>" . substr( $sText, -$iLimit );
	} // centralEllipsis

	public function array_keys_exists( $aNeedle, $aHaystack ) {
		if( !is_array( $aHaystack ) )
			return false;
		foreach( $aNeedle as $sKey )
			if( !array_key_exists( $sKey, $aHaystack ) )
				return false;
		return true;
	} // array_keys_exists

	public function array_access( $aArray, $sKey ) {
		return $aArray[ $sKey ];
	} // array_access

	public function getDefaultLanguage() {
		$aAvailableLangs = $this->data->get( ':config:lang', array() );
		if( !is_array( $aAvailableLangs ) )
			return null;
		$sDefaultLanguage = @strtolower( @substr( @array_shift( @explode( ',', @array_shift( @explode( ';', $this->globals->server( 'http_accept_language' ) ) ) ) ), 0, 2) ) ?: null; // YEAH !
		if( is_null( $sDefaultLanguage ) || !in_array( $sDefaultLanguage, $aAvailableLangs ) )
			return array_shift( $aAvailableLangs );
		else
			return $sDefaultLanguage;
	} // getDefaultLanguage

	public function getDirectoryIndex( $sBase = './' ) {
		$aAvailableFiles = $this->getTemplates( $sBase );
		$aDirectoryIndexes = array( 'index.html', 'index.htm', 'accueil.html', 'accueil.htm', 'home.html', 'home.htm' );
		foreach( $aDirectoryIndexes as $sIndexFile )
			if( array_search( $sIndexFile, $aAvailableFiles ) !== false )
				return $sIndexFile;
		return null;
	} // getDirectoryIndex

	public function getTemplates( $sBase = './' ) {
		$aAvailableFiles = array_merge( glob( $sBase . '*.html' ) ?: array() , glob( $sBase . '*.htm' ) ?: array() );
		foreach( $aAvailableFiles as &$sAvailableFile )
			$sAvailableFile = str_replace( $sBase, '', $sAvailableFile );
		return $aAvailableFiles;
	} // getTemplates

	public function getPages() {
		$aPages = $this->data->get( UtilsData::SP . 'pages' );
		$aSitemap = $this->data->get( UtilsData::SP . 'sitemap' ) ?: array();
		$aOrderedPages = array();
		foreach( $aSitemap as $sPage ) {
			$aPages[ $sPage ][ 'url' ] = $sPage;
			$aOrderedPages[] = $aPages[ $sPage ];
		}
		return $aOrderedPages;
	} // getPages

} // class::Utils
