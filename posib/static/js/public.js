/* Posib - CMSimple
 * by flatLand!
 * PHP File - /static/js/public.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

// @codekit-prepend consolex.js
// @codekit-prepend jquery.dev.js
// @codekit-prepend components/posibox.js

( function( $ ) {
    "use strict";

    var aKeysSequence = [],
        aKonamiCode = "38,38,40,40,37,39,37,39,66,65";

    var konamiCodeWatcher = function( e ) {
        aKeysSequence.push( e.keyCode );
        if( aKeysSequence.toString().indexOf( aKonamiCode ) >= 0 ) {
            window.location.href = "/admin/";
        }
    }; // konamiCodeWatcher

    $( document ).on( 'keydown.posib', konamiCodeWatcher );

    $( function() {
        $( 'img[data-gallery-images]' ).posibox();
    } );

}( jQuery ) );
