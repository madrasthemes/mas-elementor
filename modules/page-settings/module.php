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
		add_filter( 'the_content', array( $this, 'single_content_filter' ), 1 );
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
	public function single_content_filter( $content ) {
		$post_document = Plugin::instance()->documents->get( get_the_ID() );
		$settings      = $post_document->get_settings();

		// Check if we're inside the main loop in a single Post.
		if ( isset( $settings['mas_select_template'] ) ) {
			echo ( wp_kses_post( mas_render_template( $settings['mas_select_template'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Get Name.
	 *
	 * @return array
	 */
	public function get_special_settings_names() {
		return array(
			'mas_select_template',
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
				'label' => esc_html__( 'Templates', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);
		$page->add_control(
			'mas_select_template_override',
			array(
				'label'   => esc_html__( 'Templates Override', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''            => 'None',
					'single-post' => 'Single',
					'archive'     => 'Archive'
				),
			)
		);

		$page->end_controls_section();

	}
}
