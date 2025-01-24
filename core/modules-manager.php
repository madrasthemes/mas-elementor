<?php
/**
 * The Modules Manager.
 *
 * @package mas-elementor
 */

namespace MASElementor\Core;

use MASElementor\Plugin;
use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The modules manager class.
 */
final class Modules_Manager {
	/**
	 * Stores all the available modules.
	 *
	 * @var Module_Base[]
	 */
	private $modules = array();

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		$modules = array(
			// 'theme-builder',
			'mas-breadcrumbs',
			'mas-nav-tabs',
			'mas-library',
			'dynamic-tags',
			'multipurpose-text',
			'posts',
			'query-control',
			'woocommerce',
			'section',
			'column',
			'carousel-attributes',
			'mas-nav-menu',
			'accordion',
			'icon',
			'testimonial',
			'divider',
			'mas-overflow',
			'countdown',
			'video',
			'image-box',
			'jobs-filter',
			'mas-related-project',
			// 'page-settings',
			'mas-tv-shows-episodes',
			'episodes',
			'forms',
			'mas-attributes-image',
			'masvideos-genre',
			'review-form',
			'scrollspy',
			'counter',
			'icon-list',
			'mas-templates',
			'post-content',
			'button',
			'dynamic-heading',
			'mas-star-rating',
			'image',
			'audio',
			'blockquote',
			'add-to-wishlist',
			'shortcode',
			'icon-box',
			'header',
			'footer',
		);

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = '\MASElementor\Modules\\' . $class_name . '\Module';

			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_editor_categories' ) );
	}

	/**
	 * Get the modules.
	 *
	 * @param string $module_name The module name.
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}
	/**
	 * Add widget categories.
	 *
	 * @param Elements_Manager $object Elements Manager reference.
	 * @return array
	 */
	public function add_editor_categories( $object ) {
		if ( $object ) {
			$category_properties = array(
				'title'  => esc_html__( 'MAS Elementor', 'mas-addons-for-elementor' ),
				'icon'   => 'eicon-wordpress',
				'active' => true,
			);
			$object->add_category( 'mas-elements', $category_properties );

		}

		return $this->modules;
	}
}
