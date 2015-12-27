<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/form_brick.php
 */

class FormBrick extends Brick {

	const EOL = "\n";
	const CRLF = "\n\n";

	public function __get( $sName ) {
		switch( $sName ) {
			case 'targets':
				return parent::__get( 'target' );
				break;

			case 'target':
				return implode( ', ', parent::__get( 'target' ) );
				break;

			case 'boundary':
				if( is_null( $this->_sBoundary ) )
					$this->_sBoundary = '_----------=_' . md5( uniqid( time() ) );
				return $this->_sBoundary;
				break;

			default:
				return parent::__get( $sName );
				break;
		}
	} // __get

	public function __set( $sName, $mValue ) {
		switch( $sName ) {
			case 'target':
				$aEmails = explode( ',', $mValue );
				$aEmailsToSave = array();
				if( empty( $aEmails ) || !is_array( $aEmails ) )
					return;
				foreach( $aEmails as $sEmail ) {
					$sEmail = filter_var( trim( $sEmail ), FILTER_VALIDATE_EMAIL );
					if( $sEmail !== false )
						$aEmailsToSave[] = $sEmail;
				}
				if( sizeof( $aEmailsToSave ) )
					parent::__set( $sName, $aEmailsToSave );
				break;

			case 'ignored':
				$this->_aData[ 'static' ][ 'ignored' ] = $mValue;
				break;

			default:
				parent::__set( $sName, $mValue );
				break;
		}
	} // __set

	public function send( $aContents ) {
		$bOperation = true;
		foreach( $this->targets as $sTarget ) {
			$bOperation = $bOperation && mail(
				$sTarget,
				"Quelqu'un a répondu à votre formulaire !",
				$this->_generateMailContent( $aContents ),
				$this->_getHeaders()
			);
		}
		return $bOperation;
	} // sendTo

	protected function _create() {
		return array(
			'static' => array(
				'ignored' => $this->_getIgnoredInputs()
			),
			'dynamic' => array(
				'target' => array(
					time() => array( Utils::getInstance()->globals->server( 'server_admin' ) )
				)
			)
		);
	} // _create

	protected function _render() {
		$utils = Utils::getInstance();
		if( $utils->globals->session( 'send_operation' ) && $utils->globals->session( 'target_form' ) == $this->ref ) {
			$this->_oNode->setAttribute( 'method', 'get' );
			$this->_oNode->setAttribute( 'action', '#' );
			DOMParser::replaceNodeContent( $this->_getSendedMessage(), $this->_oNode );
		} else {
			if( $this->ignored !== $this->_getIgnoredInputs() ) {
				$this->ignored = $this->_getIgnoredInputs();
				$this->save();
			}
			if( $utils->globals->has( 'send_operation', 'session' ) && $utils->globals->session( 'target_form' ) == $this->ref ) {
				DOMNode::appendToNode( $this->_getErrorMessage(), $this->_oNode );
			}
			$this->_oNode->setAttribute( 'method', 'post' );
			if( $this->_sLang ) {
				$this->_oNode->setAttribute( 'action', '/' . $this->_sLang . '/form/' . $this->ref . '/send.html' );
			} else {
				$this->_oNode->setAttribute( 'action', '/form/' . $this->ref . '/send.html' );
			}
		}
		unset( $_SESSION[ 'target_form' ] );
		unset( $_SESSION[ 'send_operation' ] );
	} // _render

	protected function _getPropertyWhen( $sProperty, $iTimestamp, $bExact = false ) {
		switch( $sProperty ) {
			case 'target':
				$sTarget = parent::_getPropertyWhen( 'target', $iTimestamp, $bExact );
				return implode( ', ', $sTarget );
				break;

			default:
				return parent::_getPropertyWhen( $sProperty, $iTimestamp, $bExact );
				break;
		}
	} // _getPropertyWhen

	protected $_sType = Brick::TYPE_FORM;

	private function _getIgnoredInputs() {
		$aIgnoredInputs = array();
		return $aIgnoredInputs;
	} // _getIgnoredInputs

	private function _getSendedMessage() {
		switch( $this->_sLang ) {
			case 'nl':
				return '<p class="posib-sended-form">We hebben u aanvraag goed ontvangen.<br />Wij zullen deze zo vlug mogelijk behandelen.</p>';
				break;

			case 'fr':
			default:
				return '<p class="posib-sended-form">Votre message a bien été envoyé.<br />Nous y répondrons dès que possible.</p>';
				break;
		}
	} // _getSendedMessage

