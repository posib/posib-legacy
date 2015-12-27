/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/edit.image.js
 */

/*jshint nonstandard: true, browser: true, boss: true, scripturl: true */
/*global jQuery */

// TODO : y a un putain de cleanup à faire ici

( function( $ ) {
    "use strict";

    var that = window.posib;

    var sInfoModeImageRef,
        sImageBoxCode = '<li class="posib-image-box" data-ref="__KEY__"><img src="http://placehold.it/75x75" height="75" /><img src="/posib/static/icons/balloon-ellipsis.png" class="posib-image-tool posib-image-infos" title="modifier les informations de l\'image" /><img src="/posib/static/icons/image-zoom.png" title="aperçu de l\'image" class="posib-image-tool posib-image-zoom" data-gallery-image="http://placehold.it/500x500" /><img src="/posib/static/icons/arrow-move.png" title="déplacer l\'image" class="posib-image-tool posib-image-move" /><img src="/posib/static/icons/cross.png" title="supprimer l\'image" class="posib-image-tool posib-image-remove" /><input type="file" name="gal[__KEY__]" accept="image/*" /></li>';

    var triggerFileInput = function() {
        $( this ).parents( '.posib-image-box' ).find( 'input[type="file"]' ).click();
    }; // triggerFileInput

    var previewFile = function( e ) {
        var $imgBox,
            oFileReader;
        if( e.currentTarget.files[ 0 ] && window.File && window.FileReader ) {
            $imgBox = $( this ).parents( '.posib-image-box' );
            $imgBox.find( 'input[type="text"]' ).val( '' );
            oFileReader = new FileReader();
            oFileReader.element = $imgBox;
            oFileReader.onload = previewLoaded;
            $imgBox.find( 'img:not( .posib-image-tool )' ).fadeOut( function() {
                oFileReader.readAsDataURL( e.currentTarget.files[ 0 ] );
            } );
        } else {
            // TODO : fallback for old browsers
        }
    }; // previewFile

    var previewLoaded = function( e ) {
        var $element = e.target.element,
            $image = $element.find( 'img:not( .posib-image-tool )' );
        $image.attr( 'src', e.target.result ).load( function() {
            that.utils.resizeAndCenter( $element );
            $element.find( 'img.posib-image-zoom' ).data( 'gallery-image', $image.attr( 'src' ) );
            $image.fadeIn();
        } );
    }; // previewLoaded

    var addNewGalleryImage = function() {
        jQuery( sImageBoxCode.replace( /__KEY__/g, that.utils.genUID() ) ).hide().insertBefore( jQuery( this ) ).fadeIn( 'fast' );
    }; // addNewGalleryImage

    var removeGalleryImage = function() {
        $( this ).parents( '.posib-image-box' ).fadeOut( 'fast', function() {
            $( this ).remove();
        } );
    }; // removeGalleryImage

    var switchToInfoMode = function() {
        var imageSrc = jQuery( this ).parent().find( 'img:not(.posib-image-tool)' ).attr( 'src' );
        sInfoModeImageRef = jQuery( this ).parent().attr( 'data-ref' );
        that.utils.resizeAndCenter( that.selectors.modal.find( 'section.posib-image-informations .posib-image-box img' ).attr( 'src', imageSrc ).parents( '.posib-image-box' ) );
        that.selectors.modal.find( 'form fieldset section.posib-image-informations input[name="legend"]' ).val( that.selectors.modal.find( 'form div#posib-input-container input[name="' + ( sInfoModeImageRef == 'MAIN' ? 'legend' : ( 'gal[' + sInfoModeImageRef + '][legend]' ) ) + '"]' ).val() );
        that.selectors.modal.find( 'form fieldset section.posib-image-informations input[name="description"]' ).val( that.selectors.modal.find( 'form div#posib-input-container input[name="' + ( sInfoModeImageRef == 'MAIN' ? 'description' : ( 'gal[' + sInfoModeImageRef + '][description]' ) ) + '"]' ).val() );
        that.selectors.modal.find( 'form' ).off( 'submit' ).on( 'submit', infoModeSubmitted );
        that.selectors.modal.find( '.posib-back' ).on( 'click', discardInfoMode );
        that.selectors.modal.find( 'form .posib-modals-tools' ).slideUp( 'fast' );
        that.selectors.modal.find( 'form fieldset section.posib-image-editing' ).slideUp( 'fast' );
        that.selectors.modal.find( 'form fieldset section.posib-image-informations' ).slideDown( 'fast' );
    }; // switchToInfoMode

    var discardInfoMode = function() {
        that.selectors.modal.find( '.posib-back' ).off( 'click' );
        that.selectors.modal.find( 'form' ).off( 'submit' ).on( 'submit', modalFormSubmitted );
        that.selectors.modal.find( 'form fieldset section.posib-image-informations' ).slideUp( 'fast' );
        that.selectors.modal.find( 'form .posib-modals-tools' ).slideDown( 'fast' );
        that.selectors.modal.find( 'form fieldset section.posib-image-editing' ).slideDown( 'fast' );
        return false;
    }; // discardInfoMode

    var infoModeSubmitted = function( e ) {
        e.preventDefault();
        if( that.selectors.modal.find( 'form fieldset section.posib-image-informations input[name="legend"]' ).val() ) {
            if( !that.selectors.modal.find( 'form div#posib-input-container input[name="' + ( sInfoModeImageRef == 'MAIN' ? 'legend' : ( 'gal[' + sInfoModeImageRef + '][legend]' ) ) + '"]' ).size() ) {
                jQuery( '<input type="hidden" name="' + ( sInfoModeImageRef == 'MAIN' ? 'legend' : ( 'gal[' + sInfoModeImageRef + '][legend]' ) ) + '" />' ).appendTo( '.posib-modal form div#posib-input-container' );
            }
            that.selectors.modal.find( 'form div#posib-input-container input[name="' + ( sInfoModeImageRef == 'MAIN' ? 'legend' : ( 'gal[' + sInfoModeImageRef + '][legend]' ) ) + '"]' ).val( that.selectors.modal.find( 'form fieldset section.posib-image-informations input[name="legend"]' ).val() );
        }
        if( that.selectors.modal.find( 'form fieldset section.posib-image-informations input[name="description"]' ).val() ) {
            if( !that.selectors.modal.find( 'form div#posib-input-container input[name="' + ( sInfoModeImageRef == 'MAIN' ? 'description' : ( 'gal[' + sInfoModeImageRef + '][description]' ) ) + '"]' ).size() ) {
                jQuery( '<input type="hidden" name="' + ( sInfoModeImageRef == 'MAIN' ? 'description' : ( 'gal[' + sInfoModeImageRef + '][description]' ) ) + '" />' ).appendTo( '.posib-modal form div#posib-input-container' );
            }
            that.selectors.modal.find( 'form div#posib-input-container input[name="' + ( sInfoModeImageRef == 'MAIN' ? 'description' : ( 'gal[' + sInfoModeImageRef + '][description]' ) ) + '"]' ).val( that.selectors.modal.find( 'form fieldset section.posib-image-informations input[name="description"]' ).val() );
        }
        sInfoModeImageRef = null;
        discardInfoMode();
    }; // infoModeSubmitted

    var modalFormSubmitted = function() {
        updateGalleryOrder();
        that.selectors.modal.find( 'input[name="gallery_files_amount"]' ).val( that.selectors.modal.find( '.posib-gallery-images ul li.posib-image-box' ).size() );
        that.selectors.modal.find( 'form fieldset section.posib-image-editing > *:visible' ).slideUp( 'fast' );
        that.selectors.modal.find( 'form .posib-modals-tools' ).slideUp( 'fast' );
        that.selectors.modal.find( 'form fieldset section.posib-image-editing .posib-upload-waiter' ).slideDown( 'fast' );
        that.selectors.modal.find( 'form footer .posib-controls' ).fadeOut();
        that.selectors.modal.find( 'form header .posib-modal-close' ).fadeOut();
        that.selectors.modal.find( 'form footer a' ).attr( 'href', 'javascript' + ':void(0);' );
        that.selectors.toolbar.fadeOut(); // TODO : use the right selector
        that.selectors.backdrop.off( 'click' ); // TODO : use the right selector
        return true;
    }; // modalFormSubmitted

    var updateGalleryOrder = function() {
        var aGalleryOrder = [];
        that.selectors.modal.find( '.posib-gallery-images ul li:not(.posib-add-gallery-image)' ).each( function() {
            if( $( this ).find( 'input[type=text]' ).size() || $( this ).find( 'input[type=file]' ).val() ) {
                aGalleryOrder.push( $( this ).attr( 'data-ref' ) );
            }
        } );
        that.selectors.modal.find( 'input[name="gallery_order"]' ).val( aGalleryOrder.toString() );
    }; // updateGalleryOrder

    var open = function( e ) {
        that.modal.open( '/ajax/edit.image.html', {
            page: e.page,
            tag: e.tag,
            ref: e.ref
        } );
    }; // open

    var load = function() {
        that.selectors.modal.find( '*[title]' ).tooltip();
        that.selectors.modal.on( 'click.image.posib', '.posib-image-box img:not( .posib-image-tool )', triggerFileInput );
        that.selectors.modal.on( 'change.image.posib', '.posib-image-box input[type="file"]', previewFile );
        that.selectors.modal.find( '.posib-image-box' ).each( function() {
            that.utils.resizeAndCenter( $( this ) );
        } );
        that.selectors.modal.find( '.posib-gallery-images ul' ).sortable( {
            handle: 'img.posib-image-tool.posib-image-move',
            containment: '.posib-modal',
            stop: function() {
                that.selectors.modal.find( '.posib-gallery-images ul li.posib-add-gallery-image' ).remove().appendTo( '.posib-modal .posib-gallery-images ul' );
            }
        } );
        that.selectors.modal.find( '.posib-image-box img.posib-image-zoom' ).posibox();
        that.selectors.modal.on( 'click.image.posib', '.posib-add-gallery-image', addNewGalleryImage );
        that.selectors.modal.on( 'click.image.posib', '.posib-image-box .posib-image-tool.posib-image-remove', removeGalleryImage );
        that.selectors.modal.on( 'click', '.posib-image-box .posib-image-tool.posib-image-infos', switchToInfoMode );
        that.selectors.modal.find( '.posib-gallery-images ul' ).disableSelection();
        that.selectors.modal.find( 'form' ).on( 'submit', modalFormSubmitted );
    }; // load

    var close = function() {
        that.selectors.modal.off( '.image.posib' );
    }; // close

    $( document ).on( 'image.edit.open.modal.posib', open );
    $( document ).on( 'image.edit.load.modal.posib', load );
    $( document ).on( 'image.edit.close.modal.posib', close );

    that.components.modals.edit.image = {};

} )( jQuery );
