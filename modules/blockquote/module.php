<?php
/**
 * Countdown Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Blockquote;

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
			'Blockquote',
		);
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string.
	 */
	public function get_name() {
		return 'mas-blockquote';
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
		wp_register_style(
			'mas-blockquote',
			MAS_ELEMENTOR_ASSETS_URL . 'css/blockquote/blockquote.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}
}
