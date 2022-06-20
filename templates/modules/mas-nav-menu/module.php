<?php
/**
 * MAS Nav Menu Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\MasNavMenu;

use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module for Nav-Menu
 */
class Module extends Module_Base {

	/**
	 * Get Widgets.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array(
			'Mas_Nav_Menu',
		);
	}

	/**
	 * Get Widgets Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-nav-menu';
	}
}
