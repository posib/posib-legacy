/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/edit.list.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var iElementToDelete,
        bHasBeenReordered = false;

    var open = function( e ) {
        that.modal.open( '/ajax/list.manager.html', {
            list: e.list,
            page: e.page,
            tag: e.tag,
            ref: e.ref
        } );
    }; // open

    var load = function() {
        that.selectors.modal.find( '*[title]' ).tooltip();
        that.selectors.modal.find( '.posib-controls' ).hide();
        that.selectors.modal.find( '.posib-success-block' ).delay( 2500 ).slideUp( 'fast' );
        that.selectors.modal.find( 'ul.posib-list-elements' ).sortable( {
            handle: '.posib-tools a.posib-list-element-tools-move',
            containment: '.posib-modal',
            stop: function() {
                bHasBeenReordered = true;
                $( '.posib-modal ul.posib-list-elements li.posib-list-element-add' ).remove().appendTo( '.posib-modal ul.posib-list-elements' );
                $( '.posib-modal .posib-reorder-error' ).slideUp( 'fast' );
                $( '.posib-modal .posib-reorder-pending' ).slideDown( orderingListHasBeenStopped );
            }
        } );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-list-element-tools-delete', askDeleteListElement );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-mono-confirm-discard', discardListElementDeletion );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-mono-confirm-confirm', confirmListElementDeletion );
        that.selectors.modal.on( 'click.modal.posib', 'li.posib-list-element-add', addElement );
    }; // load

    var close = function() {
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-list-element-tools-delete', askDeleteListElement );
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-mono-confirm-discard', discardListElementDeletion );
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-mono-confirm-confirm', confirmListElementDeletion );
        that.selectors.modal.off( 'click.modal.posib', 'li.posib-list-element-add', addElement );
        if( bHasBeenReordered ) {
            window.location.reload( true );
        }
    }; // close

    var orderingListHasBeenStopped = function() {
        var aNewListOrderData = [],
            sListRef = $( '.posib-modal' ).find( 'input[name="ref"]' ).val(),
            sPageRef = $( '.posib-modal' ).find( 'input[name="page"]' ).val();
        $( '.posib-modal ul.posib-list-elements li:not( .posib-list-element-add )' ).each( function() {
            aNewListOrderData.push( $.trim( $( this ).data( 'list-element-ref' ) ) );
        } );
        $.ajax( {
            url: '/ajax/list/' + sListRef + '/list.order.html',
            type: 'post',
            data: {
                order: aNewListOrderData,
                page: sPageRef
            },
            success: function( sData ) {
                $( '.posib-modal .posib-reorder-pending' ).slideUp( 'fast' );
                if( sData == '1' ) {
                    $( '.posib-modal .posib-reorder-success' ).slideDown().delay( 1500 ).slideUp( 'fast' );
                } else {
                    $( '.posib-modal .posib-reorder-error' ).slideDown();
                }
            }
        } );
    }; // orderingListHasBeenStopped

    var askDeleteListElement = function() {
        iElementToDelete = parseInt( $( this ).parents( 'li' ).find( '.posib-element-name span' ).text(), 10 );
        that.selectors.modal.find( '.posib-list-elements' ).slideUp();
        that.selectors.modal.find( '.posib-mono-confirm' ).find( 'p span' ).text( iElementToDelete ).end().slideDown();
    }; // askDeleteListElement

    var discardListElementDeletion = function() {
        iElementToDelete = null;
        that.selectors.modal.find( '.posib-mono-confirm' ).slideUp();
        that.selectors.modal.find( '.posib-list-elements' ).slideDown();
    }; // discardListElementDeletion

    var confirmListElementDeletion = function() {
        var sListRef = that.selectors.modal.find( 'input[name="ref"]' ).val(),
            sPageRef = that.selectors.modal.find( 'input[name="page"]' ).val();
        location.href = "/admin/list/" + sListRef + "/element/" + ( iElementToDelete - 1 ) + "/delete/" + sPageRef;
        iElementToDelete = null;
    }; // confirmListElementDeletion

    var addElement = function() {
        var sListRef = that.selectors.modal.find( 'input[name="ref"]' ).val(),
            sPageRef = that.selectors.modal.find( 'input[name="page"]' ).val();
        location.href = "/admin/list/" + sListRef + "/element/add/" + sPageRef;
    }; // addElement

    $( document ).on( 'list.open.modal.posib', open );
    $( document ).on( 'list.load.modal.posib', load );
    $( document ).on( 'list.close.modal.posib', close );

    that.components.modals.edit.list = {};

} )( jQuery );
