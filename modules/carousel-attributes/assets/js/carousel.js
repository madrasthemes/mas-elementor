/**
 * Content carousel with extensive options to control behaviour and appearance
 * @requires https://github.com/nolimits4web/swiper
*/

const carousel = (() => {

  // forEach function
  let forEach = (array, callback, scope) => {
    for (let i = 0; i < array.length; i++) {
      callback.call(scope, i, array[i]); // passes back stuff we need
    }
  };

  // Carousel initialisation
  let carousels = document.querySelectorAll('.swiper');
  let thumbs = document.querySelectorAll('.mas-js-swiper-thumbs');
  forEach(carousels, (index, value) => {
    let userOptions,
        pagerOptions,
        userThumbs;
    if(value.dataset.swiperOptions != undefined) userOptions = JSON.parse(value.dataset.swiperOptions);
    if(value.dataset.swiperWidget != undefined) userThumbs = (value.dataset.swiperWidget);
  


    // Pager
    if(userOptions != undefined && userOptions.pager != undefined) {
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
    

    forEach(thumbs, (thumbsIndex, thumbsValue) => { 
      let thumbsUserOptions,
      thumbSwiperOptions;
      if(thumbsValue.dataset.swiperOptions != undefined) thumbSwiperOptions = JSON.parse(thumbsValue.dataset.swiperOptions);
      // console.log(thumbsValue.dataset.thumbsOptions);
      if(thumbsValue.dataset.thumbsOptions != undefined) thumbsUserOptions = JSON.parse(thumbsValue.dataset.thumbsOptions);
      

      if ( 'horizontal' !== thumbSwiperOptions.direction && thumbsUserOptions.thumbs_selector == userThumbs ) {
        thumbSwiperOptions['on'] = {
          'afterInit': function (swiper) {
            swiper.el.style.opacity = 1
            swiper.el.querySelectorAll('.js-swiper-pagination-progress-body-helper')
                    .forEach($progress => $progress.style.transitionDuration = `${userOptions.autoplay.delay}ms`)
          }
        };
        thumbSwiperOptions['watchSlidesVisibility'] = true;
        thumbSwiperOptions['watchSlidesProgress'] = true;
        thumbSwiperOptions['history'] = false;
        let sliderThumbs = new Swiper(thumbsValue, thumbSwiperOptions);
        userOptions['thumbs'] = {'swiper': sliderThumbs};
      }
  
      if ( 'horizontal' === thumbSwiperOptions.direction && thumbsUserOptions.thumbs_selector == userThumbs ) {

        let sliderThumbs = new Swiper(thumbsValue, thumbSwiperOptions);
    
          userOptions['thumbs'] = {'swiper': sliderThumbs};

      }

    });
    let options = {...userOptions, ...pagerOptions};
    
    // console.log(value);
    let swiper = new Swiper(value, options);

    swiper.on('init', function(swiper){
      console.log('Init')
    });


    // Tabs (linked content)
    if(userOptions != undefined && userOptions.tabs != undefined) {

      swiper.on('activeIndexChange', (e) => {
        let targetTab = document.querySelector(e.slides[e.activeIndex].dataset.swiperTab),
            previousTab = document.querySelector(e.slides[e.previousIndex].dataset.swiperTab);

        previousTab.classList.remove('active');
        targetTab.classList.add('active');
      });
    }

  });

})();

