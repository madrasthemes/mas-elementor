import TabsModule from './base-tabs';
( function () {
	"use strict";

class Accordion extends TabsModule {
	getDefaultSettings() {
		const defaultSettings = super.getDefaultSettings();

		return {
			...defaultSettings,
			showTabFn: 'slideDown',
			hideTabFn: 'slideUp',
		};
	}
}
jQuery( window ).on( 'elementor/frontend/init', () => {
    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( Progress, {
            $element,
        } );
    };

    elementorFrontend.hooks.addAction( 'frontend/element_ready/mas-product-categories-dropdown.default', addHandler );
} );
})();