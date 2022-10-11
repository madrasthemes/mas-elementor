/**
 * Around | Multipurpose Bootstrap Template
 * Copyright 2020 Createx Studio
 * Theme core scripts
 * 
 * @author Createx Studio
 * @version 1.2.0
 */


 ;(function ($) {
    'use strict';
  
    const theme = {
  
      /**
       * Theme's components/functions list
       * Comment out or delete the unnecessary component.
       * Some component have dependencies (plugins). Do not forget to remove dependency.
      */
  
      init: () => {
        theme.countdown();
      },

      /**
       * Countdown Timer
       * @memberof theme
       * @method countdown
      */
       countdown: () => {
  
        let coundown = document.querySelectorAll('.mas-elementor-countdown-wrapper');
  
        if (coundown == null) return;
  
        for (let i = 0; i < coundown.length; i++) {
  
          let endDate = coundown[i].dataset.date,
              daysVal = coundown[i].querySelector('.mas-js-cd-days'),
              hoursVal = coundown[i].querySelector('.mas-js-cd-hours'),
              minutesVal = coundown[i].querySelector('.mas-js-cd-minutes'),
              secondsVal = coundown[i].querySelector('.mas-js-cd-seconds'),
              days, hours, minutes, seconds;
          
          endDate = new Date(endDate).getTime();
  
          if (isNaN(endDate)) return;
  
          var x = setInterval(function () {
            let startDate = new Date().getTime();
            
            let timeRemaining = parseInt((endDate - startDate) / 1000);
            
            if (timeRemaining >= 0) {
              days = parseInt(timeRemaining / 86400);
              timeRemaining = (timeRemaining % 86400);
              
              hours = parseInt(timeRemaining / 3600);
              timeRemaining = (timeRemaining % 3600);
              
              minutes = parseInt(timeRemaining / 60);
              timeRemaining = (timeRemaining % 60);
              
              seconds = parseInt(timeRemaining);
              
              if (daysVal != null) {
                daysVal.innerHTML = parseInt(days, 10);
              }
              if (hoursVal != null) {
                hoursVal.innerHTML = hours < 10 ? '0' + hours : hours;
              }
              if (minutesVal != null) {
                minutesVal.innerHTML = minutes < 10 ? '0' + minutes : minutes;
              }
              if (secondsVal != null) {
                secondsVal.innerHTML = seconds < 10 ? '0' + seconds : seconds;
              }
              
            } else {
              var html  = coundown[i].querySelector(".new-message").innerHTML;
              var htmla = coundown[i].querySelector(".new-message");
              var message = htmla.dataset.message;
              var link  = htmla.getAttribute('href');
              if ( message.includes('hide') ) {
                coundown[i].querySelector(".mas-js-countdown").innerHTML = '';
              }
              if ( message.includes('message') ) {
                coundown[i].querySelector(".mas-js-countdown").innerHTML = html;
              }
              if ( message.includes('redirect') && link != '' ) {
                window.location.href= link;
              }
            }
          }, 1000);
  
          
        }
      },
  
  
    }
  
    /**
     * Init theme core
    */
    
    theme.init();
  
  })(jQuery);