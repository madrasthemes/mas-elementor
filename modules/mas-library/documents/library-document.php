<?php
/**
 * ElementorLibrary.
 *
 * @package MASElementor\Modules\MasLibrary\LibraryDocument
 */

namespace MASElementor\Modules\MasLibrary\Documents;

use Elementor\Core\Base\Document;
use Elementor\Modules\Library\Traits\Library;
use Elementor\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor library document.
 *
 * Elementor library document handler class is responsible for handling
 * a document of the library type.
 */
abstract class Library_Document extends Document {
	use Library;

	/**
	 * The taxonomy type slug for the library document.
	 */
	const TAXONOMY_TYPE_SLUG = 'elementor_library_type';

	/**
	 * Get document properties.
	 *
	 * Retrieve the document properties.
	 *
	 * @static
	 *
	 * @return array Document properties.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = 'library';
		$properties['show_in_library'] = true;
		$properties['register_type']   = true;
		$properties['cpt']             = array( Source_Local::CPT );

		// TODO: Remove - Backwards compatibility, since 'Theme_Document' does not support 'static::get_type'.
		$properties['show_in_finder'] = false;

		return $properties;
	}

	/**
	 * Get initial config.
	 *
	 * Retrieve the current element initial configuration.
	 *
	 * Adds more configuration on top of the controls list and the tabs assigned
	 * to the control. This method also adds element name, type, icon and more.
	 *
	 * @return array The initial config.
	 */
	public function get_initial_config() {
		$config = parent::get_initial_config();

		$config['library'] = array(
			'save_as_same_type' => true,
		);

		return $config;
	}
}
