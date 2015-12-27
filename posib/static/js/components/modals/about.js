/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/about.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var open = function() {
        that.modal.open( '/ajax/about.html' );
    }; // open

    $( document ).on( 'about.open.modal.posib', open );

    that.components.modals.about = {};

} )( jQuery );
