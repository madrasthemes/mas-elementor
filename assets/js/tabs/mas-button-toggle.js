( function ($, window) {
	"use strict";
	$(document).on('ready', function () {
		const toggleButtons = document.querySelectorAll('.mas-toggle-button');
		console.log(toggleButtons);

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
	});
})(jQuery, window);