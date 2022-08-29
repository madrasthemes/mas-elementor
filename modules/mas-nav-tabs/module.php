<?php
/**
 * The Mas Nav Tab Widget.
 *
 * @package MASElementor/Modules/MasNavTab/Widgets
 */

namespace MASElementor\Modules\MasNavTabs;

use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'nav-tab-stylesheet' );
	}

	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array(
			'Mas_Nav_Tabs',
		);
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-nav-tabs';
	}

	/**
	 * Add Actions.
	 */
	protected function add_actions() {
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );

	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_enqueue_style(
			'nav-tab-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'mas-nav-tabs/assets/css/mas-nav-tab.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}
}
