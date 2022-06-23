(function() {
  
    // INITIALIZATION OF SWIPER
  // =======================================================
  var swiper = new Swiper('.js-swiper-card-grid',{
    navigation: {
      nextEl: '.js-swiper-card-grid-button-next',
      prevEl: '.js-swiper-card-grid-button-prev',
    },
    slidesPerView: 1,
    spaceBetween: 30,
    loop: 1,
    breakpoints: {
      480: {
        slidesPerView: 2
      },
      768: {
        slidesPerView: 2
      },
      1024: {
        slidesPerView: 3
      },
    },
    on: {
      'imagesReady': function (swiper) {
        const preloader = swiper.el.querySelector('.js-swiper-preloader')
        preloader.parentNode.removeChild(preloader)
      }
    }
  });
})();