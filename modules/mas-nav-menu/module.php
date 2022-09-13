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

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_enqueue_style(
			'nav-menu-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'mas-nav-menu/assets/css/mas-nav-menu.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		// wp_enqueue_script(
		// 'nav-menu-script',
		// MAS_ELEMENTOR_ASSETS_URL . 'js/bootstrap-bundle.min.js',
		// array(),
		// MAS_ELEMENTOR_VERSION,
		// true
		// );
		// wp_enqueue_script(
		// 'nav-menu-script',
		// MAS_ELEMENTOR_ASSETS_URL . 'js/bootstrap.min.js',
		// array(),
		// MAS_ELEMENTOR_VERSION,
		// true
		// );.
	}

	/**
	 * Add Actions.
	 */
	protected function add_actions() {
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

	}
}
