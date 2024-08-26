/**
 * add-to-compare.js
 *
 * Handles behaviour of the theme
 */
( function( $, window ) {
    'use strict';

    $(document.body).on('click', '.mas-view-cart--icon-show .ajax_add_to_cart', function(event) {
        var button = $(this);

        // Wait for the AJAX request to complete
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            // Ensure we're targeting the right button
            if ($button.get(0) === button.get(0)) {
                var addedToCartLink = button.next('.added_to_cart');
                var addedToCartText = addedToCartLink.text();

                // Set this text to the .elementor-button-text span within the button that was clicked
                button.find('.elementor-button-text').text(addedToCartText);

                // Wrap the inner text in a span with class 'elementor-button view-cart-btn' and add it to .elementor-button-content-wrapper
                addedToCartLink.html('<span class="elementor-button view-cart-btn">' + addedToCartText + '</span>');
                
                // Move elementor-button-content-wrapper inside the .added_to_cart link
                addedToCartLink.prepend(button.find('.elementor-button-content-wrapper'));

                // Add the elementor-button class to the .added_to_cart link
                addedToCartLink.addClass('elementor-button');

                if (button.hasClass('stretched-link')) {
                    addedToCartLink.addClass('stretched-link');
                 }

                $('.added_to_cart ~ .cart-count').text(addedToCartText);
            }
        });
    });
} )( jQuery, window );

// $('.added_to_cart').each(function() {
//     // Get the inner text of the .added_to_cart link
//     var addedToCartText = $(this).text();
    
//     // Set this text to the .elementor-button-text span
//     $(this).closest('.elementor-button-wrapper').find('.elementor-button-text').text(addedToCartText);
    
//     // Remove the inner text from the .added_to_cart link
//     $(this).text('');
    
//     // Move elementor-button-content-wrapper inside the .added_to_cart link
//     $(this).prepend($(this).closest('.elementor-button-wrapper').find('.elementor-button-content-wrapper'));

//     // Add the elementor-button class to the .added_to_cart link
//     $(this).addClass('elementor-button');
// });

// $('.added_to_cart').each(function() {
//     // Get the inner text of the .added_to_cart link
//     var addedToCartText = $(this).text();
    
//     // Set this text to the .elementor-button-text span
//     $(this).closest('.elementor-button-wrapper').find('.elementor-button-text').text(addedToCartText);
    
//     // Wrap the inner text in a span with class 'elementor-button view-cart-btn'
//     $(this).html('<span class="elementor-button view-cart-btn">' + addedToCartText + '</span>');
    
//     // Move elementor-button-content-wrapper inside the .added_to_cart link
//     $(this).prepend($(this).closest('.elementor-button-wrapper').find('.elementor-button-content-wrapper'));

//     // Add the elementor-button class to the .added_to_cart link
//     $(this).addClass('elementor-button');
// });