	private function _getErrorMessage() {
		switch( $this->_sLang ) {
			case 'fr':
			default:
				return '<p class="posib-error-form">Votre message n\'a pas pu être envoyé.<br />Veuillez réessayer.</p>';
				break;
		}
	} // _getErrorMessage

	private function _generateMailContent( $aContents ) {
		$sHTMLContent = $this->_getHTMLCode( $aContents );
		$sPlaintextContent = $this->_getPlainText( $aContents );

		$sMessageContent  = 'This is a multi-part message in MIME format.';
		$sMessageContent .= self::CRLF . self::CRLF . '--' . $this->boundary . self::EOL;

		$sMessageContent .= 'Content-Disposition: inline' . self::EOL;
		// $sMessageContent .= 'Content-Length: ' . strlen( $sPlaintextContent ) . self::EOL;
		$sMessageContent .= 'Content-type: text/plain; charset=utf-8' . self::EOL;
		$sMessageContent .= 'Content-Transfer-Encoding: 8bit' . self::CRLF . self::CRLF;
		$sMessageContent .= $sPlaintextContent;

		$sMessageContent .= self::CRLF . self::CRLF . '--' . $this->boundary . self::EOL;

		$sMessageContent .= 'Content-Disposition: inline' . self::EOL;
		// $sMessageContent .= 'Content-Length: ' . strlen( $sHTMLContent ) . self::EOL;
		$sMessageContent .= 'Content-type: text/html; charset=utf-8' . self::EOL;
		$sMessageContent .= 'Content-Transfer-Encoding: 8bit' . self::CRLF . self::CRLF;
		$sMessageContent .= $sHTMLContent;

		$sMessageContent .= self::CRLF . self::CRLF . '--' . $this->boundary . '--';

		return $sMessageContent;
	} // _generateMailContent

	private function _getHTMLCode( $aContents ) {
		$sProps = '';
		foreach( $aContents as $sLabel => $sValue ) {
			if( !in_array( $sLabel, $this->ignored ) ) {
				$sProps .= '<tr><th>' . $sLabel . '</th><td>' . $sValue . '</td></tr>';
			}
		}
		return '<html><head><meta charset="utf-8" /><title>On a répondu à votre formulaire !</title></head><body><h1>On a répondu à votre formulaire !</h1><table>' . $sProps . '</table></body></html>';
	} // _getHTMLCode

	private function _getPlainText( $aContents ) {
		$sProps = '';
		foreach( $aContents as $sLabel => $sValue ) {
			if( !in_array( $sLabel, $this->ignored ) ) {
				$sProps .= $sLabel . ': ' . $sValue . self::CRLF;
			}
		}
		return 'On a répondu à votre formulaire !' . self::CRLF . self::CRLF . $sProps . self::CRLF;
	} // _getPlainText

	private function _getHeaders() {
		$utils = Utils::getInstance();
		$aDomain = explode( '.', $utils->globals->server( 'http_host' ) );
		$sDomainName = $aDomain[ sizeof( $aDomain ) - 2 ];
		$sDomain = $sDomainName . '.' . $aDomain[ sizeof( $aDomain ) - 1 ];
		$sSender = $sDomainName . ' <no-reply@' . $sDomain . '>';

		$sHeaders  = '';
		$sHeaders .= 'Reply-To: ' . $sSender . self::EOL;
		$sHeaders .= 'Return-Path: ' . $sSender . self::EOL;
		$sHeaders .= 'From: ' . $sSender . self::EOL;
		$sHeaders .= 'Organization: ' . $sDomainName . self::EOL;
		$sHeaders .= 'Date: ' . date( 'D, j M Y G:i:s O' ) . self::EOL;
		$sHeaders .= 'MIME-Version: 1.0' . self::EOL;
		$sHeaders .= 'X-Sender: <www.' . $sDomain . '>' . self::EOL;
		$sHeaders .= 'X-Priority: 1' . self::EOL;
		$sHeaders .= 'X-MSMail-Priority: High' . self::EOL;
		$sHeaders .= 'X-Mailer: PHP' . phpversion() . self::EOL;
		$sHeaders .= 'X-abuse-contact: webmaster@' . $sDomain . self::EOL;
		$sHeaders .= 'Content-type: multipart/alternative; boundary="' . $this->boundary . '"' . self::CRLF . self::CRLF;

		return $sHeaders;
	} // _getHeaders

	private $_sBoundary;

} // class::FormBrick
