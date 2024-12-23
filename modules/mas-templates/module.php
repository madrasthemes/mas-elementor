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
use Elementor\Modules\Library\Documents\Page as LibraryPageDocument;
use Elementor\Core\Base\Document;
use MASElementor\Modules\MasLibrary\Documents\Mas_Template;

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
		add_action( 'elementor/documents/register_controls', array( $this, 'action_register_template_control' ) );
		add_filter( 'template_include', array( $this, 'template_override' ), 99999 );
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
	 * Conditional statements check.
	 */
	public function conditional_location() {
		$post_types = mas_option_enabled_post_types();
		$taxonomies = array();
		$location   = '';
		foreach ( $post_types as $post_type ) {
			if ( 'page' === $post_type ) {
				continue;
			}
			if ( is_singular( $post_type ) ) {
				$location = 'single-' . $post_type;
			} elseif ( is_post_type_archive( $post_type ) || ( 'post' === $post_type && is_home() ) ) {
				$location = 'archive-' . $post_type;
			} elseif ( function_exists( 'is_cart' ) && is_cart() ) {
				$location = 'cart';
			} elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
				$location = 'checkout';
			} elseif ( function_exists( 'is_search' ) && is_search() ) {
				$location = 'archive-post';
			}
			if ( 'post' !== $post_type ) {
				$taxonomies = array_merge( get_object_taxonomies( $post_type ), $taxonomies );
			}
		}

		$exclude = array( 'post_tag', 'category', 'post_format', 'video_visibility', 'episode_visibility', 'movie_visibility', 'tv_show_visibility', 'person_visibility' );

		foreach ( $taxonomies as $taxonomy ) {
			if ( in_array( $taxonomy, $exclude, true ) ) {
				continue;
			}
			if ( is_tag() ) {
				$location = 'taxonomy-post_tag';
			}
			if ( is_category() ) {
				$location = 'taxonomy-category';
			}
			if ( is_tax( $taxonomy ) ) {
				$location = 'taxonomy-' . $taxonomy;
			}
		}
		return $location;
	}
	/**
	 * Template Override.
	 *
	 * @param string $template template.
	 */
	public function template_override( $template ) {
		$location = $this->conditional_location();

		if ( ! empty( $location ) ) {
			$page_templates_module = Plugin::$instance->modules_manager->get_modules( 'page-templates' );
			$document              = Plugin::$instance->documents->get_doc_for_frontend( get_the_ID() );
			$page_template         = $page_templates_module::TEMPLATE_HEADER_FOOTER;
			$present               = false;

			if ( ! empty( $page_template ) ) {
				$template_path = $page_templates_module->get_template_path( $page_template );
				if ( ! empty( $template_path ) ) {
					$slug           = '';
					$page_templates = mas_template_override_options( 'mas-template' );
					foreach ( $page_templates as $id => $name ) {
						if ( empty( $id ) ) {
							continue;
						}
						$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
						$page_settings_model   = $page_settings_manager->get_model( $id );

						$template_type = $page_settings_model->get_settings( 'mas_select_template_override' );
						if ( 'single' === $template_type ) {
							$template_name = $page_settings_model->get_settings( 'mas_select_single_template_override' );
						} elseif ( 'archive' === $template_type ) {
							$template_name = $page_settings_model->get_settings( 'mas_select_archive_template_override' );
						} elseif ( 'taxonomy' === $template_type ) {
							$template_name = $page_settings_model->get_settings( 'mas_select_taxonomy_template_override' );
						} elseif ( 'woocommerce' === $template_type ) {
							$template_name = $page_settings_model->get_settings( 'mas_select_woocommerce_template_override' );
						} else {
							$template_name = '';
						}
						if ( $template_name === $location ) {
							$slug          = $name;
							$present       = true;
							$page_template = $page_settings_model->get_settings( 'mas_page_template' );
							$template_path = $page_templates_module->get_template_path( $page_template );
						}
					}
					if ( ! $present ) {
						return $template;
					}
					$page_templates_module->set_print_callback(
						function() use ( $location, $slug ) {
							$this->print_template_content( $location, $slug );
						}
					);

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
			$post       = get_post();
			$authordata = get_userdata( $post->post_author ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}

	/**
	 * Print template content.
	 *
	 * @param string $location location template name.
	 * @param string $slug slug template slug.
	 * @return bool
	 */
	public function print_template_content( $location, $slug ) {

		$template = get_page_by_path( $slug, OBJECT, 'elementor_library' );
		if ( empty( $template ) ) {
			return false;
		}

		if ( is_singular() ) {
			self::set_global_authordata();
		}
		$location = str_replace( '-', '_', $location );
		$document = Plugin::instance()->documents->get( $template->ID );

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
		$document->print_content();
		// echo ( Plugin::instance()->frontend->get_builder_content_for_display( $template->ID ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		// /**
		// * After location content printed.
		// *
		// * Fires after Elementor theme location is printed.
		// *
		// * The dynamic portion of the hook name, `$location`, refers to the location name.
		// *
		// * @since 2.0.0
		// *
		// * @param Locations_Manager $this An instance of locations manager.
		// */.
		do_action( "elementor/theme/after_do_{$location}", $this );

		return true;
	}

	/**
	 * Get Name.
	 *
	 * @return array
	 */
	public function get_special_settings_names() {
		return array(
			'mas_select_template_override',
			'mas_select_single_template_override',
			'mas_select_archive_template_override',
		);
	}

	/**
	 * Register template control.
	 *
	 * Adds custom controls to any given document.
	 *
	 * Fired by `update_post_metadata` action.
	 *
	 * @since 1.0.0
	 *
	 * @param Document $document The document instance.
	 */
	public function action_register_template_control( $document ) {
		$post_types = function_exists( 'mas_option_enabled_post_types' ) ? mas_option_enabled_post_types() : array( 'post', 'page' );
		if ( $document instanceof Mas_Template ) {
			$this->post_id = $document->get_main_post()->ID;
			$this->register_template_control( $document );
		}
	}

	/**
	 * Register template control.
	 *
	 * @param Document $page   The document instance.
	 */
	public function register_template_control( $page ) {
		$this->add_template_controls( $page );
	}

	/**
	 * Add Header Controls.
	 *
	 * @param Document $page Page.
	 * @return void
	 */
	public function add_template_controls( Document $page ) {
		$page->start_controls_section(
			'document_settings_header',
			array(
				'label' => esc_html__( 'Templates', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);
		$template_options = array(
			'none'     => 'None',
			'single'   => 'Single',
			'archive'  => 'Archive',
			'taxonomy' => 'Taxonomies',
		);

		if ( class_exists( 'woocommerce' ) ) {
			$template_options['woocommerce'] = 'Woocommerce';
		}

		$page->add_control(
			'mas_select_template_override',
			array(
				'label'   => esc_html__( 'Templates Override', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $template_options,
			)
		);

		$post_types     = mas_option_enabled_post_types();
		$single_options = array(
			'none' => 'None',
		);
		foreach ( $post_types as $post_type ) {
			if ( 'page' === $post_type ) {
				continue;
			}
			$post_type_object = get_post_type_object( $post_type );
			$key              = 'single-' . $post_type;

			$single_options[ $key ] = $post_type_object->label;
		}

		$page->add_control(
			'mas_select_single_template_override',
			array(
				'label'     => esc_html__( 'Select Single Template', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'single-post',
				'options'   => $single_options,
				'condition' => array(
					'mas_select_template_override' => 'single',
				),
			)
		);

		$archive_options = array(
			'none' => 'None',
		);

		$archive_excludes = array( 'page' );

		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type, $archive_excludes, true ) ) {
				continue;
			}
			$post_type_object = get_post_type_object( $post_type );
			$key              = 'archive-' . $post_type;

			$archive_options[ $key ] = $post_type_object->label;
		}

		$page->add_control(
			'mas_select_archive_template_override',
			array(
				'label'     => esc_html__( 'Select Archive Template', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'archive-post',
				'options'   => $archive_options,
				'condition' => array(
					'mas_select_template_override' => 'archive',
				),
			)
		);

		$tax_options = array();
		$exclude     = array( 'post_format', 'video_visibility', 'episode_visibility', 'movie_visibility', 'tv_show_visibility', 'person_visibility' );
		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type, $archive_excludes, true ) ) {
				continue;
			}
			$taxonomies = get_object_taxonomies( $post_type );
			foreach ( $taxonomies as $taxonomy ) {
				if ( in_array( $taxonomy, $exclude, true ) ) {
					continue;
				}
				$key        = 'taxonomy-' . $taxonomy;
				$tax_object = get_taxonomy( $taxonomy );

				$tax_options[ $key ] = $tax_object->label;
			}
		}

		$page->add_control(
			'mas_select_taxonomy_template_override',
			array(
				'label'     => esc_html__( 'Select Archive Template', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'taxonomy-category',
				'options'   => $tax_options,
				'condition' => array(
					'mas_select_template_override' => 'taxonomy',
				),
			)
		);

		$page->add_control(
			'mas_select_woocommerce_template_override',
			array(
				'label'     => esc_html__( 'Select Woocommerce Template', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cart',
				'options'   => array(
					'cart'     => 'Cart',
					'checkout' => 'Checkout',
				),
				'condition' => array(
					'mas_select_template_override' => 'woocommerce',
				),
			)
		);

		$page->end_controls_section();

	}
}
