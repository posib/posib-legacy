/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/edit.short.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var open = function( e ) {
        that.modal.open( '/ajax/edit.short.html', {
            page: e.page,
            tag: e.tag,
            ref: e.ref
        } );
    }; // open

    $( document ).on( 'short.edit.open.modal.posib', open );

    that.components.modals.edit.short = {};

} )( jQuery );
