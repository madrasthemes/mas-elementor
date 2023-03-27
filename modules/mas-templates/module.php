<?php
/**
 * The MAS Elementor Plugin.
 *
 * @package MASElementor/Modules/PageSettings
 */

namespace MASElementor\Modules\MasTemplates;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Core\Base\Module as BaseModule;
use Elementor\Core\DocumentTypes\PageBase;
use Elementor\Plugin;
use Elementor\Modules\PageTemplates\Module as PageModule;

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
	 * @param string $content contetnt.
	 */
	public function single_content_filter( $template ) {
		if ( is_singular('post') ) {
			$location = 'single-post';
		} elseif( is_archive() ) {
			$location = 'archive';
		}

		$template_url    = get_page_by_path( $location, OBJECT, 'elementor_library' );
			$page_templates_module = Plugin::$instance->modules_manager->get_modules( 'page-templates' );
			$document = Plugin::$instance->documents->get_doc_for_frontend( get_the_ID() );
			$page_template = $page_templates_module::TEMPLATE_HEADER_FOOTER;
			
			if ( ! empty( $page_template ) ) {
				$template_path = $page_templates_module->get_template_path( $page_template );
				if ( $template_path ) {
					$page_templates_module->set_print_callback( function() use ( $location ) { 
						$this->print_content( $location);
					} );

					$template = $template_path;
				}
			}
		return $template;
	}

	public static function set_global_authordata() {
		global $authordata;
		if ( ! isset( $authordata->ID ) ) {
			$post = get_post();
			$authordata = get_userdata( $post->post_author ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}

	public function print_content( $location ) {
		$template    = get_page_by_path( $location, OBJECT, 'elementor_library' );

		if ( is_singular() ) {
			self::set_global_authordata();
		}

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
		do_action( "elementor/theme/before_do_single", $this );

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
		do_action( "elementor/theme/after_do_single", $this );

		return true;
	}
}
