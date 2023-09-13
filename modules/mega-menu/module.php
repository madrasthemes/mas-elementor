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
	 * Add Actions.
	 */
	protected function add_actions() {
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );
		add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'register_frontend_styles' ) );
		// add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		// add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'register_frontend_scripts' ) );.
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

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

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_register_style(
			'mega-menu-stylesheet',
			MAS_ELEMENTOR_ASSETS_URL . 'css/megamenu/megamenu.min.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		// wp_enqueue_script(
		// 'editor-megamenu-script',
		// MAS_ELEMENTOR_MODULES_URL . 'mega-menu/assets/js/editor.js',
		// array('jquery'),
		// MAS_ELEMENTOR_VERSION,
		// true
		// );
		// wp_enqueue_script(
		// 'megamenu-init-script',
		// MAS_ELEMENTOR_MODULES_URL . 'mega-menu/assets/js/megamenu.bundle.js',
		// array(),
		// MAS_ELEMENTOR_VERSION,
		// true
		// );.
		wp_enqueue_script(
			'editor-megamenu-script',
			MAS_ELEMENTOR_MODULES_URL . 'mega-menu/assets/js/test.js',
			array( 'wp-i18n' ),
			MAS_ELEMENTOR_VERSION,
			true
		);

	}
}
