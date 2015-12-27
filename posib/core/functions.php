<?php
/* Syemes - CMS Made Funny
 * /core/functions.php - commons functions
 */

function trace() {
	global $config;
	$aAttributes = func_get_args();
	if(!is_array($aAttributes))
		die('nothing to debug');
	$aBacktrace = debug_backtrace();
	$aBacktrace = reset( $aBacktrace );
	echo '<div style="border: 1px solid #ffc266; background: #ffffcc; padding: 5px; margin: 5px; font-size: 11px; font-family: Verdana;">';
		echo '<strong style="color: #ff944c;"><small>trace: ' . str_replace($config['path']['system'], '/', $aBacktrace['file']) . ' ln. ' . $aBacktrace['line'] . '</small></strong>';
	for($i=0; $i<count($aAttributes); $i++) {
		if($i > 0)
			echo '<hr style="height: 1px; border: 0; background: #ffd699" />';
		echo '<pre style="color: #333;">';
			var_dump($aAttributes[$i]);
		echo '</pre>';
	}
	echo '</div>';
} // trace

function include_all( $sPattern ) {
	$aFiles = glob( $sPattern );
	if( sizeof( $aFiles ) )
		foreach( $aFiles as $sFilePath )
			include( $sFilePath );
} // include_all

function genUID() {
	return substr( md5( uniqid() ), 0, 8 );
} // genUID

	// --- global accessors

function get($sKey=null, $mDefault=null) {
	return globals($sKey, $_GET, $mDefault);
} // get

function post($sKey=null, $mDefault=null) {
	return globals($sKey, $_POST, $mDefault);
} // post

function session($sKey=null, $mDefault=null) {
	return globals($sKey, $_SESSION, $mDefault, true);
} // session

function cookie($sKey=null, $mDefault=null) {
	if( !is_null( $mDefault ) )
		setcookie( $sKey, strval( $mDefault ), ( time() + 31536000 ), '/', str_replace('http://', '', Config::get('path.url') ) );
	return globals($sKey, $_COOKIE, $mDefault);
} // cookie

function server($sKey=null, $mDefault=null) {
	return globals($sKey ? strtoupper($sKey) : null, $_SERVER, $mDefault);
} // cookie

function files($sKey=null, $mDefault=null) {
	return globals($sKey, $_FILES, $mDefault);
} // cookie

function globals($sKey=null, &$aTable, $mDefault=null, $bAssign=false) {
	if( is_null( $sKey ) )
		return $aTable;
	if( $bAssign && !is_null( $mDefault ) ) {
		$aTable[$sKey] = $mDefault;
		return $aTable[$sKey];
	}
	if( isset( $aTable[$sKey] ) && ( !empty( $aTable[$sKey] ) || is_numeric( $aTable[$sKey] ) ) ) {
		if( is_string( $aTable[ $sKey ] ) )
			return stripslashes( $aTable[ $sKey ] );
		else
			return $aTable[ $sKey ];
	} else
		return $mDefault;
} // globals

function str_no_accent($Text) {
	$aReplace = array("À"=>"A","Á"=>"A","Â"=>"A","Ã"=>"A","Ä"=>"A","Å"=>"A","Ç"=>"C","Ð"=>"D","È"=>"E","É"=>"E","Ê"=>"E","Ë"=>"E","Ì"=>"I","Í"=>"I","Î"=>"I","Ï"=>"I","Ñ"=>"N","Ò"=>"O","Ó"=>"O","Ô"=>"O","Õ"=>"O","Ö"=>"O","Ø"=>"O","Š"=>"S","Ù"=>"U","Ú"=>"U","Û"=>"U","Ü"=>"U","Ý"=>"Y","Ž"=>"Z","à"=>"a","á"=>"a","â"=>"a","ã"=>"a","ä"=>"a","å"=>"a","ç"=>"c","è"=>"e","é"=>"e","ê"=>"e","ë"=>"e","ì"=>"i","í"=>"i","î"=>"i","ï"=>"i","ñ"=>"n","ð"=>"o","ò"=>"o","ó"=>"o","ô"=>"o","õ"=>"o","ö"=>"o","ø"=>"o","š"=>"s","ù"=>"u","ú"=>"u","û"=>"u","ü"=>"u","ý"=>"y","ÿ"=>"y","ž"=>"z","Æ"=>"Ae","æ"=>"ae","Œ"=>"Oe","œ"=>"oe","ß"=>"ss","Ä"=>"Ae","ä"=>"ae","Ö"=>"Oe","ö"=>"oe","Ü"=>"Ue","ü"=>"ue");
	return strtr($Text, $aReplace);
} // str_no_accent

