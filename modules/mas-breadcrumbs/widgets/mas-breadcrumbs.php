<?php
/**
 * The MAS Breadcrumb Widget.
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
		return esc_html__( 'MAS Breadcrumb', 'mas-addons-for-elementor' );
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
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elements' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'mas-breadcrumb-stylesheet' );
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
				'label' => esc_html__( 'List Items', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'mas_breadrumb_alignment',
			array(
				'label'           => esc_html__( 'Horizontal Alignment', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::SELECT,
				'devices'         => array( 'desktop', 'tablet', 'mobile' ),
				'desktop_default' => 'flex-start',
				'tablet_default'  => 'flex-start',
				'mobile_default'  => 'flex-start',
				'options'         => array(
					'flex-start'    => array(
						'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-start-h',
					),
					'center'        => array(
						'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-center-h',
					),
					'flex-end'      => array(
						'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-end-h',
					),
					'space-between' => array(
						'title' => esc_html_x( 'Space Between', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-between-h',
					),
					'space-around'  => array(
						'title' => esc_html_x( 'Space Around', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-around-h',
					),
					'space-evenly'  => array(
						'title' => esc_html_x( 'Space Evenly', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-evenly-h',
					),
				),
				'selectors'       => array(
					'{{WRAPPER}} .mas-breadcrumb-ol' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'mas_breadrumb_vertical_alignment',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => array(
					'start'  => array(
						'title' => esc_html__( 'Top', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'Bottom', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-breadcrumb-ol' => 'align-items: {{VALUE}}',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_breadrumb_box_shadow',
				'selector' => '{{WRAPPER}} .mas-breadcrumb-ol',
			)
		);

		$this->start_controls_tabs( 'breadcrumb_nav' );

		// Spacing Tab.
		$this->start_controls_tab(
			'breadcrumb_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
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
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
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
			'mas_breadrumb_border',
			array(
				'label' => esc_html__( 'Border', 'mas-addons-for-elementor' ),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_breadcrumb_border',
				'selector' => '{{WRAPPER}} .mas-breadcrumb-ol',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-breadcrumb-ol' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for List Item Controls in STYLE Tab.
		$this->start_controls_section(
			'mas_breadcrumb_list_item_section',
			array(
				'label' => esc_html__( 'List Item', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'mas_breadcrumb_li_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--mas_breadcrumb_li: {{SIZE}}{{UNIT}};',
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

		$this->start_controls_tabs( 'breadcrumb_li_item' );

		// Spacing Tab.
		$this->start_controls_tab(
			'breadcrumb_li_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'mas_breadcrumb_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mas_breadcrumb_item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'breadcrumb_li_border',
			array(
				'label' => esc_html__( 'Border', 'mas-addons-for-elementor' ),
			)
		);

		// LI Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_breadcrumb_li_border',
				'selector' => '{{WRAPPER}} .mas_breadcrumb_li',
			)
		);

		// LI Border Radius Controls.
		$this->add_responsive_control(
			'mas_breadcrumb_li_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas_breadcrumb_li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for title controls in STYLE Tab.
		$this->start_controls_section(
			'mas_breadcrumb_title',
			array(
				'label' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'mas_breadcrumb_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas_breadcrumb_li' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mas_breadcrumb_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas_breadcrumb_link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mas_breadcrumb_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .mas_breadcrumb_li',
			)
		);

		$this->start_controls_tabs( 'breadcrumb_title' );

		// Spacing Tab.
		$this->start_controls_tab(
			'breadcrumb_title_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'mas_breadcrumb_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} li:last-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mas_breadcrumb_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} li:last-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'breadcrumb_title_border',
			array(
				'label' => esc_html__( 'Border', 'mas-addons-for-elementor' ),
			)
		);

		// Anchor Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_breadcrumb_link_border',
				'selector' => '{{WRAPPER}} li:last-child',
			)
		);

		$this->add_responsive_control(
			'mas_breadcrumb_title_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} li:last-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for title controls in STYLE Tab.
		$this->start_controls_section(
			'mas_breadcrumb_separator',
			array(
				'label' => esc_html__( 'Separator', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'mas_separator_before',
			array(
				'label'       => esc_html__( 'Delimiter', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '>', 'mas-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Enter Delimiter', 'mas-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'mas_breadcrumb_separator_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-breadcrumb-ol' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mas_breadcrumb_separator_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .mas-breadcrumb-ol',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget.
	 */
	protected function render() {

		$settings  = $this->get_settings_for_display();
		$delimiter = $settings['mas_separator_before'];
		$args      = array(
			'delimiter'   => $delimiter,
			'wrap_before' => '<nav class="mas-breadcrumb" aria-label="breadcrumb"><ol class="mas-breadcrumb-ol">',
			'home'        => _x( 'Home', 'breadcrumb', 'mas-addons-for-elementor' ),
		);
		mas_elementor_breadcrumb( $args );
	}
}
