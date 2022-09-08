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
		return 'mas-breadcrumb-ols';
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
		// Section for OL Wrap Controls in STYLE Tab.
		$this->start_controls_section(
			'ol_section',
			array(
				'label' => esc_html__( 'OL Element', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .mas-breadcrumb-ol' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mas-breadcrumb-ol' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .mas-breadcrumb-ol',
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
					'{{WRAPPER}} .mas-breadcrumb-ol' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_nav_tab_ul_box_shadow',
				'selector' => '{{WRAPPER}} .mas-breadcrumb-ol',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for List Item Controls in STYLE Tab.
		$this->start_controls_section(
			'mas_breadcrumb_list_item_section',
			array(
				'label' => esc_html__( 'LI Element', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'mas_breadcrumb_li' );

		// LI Spacing Tab.
		$this->start_controls_tab(
			'mas_breadcrumb_li_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-elementor' ),
			)
		);

		// LI Padding Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_li_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_li:not(:last-child)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// LI Margin Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_li_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_li:not(:last-child)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mas_breadcrumb_li_Border',
			array(
				'label' => esc_html__( 'Border', 'mas-elementor' ),
			)
		);

		// LI Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_breadcrumb_li_border',
				'selector' => '{{WRAPPER}} .mas_breadcrumb_li:not(:last-child)',
			)
		);

		// LI Border Radius Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_li_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// LI Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_breadcrumb_li_box_shadow',
				'selector' => '{{WRAPPER}} .mas_breadcrumb_li',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for Anchor element Controls in STYLE Tab.
		$this->start_controls_section(
			'anchor_element_section',
			array(
				'label' => esc_html__( 'Anchor Element', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_anchor' );

		// Anchor Spacing Tab.
		$this->start_controls_tab(
			'mas_breadcrumb_anchor_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		// Anchor Padding Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_link_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Anchor Margin Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_breadcrumb_link_border',
				'selector' => '{{WRAPPER}} .mas_breadcrumb_link',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_breadcrumb_link_box_shadow',
				'selector' => '{{WRAPPER}} .mas_breadcrumb_link',
			)
		);

		// Background Controls.
		$this->add_control(
			'mas_breadcrumb_link_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas_breadcrumb_link' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'normal_anchor_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas_breadcrumb_link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Active Anchor Spacing Tab.
		$this->start_controls_tab(
			'mas_breadcrumb_anchor_active',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		// Active Anchor Padding Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_link_active_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_link.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Active Anchor Margin Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_link_active_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_link.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Active Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_breadcrumb_link_active_border',
				'selector' => '{{WRAPPER}} .mas_breadcrumb_link.active',
			)
		);

		// Active Border Radius Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_link_active_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_link.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_breadcrumb_link_active__box_shadow',
				'selector' => '{{WRAPPER}} .mas_breadcrumb_link.active',
			)
		);

		// Background Controls.
		$this->add_control(
			'mas_breadcrumb_link_active_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas_breadcrumb_link.active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mas_breadcrumb_active_title_color',
			array(
				'label'     => esc_html__( 'Active Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas_breadcrumb_link.active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render the widget.
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$args     = array(
			'delimiter'   => '',
			'wrap_before' => '<nav class="mas-breadcrumb" aria-label="breadcrumb"><ol class="mas-breadcrumb-ol">',
		);
		mas_elementor_breadcrumb( $args );
	}
}
