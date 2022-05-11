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
            this.elements.$wrapper.on( 'load', this.onLoadSwiper.bind( this ) );
        }

        onLoadSwiper( event ) {
            event.preventDefault();
            // this.elements.$wrapper.fadeOut().promise().done( () => {
            //     this.elements.$content.fadeIn();
            // } );
            // const swiper = new Swiper('.swiper', {
            //     speed: 400,
            //     spaceBetween: 100,
            //   });
            const carousel = (() => {

                // forEach function
                let forEach = (array, callback, scope) => {
                  for (let i = 0; i < array.length; i++) {
                    callback.call(scope, i, array[i]); // passes back stuff we need
                  }
                };
              
                // Carousel initialisation
                let carousels = document.querySelectorAll('.swiper');
                forEach(carousels, (index, value) => {
                  
                  let userOptions,
                      pagerOptions;
                  if(value.dataset.swiperOptions != undefined) userOptions = JSON.parse(value.dataset.swiperOptions);
              
              
                  // Pager
                  if(userOptions.pager) {
                    pagerOptions = {
                      pagination: {
                        el: '.pagination .list-unstyled',
                        clickable: true,
                        bulletActiveClass: 'active',
                        bulletClass: 'page-item',
                        renderBullet: function (index, className) {
                          return '<li class="' + className + '"><a href="#" class="page-link btn-icon btn-sm">' + (index + 1) + '</a></li>';
                        }
                      }
                    }
                  }
              
                  // Slider init
                  let options = {...userOptions, ...pagerOptions};
                  let swiper = new Swiper(value, options);
              
                  swiper.on('init', function(swiper){
                    console.log('Init')
                  });
              
              
                  // Tabs (linked content)
                  if(userOptions.tabs) {
              
                    swiper.on('activeIndexChange', (e) => {
                      let targetTab = document.querySelector(e.slides[e.activeIndex].dataset.swiperTab),
                          previousTab = document.querySelector(e.slides[e.previousIndex].dataset.swiperTab);
              
                      previousTab.classList.remove('active');
                      targetTab.classList.add('active');
                    });
                  }
              
                });
              
              })();
        }
    }
        jQuery( window ).on( 'elementor/frontend/init', () => {
            // const addHandler = ( $element ) => {
            //     elementorFrontend.elementsHandler.addHandler( Swiper, {
            //         $element,
            //     } );
            // };
            // elementorFrontend.hooks.addAction( 'frontend/element_ready/swiper.default', addHandler );
            elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function() {
              var swiper = new Swiper(value, options);
              });
         } );
    })();