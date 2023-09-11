<?php
/**
 * Megamenu module.
 *
 * @package MASElementor/Modules/MegaMenu
 */

namespace MASElementor\Modules\MegaMenu;

use Elementor\Core\Experiments\Manager;
use MASElementor\Base\Module_Base;
use MASElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module.
 */
class Module extends Module_Base {

	const EXPERIMENT_NAME = 'mega-menu';

	/**
	 * Get the widgets.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array(
			'Mega_Menu',
		);
	}

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mega-menu';
	}

	/**
	 * Is active.
	 *
	 * @return bool
	 */
	public static function is_active() {
		return Plugin::elementor()->experiments->is_feature_active( \Elementor\Modules\NestedElements\Module::EXPERIMENT_NAME );
	}

	/**
	 * Add to the experiments
	 *
	 * @return array
	 */
	public static function get_experimental_data() {
		$experiment_data = array(
			'name'           => static::EXPERIMENT_NAME,
			'title'          => esc_html__( 'Menu', 'mas-elementor' ),
			'description'    => sprintf(
				/* translators: 1: Opening anchor tag, 2: Closing anchor tag */
				esc_html__( 'Create beautiful menus and mega menus with new nested capabilities. Mega menus are ideal for websites with complex navigation structures and unique designs. %1$sLearn More%2$s', 'mas-elementor' ),
				'<a href="https://go.elementor.com/wp-dash-mega-menu/" target="_blank">',
				'</a>'
			),
			'hidden'         => false,
			'release_status' => Manager::RELEASE_STATUS_ALPHA,
			'default'        => Manager::STATE_INACTIVE,
			'dependencies'   => array(
				'container',
				'nested-elements',
			),
		);

		if ( version_compare( ELEMENTOR_VERSION, '3.11.0', '<' ) ) {
			$experiment_data['mutable']      = false;
			$experiment_data['dependencies'] = array();
		}
		return $experiment_data;
	}
}
