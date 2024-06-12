/**
 * add-to-compare.js
 *
 * Handles behaviour of the theme
 */
( function( $, window ) {
    'use strict';

    $(document).on('click', '.elementor-widget-mas-add-to-compare .add-to-compare-link:not(.added)', function (e) {

        e.preventDefault();
    
        var button = $(this),
            data = {
                security: yith_woocompare.add_nonce,
                action: yith_woocompare.actionadd,
                id: button.data('product_id'),
                context: 'frontend'
            },
            widget_list = $('.yith-woocompare-widget ul.products-list');
    
        // add ajax loader
        if (typeof woocommerce_params != 'undefined') {
            button.closest('.images-and-summary').block();
            button.closest('.product-inner').block();
            button.closest('.product-list-view-inner').block();
            button.closest('.product-item-inner').block();
            widget_list.block();
        }
    
        $.ajax({
            type: 'post',
            url: yith_woocompare.ajaxurl.toString().replace('%%endpoint%%', yith_woocompare.actionadd),
            data: data,
            dataType: 'json',
            success: function (response) {
    
                if (typeof woocommerce_params != 'undefined') {
                    $('.images-and-summary').unblock();
                    $('.product-inner').unblock();
                    $('.product-list-view-inner').unblock();
                    $('.product-item-inner').unblock();
                    widget_list.unblock()
                }

                console.log(button.data('compare_url'));
    
                button.addClass('added')
                    .attr('href', button.data('compare_url'))
                    .text(yith_woocompare.added_label);
    
                // add the product in the widget
                widget_list.html(response.widget_table);

                if ( yith_woocompare.auto_open == 'yes')
                    $('body').trigger( 'yith_woocompare_open_popup', { response: response.table_url, button: button } );
            }
        });
    });
} )( jQuery, window );