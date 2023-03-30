<?php
/**
 * The MAS Elementor Plugin.
 *
 * @package MASElementor/Modules/PageSettings
 */

namespace MASElementor\Modules\MasTemplates;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Module as BaseModule;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor page templates module.
 *
 * Elementor page templates module handler class is responsible for registering
 * and managing Elementor page templates modules.
 *
 * @since 1.0.0
 */
class Module extends BaseModule {

	/**
	 * Post Id,.
	 *
	 * @var Plugin
	 */
	protected $post_id = 0;
	/**
	 * Page Options.
	 *
	 * @var Plugin
	 */
	protected $mas_page_options = array();
	/**
	 * Static Content
	 *
	 * @var Plugin
	 */
	protected $static_contents = array();

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'single_content_filter' ), 99999 );
	}

	/**
	 * Get Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-override-page-settings';
	}

	/**
	 * Adding this content in single.
	 *
	 * @param string $template template.
	 */
	public function single_content_filter( $template ) {
		if ( is_singular('post') ) {
			$location = 'single-post';
		} elseif( is_archive() ) {
			$location = 'archive';
		}
		if ( ! empty( $location ) ) {
			$page_templates_module = Plugin::$instance->modules_manager->get_modules( 'page-templates' );
			$document = Plugin::$instance->documents->get_doc_for_frontend( get_the_ID() );
			$page_template = $page_templates_module::TEMPLATE_HEADER_FOOTER;
			
			if ( ! empty( $page_template ) ) {
				$template_path = $page_templates_module->get_template_path( $page_template );
				if ( $template_path ) {
					$page_templates_module->set_print_callback( function() use ( $location ) { 
						$this->do_location( $location);
					} );

					$template = $template_path;
				}
			}
		}
		return $template;
	}

	/**
	 * Get global author data.
	 *
	 * @return void
	 */
	public static function set_global_authordata() {
		global $authordata;
		if ( ! isset( $authordata->ID ) ) {
			$post = get_post();
			$authordata = get_userdata( $post->post_author ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}

	public function do_location( $location ) {
		$slug = '';
		$page_templates = mas_template_override_options('page');
		foreach( $page_templates as $id => $name ) {
			if ( empty( $id ) ) {
				continue;
			}
			$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
			$page_settings_model = $page_settings_manager->get_model( $id );

			$template_name = $page_settings_model->get_settings( 'mas_select_template_override' );
			if ( $template_name === $location ) {
				$slug = $name;
			}
		}
		

		$template    = get_page_by_path( $slug, OBJECT, 'elementor_library' );
		if ( empty( $template ) ) {
			return false;
		}

		if ( is_singular() ) {
			self::set_global_authordata();
		}
		$location = str_replace('-', '_', $location);

		/**
		 * Before location content printed.
		 *
		 * Fires before Elementor theme location is printed.
		 *
		 * The dynamic portion of the hook name, `$location`, refers to the location name.
		 *
		 * @since 2.0.0
		 *
		 * @param Locations_Manager $this An instance of locations manager.
		 */
		do_action( "elementor/theme/before_do_{$location}", $this );
		the_content();
		echo wp_kses_post( Plugin::instance()->frontend->get_builder_content_for_display( $template->ID ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		// /**
		//  * After location content printed.
		//  *
		//  * Fires after Elementor theme location is printed.
		//  *
		//  * The dynamic portion of the hook name, `$location`, refers to the location name.
		//  *
		//  * @since 2.0.0
		//  *
		//  * @param Locations_Manager $this An instance of locations manager.
		//  */
		do_action( "elementor/theme/after_do_{$location}", $this );

		return true;
	}
}