function str_urlify( $sString ) {
	if (function_exists("mb_strtolower"))
		$sString = mb_strtolower(str_no_accent(html_entity_decode($sString, null, 'UTF-8')), 'UTF-8');
	else
		$sString = strtolower(str_no_accent(html_entity_decode($sString, null, 'UTF-8')));

	$sString = preg_replace("#[^0-9a-zA-Z]#is", "-", $sString);

	$sStringTemp = str_replace("--", "-", $sString);
	while ($sString != $sStringTemp) {
		$sStringTemp = $sString;
		$sString = str_replace("--", "-", $sString);
	}

	$sString = trim($sString, "-");

	return $sString;
} // str_urlify

function str_unencode($sStr='') {
	$aEntities = array('&aacute;','&agrave;','&acirc;','&auml;','&eacute;','&egrave;','&ecirc;','&euml;','&iacute;','&igrave;','&icirc;','&iuml;','&oacute;','&ograve;','&ocirc;','&ouml;','&uacute;','&ugrave;','&ucirc;','&uuml;','&yacute;','&ygrave;','&ycirc;','&yuml;',);
	$aNoEntities = array('a','a','a','a','e','e','e','e','i','i','i','i','o','o','o','o','u','u','u','u','y','y','y','y',);
	return str_replace($aEntities, $aNoEntities, $sStr);
} // str_unencode

function str_encode( $sStr = null ) {
	return htmlentities($sStr, ENT_QUOTES, 'utf-8');
} // str_encode

function br2nl($sStr) {
	return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
} // br2nl

function array_purge( $aArray ) {
	if( is_array( $aArray ) )
		foreach( $aArray as $mKey=>$mElement )
			if( is_null( $mElement ) || empty( $mElement ) || $mElement === false )
				unset( $aArray[$mKey] );
	return $aArray;
} // array_purge

function array_clean( $aArray , $sString ) {
	foreach( $aArray as $mKey => $sElement )
		if( strpos($sElement , $sString ) )
			unset($aArray[$mKey]);
	return $aArray;
} // array_clean

function array_has_keys( $aNeedle, $aHaystack ) {
	if( !is_array( $aHaystack ) )
		return false;
	foreach( $aNeedle as $sKey )
		if( !isset($aHaystack[$sKey]) )
			return false;
	return true;
} // array_has_keys

function array_access( $aArray, $sKey ) {
	return $aArray[ $sKey ];
} // array_access

function imagetype( $sFile ) {
	$aFileInfos = getimagesize( $sFile );
	return $aFileInfos['mime'];
} // imagetype

function smarty_tpl_path( $sTemplatePath ) {
	global $smarty;
	return $smarty->tplFile( $sTemplatePath );
} // smarty_tpl_path

function clean_html( $sCode ) {
	// cleaning spaces
	$sCode = str_replace( '&nbsp;', ' ', $sCode );

	// cleaning single <p>
	$sCode = str_replace( '<p />', '', $sCode );

	// cleaning style attributes
	$sCode = preg_replace( '/\sstyle="[^"]+"/', '', $sCode );

	// cleaning whitespaces
	$sCode = str_replace( "\n", '', $sCode );
	$sCode = str_replace( "\t", '', $sCode );

	return $sCode;
} // clean_html

class Void {
	public function __toString() {
		return json_encode($this);
	} // __toString
} // Void
