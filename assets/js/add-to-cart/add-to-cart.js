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

    let lastClickedButton = null; // To track the last clicked button

    // Listen for the button click
    $(document.body).on('click', '.mas-count-in-btn-yes .ajax_add_to_cart', function(event) {
        lastClickedButton = $(this); // Save the reference to the clicked button
    });

    // Listen for the added_to_cart event only once
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
        if (lastClickedButton && $button.get(0) === lastClickedButton.get(0)) {
            // Locate the `.cart-count-btn` within the same `.elementor-button-wrapper`
            const cartCountElement = $button.closest('.elementor-button-wrapper').find('.cart-count-btn');

            // Ensure the `.cart-count-btn` exists
            if (cartCountElement.length) {
                // Extract the current cart count from `.cart-count-btn`
                const currentCartCountText = cartCountElement.text().match(/\d+/); // Match numbers in the text
                const currentCartCount = currentCartCountText ? parseInt(currentCartCountText[0]) : 0; // Default to 0 if not found

                // Get the quantity from the button's data-quantity
                const quantity = parseInt(lastClickedButton.data('quantity')) || 1;

                // Calculate the new cart count
                const newCartCount = currentCartCount + quantity;

                let prefix = "";
                let suffix = "";
                if ( cartCountElement.data('prefix') ) {
                    prefix = cartCountElement.data('prefix') + " ";
                }

                if ( cartCountElement.data('suffix') ) {
                    suffix = " " + cartCountElement.data('suffix');
                }
                

                // Update the `.cart-count-btn` text
                cartCountElement.text(prefix + newCartCount + suffix);

                // Update the button inner text to match `.cart-count-btn` text
                $button.find('.add-cart-btn').text(prefix + newCartCount + suffix);

                // Clear the last clicked button to avoid duplication
                lastClickedButton = null;
            }
        }
    });

    $(document).ready(function() {
        // Iterate through each button that needs to be updated
        $('.elementor-button-wrapper').each(function() {
            const cartCountElement = $(this).find('.cart-count-btn');

            let prefix = "";
                let suffix = "";
                if ( cartCountElement.data('prefix') ) {
                    prefix = cartCountElement.data('prefix') + " ";
                }

                if ( cartCountElement.data('suffix') ) {
                    suffix = " " + cartCountElement.data('suffix');
                }
    
            // Check if the cart count element exists and has a non-zero value
            if (cartCountElement.length) {
                const cartCountText = cartCountElement.text().trim();
                if (cartCountText && parseInt(cartCountText) !== 0) {
                    // If it's not zero, set the button text to match `.cart-count-btn`
                    $(this).find('.add-cart-btn').text(prefix + cartCountText + suffix);
                }
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