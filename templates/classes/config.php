<?php

namespace MASElementor\Templates\Classes;

use MASElementor\License\Admin;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // No access of directly access.
}
// phpcs:ignoreFile
if ( ! class_exists( 'Premium_Templates_Core_Config' ) ) {

	/**
	 * Premium Templates Core config.
	 *
	 * Templates core class is responsible for handling templates library.
	 */
	class Premium_Templates_Core_Config {

		/**
		 * Instance of the class
		 *
		 * @var object instance of the class.
		 */
		private static $instance = null;

		/**
		 * Holds config data
		 *
		 * @var array Holds config data.
		 */
		private $config;

		/**
		 * License page slug
		 *
		 * @var string License page slug.
		 */
		private $slug = 'premium-addons-pro-license';

		/**
		 * Premium_Templates_Core_Config constructor.
		 *
		 * Sets config data.
		 */
		public function __construct() {

			$base_path = apply_filters('mas_elementor_premium_templates_path', '' );
			if ( ! empty( $base_path ) ) {
				$this->config = array(
					'premium_temps' => esc_html__( 'MAS Elementor Templates', 'mas-addons-for-elementor' ),
					'key'           => $this->get_license_key(),
					'status'        => $this->get_license_status(),
					'license_page'  => $this->get_license_page(),
					'pro_message'   => $this->get_pro_message(),
					'api'           => array(
						'enabled'   => true,
						'base'      => $base_path,
						'path'      => 'wp-json/mastemp/v1',
						'endpoints' => array(
							'templates'  => '/templates/',
							'keywords'   => '/keywords/',
							'categories' => '/categories/',
							'template'   => '/template/',
							'info'       => '/info/',
							'template'   => '/template/',
						),
					),
				);
			}

		}

		/**
		 * Get license key.
		 *
		 * Gets Premium Add-ons PRO license key.
		 *
		 * @return string|boolean license key or false if no license key
		 */
		public function get_license_key() {

			$key = 'none';

			return $key;

		}

		/**
		 * Get license status.
		 *
		 * Gets Premium Add-ons PRO license status.
		 *
		 * @return string|boolean license status or false if no license key
		 */
		public function get_license_status() {

			$status = 'valid';

			return $status;

		}

		/**
		 * Get license page.
		 *
		 * Gets Premium Add-ons PRO license page.
		 *
		 * @return string admin license page or plugin URI
		 */
		public function get_license_page() {

			$url = 'https://premiumaddons.com/pro/?utm_source=premium-templates&utm_medium=wp-dash&utm_campaign=get-pro';

			return $url;

		}

		/**
		 *
		 * Get License Message
		 *
		 * @return string Pro version message
		 */
		public function get_pro_message() {

			return esc_html__( 'Get Pro', 'mas-addons-for-elementor' );

		}

		/**
		 * Get
		 *
		 * Gets a segment of config data.
		 *
		 * @param string $key Index of the config data.
		 * @return string|array|false data or false if not set
		 */
		public function get( $key = '' ) {

			return isset( $this->config[ $key ] ) ? $this->config[ $key ] : false;

		}


		/**
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
