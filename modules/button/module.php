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

		$element->add_control(
			'mas_button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button-icon i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$element->add_responsive_control(
			'mas_button_wrapper_align_items',
			array(
				'label'     => esc_html__( 'Vertical Align Items', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-button-content-wrapper' => 'align-items: {{VALUE}};',
				),
				'default'   => 'center',
			)
		);

		$element->add_responsive_control(
			'mas_button_wrapper_align_icon',
			array(
				'label'     => esc_html__( 'Vertical Align Icon', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-button-icon' => 'display:flex; align-items: {{VALUE}};',
				),
				'default'   => 'center',
			)
		);
	}

}
