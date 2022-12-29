<?php

namespace MASElementor\Templates\Classes;

use MASElementor\Templates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No access of directly access.
}
// phpcs:ignoreFile
if ( ! class_exists( 'Premium_Templates_API' ) ) {

	/**
	 * Premium API.
	 *
	 * Premium API class is responsible for getting API data.
	 */
	class Premium_Templates_API {

		/**
		 * API URL which is used to get the response from.
		 *
		 * @var (String) URL
		 */
		private $config = array();

		/**
		 * API enabled
		 *
		 * @var (Boolean)
		 */
		private $enabled = null;

		/**
		 * Premium_API constructor.
		 *
		 * Get all API data.
		 */
		public function __construct() {

			$this->config = Templates\premium_templates()->config->get( 'api' );

		}

		/**
		 * Is Enabled.
		 *
		 * Check if remote API is enabled.
		 *
		 * @return boolean
		 */
		public function is_enabled() {

			if ( null !== $this->enabled ) {
				return $this->enabled;
			}

			if ( empty( $this->config['enabled'] ) || true !== $this->config['enabled'] ) {
				$this->enabled = false;
				return $this->enabled;
			}

			if ( empty( $this->config['base'] ) || empty( $this->config['path'] ) || empty( $this->config['endpoints'] ) ) {
				$this->enabled = false;
				return $this->enabled;
			}

			$this->enabled = true;

			return $this->enabled;
		}

		/**
		 * API URL.
		 *
		 * Get API for template library area data.
		 *
		 * @param string $flag Endpoint index.
		 * @return string
		 */
		public function api_url( $flag ) {

			if ( ! $this->is_enabled() ) {
				return false;
			}

			if ( empty( $this->config['endpoints'][ $flag ] ) ) {
				return false;
			}

			return $this->config['base'] . $this->config['path'] . $this->config['endpoints'][ $flag ];
		}

		/**
		 * Get Info from the remote server.
		 *
		 * Get remote system info.
		 *
		 * @param string $key Response key.
		 * @return string
		 */
		public function get_info( $key = '' ) {

			$api_url = $this->api_url( 'info' );

			if ( ! $api_url ) {
				return false;
			}

			$response = wp_remote_get( $api_url, $this->request_args() );

			$body = wp_remote_retrieve_body( $response );
			$body = json_decode( $body, true );

			if ( ! $body || ! isset( $body['success'] ) || true !== $body['success'] ) {
				return false;
			}

			if ( ! $key ) {
				unset( $body['success'] );
				return $body;
			}

			if ( is_string( $key ) ) {
				return isset( $body[ $key ] ) ? $body[ $key ] : false;
			}

			if ( is_array( $key ) ) {

				$result = array();

				foreach ( $key as $_key ) {
					$result[ $_key ] = isset( $body[ $_key ] ) ? $body[ $_key ] : false;
				}

				return $result;

			}

		}

		/**
		 * Request Args
		 *
		 * Get request arguments for the remote request.
		 *
		 * @return array
		 */
		public function request_args() {
			return array(
				'timeout'   => 60,
				'sslverify' => false,
			);
		}
	}
}
