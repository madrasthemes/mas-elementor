/**
 * landkit.js
 *
 * Handles behaviour of the theme
 */
 ( function( $, window ) {
    'use strict';
        
    $( '.mas-login-form .login-register-tab-switcher' ).not('.first-bar').on( 'click', function (e) {
        e.preventDefault();
        $( '#customer_login > .woocommerce-notices-wrapper' ).hide();
        $("form.mas-login-form").parent().removeClass("active");
        $("form.register").parent().addClass("active");
    });

    $( '.mas-login-form .elementor-lost-password.login-register-tab-switcher' ).on( 'click', function (e) {
        e.preventDefault();
        $( '#customer_login > .woocommerce-notices-wrapper' ).hide();
        $("form.mas-login-form").parent().removeClass("active");
        $("form.mas-register").parent().removeClass("active");
        $("form.mas-forget-password").parent().addClass("active");
    });
    $( '.mas-forget-password .login-register-tab-switcher' ).on( 'click', function (e) {
        e.preventDefault();
        $( '#customer_login > .woocommerce-notices-wrapper' ).hide();
        $("form.mas-forget-password").parent().removeClass("active");
        $("form.mas-login-form").parent().addClass("active");
    });

    $( '.mas-register .login-register-tab-switcher' ).on( 'click', function (e) {
        e.preventDefault();
        $( '#customer_login > .woocommerce-notices-wrapper' ).hide();
        $("form.register").parent().removeClass("active");
        $("form.mas-login-form").parent().addClass("active");
    });

    var hash_value = window.location.hash;

    switch( hash_value ) {
        case '#customer-login-form': 
        case '#forget-password-form':
            $( 'a.login-register-tab-switcher[href="' + hash_value + '"]' ).trigger( 'click' );
        break;
    }

    //grabs the hash tag from the url
    var hash = window.location.hash;
    //checks whether or not the hash tag is set
    if (hash != "") {
        //removes all active classes from tabs
        $('.mas-tab-content div').each(function() {
           $(this).removeClass('active');
        });
        //this will add the active class on the hashtagged value
        var link = "";
        $('.mas-tab-content div').each(function() {
           link = $(this).attr('id');
            if ('#'+link == hash) {
                $(this).addClass('active');
            }
        });
    }

    // Show password visiblity hover icon on MAS forms
    $( '.mas-form-fields-wrapper .password_input[type="password"]' ).wrap( '<span class="password-input"></span>' );
    // Add 'password-input' class to the password wrapper in Login.
    $( '.mas-form-fields-wrapper' ).filter(':password').parent('span').addClass('password-input');
    $( '.password-input' ).append( '<span class="show-password-input"></span>' );

    $( '.show-password-input' ).on( 'click',
        function() {
            if ( $( this ).hasClass( 'display-password' ) ) {
                $( this ).removeClass( 'display-password' );
            } else {
                $( this ).addClass( 'display-password' );
            }
            if ( $( this ).hasClass( 'display-password' ) ) {
                $( this ).siblings( ['input[type="password"]'] ).prop( 'type', 'text' );
            } else {
                $( this ).siblings( 'input[type="text"]' ).prop( 'type', 'password' );
            }
        }
    );


 } )( jQuery, window );