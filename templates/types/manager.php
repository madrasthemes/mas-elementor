<?php

namespace MASElementor\Templates\Types;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No access of directly access
}
// phpcs:ignoreFile
if ( ! class_exists( 'Premium_Templates_Types' ) ) {

	/**
	 * Premium Templates Types.
	 *
	 * Templates types responsible for handling templates library tabs
	 *
	 * @since 3.6.0
	 */
	class Premium_Templates_Types {

		/*
		 * Templates Types
		 */
		private $types = null;

		/**
		 * Premium_Templates_Types constructor.
		 *
		 * Get available types for the templates.
		 *
		 * @since 3.6.0
		 * @access public
		 */
		public function __construct() {

			$this->register_types();

		}

		/**
		 * Register default templates types
		 *
		 * @since 3.6.0
		 * @access public
		 *
		 * @return void
		 */
		public function register_types() {

			$base_path = MAS_ELEMENTOR_TEMPLATES_PATH . 'types/';

			require $base_path . 'base.php';

			$temp_types = array(
				__NAMESPACE__ . '\Premium_Structure_Section' => $base_path . 'section.php',
				__NAMESPACE__ . '\Premium_Structure_Page' => $base_path . 'page.php',
			);

			$is_container_active = Plugin::$instance->experiments->is_feature_active( 'container' );

			if ( $is_container_active ) {
				$temp_types = array_merge(array(__NAMESPACE__ . '\Premium_Structure_Container' => $base_path . 'container.php'), $temp_types );
			}

			array_walk(
				$temp_types,
				function( $file, $class ) {
					require $file;
					$this->register_type( $class );
				}
			);

			do_action( 'mas-premium-templates/types/register', $this );

		}

		/**
		 * Register templates type
		 *
		 * @since 3.6.0
		 * @access public
		 *
		 * @return void
		 */
		public function register_type( $class ) {

			$instance = new $class();

			$this->types[ $instance->get_id() ] = $instance;

			if ( true === $instance->is_location() ) {

				register_structure()->locations->register_location( $instance->location_name(), $instance );

			}

		}

		/**
		 * Returns all templates types data
		 *
		 * @since 3.6.0
		 * @access public
		 *
		 * @return array
		 */
		public function get_types() {

			return $this->types;

		}

		/**
		 * Returns all templates types data
		 *
		 * @since 3.6.0
		 * @access public
		 *
		 * @return object
		 */
		public function get_type( $id ) {

			return isset( $this->types[ $id ] ) ? $this->types[ $id ] : false;

		}


		/**
		 * Return types prepared for templates library tabs
		 *
		 * @since 3.6.0
		 * @access public
		 */
		public function get_types_for_popup() {

			$result = array();

			foreach ( $this->types as $id => $structure ) {
				$result[ $id ] = array(
					'title'    => $structure->get_plural_label(),
					'data'     => array(),
					'sources'  => $structure->get_sources(),
					'settings' => $structure->library_settings(),
				);
			}

			return $result;

		}

	}

}
