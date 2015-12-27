/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/posib.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

if( !window.posib ) {
    window.posib = {};
}

( function( $ ) {
    "use strict";

    // ----- selectors

    var $selectors = {
        load: function() {
            this.backdrop = $( '.posib-backdrop' );
            this.modal = $( '.posib-modal' );
            this.toolbar = $( '.posib-toolbar' );
        }
    };
    $selectors.load();

    // ----- events

    var eventDispatch = function( sType, oData ) {
        $( document ).trigger( $.Event( sType + '.posib', oData || {} ) );
    }; // eventDispatch

    // ----- modal

    var ajaxModalExists = function() {
        $selectors.load();
        return $selectors.modal.size() > 0;
    };

    var ajaxModalOpen = function( sURL, oData ) {
        if( ajaxModalExists() ) {
            ajaxModalClose( null, true );
        }
        $selectors.backdrop.addClass( 'posib-loader' ).fadeIn( 'fast' );
        $.ajax( {
            url: sURL,
            type: 'post',
            data: oData || {},
            success: ajaxModalSuccess
        } );
    }; // ajaxModalOpen

    var ajaxModalSuccess = function( sData ) {
        $selectors.backdrop.removeClass( 'posib-loader' );
        $( sData ).hide().appendTo( 'body' ).fadeIn();
        $selectors.load();
        ajaxModalCommonEventsEnable();
        eventDispatch( $selectors.modal.data( 'modal-type' ) + '.load.modal' );
    }; // ajaxModalSuccess

    var ajaxModalClose = function( e, bOverride ) {
        eventDispatch( $selectors.modal.data( 'modal-type' ) + '.close.modal' );
        if( bOverride ) {
            $selectors.modal.remove();
        } else {
            $selectors.modal.fadeOut( 'fast', function() {
                $selectors.modal.remove();
            } );
            $selectors.backdrop.fadeOut();
        }
        ajaxModalCommonEventsDisable();
        $selectors.load();
    }; // ajaxModalClose

    var ajaxModalCommonEventsEnable = function() {
        $selectors.backdrop.on( 'click.modal.posib', ajaxModalClose );
        $selectors.backdrop.on( 'mousewheel.modal.posib', ajaxModalPreventBackdropScrolling );
        $selectors.modal.on( 'click.modal.posib', '.posib-modal-close', ajaxModalClose );
        $selectors.modal.on( 'click.modal.posib', '.posib-back', ajaxModalClose );
        $selectors.modal.on( 'click.modal.posib', 'a.posib-modal-restore', ajaxModalRestoreButtonClicked );
        $selectors.modal.on( 'click.modal.posib', 'a.posib-modal-list', ajaxModalListButtonClicked );
    }; // ajaxModalCommonEventsEnable

    var ajaxModalCommonEventsDisable = function() {
        $selectors.backdrop.off( '.modal.posib' );
        $selectors.modal.off( 'click.modal.posib', '.posib-modal-close' );
        $selectors.modal.off( 'click.modal.posib', '.posib-back' );
    }; // ajaxModalCommonEventsDisable

    var ajaxModalPreventBackdropScrolling = function( e ) {
        e.preventDefault();
        return false;
    }; // ajaxModalPreventBackdropScrolling

    var ajaxModalRestoreButtonClicked = function( e ) {
        e.preventDefault();
        eventDispatch( 'restore.open.modal', {
            brick: $selectors.modal.data( 'modal-type' ).replace( '.edit', '' ),
            ref: $selectors.modal.find( 'input[name="ref"]' ).val(),
            page: $selectors.modal.find( 'input[name="page"]' ).val()
        } );
    }; // ajaxModalRestoreButtonClicked

    var ajaxModalListButtonClicked = function( e ) {
        e.preventDefault();
        eventDispatch( 'list.open.modal', {
            tag: 'ol', // TODO : need to change this ?
            list: $( this ).data( 'list-ref' ),
            ref: $selectors.modal.find( 'input[name="ref"]' ).val(),
            page: $selectors.modal.find( 'input[name="page"]' ).val()
        } );
    }; // ajaxModalListButtonClicked

    // ----- utils

    var aUIDKeys = "abcdef123456789";

    var genUID = function( iLength ) {
        var sUID = '';
        for ( var i = 0; i < ( iLength || 8 ); i++ ) {
            sUID += aUIDKeys.charAt( Math.floor( Math.random() * aUIDKeys.length ) );
        }
        return sUID;
    }; // genUID

    var resizeAndCenter = function( $targetImgBox ) {
        var iSize = $targetImgBox.width(),
            $img = $targetImgBox.find( 'img:not( .posib-image-tool )' );
        $img.removeAttr( 'width' );
        $img.removeAttr( 'height' );
        $img.css( 'marginLeft', 0 );
        $img.css( 'marginTop', 0 );
        if( $img.width() > $img.height() ) {
            $img.height( iSize );
            if( ( ( $img.width() - iSize ) / 2 ) > 0 ) {
                $img.css( 'marginLeft', -( ( $img.width() - iSize ) / 2 ) );
            }
        } else {
            $img.width( iSize );
            if( ( ( $img.height() - iSize ) / 2 ) > 0 ) {
                $img.css( 'marginTop', -( ( $img.height() - iSize ) / 2 ) );
            }
        }
    }; // resizeAndCenter

    // ----- init

    window.posib = {
        events: {
            dispatch: eventDispatch
        },
        modal: {
            open: ajaxModalOpen,
            close: ajaxModalClose,
            exists: ajaxModalExists,
            events: {
                enable: ajaxModalCommonEventsEnable,
                disable: ajaxModalCommonEventsDisable
            }
        },
        components: {
            modals: {
                edit: {}
            }
        },
        selectors: $selectors,
        utils: {
            genUID: genUID,
            resizeAndCenter: resizeAndCenter
        }
    };

}( jQuery ) );
