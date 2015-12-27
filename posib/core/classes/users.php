<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/users.php
 */

class Users extends Singleton {

	public function isRoot( $sLogin, $sPassword ) {
		if( isset( $this->_aRootUsers[ trim( $sLogin ) ] ) ) {
			return sha1( trim( $sPassword ) ) === $this->_aRootUsers[ trim( $sLogin ) ];
		} else
			return false;
	} // isRoot

	public function isBrandingAdmin( $sLogin, $sPassword ) {
		Branding::getInstance()->brand = UtilsData::getInstance()->get( UtilsData::SP . 'config' . UtilsData::SP . 'brand', Branding::BRAND_POSIB );
		$aAdminUsers = Branding::getInstance()->users;
		if( isset( $aAdminUsers[ trim( $sLogin ) ] ) ) {
			return sha1( trim( $sPassword ) ) === $aAdminUsers[ trim( $sLogin ) ];
		} else
			return false;
	} // isBrandingAdmin

	public function isRegularUser( $sLogin, $sPassword ) {
		$aRegularUsers = UtilsData::getInstance()->get( UtilsData::SP . 'users' );
		if( isset( $aRegularUsers[ trim( $sLogin ) ] ) ) {
			return sha1( trim( $sPassword ) ) === $aRegularUsers[ trim( $sLogin ) ];
		} else
			return false;
	} // isRegularUser

	private $_aRootUsers = array();

} // class::Users
