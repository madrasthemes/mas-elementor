(function() {
    // INITIALIZATION OF STICKY BLOCKS
    // =======================================================
    Promise.all(Array.from(document.images)
      .filter(img => !img.complete)
      .map(img => new Promise(resolve => {
        img.onload = img.onerror = resolve
      })))
      .then(() => {
        new HSStickyBlock('.js-sticky-block', {
          targetSelector: document.getElementById('header').classList.contains('navbar-fixed') ? '#header' : null
        })
      })


    // INITIALIZATION OF SCROLLSPY
    // =======================================================
    new bootstrap.ScrollSpy(document.body, {
      target: '#navbarSettingsEg2',
      offset: 10
    })

    new HSScrollspy('#navbarVerticalNavMenuEg2', {
      breakpoint: 'lg'
    })
  })()