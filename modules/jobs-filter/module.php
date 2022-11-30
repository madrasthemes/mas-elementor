<?php
namespace MASElementor\Modules\JobsFilter;

use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {

	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		$classes = [];
		if ( class_exists( 'WP_Job_Manager' ) ) {
			$classes[] = 'Jobs_Filter';
		}

		return $classes;
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-jobs-filter';
	}
}