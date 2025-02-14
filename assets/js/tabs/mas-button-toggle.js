( function ($, window) {
	"use strict";
	$(document).on('ready', function () {
		const toggleButtons = document.querySelectorAll('.mas-toggle-button');

		if ( toggleButtons ) {
			toggleButtons.forEach(button => {
				button.addEventListener('click', function () {
					const targetId = this.getAttribute('data-target');
					const content = document.getElementById(targetId);
					if (content) {
					 content.classList.toggle('mas-collapsed');
					}
				});
				});	
		}		

		const hoverButtons = document.querySelectorAll('.mas-hover-button');

        function isTouchDevice() {
            return window.matchMedia("(hover: none) and (pointer: coarse)").matches;
        }

        function handleMouseOver(event) {
            const targetId = event.currentTarget.getAttribute('data-hover');
            const content = document.getElementById(targetId);
            if (content) {
                content.classList.remove('v-hidden');
                content.classList.add('mas-button-hover-content');
            }
        }

        function handleMouseOut(event) {
            const targetId = event.currentTarget.getAttribute('data-hover');
            const content = document.getElementById(targetId);
            if (content) {
                content.classList.remove('mas-button-hover-content');
                content.classList.add('v-hidden');
            }
        }

        function handleClick(event) {
            const targetId = event.currentTarget.getAttribute('data-hover');
            const content = document.getElementById(targetId);
            if (content) {
                content.classList.toggle('v-hidden');
                content.classList.toggle('mas-button-hover-content');
            }
        }

        function closeAllContents() {
            hoverButtons.forEach(button => {
                const targetId = button.getAttribute('data-hover');
                const content = document.getElementById(targetId);
                if (content) {
                    content.classList.add('v-hidden');
                    content.classList.remove('mas-button-hover-content');
                }
            });
        }

        function setupEventListeners() {
            closeAllContents();
            hoverButtons.forEach(button => {
                // Remove all previous event listeners
                button.removeEventListener('click', handleClick);
                button.removeEventListener('mouseover', handleMouseOver);
                button.removeEventListener('mouseout', handleMouseOut);

                if (isTouchDevice() || null !== button.getAttribute('data-click') ) {
                    // Use click for touch devices
                    button.addEventListener('click', handleClick);
                } else {
                    // Use mouseover/mouseout for non-touch devices
                    button.addEventListener('mouseover', handleMouseOver);
                    button.addEventListener('mouseout', handleMouseOut);
                }
            });

            // Close contents when clicking outside
            document.addEventListener('click', function(event) {
                const isInsideClick = Array.from(hoverButtons).some(button => 
                    button.contains(event.target) || 
                    (document.getElementById(button.getAttribute('data-hover')) || {}).contains(event.target)
                );

                if (!isInsideClick) {
                    closeAllContents();
                }
            });
        }

        setupEventListeners();

        // Reapply event listeners on resize or orientation change
        window.addEventListener('resize', setupEventListeners);

		
	});
})(jQuery, window);