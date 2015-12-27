/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/root.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var sUserToDelete,
        sUserToEdit;

    var askDeleteUser = function() {
        sUserToDelete = $( this ).parents( 'li' ).find( '.posib-user-name' ).text();
        $( '.posib-modal .posib-root-users' ).slideUp();
        $( '.posib-modal .posib-mono-confirm' ).find( 'p span' ).text( sUserToDelete ).end().slideDown();
    }; // askDeleteUser

    var discardDeletion = function() {
        sUserToDelete = null;
        $( '.posib-modal .posib-mono-confirm' ).slideUp();
        $( '.posib-modal .posib-root-users' ).slideDown();
        return false;
    }; // discardDeletion

    var confirmDeletion = function() {
        location.href = "/admin/root/users/delete/" + sUserToDelete + "/";
        sUserToDelete = null;
    }; // confirmDeletion

    var addUserForm = function() {
        that.selectors.modal.find( '.posib-root-users' ).slideUp();
        that.selectors.modal.find( 'form' ).find( '.help-inline' ).hide().end()[0].reset();
        that.selectors.modal.find( '.posib-controls' ).fadeIn();
        that.selectors.modal.find( '.posib-field-group' ).show();
        that.selectors.modal.find( '.posib-edit-user' ).slideDown();
        that.selectors.modal.find( '.posib-back' ).off( 'click' );
        that.selectors.modal.find( '.posib-back' ).on( 'click', discardAddUserForm );
    }; // addUserForm

    var discardAddUserForm = function() {
        that.selectors.modal.find( '.posib-edit-user' ).slideUp();
        that.selectors.modal.find( '.posib-controls' ).fadeOut();
        that.selectors.modal.find( '.posib-root-users' ).slideDown();
        that.selectors.modal.find( '.posib-back' ).off( 'click' );
        return false;
    }; // discardAddUserForm

    var editUserForm = function() {
        sUserToEdit = $( this ).parents( 'li' ).find( '.posib-user-name' ).text();
        that.selectors.modal.find( '.posib-root-users' ).slideUp();
        that.selectors.modal.find( 'form' ).find( '.help-inline' ).show().end()[0].reset();
        that.selectors.modal.find( 'form' ).find( '.posib-input input:first' ).attr( 'disabled', 'disabled' ).val( sUserToEdit );
        that.selectors.modal.find( '.posib-controls' ).fadeIn();
        that.selectors.modal.find( '.posib-field-group' ).show();
        that.selectors.modal.find( '.posib-edit-user' ).slideDown();
        that.selectors.modal.find( '.posib-back' ).off( 'click' );
        that.selectors.modal.find( '.posib-back' ).on( 'click', discardEditUserForm );
        that.selectors.modal.find( 'form' ).on( 'submit', editUserFormSubmitted );
    }; // editUserForm

    var discardEditUserForm = function() {
        sUserToEdit = null;
        that.selectors.modal.find( '.posib-edit-user' ).slideUp();
        that.selectors.modal.find( 'form' ).find( '.posib-input input:first' ).removeAttr( 'disabled' );
        that.selectors.modal.find( '.posib-controls' ).fadeOut();
        that.selectors.modal.find( '.posib-root-users' ).slideDown();
        that.selectors.modal.find( '.posib-back' ).off( 'click' );
        that.selectors.modal.find( 'form' ).off( 'submit', editUserFormSubmitted );
        return false;
    }; // discardEditUserForm

    var editUserFormSubmitted = function() {
        that.selectors.modal.find( 'form' ).find( '.posib-input input:first' ).removeAttr( 'disabled' );
    }; // editUserFormSubmitted

    var openBrand = function() {
        that.modal.open( '/ajax/root.brand.html', {} );
    }; // openBrand

    var openUsers = function() {
        that.modal.open( '/ajax/root.users.html', {} );
    }; // openUsers

    var loadUsers = function() {
        that.selectors.modal.find( '*[title]' ).tooltip();
        that.selectors.modal.find( '.posib-controls' ).hide();
        that.selectors.modal.find( '.posib-success-block' ).delay( 2500 ).slideUp( 'fast', function() {
            $( this ).remove();
        } );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-user-tools-edit', editUserForm );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-user-tools-delete', askDeleteUser );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-mono-confirm-discard', discardDeletion );
        that.selectors.modal.on( 'click.modal.posib', 'a.posib-mono-confirm-confirm', confirmDeletion );
        that.selectors.modal.on( 'click.modal.posib', 'li.posib-user-add', addUserForm );
    }; // loadUsers

    var closeUsers = function() {
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-user-tools-edit' );
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-user-tools-delete' );
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-mono-confirm-discard' );
        that.selectors.modal.off( 'click.modal.posib', 'a.posib-mono-confirm-confirm' );
        that.selectors.modal.off( 'click.modal.posib', 'li.posib-user-add' );
    }; // closeUsers

    var openConfig = function() {
        that.modal.open( '/ajax/root.config.html', {} );
    }; // openConfig

    var loadConfig = function() {
        that.selectors.modal.find( '.posib-controls' ).show();
    }; // loadConfig

    $( document ).on( 'root.config.open.modal.posib', openConfig );
    $( document ).on( 'root.config.load.modal.posib', loadConfig );

    $( document ).on( 'root.users.open.modal.posib', openUsers );
    $( document ).on( 'root.users.load.modal.posib', loadUsers );
    $( document ).on( 'root.users.close.modal.posib', closeUsers );

    $( document ).on( 'root.brand.open.modal.posib', openBrand );

    that.components.modals.restore = {};

} )( jQuery );
