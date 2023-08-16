<?php
/**
 * Multipurpose Text Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\DynamicHeading;

use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get the widget of the module.
	 *
	 * @return string
	 */
	public function get_widgets() {
		return array(
			'Dynamic_Heading',
		);
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-dynamic-heading';
	}
}
