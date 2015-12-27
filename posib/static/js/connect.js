/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/connect.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

// @codekit-prepend consolex.js
// @codekit-prepend jquery.dev.js

( function( $ ) {
    "use strict";

    var $backdrop;

    var connectBoxLoaded = function( data ) {
        $backdrop.removeClass( 'posib-loader' );
        $( '#posib-ie-warning' ).fadeOut( 'fast', function() {
            $( data ).hide().appendTo( 'body' ).fadeIn();
        } );
    }; // connectBoxLoaded

    var discardConnectBox = function() {
        window.location.href = "/";
    }; // discardConnectBox

    var loadConnectBox = function() {
        $backdrop.addClass( 'posib-loader' );
        $.ajax( {
            url: '/ajax/connect.html',
            data: {
                error: $( 'body' ).hasClass( 'posib-connect-error' )
            },
            type: 'post',
            success: connectBoxLoaded
        } );
    }; // loadConnectBox

    $( function() {
        $backdrop = $( '.posib-backdrop' );
        $backdrop.on( 'click.posib', discardConnectBox );
        $( document ).on( 'click.posib', '.posib-modal-close, .posib-back', discardConnectBox );
        if( $( '.posib-connect' ).size() ) {
            loadConnectBox();
        }
    } );

}( jQuery ) );
