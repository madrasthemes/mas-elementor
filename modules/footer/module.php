<?php
/**
 * Countdown Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Footer;

use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {

	/**
	 * Get the widget of the module.
	 *
	 * @return string
	 */
	public function get_widgets() {
		return array(
			'Footer',
		);
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string.
	 */
	public function get_name() {
		return 'mas-footer-widget';
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
	}
}
