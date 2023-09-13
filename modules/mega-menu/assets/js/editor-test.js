( function( $, window ) {
    "use strict";
    /*!*****************************************!*\
      !*** ../assets/dev/js/editor/editor.js ***!
      \*****************************************/
    
    
    var ElementorPro = Marionette.Application.extend({
      config: {},
      modules: {},
      initModules() {
        this.modules = {
        };
    
        if (elementorCommon.config.experimentalFeatures['mega-menu']) {
          elementorCommon.elements.$window.on('elementor/nested-element-type-loaded', async () => {
            // The module should be loaded only when `nestedElements` is available.
            this.modules.megaMenu = new (await __webpack_require__.e(/*! import() | mega-menu-editor */ "mega-menu-editor").then(__webpack_require__.bind(__webpack_require__, /*! modules/mega-menu/assets/js/editor/module */ "../modules/mega-menu/assets/js/editor/module.js"))).default();
          });
        }
        if (elementorCommon.config.experimentalFeatures['nested-elements']) {
          elementorCommon.elements.$window.on('elementor/nested-element-type-loaded', async () => {
            // The module should be loaded only when `nestedElements` is available.
            this.modules.nestedCarousel = new (await __webpack_require__.e(/*! import() | nested-carousel-editor */ "nested-carousel-editor").then(__webpack_require__.bind(__webpack_require__, /*! modules/nested-carousel/assets/js/editor/module */ "../modules/nested-carousel/assets/js/editor/module.js"))).default();
          });
        }
      },
      ajax: {
        prepareArgs(args) {
          args[0] = 'pro_' + args[0];
          return args;
        },
        send() {
          return elementorCommon.ajax.send.apply(elementorCommon.ajax, this.prepareArgs(arguments));
        },
        addRequest() {
          return elementorCommon.ajax.addRequest.apply(elementorCommon.ajax, this.prepareArgs(arguments));
        }
      },
      translate(stringKey, templateArgs) {
        return elementorCommon.translate(stringKey, null, templateArgs, this.config.i18n);
      },
      onStart() {
        this.config = elementorProEditorConfig;
        this.initModules();
        jQuery(window).on('elementor:init', () => this.onElementorInit()).on('elementor/connect/success/editor-pro-activate', this.onActivateSuccess);
      },
      onElementorInit() {
        elementor.on('preview:loaded', () => this.onElementorPreviewLoaded());
        elementorPro.libraryRemoveGetProButtons();
        elementorCommon.debug.addURLToWatch('elementor-pro/assets');
      },
      onElementorPreviewLoaded() {
        elementor.$preview[0].contentWindow.elementorPro = this;
      },
      libraryRemoveGetProButtons() {
        elementor.hooks.addFilter('elementor/editor/template-library/template/action-button', function (viewID, templateData) {
          if (templateData.accessLevel === undefined || elementor.config?.library_connect?.current_access_level === undefined) {
            // BC support.
            return templateData.isPro && !elementorPro.config.isActive ? '#tmpl-elementor-pro-template-library-activate-license-button' : '#tmpl-elementor-template-library-insert-button';
          }
    
          // When the template should be at least "pro" and the license is not active.
          if (templateData.accessLevel > 0 && !elementorPro.config.isActive) {
            return '#tmpl-elementor-pro-template-library-activate-license-button';
          }
    
          // When the template access levels is greater than the current license access level it should
          // return the "core" view template which is by default "go pro" or "go expert" button.
          if (templateData.accessLevel > elementor.config.library_connect.current_access_level) {
            return viewID;
          }
    
          // When the current license can insert the template.
          return '#tmpl-elementor-template-library-insert-button';
        });
      },
      onActivateSuccess() {
        // Hide notice.
        elementor.noticeBar.onCloseClick();
    
        // Mark site connect for insert templates connect screen.
        elementor.config.library_connect.is_connected = true;
    
        // Mark pro is active - for `this.libraryRemoveGetProButtons`.
        elementorPro.config.isActive = true;
        elementor.notifications.showToast({
          message: __('Connected Successfully', 'elementor')
        });
      }
    });
    window.elementorPro = new ElementorPro();
    elementorPro.start();
} )( jQuery, window );