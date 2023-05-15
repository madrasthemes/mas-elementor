<?php
/**
 * Multipurpose Text Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\MultipurposeText;

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

		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
	}

	/**
	 * Get the widget of the module.
	 *
	 * @return string
	 */
	public function get_widgets() {
		return array(
			'Multipurpose_Text',
		);
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'multipurpose-text';
	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_register_script(
			'mas-typed',
			MAS_ELEMENTOR_MODULES_URL . 'multipurpose-text/assets/js/typed.min.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
		wp_register_script(
			'mas-typed-init',
			MAS_ELEMENTOR_MODULES_URL . 'multipurpose-text/assets/js/typed-text.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
	}
}
