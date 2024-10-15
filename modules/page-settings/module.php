<?php
/**
 * The MAS Elementor Plugin.
 *
 * @package MASElementor/Modules/PageSettings
 */

namespace MASElementor\Modules\PageSettings;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Core\Base\Module as BaseModule;
use Elementor\Core\DocumentTypes\PageBase;
use Elementor\Plugin;
use Elementor\Modules\Library\Documents\Page as LibraryPageDocument;

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
	 * Get Name.
	 *
	 * @return array
	 */
	public function get_special_settings_names() {
		return array(
			'mas_select_template_override',
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
		if ( $document instanceof LibraryPageDocument ) {
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
		$page->add_control(
			'mas_select_template_override',
			array(
				'label'   => esc_html__( 'Templates Override', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''        => 'None',
					'single'  => 'Single',
					'archive' => 'Archive',
				),
			)
		);
		$post_types     = mas_option_enabled_post_types();
		$single_options = array(
			'single' => 'All Singluar',
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
				'label'      => esc_html__( 'Select Single Template', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => $single_options,
				'conditions' => array(
					'mas_select_template_override' => 'single',
				),
			)
		);

		$archive_options = array(
			'archive' => 'All Archives',
		);

		foreach ( $post_types as $post_type ) {
			if ( 'page' === $post_type ) {
				continue;
			}
			$post_type_object = get_post_type_object( $post_type );
			$key              = 'archive-' . $post_type;

			$archive_options[ $key ] = $post_type_object->label;
		}

		$page->add_control(
			'mas_select_archive_template_override',
			array(
				'label'      => esc_html__( 'Select Archive Template', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => $archive_options,
				'conditions' => array(
					'mas_select_template_override' => 'archive',
				),
			)
		);

		$page->end_controls_section();

	}
}
