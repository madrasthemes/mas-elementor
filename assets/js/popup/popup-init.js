/**
 * popup.js
 *
 * Handles behaviour of the theme
 */
( function( $, window ) {
    'use strict';

    $(document).on('ready', function () {
        // Magnific popup
        if ($(".mas-popup").length) {
          $(".mas-popup").magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: true,
          });
        }
      });
} )( jQuery, window );

