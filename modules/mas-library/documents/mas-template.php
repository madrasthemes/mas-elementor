<?php
/**
 * MAS library type documents.
 *
 * @package MASElementor\Modules\MasLibrary\documents\mas-post.php
 */

namespace MASElementor\Modules\MasLibrary\Documents;

use MASElementor\Modules\MasLibrary\Module as Mas_Library_Module;
use Elementor\Modules\Library\Documents\Library_Document;
use Elementor\Core\DocumentTypes\Post;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\Modules\PageTemplates\Module as PageTemplatesModule;
use Elementor\Controls_Manager;
use Elementor\Modules\Library\Documents\Page;
use Elementor\Plugin;



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor mas-post library document.
 *
 * Elementor mas-post library document handler class is responsible for
 * handling a document of a mas-post type.
 */
class Mas_Template extends Page {

	/**
	 * Instance.
	 *
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * Get library type libraries.
	 */
	public static function get_type() {
		return 'mas-template';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return esc_html__( 'MAS Template', 'mas-addons-for-elementor' );
	}
	/**
	 * Get plural titles.
	 */
	public static function get_plural_title() {
		return __( 'MAS Templates', 'mas-addons-for-elementor' );
	}

	/**
	 * Get Instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_injection(
			array(
				'at' => 'before',
				'of' => 'mas_select_template_override',
			)
		);

		$this->add_control(
			'mas_page_template',
			array(
				'label'   => esc_html__( 'Template Layout', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => PageTemplatesModule::TEMPLATE_HEADER_FOOTER,
				'options' => array(
					PageTemplatesModule::TEMPLATE_CANVAS => esc_html__( 'Elementor Canvas', 'mas-addons-for-elementor' ),
					PageTemplatesModule::TEMPLATE_HEADER_FOOTER => esc_html__( 'Elementor Full Width', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'mas_page_template_default_description',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Default Page Template from your theme', 'mas-addons-for-elementor' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'mas_page_template' => 'default',
				),
			)
		);

		$this->add_control(
			'mas_page_template_canvas_description',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'No header, no footer, just Elementor', 'mas-addons-for-elementor' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'mas_page_template' => PageTemplatesModule::TEMPLATE_CANVAS,
				),
			)
		);

		$this->add_control(
			'mas_page_template_header_footer_description',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This template includes the header, full-width content and footer', 'mas-addons-for-elementor' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'mas_page_template' => PageTemplatesModule::TEMPLATE_HEADER_FOOTER,
				),
			)
		);

		$this->end_injection();
	}

	/**
	 * Before Content.
	 *
	 * @return void
	 */
	public function before_get_content() {
		$preview_manager = Mas_Library_Module::instance()->get_preview_manager();
		// $preview_manager->switch_to_preview_query();.
	}

	/**
	 * After Content.
	 *
	 * @return void
	 */
	public function after_get_content() {
		$preview_manager = Mas_Library_Module::instance()->get_preview_manager();
		// $preview_manager->restore_current_query();.
	}

	/**
	 * Get Content.
	 *
	 * @param bool $with_css with css.
	 * @return string
	 */
	public function get_content( $with_css = false ) {
		$this->before_get_content();

		$content = parent::get_content( $with_css );

		$this->after_get_content();

		return $content;
	}

	/**
	 * Print Content.
	 *
	 * @return void
	 */
	public function print_content() {
		$plugin = Plugin::instance();

		if ( $plugin->preview->is_preview_mode( $this->get_main_id() ) ) {
			// PHPCS - the method builder_wrapper is safe.
			echo $plugin->preview->builder_wrapper( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			// PHPCS - the method get_content is safe.
			echo $this->get_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
