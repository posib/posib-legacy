<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/modals.php
 */

class Modals {

	public function __construct() {
		// default template vars
		$this->_addTemplateVar( 'bHistoryManager', false );
		$this->_addTemplateVar( 'bListManager', false );
		$this->_addTemplateVar( 'bIsUpload', false );
		$this->_addTemplateVar( 'bHasButtons', true );
		$this->_addTemplateVar( 'sFormAction', 'javascript:void(0);' );
		$this->_addTemplateVar( 'sSubmitValue', 'enregistrer' );
	} // __construct

	public function displaySitemapBox( $aPages ) {
		$this->_sContentTemplate = 'sitemap';
		$this->_addTemplateVar( 'pages', $aPages );
		$this->_addTemplateVar( 'sModalType', 'sitemap' );
		$this->_addTemplateVar( 'sFormAction', '/admin/sitemap.save.html' );
		$this->_render();
	} // displaySitemapBox

	public function displayInfosBox( $sRef ) {
		$this->_sContentTemplate = 'infos';
		$this->_addTemplateVar( 'sFormAction', '/admin/infos.save.html' );
		$this->_addTemplateVar( 'sModalType', 'edit.infos' );
		$this->_addTemplateVar( 'ref', $sRef );
		$this->_render();
	} // displayInfosBox

	public function displayRestoreBox( $sType, Brick $oBrick ) {
		$this->_sContentTemplate = 'restore';
		$this->_addTemplateVar( 'sStepTemplate', 'restore.' . $sType );
		$this->_addTemplateVar( 'bHasButtons', false );
		$this->_addTemplateVar( 'sModalType', 'restore' );
		$this->_addTemplateVar( 'brick', $oBrick );
		$this->setTitle( "Historique des versions : " . $oBrick->ref, "clock-history-frame" );
		$this->_render();
	} // displayRestoreBox

	public function displayRootBox( $sType ) {
		$this->_sContentTemplate = 'root.' . $sType;
		$this->_addTemplateVar( 'sFormAction', '/admin/root.save.' . $sType . '.html' );
		$this->_addTemplateVar( 'sModalType', 'root.' . $sType );
		$this->_render();
	} // displayRootBox

	public function displayEditBox( $sType, Brick $oBrick, $bIsUpload = false ) {
		$this->_sContentTemplate = 'edit.' . $sType;
		$this->_addTemplateVar( 'bListManager', $oBrick->isInAList() );
		$this->_addTemplateVar( 'bHistoryManager', true );
		$this->_addTemplateVar( 'sFormAction', '/admin/save.' . $sType . '.html' );
		$this->_addTemplateVar( 'bIsUpload', $bIsUpload );
		$this->_addTemplateVar( 'sModalType', $sType . '.edit' );
		$this->_addTemplateVar( 'brick', $oBrick );
		$this->_render();
	} // display

	public function displayConnectBox( $bError = false ) {
		$this->_sContentTemplate = 'connect';
		$this->_addTemplateVar( 'error', $bError );
		$this->_addTemplateVar( 'sSubmitValue', 'connexion' );
		$this->_addTemplateVar( 'sModalType', 'connect' );
		$this->_addTemplateVar( 'sFormAction', '/admin/' );
		$this->_render();
	} // displayConnectBox

	public function displayListManagerBox( $sListRef, ListBrick $oListBrick ) {
		$this->_sContentTemplate = 'list';
		$this->_addTemplateVar( 'bHistoryManager', true );
		$this->_addTemplateVar( 'sModalType', 'list' );
		$this->_addTemplateVar( 'brick', $oListBrick );
		$this->_render();
	} // displayListManagerBox

	public function displayAboutBox( Branding $oBranding, $sChangelog ) {
		$this->_sContentTemplate = 'about';
		$this->_addTemplateVar( 'sModalType', 'about' );
		$this->_addTemplateVar( 'bHasButtons', false );
		$this->_addTemplateVar( 'branding', $oBranding );
		$this->_addTemplateVar( 'changelog', $sChangelog );
		$this->_render();
	} // displayAboutBox

	public function setTitle( $sTitle, $sIcon = 'jar-label' ) {
		$this->_addTemplateVar( 'sTitleIcon', $sIcon );
		$this->_addTemplateVar( 'sTitle', $sTitle );
	} // setTitle

	public function setAction( $sAction ) {
		$this->_addTemplateVar( 'sFormAction', $sAction );
	} // setAction

	private function _render() {
		$sContentTemplate = $this->_sContentTemplate;
		foreach( $this->_aTemplateVars as $sVarName => $mValue )
			$$sVarName = $mValue;
		include( POSIB . 'includes/modals/modal.inc' );
		die();
	} // _render

	private function _addTemplateVar( $sVarName, $mValue ) {
		if( is_null( $this->_aTemplateVars ) )
			$this->_aTemplateVars = array();
		$this->_aTemplateVars[ $sVarName ] = $mValue;
	} // _addTemplateVar

	private $_sContentTemplate;
	private $_aTemplateVars;

} // class::Modals
