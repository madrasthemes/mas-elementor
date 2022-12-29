
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


      // INITIALIZATION OF STICKY BLOCKS
      // =======================================================
       new HSStickyBlock('.js-sticky-block', {
        targetSelector: document.getElementById('masthead').classList.contains('navbar-fixed') ? '#masthead' : null
      })


      // SCROLLSPY
      // =======================================================
      new bootstrap.ScrollSpy(document.body, {
        target: '#navbarSettings',
        offset: 10
      })

      new HSScrollspy('#navbarVerticalNavMenu', {
        breakpoint: 'lg'
      })
    })()
  