<?php
/**
 * The base widget trait file.
 *
 * @package mas-elementor
 */

namespace MASElementor\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

trait Base_Widget_Trait {

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elementor-elements' );
	}

	/**
	 * Get the CSS configuration for a given widget.
	 *
	 * @param string $widget_name The name of the widget.
	 * @return array
	 */
	public function get_widget_css_config( $widget_name ) {
		$direction = is_rtl() ? '-rtl' : '';

		$css_file_path = 'css/widget-' . $widget_name . $direction . '.min.css';

		return array(
			'key'       => $widget_name,
			'version'   => MAS_ELEMENTOR_VERSION,
			'file_path' => MAS_ELEMENTOR_ASSETS_PATH . $css_file_path,
			'data'      => array(
				'file_url' => MAS_ELEMENTOR_ASSETS_URL . $css_file_path,
			),
		);
	}
}
