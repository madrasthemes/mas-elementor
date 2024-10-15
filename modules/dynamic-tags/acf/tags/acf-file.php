<?php
/**
 * ACF Dynamic File tag .
 *
 * @package MASElementor\Modules\DynamicTags\ACF\Tags
 */

namespace MASElementor\Modules\DynamicTags\ACF\Tags;

use MASElementor\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * ACF File Class - Dynamic.
 */
class ACF_File extends ACF_Image {

	/**
	 * Get the name of dynamic  acf-file.
	 */
	public function get_name() {
		return 'acf-file';
	}

	/**
	 * Get the title of dynamic acf-file.
	 */
	public function get_title() {
		return esc_html__( 'ACF', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'File Field', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the categories of dynamic acf-file.
	 */
	public function get_categories() {
		return array(
			Module::MEDIA_CATEGORY,
		);
	}

	/**
	 * Get supported fields.
	 */
	public function get_supported_fields() {
		return array(
			'file',
		);
	}
}
