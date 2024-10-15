<?php
/**
 * ImageBox.
 *
 * @package MASElementor\Modules\ImageBox
 */

namespace MASElementor\Modules\ImageBox;

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
		add_action( 'elementor/element/image-box/section_style_content/before_section_end', array( $this, 'add_content_style_controls' ), 15 );
	}

	/**
	 * Add style controls to the element.
	 *
	 * @param Element $element element object.
	 */
	public function add_content_style_controls( $element ) {

		$element->add_responsive_control(
			'mas_image_box_content_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-image-box-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

}
