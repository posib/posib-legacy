/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/edit.file.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var triggerFileInput = function() {
        $( '#posib-file-input' ).click();
    }; // triggerFileInput

    var previewFile = function( e ) {
        var oFile = e.currentTarget.files[ 0 ],
            $fileinfo = $( '#posib-file-fileinfos' );
        if( oFile && window.File ) {
            $fileinfo.find( 'strong' ).html( centralEllipsis( oFile.name, 30 ) ).attr( 'title', oFile.name ).end().find( 'small' ).text( HUSize( oFile.size ) );
            if( $fileinfo.hasClass( 'empty' ) ) {
                $fileinfo.slideDown( function() {
                    $fileinfo.removeClass( 'empty' );
                } );
            }
        } else {
            // TODO : fallback for old browsers
        }
    }; // previewFile

    var HUSize = function( iSize ) {
        if( iSize < 1024 ) {
            return iSize + "o";
        } else if( iSize < 1048576 ) {
            return Math.round( ( iSize / 1024 ) * 100 ) / 100 + "ko";
        } else if( iSize < 1073741824 ) {
            return Math.round( ( iSize / 1048576 ) * 100 ) / 100 + "Mo";
        } else {
            return Math.round( ( iSize / 1073741824 ) * 100 ) / 100 + "Go";
        }
    }; // HUSize

    var centralEllipsis = function( sText, iWrapSize ) {
        var iLimit;
        if( sText.length <= iWrapSize ) {
            return sText;
        }
        iLimit = Math.round( iWrapSize / 2 ) - 1;
        return sText.substr( 0, iLimit ) + "<span>&hellip;</span>" + sText.substr( -iLimit );
    }; // centralEllipsis

    var modalFormSubmitted = function() {
        that.selectors.modal.find( '*[title]' ).tooltip();
        that.selectors.modal.find( 'form fieldset .posib-field-group:visible' ).slideUp( 'fast' );
        that.selectors.modal.find( 'form .posib-modals-tools' ).slideUp( 'fast' );
        that.selectors.modal.find( 'form fieldset .posib-upload-waiter' ).slideDown( 'fast' );
        that.selectors.modal.find( 'form footer .posib-controls' ).fadeOut();
        that.selectors.modal.find( 'form header .posib-modal-close' ).fadeOut();
        that.selectors.modal.find( 'form footer a' ).attr( 'href', 'javascript' + ':void(0);' );
        that.selectors.toolbar.fadeOut(); // TODO : use the right selector
        that.selectors.backdrop.off( 'click' ); // TODO : use the right selector
        return true;
    }; // modalFormSubmitted

    var open = function( e ) {
        that.modal.open( '/ajax/edit.file.html', {
            page: e.page,
            tag: e.tag,
            ref: e.ref
        } );
    }; // open

    var load = function() {
        that.selectors.modal.find( '*[title]' ).tooltip();
        that.selectors.modal.on( 'click.file.posib', '#posib-file-button', triggerFileInput );
        that.selectors.modal.on( 'change.file.posib', '#posib-file-input', previewFile );
        that.selectors.modal.find( 'form' ).on( 'submit.file.posib', modalFormSubmitted );
    }; // load

    var close = function() {
        that.selectors.modal.off( '.file.posib' );
    }; // close

    $( document ).on( 'file.edit.open.modal.posib', open );
    $( document ).on( 'file.edit.load.modal.posib', load );
    $( document ).on( 'file.edit.close.modal.posib', close );

    that.components.modals.edit.file = {};

} )( jQuery );
