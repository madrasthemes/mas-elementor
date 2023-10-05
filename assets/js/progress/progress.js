( function () {
	"use strict";
        class Progress extends elementorModules.frontend.handlers.Base {
            getDefaultSettings() {
                return {
                    selectors: {
                        progressNumber: '.elementor-progress-bar',
                    },
                };
            }

            getDefaultElements() {
                const selectors = this.getSettings( 'selectors' );
                return {
                    $progressNumber: this.$element.find( selectors.progressNumber ),
                };
            }

            onInit() {
                super.onInit();

                elementorFrontend.waypoint( this.elements.$progressNumber, () => {
                    const $progressbar = this.elements.$progressNumber;

                    $progressbar.css( 'width', $progressbar.data( 'max' ) + '%' );
                } );
            }
        }
    jQuery( window ).on( 'elementor/frontend/init', () => {
        const addHandler = ( $element ) => {
            elementorFrontend.elementsHandler.addHandler( Progress, {
                $element,
            } );
        };
    
        elementorFrontend.hooks.addAction( 'frontend/element_ready/mas-woocommerce-progress-bar.default', addHandler );
    } );
})();
