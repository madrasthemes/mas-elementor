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
		wp_register_style(
			'nav-menu-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'mas-nav-menu/assets/css/mas-nav-menu.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
		wp_register_style(
			'mas-el-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'mas-nav-menu/assets/css/bootstrap-menu.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_scripts() {

		wp_register_script(
			'bootstrap-script',
			MAS_ELEMENTOR_ASSETS_URL . 'bootstrap.min.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_register_script(
			'mas-nav-init',
			MAS_ELEMENTOR_MODULES_URL . 'mas-nav-menu/assets/js/nav-menu.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

	}

	/**
	 * Register frontend styles.
	 */
	public function mas_add_nav_menus() {
		register_nav_menus();
	}

	/**
	 * Add Actions.
	 */
	protected function add_actions() {
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );
		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'init', array( $this, 'mas_add_nav_menus' ) );
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

	}
}
