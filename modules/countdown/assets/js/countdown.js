(function() {
      // INITIALIZATION OF COUNTDOWN
      // =======================================================
      var el = document.querySelector('.mas-elementor-countdown-wrapper');
      var dated = el.dataset.date;
      var countDownDate = new Date( dated ).getTime();
      // const oneYearFromNow = new Date()

      document.querySelectorAll('.mas-js-countdown').forEach(item => {
        const days = item.querySelector('.mas-js-cd-days'),
          hours = item.querySelector('.mas-js-cd-hours'),
          minutes = item.querySelector('.mas-js-cd-minutes'),
          seconds = item.querySelector('.mas-js-cd-seconds')

        countdown(countDownDate,
          ts => {
            days.innerHTML = ts.days
            hours.innerHTML = ts.hours
            minutes.innerHTML = ts.minutes
            seconds.innerHTML = ts.seconds
          },
          countdown.DAYS | countdown.HOURS | countdown.MINUTES | countdown.SECONDS
        )
      })
    })()