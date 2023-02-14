/**
 * popup.js
 *
 * Handles behaviour of the theme
 */
( function( $, window ) {
    'use strict';

    $(document).on('ready', function () {
        'use strict';
        if ($(window).width() < 1200) {
            console.log("lk");
            $('.header-menu .mas-nav-menu .navbar-toggler').on('click', function () {
                $('body').toggleClass("off-canvas-active");
            });
        }
      });
} )( jQuery, window );

