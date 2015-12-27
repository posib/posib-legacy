/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/editable.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib,
        $editableZones,
        $currentZoneTooltip;

    var zoneVoid = function() {}; // zoneVoid

    var getZoneType = function( sTagName ) {
        switch( sTagName.toLowerCase() ) {
            case 'address':
                return {
                    type: 'map',
                    title: "Google Map"
                };

            case 'form':
                return {
                    type: 'form',
                    title: "Formulaire"
                };

            case 'img':
                return {
                    type: 'image',
                    title: "Image"
                };

            case 'time':
                return {
                    type: 'time',
                    title: "Date"
                };

            case 'a':
                return {
                    type: 'file',
                    title: "Fichier"
                };

            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
            case 'span':
            case 'strong':
            case 'em':
            case 'b':
            case 'u':
            case 'i':
            case 'del':
                return {
                    type: 'short',
                    title: "Texte court"
                };

            case 'p':
                return {
                    type: 'rich',
                    title: "Bloc de texte"
                };

            default:
                return {
                    type: '?',
                    title: "Bloc inconnu"
                };
        }
    }; // getZoneType

    var zoneOver = function() {
        var sTagName = $( this )[ 0 ].tagName,
            sTypeInfo = getZoneType( sTagName ),
            sBrickRef = $( this ).attr( 'data-brick' ),
            bIsList = $( this ).attr( 'data-list-ref' ) ? true : false;
        if( bIsList ) {
            $currentZoneTooltip.find( 'div small sup' ).text( parseInt( $( this ).attr( 'data-list-index' ), 10 ) > 1 ? 'ème' : 'er' );
            $currentZoneTooltip.find( 'div small span' ).text( $( this ).attr( 'data-list-index' ) );
        }
        $currentZoneTooltip.data( {
            tag: sTagName,
            ref: sBrickRef
        } ).css( {
            top: $( this ).offset().top,
            left: $( this ).offset().left,
            width: $( this ).width(),
            height: $( this ).height()
        } ).find( 'div' ).toggleClass( 'list', bIsList ).end().find( 'div strong' ).removeClass().addClass( sTypeInfo.type ).text( sTypeInfo.title ).end().show();
    }; // zoneOver

    var zoneOut = function() {
        $currentZoneTooltip.data( {
            tag: null,
            ref: null
        } ).hide();
    }; // zoneOut

    var zoneClicked = function( e ) {
        e.preventDefault();
        var sTag = $( this ).data( 'tag' ),
            sRef = $( this ).data( 'ref' ),
            sType = getZoneType( sTag );
        if( sTag === null || sRef === null ) {
            return; // TODO : send exception ?
        }
        zoneOut();
        that.events.dispatch( sType.type + '.edit.open.modal', {
            page: $( 'body' ).data( 'posib-ref' ),
            tag: sTag.toLowerCase(),
            ref: sRef
        } );
    }; // zoneClicked

    var init = function() {
        $editableZones = $( '.posib-editable' );
        $currentZoneTooltip = $( '.posib-editable-hover' );
        $editableZones.hover( zoneOver, zoneVoid );
        $currentZoneTooltip.hover( zoneVoid, zoneOut ).on( 'click.posib', zoneClicked );
    }; // init

    that.editable = {
        init: init,
        hide: zoneOut
    };

}( jQuery ) );
