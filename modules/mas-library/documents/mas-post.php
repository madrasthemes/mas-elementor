<?php
/**
 * Mas library type documents.
 *
 * @package MASElementor\Modules\MasLibrary\documents\mas-post.php
 */

namespace MASElementor\Modules\MasLibrary\Documents;

use MASElementor\Modules\MasLibrary\Module as Mas_Library_Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor mas-post library document.
 *
 * Elementor mas-post library document handler class is responsible for
 * handling a document of a mas-post type.
 */
class Mas_Post extends Library_Document {
	/**
	 * Get properties libraries.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['support_kit']    = true;
		$properties['show_in_finder'] = true;

		return $properties;
	}
	/**
	 * Get library type libraries.
	 */
	public static function get_type() {
		return 'mas-post';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return esc_html__( 'Mas Post', 'mas-elementor' );
	}
	/**
	 * Get plural titles.
	 */
	public static function get_plural_title() {
		return __( 'Mas Posts', 'mas-elementor' );
	}
}
