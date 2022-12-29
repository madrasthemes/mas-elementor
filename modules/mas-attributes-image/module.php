<?php
/**
 * The MAS Breadcrumb Widget.
 *
 * @package MASElementor/Modules/MasBreadcrumb/Widgets
 */

namespace MASElementor\Modules\MasAttributesImage;

use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {
	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		$widgets = array();
		if ( class_exists( 'MasVideos' ) && class_exists( '\acf' ) && function_exists( 'acf_get_field_groups' ) ) {
			$widgets[] = 'Mas_Movie_Attributes_ACF_Image';
			$widgets[] = 'Mas_TV_Show_Attributes_ACF_Image';
			$widgets[] = 'Mas_Video_Attributes_ACF_Image';
		}
		return $widgets;
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-attributes-acf-image';
	}
}
