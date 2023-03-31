<?php
/**
 * Mas library type documents.
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



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor mas-post library document.
 *
 * Elementor mas-post library document handler class is responsible for
 * handling a document of a mas-post type.
 */
class Mas_Template extends Library_Document {
	/**
	 * Get properties libraries.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['support_wp_page_templates'] = true;
		$properties['support_kit']               = true;
		$properties['show_in_finder']            = true;

		return $properties;
	}
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
		return esc_html__( 'Mas Template', 'mas-elementor' );
	}
	/**
	 * Get plural titles.
	 */
	public static function get_plural_title() {
		return __( 'Mas Templates', 'mas-elementor' );
	}

	/**
	 * Get CSS wrapper selector.
	 */
	public function get_css_wrapper_selector() {
		return 'body.elementor-page-' . $this->get_main_id();
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_injection(
			array(
				'of'       => 'post_status',
				'fallback' => array(
					'of' => 'post_title',
				),
			)
		);

		$this->add_control(
			'mas_page_template',
			array(
				'label'   => esc_html__( 'Page Layout', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''                                   => esc_html__( 'Default', 'mas-elementor' ),
					PageTemplatesModule::TEMPLATE_CANVAS => esc_html__( 'Elementor Canvas', 'mas-elementor' ),
					PageTemplatesModule::TEMPLATE_HEADER_FOOTER => esc_html__( 'Elementor Full Width', 'mas-elementor' ),
				),
			)
		);

		$this->add_control(
			'mas_page_template_default_description',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Default Page Template from your theme', 'mas-elementor' ),
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
				'raw'             => esc_html__( 'No header, no footer, just Elementor', 'mas-elementor' ),
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
				'raw'             => esc_html__( 'This template includes the header, full-width content and footer', 'mas-elementor' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'mas_page_template' => PageTemplatesModule::TEMPLATE_HEADER_FOOTER,
				),
			)
		);

		$this->end_injection();
	}
}
