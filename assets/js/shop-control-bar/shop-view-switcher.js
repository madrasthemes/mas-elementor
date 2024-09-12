/**
 * popup.js
 *
 * Handles behaviour of the theme
 */
( function( $, window ) {
    'use strict';

      var wrapper = $('.mas-products-grid'); // Select the wrapper element
    
      if( ! wrapper ) {
        return;
      }

      if ( wrapper.attr('class') ) {
        var currentClass = wrapper.attr('class').split(' ').find(function (className) {
          return className.startsWith('mas-grid-');
          });
      }
      

      $('.shop-view-switcher').on('click', '.nav-link', function () {
        $('[data-bs-toggle="mas-shop-products"]').attr('data-view', $(this).data('archiveClass'));
        var clickedView = $(this).data('archiveClass'); // Get the data-archiveClass of the clicked element
      
        
        // Check if the clickedView matches the particular views where you want to remove the mas-grid-% class
        if (clickedView === 'mas-list-view' || clickedView === 'mas-list-view-small') {
            // Fetch the current mas-grid class and remove any class that starts with 'mas-grid-'
            
            console.log(currentClass);
            if (currentClass) {
                wrapper.removeClass(currentClass);
            }
        } else {
            // Add the class that was removed
            wrapper.addClass(currentClass); // Add the removed class
        }
    });
} )( jQuery, window );
