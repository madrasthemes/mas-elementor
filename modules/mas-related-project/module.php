<?php
/**
 * MAS Related Project Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\MasRelatedProject;

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
			'Mas_Related_Project',
		);
	}

	/**
	 * Get Widgets Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-related-project';
	}


}
