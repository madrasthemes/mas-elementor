( function () {
	"use strict";

	class Swiper extends elementorModules.frontend.handlers.Base {
		getDefaultSettings() {
			return {
                selectors: {
					wrapper: '.swiper',
					sildesWrapper: '.swiper-wrapper',
					slide: '.swiper-slide',
				}
			};
		}
        
		getDefaultElements() {
			const selectors = this.getSettings('selectors');
			
            return {
                $wrapper: this.$element.find( selectors.wrapper ),

            };
		}
        bindEvents() {
            this.elements.$button.on( 'load', this.onLoadSwiper.bind( this ) );
        }

        onLoadSwiper( event ) {
            event.preventDefault();
            // this.elements.$wrapper.fadeOut().promise().done( () => {
            //     this.elements.$content.fadeIn();
            // } );
            const swiper = new Swiper('.swiper', {
                speed: 400,
                spaceBetween: 100,
              });
        }
    }
        jQuery( window ).on( 'elementor/frontend/init', () => {
            const addHandler = ( $element ) => {
                elementorFrontend.elementsHandler.addHandler( Swiper, {
                    $element,
                } );
            };
            elementorFrontend.hooks.addAction( 'frontend/element_ready/swiper.default', addHandler );
         } );
    })();