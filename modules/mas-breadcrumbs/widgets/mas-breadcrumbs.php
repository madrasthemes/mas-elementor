<?php
/**
 * The Mas Breadcrumb Widget.
 *
 * @package MASElementor/Modules/MasBreadcrumb/Widgets
 */

namespace MASElementor\Modules\MasBreadcrumbs\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use MASElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use MASElementor\Core\Controls_Manager as MAS_Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Nav Tabs Elementor Widget.
 */
class Mas_Breadcrumbs extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-breadcrumbs';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Breadcrumb', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-breadcrumbs';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'breadcrumb', 'nav', 'navigation', 'mas' );
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_header',
			array(
				'label' => esc_html__( 'Section', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'nav_class',
			array(
				'label'   => esc_html__( 'Nav Class', 'mas-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'container py-4 mb-2 my-lg-3 ', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'ol_class',
			array(
				'label' => esc_html__( 'Ordered List Class', 'mas-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget.
	 */
	protected function render() {

		$settings  = $this->get_settings_for_display();
		$nav_class = 'container pt-4 mt-lg-3';
		if ( ! empty( $settings ) ) {
			$nav_class = $settings['nav_class'];
		}

		$ol_class = ! empty( $settings['ol_class'] ) ? ' ' . $settings['ol_class'] : '';

		$args = array(
			'wrap_before' => '<nav aria-label="breadcrumb" class="' . $nav_class . '"><ol class="breadcrumb mb-0' . $ol_class . '">',
		);
		mas_elementor_breadcrumb( $args );
	}
}
