/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/edit.rich.js
 */

// TODO : renommer les variables

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var washer;

    var clickingButton = function() {
        switch( $( this ).attr( 'class' ).replace( 'posib-editor-button-', '' ) ) {
            case 'bold':
                insertElement( 'strong', 'bold' );
                break;
            case 'italic':
                insertElement( 'italic', 'italic' );
                break;
            case 'underline':
                insertElement( 'underline', 'underline' );
                break;
            case 'link':
                showInsertLinkPane();
                break;
            case 'unlink':
                removeLink();
                break;
            case 'removeformat':
                cleanCurrentFormat();
                break;
        }
        updateTextarea();
    }; // clickingButton

    var insertElement = function( tagName, command ) {
        document.execCommand( command, false, null );
    }; // insertElement

    var showInsertLinkPane = function() {
        // TODO: detect if we are already in a link
        EditorUtility.saveRange();
        that.selectors.modal.find( '.posib-editor-field-group' ).slideUp();
        that.selectors.modal.find( '.posib-modals-tools' ).slideUp();
        that.selectors.modal.find( '.posib-field-insert-link' ).slideDown( function() {
            $( '.posib-field-insert-link input[name="href"]' ).focus();
        } );
        that.selectors.modal.find( '.posib-controls' ).fadeOut();
        that.selectors.modal.on( 'click', '.posib-field-insert-link-discard', discardLinkInsertion );
        that.selectors.modal.on( 'click', '.posib-field-insert-link-confirm', insertLink );
    }; // showInsertLinkPane

    var discardLinkInsertion = function() {
        that.selectors.modal.off( 'click', '.posib-field-insert-link-discard' );
        that.selectors.modal.off( 'click', '.posib-field-insert-link-confirm' );
        that.selectors.modal.find( '.posib-editor-field-group' ).slideDown();
        that.selectors.modal.find( '.posib-modals-tools' ).slideDown();
        that.selectors.modal.find( '.posib-field-insert-link' ).slideUp();
        that.selectors.modal.find( '.posib-controls' ).fadeIn();
    }; // discardLinkInsertion

    var insertLink = function() {
        var href = $( '.posib-field-insert-link input[name="href"]' ).val();
        var title = $( '.posib-field-insert-link input[name="infobulle"]' ).val();
        var target = $( '.posib-field-insert-link select[name="target"]' ).val();
        discardLinkInsertion();
        EditorUtility.restoreRange();
        document.execCommand( 'createLink', false, href );
        if( title !== '' ) {
            $( '.posib-editor-content a:contains("' + (EditorUtility.getTextSelection() + '') + '")[href="' + href + '"]' ).attr( 'title', title );
        }
        if( target !== '' ) {
            $( '.posib-editor-content a:contains("' + (EditorUtility.getTextSelection() + '') + '")[href="' + href + '"]' ).attr( 'target', target );
        }
        updateTextarea();
    }; // insertLink

    var removeLink = function() {
        document.execCommand( 'unlink', false, null );
    }; // removeLink

    var cleanCurrentFormat = function() {
        document.execCommand( 'removeformat', false, null );
    }; // cleanCurrentFormat

    var keyboardEvents = function() {
        // TODO
        updateTextarea();
    }; // keyboardEvents

    var updateTextarea = function() {
        $( '#posib-content' ).val( $( '.posib-editor-content' ).html() );
    }; // updateTextarea

    var cleanBeforeSubmit = function() {
        updateTextarea();
        washer = $( '<div></div>' ).html( $( '#posib-content' ).val() );
        // empty tags
        washer.children( ':empty' ).remove();
        // text with no parents
        if( washer.contents().size() > washer.children().size() ) {
            washer.contents().wrap( '<p />' );
        }
        // block with only one empty children : TODO
        /*
        washer.children().each( function( ) {
            console.log( $(this).html(), $( this ).children().size(), $( this ).children( ':empty' ).size() );
            if( $( this ).children().size() > 0 && $( this ).children().size() == $( this ).children( ':empty' ).size() ) {
                $( this ).remove();
            }
        } );
        */
        washer.find( 'span.Apple-style-span' ).remove();
        $( '#posib-content' ).val( washer.html() );
    }; // cleanBeforeSubmit

    // utilities function
    var EditorUtility = function() {
        var savedRange = false,
            savedSelection = false;

        var getSelection = function() {
            return (window.getSelection) ? window.getSelection() : document.selection;
        }; // getSelection

        var getRange = function() {
            var s = getSelection();
            if( !s ) {
                return null;
            }
            return ( s.rangeCount > 0 ) ? s.getRangeAt( 0 ) : s.createRange && s.createRange() || document.createRange();
        }; // getRange

        var selectRange = function( rng, s ) {
            if( window.getSelection ) {
                s.removeAllRanges();
                s.addRange( rng );
            } else {
                rng.select();
            }
        }; // selectRange

        var getTextSelection = function() {
            if( window.getSelection ) {
                return window.getSelection();
            } else if(document.getSelection) {
                return document.getSelection();
            } else if(document.selection) {
                return document.selection.createRange().text;
            }
        }; // getTextSelection

        var saveRange = function() {
            savedRange = getRange();
            savedSelection = getSelection();
        }; // saveRange

        var restoreRange = function() {
            if( savedRange ) {
                selectRange( savedRange, savedSelection );
                savedRange = false;
                savedSelection = false;
            }
        }; // restoreRange

        return {
            saveRange: saveRange,
            restoreRange: restoreRange,
            getTextSelection: getTextSelection
        };
    }();

    var open = function( e ) {
        that.modal.open( '/ajax/edit.rich.html', {
            page: e.page,
            tag: e.tag,
            ref: e.ref
        } );
    }; // open

    var load = function() {
        that.selectors.modal.find( '*[title]' ).tooltip();
        that.selectors.modal.on( 'click.editor.posib', '.posib-editor-toolbar a[class*="posib-editor-button-"]', clickingButton );
        that.selectors.modal.on( 'blur.editor.posib focus.editor.posib', '.posib-editor-content', updateTextarea );
        that.selectors.modal.on( 'keyup.editor.posib', '.posib-editor-content', keyboardEvents );
        that.selectors.modal.on( 'click.editor.posib', '.posib-editor-link-pane-insert', insertLink );
        that.selectors.modal.on( 'submit.editor.posib', 'form:has(.posib-editor)', cleanBeforeSubmit );
    }; // load

    var close = function() {
        that.selectors.modal.off( '.editor.posib' );
    }; // close

    $( document ).on( 'rich.edit.open.modal.posib', open );
    $( document ).on( 'rich.edit.load.modal.posib', load );
    $( document ).on( 'rich.edit.close.modal.posib', close );

    that.components.modals.edit.rich = {};

} )( jQuery );
