<?php
/**
 * The MAS Breadcrumb Widget.
 *
 * @package MASElementor/Modules/MasBreadcrumb/Widgets
 */

namespace MASElementor\Modules\MasEpisodes;

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
		return array( 'mas-episodes-stylesheet' );
	}

	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array(
			'Mas_Episodes',
		);
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-episodes';
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
			'mas-episodes-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'mas-episodes/css/mas-episodes-tab.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}
}
