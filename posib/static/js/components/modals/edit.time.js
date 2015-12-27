/* Posib - CMSimple
 * by flatLand!
 * JS File - /static/js/components/modals/edit.time.js
 */

// TODO : trouver le courage de renommer toutes les variables (et ouais)

/*jshint nonstandard: true, browser: true, boss: true */
/*global jQuery */

( function( $ ) {
    "use strict";

    var that = window.posib;

    var currentDate,
        displayedMonth,
        displayedYear,
        todayDate = new Date();

    var selectDateInCalendar = function( date ) {
        currentDate = date;
        if( displayedMonth !== date.getMonth() || displayedYear !== date.getFullYear() ) {
            generateCalendar( date.getMonth(), date.getFullYear() );
        }
        $( '#posib-calendar tbody tr td.active' ).removeClass( 'active' );
        $( '#posib-calendar tbody td[data-datetime="' + [ date.getFullYear(), date.getMonth(), date.getDate() ].toString() + '"]' ).addClass( 'active' );
        $( '.posib-modal #posib-datetime' ).val( Math.floor( date.getTime() / 1000 ) );
    }; // selectDateInCalendar

    var generateCalendar = function( month, year ) {
        var firstOfMonth = new Date( year, month, 1 ),
            firstDayOfMonth = firstOfMonth.getDay(),
            daysInMonth = getDaysAmountOfMonth( month, year ),
            lastOfMonth = new Date( year, month, daysInMonth ),
            lastDayOfMonth = lastOfMonth.getDay(),
            d, i, w;
        // reset
        $( '#posib-calendar tbody tr td.other-month' ).removeClass( 'other-month' );
        $( '#posib-calendar tbody tr td.active' ).removeClass( 'active' );
        $( '#posib-calendar tbody tr td.today' ).removeClass( 'today' );
        // filling first week
        if( firstDayOfMonth !== 1 ) {
            var lastOfPrevMonth = getDaysAmountOfMonth( month === 0 ? 11 : month - 1, month === 0 ? year - 1 : year );
            for( d = lastOfPrevMonth, i = ( firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1 ) - 1; i >= 0; i--, d-- ) {
                $( '#posib-calendar tbody tr' ).eq( 1 ).find( 'td' ).eq( i ).addClass( 'other-month' ).attr( 'data-datetime', [ ( month === 0 ? year-1 : year ), ( month === 0 ? 11 : month-1 ), d ].toString() ).text( d );
            }
        }
        for( d = 1, i = ( firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1 ); i < 7 ; i++, d++ ) {
            $( '#posib-calendar tbody tr' ).eq( 1 ).find( 'td' ).eq( i ).attr( 'data-datetime', [ year, month, d ].toString() ).text( d );
        }
        // filling other weeks
        for( w = 2; d <= daysInMonth; w++ ) {
            if( w === 6 ) {
                $( '#posib-calendar tbody' ).append( $( '#posib-calendar tbody tr:last-child' ).clone() );
            }
            for( i = 0; i < 7 && d <= daysInMonth; i++, d++ ) {
                $( '#posib-calendar tbody tr' ).eq( w ).show().find( 'td' ).eq( i ).attr( 'data-datetime', [ year, month, d ].toString() ).text( d );
            }
        }
        for( d = 1, i = ( lastDayOfMonth === 0 ? 6 : lastDayOfMonth - 1 ) + 1; i < 7; i++, d++ ) {
            $( '#posib-calendar tbody tr' ).eq( w-1 ).find( 'td' ).eq( i ).addClass( 'other-month' ).attr( 'data-datetime', [ ( month === 11 ? year+1 : year ), ( month === 11 ? 0 : month+1 ), d ].toString() ).text( d );
        }
        if( w === 6 ) {
            $( '#posib-calendar tbody tr' ).eq( 6 ).remove();
        }
        // display month name
        $( '#posib-calendar thead th.posib-date-month-picker' ).text( getMonthName( month ) );
        $( '#posib-calendar thead th.posib-date-year-picker' ).text( year );
        displayedMonth = month;
        displayedYear = year;
        $( '#posib-calendar tbody td[data-datetime="' + [ todayDate.getFullYear(), todayDate.getMonth(), todayDate.getDate() ].toString() + '"]' ).addClass( 'today' );
        $( '#posib-calendar tbody td[data-datetime="' + [ currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() ].toString() + '"]' ).addClass( 'active' );
    }; // generateCalendar

    var getDaysAmountOfMonth = function( month, year ) {
        switch( month ) {
            case 3:
            case 5:
            case 8:
            case 10:
                return 30;
            case 1:
                return ( ( ( year % 4 ) === 0 ) && ( ( year % 100 !== 0 ) || ( year % 400 === 0 ) ) ) ? 29 : 28;
            default:
                return 31;
        }
    }; // getDaysAmountOfMonth

    var getMonthName = function( month ) {
        switch( month ) {
            case  0: return 'Janvier';
            case  1: return 'Février';
            case  2: return 'Mars';
            case  3: return 'Avril';
            case  4: return 'Mai';
            case  5: return 'Juin';
            case  6: return 'Juillet';
            case  7: return 'Août';
            case  8: return 'Septembre';
            case  9: return 'Octobre';
            case 10: return 'Novembre';
            case 11: return 'Décembre';
        }
    }; // getMonthName

    var open = function( e ) {
        that.modal.open( '/ajax/edit.time.html', {
            page: e.page,
            tag: e.tag,
            ref: e.ref
        } );
    }; // open

    var load = function() {
        that.selectors.modal.find( '*[title]' ).tooltip();

        displayedMonth = null;
        displayedYear = null;

        selectDateInCalendar( new Date( parseInt( that.selectors.modal.find( '#posib-datetime' ).val(), 10 ) * 1000 ) );

        that.selectors.modal.on( 'click.time.posib', 'table#posib-calendar thead th.posib-date-month-prev', function() {
            generateCalendar( displayedMonth === 0 ? 11 : displayedMonth-1, displayedMonth === 0 ? displayedYear-1 : displayedYear );
        } );
        that.selectors.modal.on( 'click.time.posib', 'table#posib-calendar thead th.posib-date-month-next', function() {
            generateCalendar( displayedMonth === 11 ? 0 : displayedMonth+1, displayedMonth === 11 ? displayedYear+1 : displayedYear );
        } );

        that.selectors.modal.on( 'click.time.posib', 'table#posib-calendar tbody td', function() {
            var dateData = $( this ).attr( 'data-datetime' ).split( ',' );
            selectDateInCalendar( new Date( dateData[0], dateData[1], dateData[2], $( '#posib-hours' ).val(), $( '#posib-minutes' ).val() ) );
        } );

        that.selectors.modal.on( 'change.time.posib', '#posib-hours, #posib-minutes', function() {
            selectDateInCalendar( new Date( currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), $( '#posib-hours' ).val(), $( '#posib-minutes' ).val() ) );
        } );
    }; // load

    var close = function() {
        that.selectors.modal.off( '.time.posib' );
    }; // close

    $( document ).on( 'time.edit.open.modal.posib', open );
    $( document ).on( 'time.edit.load.modal.posib', load );
    $( document ).on( 'time.edit.close.modal.posib', close );

    that.components.modals.edit.time = {};

} )( jQuery );
