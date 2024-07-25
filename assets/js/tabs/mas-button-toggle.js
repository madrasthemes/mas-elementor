( function ($, window) {
	"use strict";
	$(document).on('ready', function () {
		const toggleButtons = document.querySelectorAll('.mas-toggle-button');

		if ( toggleButtons ) {
			toggleButtons.forEach(button => {
				button.addEventListener('click', function () {
					const targetId = this.getAttribute('data-target');
					const content = document.getElementById(targetId);
					console.log(content);
					if (content) {
					 content.classList.toggle('mas-collapsed');
					}
				});
				});	
		}		

		const hoverButtons = document.querySelectorAll('.mas-hover-button');
		if ( hoverButtons ) {
			hoverButtons.forEach(button => {
				button.addEventListener('mouseover', function () {
					const targetId = this.getAttribute('data-hover');
					const content = document.getElementById(targetId);
					console.log(content);
					if (content) {
						content.classList.remove('v-hidden');
                        content.classList.add('mas-button-hover-content');
					}
				});
	
				button.addEventListener('mouseout', function () {
					const targetId = this.getAttribute('data-hover');
					const content = document.getElementById(targetId);
					console.log(content);
					if (content) {
						content.classList.remove('mas-button-hover-content');
                        content.classList.add('v-hidden');
					}
				});
			});	
		}

		
	});
})(jQuery, window);