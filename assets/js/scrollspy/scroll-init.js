
  (function() {
    // INITIALIZATION OF NAVBAR
    // =======================================================
    // new HSHeader('#masthead').init()

    
    // INITIALIZATION OF MEGA MENU
    // =======================================================
    // const megaMenu = new HSMegaMenu('.js-mega-menu', {
    //   desktop: {
    //     position: 'left'
    //   }
    // })


    // INITIALIZATION OF GO TO
    // =======================================================
    // new HSGoTo('.js-go-to')


    

    // forEach function
    let forEach = (array, callback, scope) => {
      for (let i = 0; i < array.length; i++) {
        callback.call(scope, i, array[i]); // passes back stuff we need
      }
    };
    let carousels = document.querySelectorAll('.js-scrollspy');
    forEach(carousels, (index, value) => {
      
      let userOptions,
          pagerOptions;
      if(value.dataset.hsStickyBlockOptions != undefined) blockOptions = JSON.parse(value.dataset.hsStickyBlockOptions);
  
    // INITIALIZATION OF STICKY BLOCKS
    // =======================================================
      new HSStickyBlock('.js-sticky-block', {
      targetSelector: document.getElementById(blockOptions.targetSelector.substr(1)).classList.contains('navbar-fixed') ? blockOptions.targetSelector : null
    })

      // SCROLLSPY
    // =======================================================
    new bootstrap.ScrollSpy(document.body, {
      target: blockOptions.scrollspyId,
      offset: 10
    })

    new HSScrollspy(blockOptions.parentSelector, {
      breakpoint: 'lg'
    })
  
    });

    
  })();
  