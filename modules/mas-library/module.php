<?php
/**
 * Mas Library.
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
 * Elementor Mas Library module.
 *
 * Elementor MasLibrary module handler class is responsible for registering and
 * managing Elementor MasLibrary modules.
 *
 * @since 2.0.0
 */
class Module extends BaseModule {

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
	 * MAS Library module constructor.
	 *
	 * Initializing Elementor MAS library module.
	 */
	public function __construct() {
		Plugin::$instance->documents->register_document_type( 'mas-post', Documents\Mas_Post::get_class_full_name() );
	}
}
