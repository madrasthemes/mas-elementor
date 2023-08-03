<?php
/**
 * Button.
 *
 * @package MASElementor\Modules\Button
 */

namespace MASElementor\Modules\Button;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module
 */
class Module extends Module_Base {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	/**
	 * Get Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'button';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/button/section_style/before_section_end', array( $this, 'add_content_style_controls' ), 15 );
	}

	/**
	 * Add style controls to the element.
	 *
	 * @param Element $element element object.
	 */
	public function add_content_style_controls( $element ) {

		$element->add_control(
			'mas_button_opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'.elementor-button' => 'opacity: {{SIZE}};',
				),
				'separator' => 'before',
			)
		);

		$element->add_control(
			'mas_button_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-button-icon i, {{WRAPPER}} .elementor-button-icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);
	}

}