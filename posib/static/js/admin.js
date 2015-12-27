/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/admin.js
 */

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

// @codekit-prepend consolex.js
// @codekit-prepend jquery.dev.js
// @codekit-prepend ui/jquery.ui.core.js
// @codekit-prepend ui/jquery.ui.widget.js
// @codekit-prepend ui/jquery.ui.mouse.js
// @codekit-prepend ui/jquery.ui.sortable.js
// @codekit-prepend bootstrap-tooltip.js

// @codekit-prepend components/posibox.js

// @codekit-prepend components/posib.js
// @codekit-prepend components/toolbar.js
// @codekit-prepend components/editable.js
// @codekit-prepend components/modals/about.js
// @codekit-prepend components/modals/infos.js
// @codekit-prepend components/modals/edit.short.js
// @codekit-prepend components/modals/edit.time.js
// @codekit-prepend components/modals/edit.map.js
// @codekit-prepend components/modals/edit.rich.js
// @codekit-prepend components/modals/edit.form.js
// @codekit-prepend components/modals/edit.image.js
// @codekit-prepend components/modals/edit.list.js
// @codekit-prepend components/modals/edit.file.js
// @codekit-prepend components/modals/restore.js
// @codekit-prepend components/modals/sitemap.js
// @codekit-prepend components/modals/root.js
//
// TODO : good practice refactor : tab spaces and double quotes everywhere

( function( $ ) {
    "use strict";

    var autoloadModal = function() {
        var oAutoloadModalData = window.posib_autoload_data;
        if( typeof oAutoloadModalData != "undefined" ) {
            if( typeof oAutoloadModalData.modal != "undefined" ) {
                window.posib.modal.open( oAutoloadModalData.modal.url, oAutoloadModalData.modal.data );
            }
        }
    }; // autoloadModal

    $( function() {
        window.posib.toolbar.init();
        window.posib.editable.init();

        $( 'a[rel*="external"]' ).attr( 'target', '_new' );

        autoloadModal();
    } );

}( jQuery ) );
