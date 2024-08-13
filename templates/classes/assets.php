<?php

namespace MASElementor\Templates\Classes;

use MASElementor\Templates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No access of directly access.
}
// phpcs:ignoreFile
if ( ! class_exists( 'Premium_Templates_Assets' ) ) {

	/**
	 * Premium Templates Assets.
	 *
	 * Premium Templates Assets class is responsible for enqueuing all required assets for integration templates on the editor page.
	 */
	class Premium_Templates_Assets {

		/**
		 * Instance of the class
		 *
		 * @var object instance of the class.
		 */
		private static $instance = null;

		/**
		 * Premium_Templates_Assets constructor.
		 *
		 * Triggers the required hooks to enqueue CSS/JS files.
		 */
		public function __construct() {

			add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_styles' ) );

			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ), 0 );

			add_action( 'elementor/editor/footer', array( $this, 'load_footer_scripts' ) );

		}

		/**
		 * Preview Styles
		 *
		 * Enqueue required templates CSS file.
		 */
		public function enqueue_preview_styles() {

			$is_rtl = is_rtl() ? '-rtl' : '';

			wp_enqueue_style(
				'mas-elementor-templates-editor-preview-style',
				MAS_ELEMENTOR_ASSETS_URL . '/templates/css/preview' . $is_rtl . '.css',
				array(),
				MAS_ELEMENTOR_VERSION,
				'all'
			);

		}

		/**
		 * Editor Styles
		 *
		 * Enqueue required editor CSS files.
		 */
		public function editor_styles() {

			$is_rtl = is_rtl() ? '-rtl' : '';

			wp_enqueue_style(
				'mas-elementor-templates-editor-style',
				MAS_ELEMENTOR_ASSETS_URL . '/templates/css/editor' . $is_rtl . '.css',
				array(),
				MAS_ELEMENTOR_VERSION,
				'all'
			);

		}

		/**
		 * Editor Scripts
		 *
		 * Enqueue required editor JS files, localize JS with required data.
		 */
		public function editor_scripts() {

			wp_enqueue_script(
				'mas-elementor-templates-editor',
				MAS_ELEMENTOR_ASSETS_URL . '/templates/js/editor.js',
				array(
					'jquery',
					'underscore',
					'backbone-marionette',
				),
				MAS_ELEMENTOR_VERSION,
				true
			);

			wp_localize_script(
				'mas-elementor-templates-editor',
				'MasPremiumTempsData',
				apply_filters(
					'mas_elementor_templates_editor_localize',
					array(
						'Elementor_Version'   => ELEMENTOR_VERSION,
						'PremiumTemplatesBtn' => Templates\premium_templates()->config->get( 'premium_temps' ),
						'modalRegions'        => $this->get_modal_region(),
						'license'             => array(
							'status'       => Templates\premium_templates()->config->get( 'status' ),
							'activateLink' => Templates\premium_templates()->config->get( 'license_page' ),
							'proMessage'   => Templates\premium_templates()->config->get( 'pro_message' ),
						),
						'imageUrl'		   => MAS_ELEMENTOR_ASSETS_URL . 'templates/images/gradient.png',
					)
				)
			);

		}

		/**
		 * Get Modal Region
		 *
		 * Get modal region in the editor.
		 */
		public function get_modal_region() {

			return array(
				'modalHeader'  => '.dialog-header',
				'modalContent' => '.dialog-message',
			);

		}

		/**
		 * Add Templates Scripts
		 *
		 * Load required templates for the templates library.
		 */
		public function load_footer_scripts() {
			$scripts = glob( MAS_ELEMENTOR_TEMPLATES_PATH . 'scripts/*.php' );
			array_map(
				function( $file ) {

					$name = basename( $file, '.php' );
					ob_start();
					include $file;
					printf( '<script type="text/html" id="tmpl-mas-premium-%1$s">%2$s</script>', $name, ob_get_clean() ); //phpcs:ignore

				},
				$scripts
			);

		}

		/**
		 * Get Instance
		 *
		 * Creates and returns an instance of the class.
		 *
		 * @return object
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;

		}


	}

}
