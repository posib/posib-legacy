<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/branding.php
 */

class Branding extends Singleton {

	const BRAND_POSIB = 'posib';
	const BRAND_MINIBOS = 'minibos';

	public function __get( $sName ) {
		switch( $sName ) {
			case 'version':
				return POSIB_VERSION;
				break;
			case 'users':
			case 'name':
			case 'url':
				return $this->_aBrandingInfos[ $this->_sCurrentBrand ][ $sName ];
				break;
			case 'brands':
				return array_keys( $this->_aBrandingInfos );
				break;
			case 'brand':
				return $this->_sCurrentBrand;
				break;
		}
	} // __get

	public function __set( $sName, $mValue ) {
		switch( $sName ) {
			case 'brand':
				$this->_sCurrentBrand = isset( $this->_aBrandingInfos[ $mValue ] ) ? $mValue : self::BRAND_POSIB ;
				break;
		}
	} // __set

	private $_sCurrentBrand = self::BRAND_POSIB;

	private $_aBrandingInfos = array(
		self::BRAND_POSIB => array(
			'name' => 'posib.',
			'url' => 'http://posib.be',
			'users' => array()
		),
		self::BRAND_MINIBOS => array(
			'name' => 'Minibòs',
			'url' => 'http://minibos.be',
			'users' => array()
		)
	);

} // class::Branding
