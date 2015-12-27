<?php
/** flatLand! : Syemes
 * core/extends/cms_fs_factory.php : file factorisation
 */

abstract class CMSFS {

	public static function factory( $mData ) {
		if( is_array( $mData ) ) {
			if( strpos( $mData['type'], 'image' ) !== false ) {
				return new CMSImage( $mData );
			} else
				return new CMSFile( $mData );
		} else {
			if( file_exists( $mData ) ) {
				if( strpos( mime_content_type( $mData ), 'image' ) !== false ) {
					return new CMSImage( $mData );
				} else
					return new CMSFile( $mData );
			} else
				return new CMSFile( $mData );
		}
	} // factory

} // CMSFSFactory
