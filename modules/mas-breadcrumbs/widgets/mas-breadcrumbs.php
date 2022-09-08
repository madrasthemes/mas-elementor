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
		// Section for Nav Wrap Controls in STYLE Tab.
		$this->start_controls_section(
			'nav_section',
			array(
				'label' => esc_html__( 'NAV Element', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'breadcrumb_nav' );

		// Spacing Tab.
		$this->start_controls_tab(
			'breadcrumb_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-elementor' ),
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-breadcrumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-breadcrumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		// Border Tab.
		$this->start_controls_tab(
			'breadrumb_border',
			array(
				'label' => esc_html__( 'Border', 'mas-elementor' ),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_breadcrumb_nav_border',
				'selector' => '{{WRAPPER}} .mas-breadcrumb',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_nav_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-breadcrumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_nav_tab_ul_box_shadow',
				'selector' => '{{WRAPPER}} .mas-breadcrumb',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-breadcrumb' => 'color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .mas-breadcrumb',
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-breadcrumb' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget.
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$args     = array(
			'delimiter'   => '',
			'wrap_before' => '<nav class="mas-breadcrumb" aria-label="breadcrumb"><ol>',
		);
		mas_elementor_breadcrumb( $args );
	}
}
