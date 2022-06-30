<?php
namespace MASElementor\Modules\MasTemplatetypes\Documents;

use Elementor\Core\DocumentTypes\PageBase;
use MASElementor\Modules\MasTemplatetypes\Module as Mas_Templatetypes_Module;
use Elementor\Modules\Library\Traits\Library;
use Elementor\Modules\PageTemplates\Module as Page_Templates_Module;
use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Temp extends PageBase {

	// Library Document Trait
	use Library;

	public static function get_properties() {
		$properties = parent::get_properties();
        
        // $properties['admin_tab_group'] = '';
		$properties['support_kit'] = true;
		$properties['show_in_library'] = true;
		$properties['cpt'] = [ Mas_Templatetypes_Module::CPT ];

		return $properties;
	}

	public static function get_type() {
		return Mas_Templatetypes_Module::DOCUMENT_TYPE;
	}

	/**
	 * @access public
	 */
	public function get_name() {
		return Mas_Templatetypes_Module::DOCUMENT_TYPE;
	}

	/**
	 * @access public
	 * @static
	 */
	public static function get_title() {
		return esc_html__( 'MAS Temp', 'elementor' );
	}

	/**
	 * @access public
	 * @static
	 */
	public static function get_plural_title() {
		return __( 'MAS Temps', 'elementor' );
	}

	public static function get_create_url() {
		return parent::get_create_url() . '#library';
	}

	/**
	 * Save Document.
	 *
	 * Save an Elementor document.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @param $data
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
	 * @param $column_name
	 * @access public
	 */
	public function admin_columns_content( $column_name ) {
		if ( 'elementor_library_type' === $column_name ) {
			$this->print_admin_column_type();
		}
	}

	protected function get_remote_library_config() {
		$config = [
			'type' => 'lp',
			'default_route' => 'templates/mas-templatetypes',
			'autoImportSettings' => true,
		];

		return array_replace_recursive( parent::get_remote_library_config(), $config );
	}
}
