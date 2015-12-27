<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/tools/utils/strings.php - Strings Utils class
 */

class UtilsStrings extends Singleton {

	public function encode( $sStr = null ) {
		return htmlentities( $sStr, ENT_QUOTES, 'utf-8' );
	} // encode

	public function decode( $sStr = null ) {
		return html_entity_decode( $sStr, ENT_QUOTES, 'utf-8' );
	} // decode

	public function unencode( $sStr = '' ) {
		$aEntities = array('&aacute;','&agrave;','&acirc;','&auml;','&eacute;','&egrave;','&ecirc;','&euml;','&iacute;','&igrave;','&icirc;','&iuml;','&oacute;','&ograve;','&ocirc;','&ouml;','&uacute;','&ugrave;','&ucirc;','&uuml;','&yacute;','&ygrave;','&ycirc;','&yuml;',);
		$aNoEntities = array('a','a','a','a','e','e','e','e','i','i','i','i','o','o','o','o','u','u','u','u','y','y','y','y',);
		return str_replace( $aEntities, $aNoEntities, $sStr );
	} // unencode

	public function no_accent( $sStr = '' ) {
		$aReplace = array( "À"=>"A","Á"=>"A","Â"=>"A","Ã"=>"A","Ä"=>"A","Å"=>"A","Ç"=>"C","Ð"=>"D","È"=>"E","É"=>"E","Ê"=>"E","Ë"=>"E","Ì"=>"I","Í"=>"I","Î"=>"I","Ï"=>"I","Ñ"=>"N","Ò"=>"O","Ó"=>"O","Ô"=>"O","Õ"=>"O","Ö"=>"O","Ø"=>"O","Š"=>"S","Ù"=>"U","Ú"=>"U","Û"=>"U","Ü"=>"U","Ý"=>"Y","Ž"=>"Z","à"=>"a","á"=>"a","â"=>"a","ã"=>"a","ä"=>"a","å"=>"a","ç"=>"c","è"=>"e","é"=>"e","ê"=>"e","ë"=>"e","ì"=>"i","í"=>"i","î"=>"i","ï"=>"i","ñ"=>"n","ð"=>"o","ò"=>"o","ó"=>"o","ô"=>"o","õ"=>"o","ö"=>"o","ø"=>"o","š"=>"s","ù"=>"u","ú"=>"u","û"=>"u","ü"=>"u","ý"=>"y","ÿ"=>"y","ž"=>"z","Æ"=>"Ae","æ"=>"ae","Œ"=>"Oe","œ"=>"oe","ß"=>"ss","Ä"=>"Ae","ä"=>"ae","Ö"=>"Oe","ö"=>"oe","Ü"=>"Ue","ü"=>"ue" );
		return strtr( $sStr, $aReplace );
	} // no_accent

	public function urlify( $sStr ) {
		if( function_exists( "mb_strtolower" ) )
			$sStr = mb_strtolower( $this->no_accent( $this->decode( $sStr ) ), 'UTF-8' );
		else
			$sStr = strtolower( $this->no_accent( $this->decode( $sStr ) ) );
		$sStr = preg_replace( "#[^0-9a-zA-Z]#is", "-", $sStr );
		$sStrTmp = str_replace( "--", "-", $sStr );
		while ( $sStr != $sStrTmp ) {
			$sStrTmp = $sStr;
			$sStr = str_replace( "--", "-", $sStr );
		}
		$sStr = trim( $sStr, "-" );
		return $sStr;
	} // urlify

	public function br2nl( $sStr ) {
		return preg_replace( '/\<br(\s*)?\/?\>/i', "\n", $sStr );
	} // br2nl

} // class::UtilsStrings
