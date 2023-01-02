<?php
/**
 * Countdown Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Scrollspy;

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
			'Scrollspy',
		);
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string.
	 */
	public function get_name() {
		return 'mas-scrollspy';
	}

	/**
	 * Initialize the scrollspy module object.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );

	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_register_script(
			'mas-scroll-script',
			MAS_ELEMENTOR_ASSETS_URL . 'js/scrollspy/scroll.min.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_register_script(
			'scrollspy-init-script',
			MAS_ELEMENTOR_ASSETS_URL . 'js/scrollspy/scroll-init.js',
			array( 'mas-bootstrap-bundle' ),
			MAS_ELEMENTOR_VERSION,
			true
		);
	}
}
