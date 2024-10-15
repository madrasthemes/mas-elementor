<?php
/**
 * Testimonial.
 *
 * @package MASElementor\Modules\testimonial
 */

namespace MASElementor\Modules\testimonial;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;


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
		return 'testimonial';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/testimonial/section_style_testimonial_content/before_section_end', array( $this, 'add_testimonial_style_controls' ), 15 );
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_testimonial_style_controls( $element ) {

		$element->start_injection(
			array(
				'of' => 'content_shadow',
				'at' => 'before',
			)
		);
		$element->add_responsive_control(
			'mas_description_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$element->end_injection();
	}

}
