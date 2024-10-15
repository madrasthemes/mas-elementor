<?php
/**
 * Counter Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Counter;

use MASElementor\Base\Module_Base;
use MASElementor\Modules\Counter\Skins;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {

	/**
	 * Instantiate the object.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string.
	 */
	public function get_name() {
		return 'override-Counter';
	}

	/**
	 * Actions in this module.
	 */
	public function add_actions() {
		add_action( 'elementor/widget/counter/skins_init', array( $this, 'init_skins' ), 10 );
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'register_frontend_scripts' ) );
	}

	/**
	 * Init skins for this module.
	 *
	 * @param Elementor\Widget_Accordion $widget The Counter widget.
	 */
	public function init_skins( $widget ) {
		$widget->add_skin( new Skins\Skin_Counter_V1( $widget ) );
	}

	/**
	 * Remove Style Counter Controls.
	 *
	 * @param Elementor\Widget_Counter $widget The Counter widget.
	 */
	public function remove_style_counter_controls( $widget ) {

		$widget->add_control(
			'title_css',
			array(
				'label'   => esc_html__( 'Title CSS', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);
	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_enqueue_script(
			'mas-counter-appear-script',
			MAS_ELEMENTOR_MODULES_URL . 'counter/assets/js/appear.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
		wp_enqueue_script(
			'mas-counter-circle-script',
			MAS_ELEMENTOR_MODULES_URL . 'counter/assets/js/circle.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
		wp_enqueue_script(
			'mas-counter-core-script',
			MAS_ELEMENTOR_MODULES_URL . 'counter/assets/js/core.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
		wp_enqueue_script(
			'mas-counter-script',
			MAS_ELEMENTOR_MODULES_URL . 'counter/assets/js/counter.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
		wp_enqueue_script(
			'mas-counter-init-script',
			MAS_ELEMENTOR_MODULES_URL . 'counter/assets/js/counter-init.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

	}
}
