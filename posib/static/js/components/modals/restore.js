/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/restore.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery, google */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var initRestoredMap = function(  ) {
        var gRestoredMap, gRestoredMarker;
        gRestoredMap = new google.maps.Map( $( this )[0], {
            mapTypeId: "roadmap",
            zoom: parseInt( $( this ).data( 'map-zoom' ), 10 ),
            streetViewControl: false,
            scrollwheel: false,
            mapTypeControl: false,
            center: new google.maps.LatLng( parseFloat( $( this ).data( 'map-lat' ) ) , parseFloat( $( this ).data( 'map-lng' ) ) )
        } );
        gRestoredMarker = new google.maps.Marker( {
            position: new google.maps.LatLng( parseFloat( $( this ).data( 'map-marker-lat' ) ) , parseFloat( $( this ).data( 'map-marker-lng' ) ) ),
            map: gRestoredMap
        } );
    }; // initRestoredMap

    var open = function( e ) {
        that.modal.open( '/ajax/restore.' + e.brick + '.html', {
            page: e.page,
            ref: e.ref
        } );
    }; // open

    var load = function() {
        that.selectors.modal.find( '.posib-image-box' ).each( function() {
            that.utils.resizeAndCenter( $( this ) );
        } ).find( 'img.posib-image-zoom' ).posibox();
        // TODO : y a une sorte de gros bug débile avec les boutons de la posibox dans ce contexte : les événements n'ont pas l'air d'être activés.
        $( '.posib-restored-map' ).each( initRestoredMap );
    }; // load

    $( document ).on( 'restore.open.modal.posib', open );
    $( document ).on( 'restore.load.modal.posib', load );

    that.components.modals.restore = {};

} )( jQuery );
