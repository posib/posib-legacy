<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/time_brick.php
 */

class TimeBrick extends Brick {

	public function __set( $sName, $mValue ) {
		switch( $sName ) {
			default:
				parent::__set( $sName, $mValue );
				break;
		}
	} // __set

	public function setLocale() {
		$this->_setLocale();
	} // setLocale

	protected function _create() {
		$iCurrentTime = time();
		return array(
			'static' => array(),
			'dynamic' => array(
				'datetime' => array(
					$iCurrentTime => $iCurrentTime
				),
				'format' => array(
					$iCurrentTime => '%d %B %Y'
				)
			)
		);
	} // _create

	protected function _render() {
		DOMParser::emptyNodeContent( $this->_oNode );
		// TODO : use pubdate if member of article !
		$this->_oNode->setAttribute( 'datetime', date( DATE_W3C, $this->datetime ) );
		if( is_null( self::$_sLocale ) ) {
			$this->_setLocale();
		}
		$this->_oNode->appendChild( new DOMText( strftime( $this->format, $this->datetime ) ) );
	} // _render

	protected $_sType = Brick::TYPE_TIME;

	private function _setLocale(  ) {
		$aLocaleParams = array( LC_TIME );
		switch( $this->_sLang ) {
			case 'nl':
				$aLocaleParams[] = 'nl_NL.utf8';
				$aLocaleParams[] = 'nl_NL@euro';
				$aLocaleParams[] = 'nl_NL';
				break;
			case 'en':
				$aLocaleParams[] = 'en_US.utf8';
				$aLocaleParams[] = 'en_US@euro';
				$aLocaleParams[] = 'en_US';
				break;
			case 'de':
				$aLocaleParams[] = 'de_DE.utf8';
				$aLocaleParams[] = 'de_DE@euro';
				$aLocaleParams[] = 'de_DE';
				break;
			case 'es':
				$aLocaleParams[] = 'es_ES.utf8';
				$aLocaleParams[] = 'es_ES@euro';
				$aLocaleParams[] = 'es_ES';
				break;
			case 'it':
				$aLocaleParams[] = 'it_IT.utf8';
				$aLocaleParams[] = 'it_IT@euro';
				$aLocaleParams[] = 'it_IT';
				break;
			case 'fr':
			default:
				$aLocaleParams[] = 'fr_FR.utf8';
				$aLocaleParams[] = 'fr_FR@euro';
				$aLocaleParams[] = 'fr_FR';
				break;
		}
		self::$_sLocale = call_user_func_array( 'setlocale', $aLocaleParams );
	} // _setLocale

	private static $_sLocale;

} // class::TimeBrick
