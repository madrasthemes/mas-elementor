<?php
/**
 * Icon List.
 *
 * @package MASElementor\Modules\icon
 */

namespace MASElementor\Modules\IconList;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module
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
	 * Action Hooks.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/icon-list/section_icon_list/before_section_end', array( $this, 'add_style_controls' ), 10 );
	}

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-override-icon-list';
	}

	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 * @return void
	 */
	public function add_style_controls( $element ) {
		$element->add_responsive_control(
			'mas_icon_list_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon-list-items .elementor-icon-list-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

	}
}
