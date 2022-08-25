<?php
/**
 * The Mas Nav Tab Widget.
 *
 * @package MASElementor/Modules/MasNavTab/Widgets
 */

namespace MASElementor\Modules\MasNavTabs\Widgets;

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
class Mas_Nav_Tabs extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-nav-tabs';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Nav Tabs', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'nav', 'tabs', 'tabs', 'mas' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'section_list',
			array(
				'label' => esc_html__( 'Nav Tabs List', 'mas-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'list',
			array(
				'label'       => esc_html__( 'Tab Title', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tab Title', 'mas-elementor' ),
				'default'     => esc_html__( 'Tab Title', 'mas-elementor' ),
			)
		);

		$repeater->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title Tag', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'    => 'H1',
					'h2'    => 'H2',
					'h3'    => 'H3',
					'h4'    => 'H4',
					'h5'    => 'H5',
					'h6'    => 'H6',
					'div'   => 'div',
					'span'  => 'span',
					'small' => 'small',
					'p'     => 'p',
				),
				'default' => 'div',
			)
		);

		$repeater->add_control(
			'list_url',
			array(
				'label'       => esc_html__( 'Link', 'mas-elementor' ),
				'default'     => array(
					'url' => esc_url( '#', 'mas-elementor' ),
				),
				'placeholder' => esc_html__( 'https://your-link.com', 'mas-elementor' ),
				'type'        => Controls_Manager::URL,
			)
		);

		$repeater->add_control(
			'content_id',
			array(
				'label'       => esc_html__( 'Tab Content ID', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tab Content Id', 'mas-elementor' ),
			)
		);

		$repeater->add_control(
			'description_text',
			array(
				'label'       => esc_html__( 'Tab Desciption', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'mas-elementor' ),
				'placeholder' => esc_html__( 'Enter your description', 'mas-elementor' ),
				'rows'        => 10,
				'separator'   => 'none',
				'show_label'  => true,
			)
		);

		$repeater->add_control(
			'tab_icon',
			array(
				'label'            => esc_html__( 'Icon', 'mas-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'default'          => array(
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				),
				'fa4compatibility' => 'icon',
			)
		);

		$this->add_control(
			'nav_tabs',
			array(
				'label'       => esc_html__( 'Nav List', 'mas-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'title'    => esc_html__( 'Featured Jobs', 'mas-elementor' ),
						'tab_icon' => array(
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						),
					),
					array(
						'title'    => esc_html__( 'Recent Jobs', 'mas-elementor' ),
						'tab_icon' => array(
							'value'   => 'fas fa-times',
							'library' => 'fa-solid',
						),
					),
				),
				'title_field' => '{{{ list }}}',
			)
		);

		$this->add_control(
			'type',
			array(
				'label'        => esc_html__( 'Position', 'mas-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'horizontal',
				'options'      => array(
					'horizontal' => esc_html__( 'Horizontal', 'mas-elementor' ),
					'vertical'   => esc_html__( 'Vertical', 'mas-elementor' ),
				),
				'prefix_class' => 'mas-elementor-nav-tab-layout-',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();

		// Section for List Controls in STYLE Tab.
		$this->start_controls_section(
			'list_section',
			array(
				'label' => esc_html__( 'UL Element', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ul_wrap',
			array(
				'label'       => esc_html__( 'UL Wrap Class', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Wrap Class', 'mas-elementor' ),
				'default'     => 'nav nav-tabs',
			)
		);

		$this->start_controls_tabs( 'tabs_ul' );

		// Spacing Tab.
		$this->start_controls_tab(
			'tab_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-elementor' ),
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_nav_tab_ul_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_nav_tab_ul_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		// Border Tab.
		$this->start_controls_tab(
			'tab_border',
			array(
				'label' => esc_html__( 'Border', 'mas-elementor' ),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_nav_tab_ul_border',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_nav_tab_ul_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_nav_tab_ul_box_shadow',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for List Item Controls in STYLE Tab.
		$this->start_controls_section(
			'list_item_section',
			array(
				'label' => esc_html__( 'LI Element', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'li_wrap',
			array(
				'label'       => esc_html__( 'LI Wrap Class', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your li Wrap Class', 'mas-elementor' ),
				'default'     => 'nav-item',
			)
		);

		$this->start_controls_tabs( 'tabs_li' );

		// LI Spacing Tab.
		$this->start_controls_tab(
			'tab_li_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-elementor' ),
			)
		);

		// LI Padding Controls.
		$this->add_responsive_control(
			'mas_nav_tab_li_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab-li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// LI Margin Controls.
		$this->add_responsive_control(
			'mas_nav_tab_li_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab-li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_li_Border',
			array(
				'label' => esc_html__( 'Border', 'mas-elementor' ),
			)
		);

		// LI Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_nav_tab_li_border',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab-li',
			)
		);

		// LI Border Radius Controls.
		$this->add_responsive_control(
			'mas_nav_tab_li_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab-li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// LI Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_nav_tab_li_box_shadow',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab-li',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for Anchor or Button element Controls in STYLE Tab.
		$this->start_controls_section(
			'anchor_element_section',
			array(
				'label' => esc_html__( 'Anchor Element', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'anchor_wrap',
			array(
				'label'       => esc_html__( 'Anchor Class', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Anchor Class', 'mas-elementor' ),
				'default'     => 'nav-link',
			)
		);

		$this->start_controls_tabs( 'tabs_anchor' );

		// Anchor Spacing Tab.
		$this->start_controls_tab(
			'tab_anchor_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		// Anchor Padding Controls.
		$this->add_responsive_control(
			'mas_nav_link_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Anchor Margin Controls.
		$this->add_responsive_control(
			'mas_nav_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-nav-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_nav_link_border',
				'selector' => '{{WRAPPER}} .mas-nav-link',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_nav_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-nav-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_nav_link_box_shadow',
				'selector' => '{{WRAPPER}} .mas-nav-link',
			)
		);

		// Background Controls.
		$this->add_control(
			'mas_nav_link_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-nav-link' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'normal_anchor_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-nav-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Active Anchor Spacing Tab.
		$this->start_controls_tab(
			'tab_anchor_active',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		// Active Anchor Padding Controls.
		$this->add_responsive_control(
			'mas_nav_link_active_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-nav-link.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Active Anchor Margin Controls.
		$this->add_responsive_control(
			'mas_nav_link_active_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-nav-link.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Active Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_nav_link_active_border',
				'selector' => '{{WRAPPER}} .mas-nav-link.active',
			)
		);

		// Active Border Radius Controls.
		$this->add_responsive_control(
			'mas_nav_link_active_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-nav-link.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_nav_linkactive__box_shadow',
				'selector' => '{{WRAPPER}} .mas-nav-link.active',
			)
		);

		// Background Controls.
		$this->add_control(
			'mas_nav_link_active_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-nav-link.active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'active_title_color',
			array(
				'label'     => esc_html__( 'Active Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-nav-link.active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for Icon Controls in STYLE Tab.
		$this->start_controls_section(
			'tab_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-tab-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mas-tab-icon svg' => 'fill: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => esc_html__( 'Size', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 14,
				),
				'range'     => array(
					'px' => array(
						'min' => 6,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--e-icon-tab-icon-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$e_icon_tab_icon_css_var = 'var(--e-icon-tab-icon-size, 1em)';

		$this->start_controls_tabs( 'tabs_icon' );

		// ICON Spacing Tab.
		$this->start_controls_tab(
			'tab_icon_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-elementor' ),
			)
		);

		// Icon Padding Controls.
		$this->add_responsive_control(
			'mas_icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-tab-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Icon Margin Controls.
		$this->add_responsive_control(
			'mas_icon_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-tab-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		// ICON Border Tab.
		$this->start_controls_tab(
			'tab_icon_border',
			array(
				'label' => esc_html__( 'Border', 'mas-elementor' ),
			)
		);

		// Icon Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_icon_border',
				'selector' => '{{WRAPPER}} .mas-tab-icon',
			)
		);

		// Icon Border Radius Controls.
		$this->add_responsive_control(
			'mas_icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-tab-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Icon Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_icon_box_shadow',
				'selector' => '{{WRAPPER}} .mas-tab-icon',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Title Section in Style TAB.
		$this->start_controls_section(
			'tab_title_style',
			array(
				'label' => esc_html__( 'Title', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_class',
			array(
				'label' => esc_html__( 'Title Class', 'mas-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		// Title Color Controls.
		$this->add_control(
			'normal_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-tab .mas_tab_title' => 'color: {{VALUE}};',
				),
			)
		);

		// Title Typography Controls.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab .mas_tab_title',

			)
		);

		// Title Background Color Controls.
		$this->add_control(
			'normal_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-tab .mas_tab_title' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Title Padding Controls.
		$this->add_responsive_control(
			'mas_nav_tab_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_tab_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Title Margin Controls.
		$this->add_responsive_control(
			'mas_nav_tab_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_tab_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Description Section in Style TAB.
		$this->start_controls_section(
			'tab_description_style',
			array(
				'label' => esc_html__( 'Description', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Description Color Controls.
		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Description Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-tab-description' => 'color: {{VALUE}};',
				),
			)
		);

		// Description Typography Controls.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .mas-tab-description',

			)
		);

		// Description Padding Controls.
		$this->add_responsive_control(
			'mas_nav_tab_description_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-tab-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Description Margin Controls.
		$this->add_responsive_control(
			'mas_nav_tab_description_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-tab-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Renders the Nav Tabs widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$list_id  = uniqid( 'tabs-' );

		$fallback_defaults = array(
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		);

		$this->add_render_attribute(
			'list',
			array(
				'class' => array( 'mas-elementor-nav-tab', $settings['ul_wrap'] ),
				'role'  => 'tablist',
				'id'    => $list_id,
			)
		);

		$this->add_render_attribute( 'description_text', 'class', 'mas-tab-description' );
		$this->add_inline_editing_attributes( 'description_text' );

		?>
		<ul <?php $this->print_render_attribute_string( 'list' ); ?>>
			<?php
			foreach ( $settings['nav_tabs'] as $index => $item ) :
				$count    = $index + 1;
				$active   = '';
				$selected = 'false';

				$heading_tag = $item['title_tag'];

				$title_class = array( 'mas_tab_title' );
				if ( $settings['heading_class'] ) {
					$title_class[] = $settings['heading_class'];
				}

				if ( 1 === $count ) {
					$this->add_render_attribute( 'heading_class', 'class', $title_class );
				}
				$migration_allowed = Icons_Manager::is_migration_allowed();

				$this->add_render_attribute(
					'list_item' . $count,
					array(
						'class' => array( 'mas-elementor-nav-tab-li', $settings['li_wrap'] ),
						'role'  => 'presentation',
					)
				);

				if ( 1 === $count ) {
					$active   = 'active';
					$selected = 'true';
					$this->add_render_attribute( 'list_link_item' . $count, 'class' );
				}

				if ( isset( $item['list_url']['url'] ) ) {
					$this->add_link_attributes( 'list_link_item' . $count, $item['list_url'] );

				}

				$this->add_render_attribute(
					'list_link_item' . $count,
					array(
						'class'          => array( 'mas-nav-link', $active, $settings['anchor_wrap'] ),
						'id'             => 'mas-' . $item['content_id'],
						'data-bs-toggle' => 'tab',
						'data-bs-target' => '#' . $item['content_id'],
						'role'           => 'tab',
						'aria-controls'  => $item['content_id'],
						'aria-selected'  => $selected,
					)
				);

				if ( 1 === $count ) {
					$active   = 'active';
					$selected = 'true';
					$this->add_render_attribute( 'list_item' . $count, 'class' );
				}

				?>
				<li <?php $this->print_render_attribute_string( 'list_item' . $count ); ?>>
					<?php if ( isset( $item['list_url']['url'] ) ) : ?>
						<a  <?php $this->print_render_attribute_string( 'list_link_item' . $count ); ?>>
							<div class="mas-tab-flex">
								<?php
								// add old default.
								if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
									$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
								}

								$migrated = isset( $item['__fa4_migrated']['tab_icon'] );
								$is_new   = ! isset( $item['icon'] ) && $migration_allowed;
								if ( ! empty( $item['icon'] ) || ( ! empty( $item['tab_icon']['value'] ) && $is_new ) ) :
									?>
									<span class="mas-tab-icon">
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $item['tab_icon'], array( 'aria-hidden' => 'true' ) );
										} else {
											?>
												<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
										<?php } ?>
									</span>
								<?php endif; ?>
								<div class="mas-tab-flex-grow">
									<<?php echo esc_html( $heading_tag ); ?> <?php $this->print_render_attribute_string( 'heading_class' ); ?>>
										<?php echo esc_html( $item['list'] ); ?>
									</<?php echo esc_html( $heading_tag ); ?>>
									<?php if ( ! empty( $item['description_text'] ) ) : ?>
										<p <?php $this->print_render_attribute_string( 'description_text' ); ?>><?php echo esc_html( $item['description_text'] ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						</a>
					<?php endif; ?>
				</li>
				<?php
			endforeach;
			?>
		</ul>
		<?php
	}
}
