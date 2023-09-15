<?php
/**
 * Dark Mode Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\DarkMode;

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
			'Dark_Mode',
		);
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string.
	 */
	public function get_name() {
		return 'mas-dark-mode';
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

	}


	/**
	 * Add Actions.
	 */
	protected function add_actions() {

		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );

	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_scripts() {

		wp_register_script(
			'mas-dark-mode',
			MAS_ELEMENTOR_MODULES_URL . 'dark-mode/assets/js/dark-mode.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_register_script(
			'mas-dark-mode-init',
			MAS_ELEMENTOR_MODULES_URL . 'dark-mode/assets/js/dark-mode-init.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

	}

	/**
	 * Editor Scripts
	 *
	 * Enqueue required editor JS files, localize JS with required data.
	 */
	public function enqueue_editor_scripts() {

		wp_enqueue_script(
			'mas-dark-mode-editor',
			MAS_ELEMENTOR_MODULES_URL . 'dark-mode/assets/js/dark-mode.js',
			array(
				'jquery',
			),
			MAS_ELEMENTOR_VERSION,
			true
		);

	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_enqueue_style(
			'mas-dark-mode-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'dark-mode/assets/css/dark-mode.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}
}
