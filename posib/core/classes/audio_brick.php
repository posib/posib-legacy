<?php
/* Posib - CMSimple
 * by flatLand!
 * PHP File - /core/classes/audio_brick.php
 */

class AudioBrick extends Brick {

	public function __get( $sName ) {
		switch( $sName ) {
			case 'path':
				if( substr( $this->src, 0, 4 ) == 'http' )
					return $this->src;
				else
					return '/' .str_replace( ROOT, '', dirname( DATA_PATH ) ) . '/' . $this->src;
				break;

			default:
				return parent::__get( $sName );
				break;
		}
	} // __get

	protected function _create() {
		return array(
			'static' => array(),
			'dynamic' => array(
				'src' => array(
					time() => $this->_oNode->hasAttribute( 'src' ) ? $this->_oNode->getAttribute( 'src' ) : 'http://placeholder.flatland.be/audio.mp3'
				)
			)
		);
	} // _create

	protected function _render() {
		$this->_oNode->setAttribute( 'src', $this->path );
		if( !$this->_oNode->hasAttribute( 'controls' ) )
			$this->_oNode->setAttribute( 'controls', 'controls' );
	} // _render

	protected $_sType = Brick::TYPE_SHORT;

} // class::AudioBrick
