<?php
/**
 * Mas template type documents.
 *
 * @package MASElementor\Modules\MasTemplateTypes\documents\post-temp.php
 */

namespace MASElementor\Modules\MasTemplatetypes\Documents;

use Elementor\Core\DocumentTypes\PageBase;
use MASElementor\Modules\MasTemplatetypes\Module as Mas_Templatetypes_Module;
use Elementor\Modules\Library\Traits\Library;
use Elementor\Modules\PageTemplates\Module as Page_Templates_Module;
use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Post Template class.
 */
class Post_Temp extends PageBase {

	// Library Document Trait.
	use Library;
	/**
	 * Get template properties.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['support_kit']     = true;
		$properties['show_in_library'] = true;
		$properties['cpt']             = array( Mas_Templatetypes_Module::CPT );

		return $properties;
	}
	/**
	 * Get the template type.
	 */
	public static function get_type() {
		return Mas_Templatetypes_Module::DOCUMENT_TYPE;
	}

	/**
	 * Get the name.
	 */
	public function get_name() {
		return Mas_Templatetypes_Module::DOCUMENT_TYPE;
	}

	/**
	 * Get the title.
	 */
	public static function get_title() {
		return esc_html__( 'MAS Temp', 'mas-elementor' );
	}

	/**
	 * Get plural title.
	 */
	public static function get_plural_title() {
		return __( 'MAS Temps', 'mas-elementor' );
	}
	/**
	 * Create new url.
	 */
	public static function get_create_url() {
		return parent::get_create_url() . '#library';
	}

	/**
	 * Save Document.
	 *
	 * Save an Elementor document.
	 *
	 * @since 3.1.0
	 *
	 * @param array $data data.
	 *
	 * @return bool
	 */
	public function save( $data ) {
		// This is for the first time a Landing Page is created. It is done in order to load a new Landing Page with
		// 'Canvas' as the default page template.
		if ( empty( $data['settings']['template'] ) ) {
			$data['settings']['template'] = Page_Templates_Module::TEMPLATE_CANVAS;
		}

		return parent::save( $data );
	}

	/**
	 * Admin Columns Content
	 *
	 * @since 3.1.0
	 *
	 * @param array $column_name column.
	 */
	public function admin_columns_content( $column_name ) {
		if ( 'elementor_library_type' === $column_name ) {
			$this->print_admin_column_type();
		}
	}
	/**
	 * Get configuration for remote libraries.
	 */
	protected function get_remote_library_config() {
		$config = array(
			'type'               => 'lp',
			'default_route'      => 'templates/mas-templatetypes',
			'autoImportSettings' => true,
		);

		return array_replace_recursive( parent::get_remote_library_config(), $config );
	}
}
