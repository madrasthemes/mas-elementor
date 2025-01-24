<?php
/**
 * Header Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Header\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Plugin;
use MASElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Header Widget.
 */
class Header extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-header';
	}

	/**
	 * Get the name of the title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Header', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the name of the icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-header';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'header', 'quote', 'paragraph', 'testimonial', 'text', 'twitter', 'tweet' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_header_content',
			array(
				'label' => esc_html__( 'Header', 'mas-addons-for-elementor' ),
			)
		);

		$slug_options = function_exists( 'mas_template_slug_options' ) ? mas_template_slug_options( 'mas-header' ) : array();
		$this->add_control(
			'slug_select_template',
			array(
				'label'   => esc_html__( 'MAS Headers', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $slug_options,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['slug_select_template'] ) ) {
			$default_template = get_page_by_path( $settings['slug_select_template'], OBJECT, 'elementor_library' );
			if ( ! empty( $default_template->ID ) ) {
				print( mas_render_template( $default_template->ID, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

	}
}
