/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/sitemap.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var sPageToDelete;

    var orderingListHasBeenStopped = function() {
        var aNewPageOrderData = [];
        that.selectors.modal.find( 'ul.posib-sitemap li:not( .posib-page-add )' ).each( function() {
            aNewPageOrderData.push( $.trim( $( this ).find( 'span.posib-page-url' ).text() ) );
        } );
        $.ajax( {
            url: '/ajax/sitemap.order.html',
            type: 'post',
            data: {
                order: aNewPageOrderData
            },
            success: function( sData ) {
                that.selectors.modal.find( '.posib-reorder-pending' ).slideUp( 'fast' );
                if( sData == '1' ) {
                    that.selectors.modal.find( '.posib-reorder-success' ).slideDown().delay( 1500 ).slideUp( 'fast' );
                } else {
                    that.selectors.modal.find( '.posib-reorder-error' ).slideDown();
                }
            }
        } );
    }; // orderingListHasBeenStopped

    var askDeletePage = function() {
        sPageToDelete = jQuery( this ).parents( 'li' ).find( '.posib-page-url' ).text();
        that.selectors.modal.find( '.posib-sitemap' ).slideUp();
        that.selectors.modal.find( '.posib-mono-confirm' ).find( 'p span' ).text( sPageToDelete ).end().slideDown();
    }; // askDeletePage

    var discardDeletion = function() {
        sPageToDelete = null;
        that.selectors.modal.find( '.posib-mono-confirm' ).slideUp();
        that.selectors.modal.find( '.posib-sitemap' ).slideDown();
    }; // discardDeletion

    var confirmDeletion = function() {
        location.href = "/admin/sitemap/" + sPageToDelete + "/delete.html";
        sPageToDelete = null;
    }; // confirmDeletion

    var addPageForm = function() {
        that.selectors.modal.find( '.posib-sitemap' ).slideUp();
        that.selectors.modal.find( '.posib-controls' ).fadeIn();
        that.selectors.modal.find( '.posib-field-group' ).show();
        that.selectors.modal.find( '.posib-edit-page-template' ).show().find( 'select' ).removeAttr( 'disabled' );
        that.selectors.modal.find( '.posib-edit-page-url' ).show();
        that.selectors.modal.find( 'form' )[0].reset();
        that.selectors.modal.find( '.posib-edit-page' ).slideDown();
        that.selectors.modal.find( '.posib-back' ).off( 'click' );
        that.selectors.modal.find( '.posib-back' ).on( 'click', discardAddPageForm );
    }; // addPageForm

    var discardAddPageForm = function() {
        that.selectors.modal.find( '.posib-edit-page' ).slideUp();
        that.selectors.modal.find( '.posib-controls' ).fadeOut();
        that.selectors.modal.find( '.posib-sitemap' ).slideDown();
        that.selectors.modal.find( '.posib-back' ).off( 'click' );
    }; // discardAddPageForm

    var editPageForm = function() {
        var sPageName = jQuery.trim( jQuery( this ).parents( 'li' ).find( '.posib-page-name' ).text() ),
            sPageURL = jQuery.trim( jQuery( this ).parents( 'li' ).find( '.posib-page-url' ).text() );
        that.selectors.modal.find( '.posib-sitemap' ).slideUp();
        that.selectors.modal.find( '.posib-controls' ).fadeIn();
        that.selectors.modal.find( '.posib-field-group' ).show();
        that.selectors.modal.find( '.posib-edit-page-template' ).hide().find( 'select' ).attr( 'disabled', 'disabled' );
        that.selectors.modal.find( '.posib-edit-page-url' ).hide().find( 'input' ).val( sPageURL );
        that.selectors.modal.find( '.posib-edit-page-name' ).find( 'input' ).val( sPageName );
        that.selectors.modal.find( '.posib-edit-page' ).slideDown();
        that.selectors.modal.find( '.posib-back' ).off( 'click' );
        that.selectors.modal.find( '.posib-back' ).on( 'click', discardEditPageForm );
    }; // editPageForm

    var discardEditPageForm = function() {
        that.selectors.modal.find( '.posib-edit-page' ).slideUp();
        that.selectors.modal.find( '.posib-controls' ).fadeOut();
        that.selectors.modal.find( '.posib-sitemap' ).slideDown();
        that.selectors.modal.find( '.posib-back' ).off( 'click' );
        return false;
    }; // discardEditPageForm

    var open = function() {
        that.modal.open( '/ajax/sitemap.html', {} );
    }; // open

    var load = function() {
        that.selectors.modal.find( '*[title]' ).tooltip();
        that.selectors.modal.find( '.posib-controls' ).hide();
        that.selectors.modal.find( '.posib-success-block' ).delay( 2500 ).slideUp( 'fast' );
        that.selectors.modal.find( 'ul.posib-sitemap' ).sortable( {
            handle: '.posib-tools a.posib-document-tools-move',
            containment: '.posib-modal',
            stop: function() {
                that.selectors.modal.find( 'ul.posib-sitemap li.posib-page-add' ).remove().appendTo( '.posib-modal ul.posib-sitemap' );
                that.selectors.modal.find( '.posib-reorder-error' ).slideUp( 'fast' );
                that.selectors.modal.find( '.posib-reorder-pending' ).slideDown( orderingListHasBeenStopped );
            }
        } );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-document-tools-edit', editPageForm );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-document-tools-delete', askDeletePage );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-mono-confirm-discard', discardDeletion );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-mono-confirm-confirm', confirmDeletion );
        that.selectors.modal.on( 'click.modal.posib', 'li.posib-page-add', addPageForm );
    }; // load

    var close = function() {
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-document-tools-edit' );
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-document-tools-delete' );
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-mono-confirm-discard' );
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-mono-confirm-confirm' );
        that.selectors.modal.off( 'click.modal.posib', 'li.posib-page-add' );
    }; // close

    $( document ).on( 'sitemap.open.modal.posib', open );
    $( document ).on( 'sitemap.load.modal.posib', load );
    $( document ).on( 'sitemap.close.modal.posib', close );

    that.components.modals.restore = {};

} )( jQuery );
