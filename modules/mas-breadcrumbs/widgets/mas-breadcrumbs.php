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
			'mas_breadrumb_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'flex-start',
				'options'   => array(
					'flex-start'    => array(
						'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-start-h',
					),
					'center'        => array(
						'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-center-h',
					),
					'flex-end'      => array(
						'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-end-h',
					),
					'space-between' => array(
						'title' => esc_html_x( 'Space Between', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-between-h',
					),
					'space-around'  => array(
						'title' => esc_html_x( 'Space Around', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-around-h',
					),
					'space-evenly'  => array(
						'title' => esc_html_x( 'Space Evenly', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-evenly-h',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-breadcrumb-ol' => 'justify-content: {{VALUE}}',
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
			'mas_breadrumb_border',
			array(
				'label' => esc_html__( 'Border', 'mas-elementor' ),
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
				'name'     => 'mas_breadrumb_box_shadow',
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

		$this->add_responsive_control(
			'mas_breadcrumb_li_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'mas-elementor' ),
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

		$this->end_controls_section();

		// Section for title controls in STYLE Tab.
		$this->start_controls_section(
			'mas_breadcrumb_title',
			array(
				'label' => esc_html__( 'Title', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'mas_breadcrumb_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas_breadcrumb_link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mas_breadcrumb_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas_breadcrumb_li' => 'color: {{VALUE}};',
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

		// Anchor Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_breadcrumb_link_border',
				'selector' => '{{WRAPPER}} .mas_breadcrumb_link',
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
			'delimiter'   => '&nbsp;&#47;&nbsp;',
			'wrap_before' => '<nav class="mas-breadcrumb" aria-label="breadcrumb"><ol class="mas-breadcrumb-ol">',
			'home'        => _x( 'Home', 'breadcrumb', 'mas-elementor' ),
		);
		mas_elementor_breadcrumb( $args );
	}
}
