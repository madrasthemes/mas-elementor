<?php
/**
 * The Modules Manager.
 *
 * @package mas-elementor
 */

namespace MASElementor\Core;

use MASElementor\Plugin;
use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The modules manager class.
 */
final class Modules_Manager {
	/**
	 * Stores all the available modules.
	 *
	 * @var Module_Base[]
	 */
	private $modules = array();

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		$modules = array(
			'multipurpose-text',
			'posts',
			'query-control',
			'woocommerce',
			'section',
			'column',
			'carousel-attributes',
			'nav-menu',
			'accordion',
		);

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = '\MASElementor\Modules\\' . $class_name . '\Module';

			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * Get the modules.
	 *
	 * @param string $module_name The module name.
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}
}
