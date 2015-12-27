/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/infos.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var open = function() {
        that.modal.open( '/ajax/edit.infos.html', {
            ref: $( 'body' ).data( 'posib-ref' )
        } );
    }; // open

    $( document ).on( 'infos.open.modal.posib', open );

    that.components.modals.infos = {};

} )( jQuery );
