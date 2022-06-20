<?php
/**
 * The Base Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use MASElementor\Modules\Woocommerce\Classes\Products_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Base_Widget
 */
abstract class Base_Widget extends \MASElementor\Base\Base_Widget {

	/**
	 * Get Categories.
	 */
	public function get_categories() {
		return array( 'woocommerce-elements-single' );
	}

	/**
	 * Get Devices Default Args.
	 */
	protected function get_devices_default_args() {
		$devices_required = array();

		// Make sure device settings can inherit from larger screen sizes' breakpoint settings.
		foreach ( Breakpoints_Manager::get_default_config() as $breakpoint_name => $breakpoint_config ) {
			$devices_required[ $breakpoint_name ] = array(
				'required' => false,
			);
		}

		return $devices_required;
	}

	/**
	 * Add Columns Responsive Control.
	 */
	protected function add_columns_responsive_control() {
		$this->add_responsive_control(
			'columns',
			array(
				'label'               => esc_html__( 'Columns', 'mas-elementor' ),
				'type'                => Controls_Manager::NUMBER,
				'prefix_class'        => 'elementor-grid%s-',
				'min'                 => 1,
				'max'                 => 12,
				'default'             => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
				'tablet_default'      => '3',
				'mobile_default'      => '2',
				'required'            => true,
				'device_args'         => $this->get_devices_default_args(),
				'min_affected_device' => array(
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET  => Controls_Stack::RESPONSIVE_TABLET,
				),
			)
		);
	}
}
