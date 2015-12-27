/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/toolbar.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var toggleLangSwitcher = function() {
        $( '#posib-lang-switcher' ).toggle();
    }; // toggleLangSwitcher

    var toolButtonClicked = function( e ) {
        e.preventDefault();
        that.events.dispatch( $( e.currentTarget ).attr( 'rel' ) + '.open.modal' );
    }; // toolButtonClicked

    var init = function init() {
        that.selectors.load();
        that.selectors.toolbar.find( '*[title]' ).tooltip();
        eventsEnable();
    };

    var eventsEnable = function() {
        that.selectors.toolbar.on( 'click.toolbar.posib', '#posib-lang', toggleLangSwitcher );
        that.selectors.toolbar.on( 'click.toolbar.posib', '.posib-tools a[rel]', toolButtonClicked );
    }; // eventsEnable

    var eventsDisable = function() {
        that.selectors.toolbar.off( '.toolbar.posib' );
    }; // eventsDisable

    that.toolbar = {
        init: init,
        events: {
            enable: eventsEnable,
            disable: eventsDisable
        },
        show: function() { that.selectors.toolbar.show(); },
        hide: function() { that.selectors.toolbar.hide(); },
        fadeIn: function() { that.selectors.toolbar.fadeIn(); },
        fadeOut: function() { that.selectors.toolbar.fadeOut(); }
    };

} )( jQuery );
