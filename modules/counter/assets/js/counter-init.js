/**
 * counter.js
 *
 * Handles behaviour of the theme
 */
( function( $, window ) {
    'use strict';

    $(document).on('ready', function () {
        // initialization of chart pies
        if ( $.HSCore.components.hasOwnProperty('HSChartPie') ) {
            $.HSCore.components.HSChartPie.init('.mas-js-pie');
        }
    });
} )( jQuery, window );