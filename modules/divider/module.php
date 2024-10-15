<?php
/**
 * Divider.
 *
 * @package MASElementor\Modules\divider
 */

namespace MASElementor\Modules\Divider;

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
		return 'divider';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/divider/section_divider/before_section_end', array( $this, 'add_divider_style_controls' ), 15 );
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_divider_style_controls( $element ) {

		$element->add_control(
			'mas_elementor_divider_height_enable',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Height?', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
			)
		);

		$element->add_responsive_control(
			'mas_elementor_divider_height',
			array(
				'label'          => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 1000,
					),
				),
				'default'        => array(
					'size' => 100,
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'selectors'      => array(
					'{{WRAPPER}} .elementor-divider-separator' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'mas_elementor_divider_height_enable' => 'yes',
				),
			)
		);
	}
}
