<?php
/**
 * MAS Library.
 *
 * @package MASElementor\Modules\MasLibrary
 */

namespace MASElementor\Modules\MasLibrary;

use Elementor\Core\Base\Module as BaseModule;
use MASElementor\Modules\MasLibrary\Documents;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor MAS Library module.
 *
 * Elementor MasLibrary module handler class is responsible for registering and
 * managing Elementor MasLibrary modules.
 *
 * @since 2.0.0
 */
class Module extends BaseModule {

	/**
	 * Document types..
	 *
	 * @var array
	 */
	private $docs_types = array();

	/**
	 * Get module name.
	 *
	 * Retrieve the mas-library module name.
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'mas-library';
	}

	/**
	 * Get preview manager.
	 *
	 * @return Classes\Preview_Manager
	 */
	public function get_preview_manager() {
		return $this->get_component( 'preview' );
	}


	/**
	 * MAS Library module constructor.
	 *
	 * Initializing Elementor MAS library module.
	 */
	public function __construct() {
		$this->docs_types = array(
			'mas-post'     => Documents\Mas_Post::get_class_full_name(),
			'mas-template' => Documents\Mas_Template::get_class_full_name(),
			'mas-header' => Documents\MAS_Header::get_class_full_name(),
			'mas-footer' => Documents\MAS_Footer::get_class_full_name(),
		);
		foreach ( $this->docs_types as $type => $class_name ) {
			Plugin::$instance->documents
			->register_document_type( $type, $class_name );
		}
	}
}
