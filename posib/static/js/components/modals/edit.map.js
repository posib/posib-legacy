/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/edit.map.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery, google */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var gMap,
        gMarker,
        gGeocoder;

    var open = function( e ) {
        that.modal.open( '/ajax/edit.map.html', {
            page: e.page,
            tag: e.tag,
            ref: e.ref
        } );
    }; // open

    var load = function() {
        gGeocoder = new google.maps.Geocoder();
        gMap = new google.maps.Map( document.getElementById( 'posib-modal-map' ), {
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoom: parseInt( $( '#posib-map-zoom' ).val(), 10 ),
            streetViewControl: false,
            scrollwheel: false,
            mapTypeControl: false,
            center: new google.maps.LatLng( parseFloat( $( '#posib-map-lat' ).val() ), parseFloat( $( '#posib-map-lng' ).val() ) )
        } );
        google.maps.event.addListener( gMap, 'zoom_changed', updateFields );
        google.maps.event.addListener( gMap, 'dragend', updateFields );
        gMarker = new google.maps.Marker( {
            map: gMap,
            draggable: true,
            position: new google.maps.LatLng( parseFloat( $( '#posib-marker-lat' ).val() ), parseFloat( $( '#posib-marker-lng' ).val() ) )
        } );
        google.maps.event.addListener( gMarker, 'dragend', updateFields );
        that.selectors.modal.on( 'click.gmap.posib', '#posib-address-finder-input', addressFinderClicked );
    }; // load

    var close = function() {
        that.selectors.modal.off( '.gmap.posib' );
    }; // close

    var updateFields = function() {
        $( '#posib-map-zoom' ).val( gMap.getZoom() );
        $( '#posib-marker-lat' ).val( gMarker.getPosition().lat() );
        $( '#posib-marker-lng' ).val( gMarker.getPosition().lng() );
        $( '#posib-map-lat' ).val( gMap.getCenter().lat() );
        $( '#posib-map-lng' ).val( gMap.getCenter().lng() );
    }; // updateFields

    var addressFinderClicked = function() {
        gGeocoder.geocode( { address: $( '#posib-address-finder textarea' ).val() }, geocoderRequested );
    }; // addressFinderClicked

    var geocoderRequested = function( aResults, sStatus ) {
        if ( sStatus === google.maps.GeocoderStatus.OK ) {
            gMarker.setPosition( aResults[ 0 ].geometry.location );
            gMap.setCenter( aResults[ 0 ].geometry.location );
            updateFields();
        } else {
            window.alert( "L'adresse n'a pas pu être trouvée sur la carte.\n\n" + sStatus );
        }
    }; // geocoderRequested

    $( document ).on( 'map.edit.open.modal.posib', open );
    $( document ).on( 'map.edit.load.modal.posib', load );
    $( document ).on( 'map.edit.close.modal.posib', close );

    that.components.modals.edit.map = {};

} )( jQuery );
