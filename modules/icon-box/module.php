<?php
/**
 * IconBox.
 *
 * @package MASElementor\Modules\IconBox
 */

namespace MASElementor\Modules\IconBox;

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
		return 'imagebox';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/icon-box/section_style_box/before_section_end', array( $this, 'add_box_style_controls' ), 15 );
	}

	/**
	 * Add style controls to the element.
	 *
	 * @param Element $element element object.
	 */
	public function add_box_style_controls( $element ) {

		$element->add_responsive_control(
			'mas_icon_box_title_margin',
			array(
				'label'      => esc_html__( 'Title Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

}
