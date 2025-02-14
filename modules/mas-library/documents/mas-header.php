<?php
/**
 * MAS library type documents.
 *
 * @package MASElementor\Modules\MasLibrary\documents\mas-header.php
 */

namespace MASElementor\Modules\MasLibrary\Documents;

use MASElementor\Modules\MasLibrary\Module as Mas_Library_Module;
use Elementor\Modules\Library\Documents\Library_Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor mas-header library document.
 *
 * Elementor mas-header library document handler class is responsible for
 * handling a document of a mas-header type.
 */
class MAS_Header extends Library_Document {
	/**
	 * Get properties libraries.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['support_kit']               = true;
		$properties['show_in_finder']            = true;
		$properties['support_wp_page_templates'] = true;

		return $properties;
	}
	/**
	 * Get library type libraries.
	 */
	public static function get_type() {
		return 'mas-header';
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
		return esc_html__( 'MAS Header', 'mas-addons-for-elementor' );
	}
	/**
	 * Get plural titles.
	 */
	public static function get_plural_title() {
		return __( 'MAS Headers', 'mas-addons-for-elementor' );
	}
}
