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
use Elementor\Modules\Library\Documents\Page;



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
				'label'   => esc_html__( 'Template Layout', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => PageTemplatesModule::TEMPLATE_HEADER_FOOTER,
				'options' => array(
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
