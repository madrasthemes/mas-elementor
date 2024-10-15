<?php
/**
 * MAS Overflow Controls.
 *
 * @package MASElementor\Modules\MasOverflow
 */

namespace MASElementor\Modules\MasOverflow;

use Elementor\Controls_Stack;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use MASElementor\Base\Module_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Tab Controls module class
 */
class Module extends Module_Base {

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'overflow-stylesheet' );
	}

	/**
	 * Return the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-overflow-attributes';
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

		add_action( 'elementor/frontend/before_enqueue_styles', array( $this, 'register_frontend_styles' ) );

	}

	/**
	 * Add Actions.
	 */
	protected function add_actions() {

		// Actions for adding contols in section wrappers.
		add_action( 'elementor/frontend/section/before_render', array( $this, 'before_render_section' ), 5 );
		add_action( 'elementor/element/section/section_advanced/before_section_end', array( $this, 'add_wrapper_controls' ) );

		// Actions for adding contols in column wrappers.
		add_action( 'elementor/frontend/column/before_render', array( $this, 'before_render_column' ), 10 );
		add_action( 'elementor/element/column/section_advanced/before_section_end', array( $this, 'add_wrapper_controls' ) );
	}

	/**
	 * Add wrap controls to the section and column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_wrapper_controls( $element ) {

		$element->add_control(
			'enable_overflow',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Enable Overflow Scroll', 'mas-addons-for-elementor' ),
				'default' => 'no',
			)
		);
	}

	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 *
	 * @return void
	 */
	public function before_render_section( $element ) {

		$settings        = $element->get_settings();
		$container_class = $settings['gap'];

		if ( 'no' === $settings['gap'] ) {
			$container_class = $settings['gap'] . ' no-gutters';
		}

		if ( isset( $settings['enable_overflow'] ) && 'yes' === $settings['enable_overflow'] ) {
			$container_class .= ' nowrap';
			$element->add_render_attribute( '_wrapper', 'class', 'enable-overflow' );
		}

		if ( ! empty( $container_class ) ) {
			$element->set_settings( 'gap', $container_class );
		}

	}

	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 *
	 * @return void
	 */
	public function before_render_column( $element ) {

		$settings = $element->get_settings_for_display();

		if ( 'yes' === $settings['enable_overflow'] ) {
			$element->add_render_attribute( '_wrapper', 'class', 'enable-width' );
		}

	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_enqueue_style(
			'overflow-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'mas-overflow/assets/css/overflow.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}

}
