<?php
namespace MASElementor\Modules\JobsFilter\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use MASElementor\Base\Base_Widget;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use MASElementor\Modules\JobsFilter\Skins;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Hivepress Advanced mas-elementor Elementor Widget.
 */
class Jobs_Filter extends Base_Widget {

	protected $_has_template_content = false;

    /**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elements' );
	}

    
    /**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-jobs-filter';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Jobs Filter', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-filter';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'jobs', 'attributes', 'attribute'];
	}

	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_keyword',
			array(
				'label' => esc_html__( 'Keyword', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'keyword_placeholder',
			array(
				'label'       => esc_html__( 'Keyword Placeholder', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default' => esc_html__( 'Job title, keywords or company name', 'mas-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'job_title_icon',
			[
				'label' => esc_html__( 'Keyword Icon', 'mas-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'far fa-keyboard',
					'library' => 'fa-solid',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_location',
			array(
				'label' => esc_html__( 'Location', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'location_placeholder',
			array(
				'label'       => esc_html__( 'Location Placeholder', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default' => esc_html__( 'City, province or region', 'mas-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'location_icon',
			[
				'label' => esc_html__( 'Location Icon', 'mas-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-map-marker-alt',
					'library' => 'fa-solid',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_category',
			array(
				'label' => esc_html__( 'Category', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'show_category',
			array(
				'label'     => esc_html__( 'Show Category', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'default'   => 'no',
			)
		);

		$this->add_control(
			'category_position',
			array(
				'label'     => esc_html__( 'Position', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '3',
				'options'   => array(
					'2'     => esc_html__( '2', 'mas-elementor' ),
					'3'    => esc_html__( '3', 'mas-elementor' ),
				),
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_placeholder',
			array(
				'label'       => esc_html__( 'Category Placeholder', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default' => esc_html__( 'Any Category', 'mas-elementor' ),
				'label_block' => true,
				'condition' => [
					'show_category' => 'yes',
				],
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_search',
			array(
				'label' => esc_html__( 'Search', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'search_text',
			array(
				'label'       => esc_html__( 'Search Text', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default' => esc_html__( 'Search', 'mas-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'search_icon',
			[
				'label' => esc_html__( 'Search Icon', 'mas-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_form_style', [
				'label' => esc_html__( 'Form', 'mas-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mas-search-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'form_border',
			[
				'label' => esc_html__( 'Border Type', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mas-elementor' ),
					'solid' => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mas-search-form' => 'border-style: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'form_border_width',
			[
				'label' => esc_html__( 'Width', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mas-search-form' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'form_border!' => '',
				],
			]
		);

		$this->add_control(
			'form_border_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-search-form' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'form_border!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'form_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-search-form' => 'justify-content: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_keyword_style', [
				'label' => esc_html__( 'Keyword', 'mas-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'keyword_size',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
					'px' => [
						'min' => 0,
						'max' => 800,
						'step' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-keywords input' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'keyword_background',
			[
				'label' => esc_html__( 'Background Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'keyword_placeholder_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .mas-job-search-keywords input::placeholder',
			]
		);

		$this->add_control(
			'keyword_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-keywords input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'keyword_icon_color', [
				'label'     => esc_html__( 'Icon Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-keywords i' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'keyword_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'mas-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-keywords i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'keyword_padding', [
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
        	] 
		);

		$this->add_responsive_control(
			'keyword_margin', [
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .mas-job-search-keywords' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
        	] 
		);

		$this->start_controls_tabs( 'form_keyword_border' );
		$this->start_controls_tab(
			'keyword_border_normal',
			[
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'keyword_border',
			[
				'label' => esc_html__( 'Border Type', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mas-elementor' ),
					'solid' => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'border-style: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'keyword_border_width',
			[
				'label' => esc_html__( 'Width', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'keyword_border!' => '',
				],
			]
		);

		$this->add_control(
			'keyword_border_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'keyword_border!' => '',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'keyword_border_hover',
			[
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'keyword_hover_border',
			[
				'label' => esc_html__( 'Border Type', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mas-elementor' ),
					'solid' => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-keywords:hover' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'keyword_hover_border_width',
			[
				'label' => esc_html__( 'Width', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-keywords:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'keyword_hover_border!' => '',
				],
			]
		);

		$this->add_control(
			'keyword_hover_border_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-keywords:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'keyword_hover_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'keyword_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'keyword_box_shadow',
				'selector' => '{{WRAPPER}} .mas-elementor-search-keywords',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_location_style', [
				'label' => esc_html__( 'Location', 'mas-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'location_size',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
					'px' => [
						'min' => 0,
						'max' => 800,
						'step' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-location input' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'location_background',
			[
				'label' => esc_html__( 'Background Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-location' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'location_placeholder_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .mas-job-search-location input::placeholder',
			]
		);

		$this->add_control(
			'location_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-location input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'location_icon_color', [
				'label'     => esc_html__( 'Icon Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-location i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'location_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-location i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'location_padding', [
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .mas-elementor-search-location' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
        	] 
		);

		$this->add_responsive_control(
			'location_margin', [
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .mas-job-search-location' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
        	] 
		);


		$this->start_controls_tabs( 'form_location_border' );
		$this->start_controls_tab(
			'location_border_normal',
			[
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'location_border',
			[
				'label' => esc_html__( 'Border Type', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mas-elementor' ),
					'solid' => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-location' => 'border-style: {{VALUE}} !important;;',
				],
			]
		);

		$this->add_control(
			'location_border_width',
			[
				'label' => esc_html__( 'Width', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-location' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;;',
				],
				'condition' => [
					'location_border!' => '',
				],
			]
		);

		$this->add_control(
			'location_border_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-location' => 'border-color: {{VALUE}} !important;;',
				],
				'condition' => [
					'location_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'location_border_hover',
			[
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'location_hover_border',
			[
				'label' => esc_html__( 'Border Type', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mas-elementor' ),
					'solid' => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-location:hover' => 'border-style: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'location_hover_border_width',
			[
				'label' => esc_html__( 'Width', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-location:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'location_hover_border!' => '',
				],
			]
		);

		$this->add_control(
			'location_hover_border_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-location:hover' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'location_hover_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'location_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-search-location' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'location_box_shadow',
				'selector' => '{{WRAPPER}} .mas-elementor-search-location',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_category_style', [
				'label' => esc_html__( 'Category', 'mas-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'category_size',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
					'px' => [
						'min' => 0,
						'max' => 800,
						'step' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_background',
			[
				'label' => esc_html__( 'Background Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category select' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'category_placeholder_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .mas-job-search-category #search_category',
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category #search_category' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'category_padding', [
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .mas-job-search-category select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
        	] 
		);

		$this->add_responsive_control(
			'category_margin', [
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .mas-job-search-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
        	] 
		);

		$this->start_controls_tabs( 'form_category_border' );
		$this->start_controls_tab(
			'category_border_normal',
			[
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_border',
			[
				'label' => esc_html__( 'Border Type', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mas-elementor' ),
					'solid' => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category select' => 'border-style: {{VALUE}} !important;',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_border_width',
			[
				'label' => esc_html__( 'Width', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'category_border!' => '',
					'show_category'    => 'yes',
				],
			]
		);

		$this->add_control(
			'category_border_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category select' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'category_border!' => '',
					'show_category'    => 'yes',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'category_border_hover',
			[
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_hover_border',
			[
				'label' => esc_html__( 'Border Type', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mas-elementor' ),
					'solid' => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category select:hover' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_hover_border_width',
			[
				'label' => esc_html__( 'Width', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category select:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'category_hover_border!' => '',
					'show_category'          => 'yes',
				],
			]
		);

		$this->add_control(
			'category_hover_border_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category select:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'category_hover_border!' => '',
					'show_category'          => 'yes',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'category_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-category select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'category_box_shadow',
				'selector' => '{{WRAPPER}} .mas-job-search-category',
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_search_button_style', [
				'label' => esc_html__( 'Search', 'mas-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'search_size',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
					'px' => [
						'min' => 0,
						'max' => 800,
						'step' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'search_button' );
		$this->start_controls_tab(
			'search_button_normal',
			[
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'search_button_background',
			[
				'label' => esc_html__( 'Background Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'search_button_text_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .mas-job-search-submit button span' => 'color: {{VALUE}} !important;',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->start_controls_tab(
			'search_button_hover',
			[
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'search_button_background_hover',
			[
				'label' => esc_html__( 'Background Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'search_button_text_color_hover',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button:hover .mas-job-search-icon' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .mas-job-search-submit button:hover .mas-job-search-text' => 'color: {{VALUE}} !important;',

				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'search_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'mas-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-icon ' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => array(
					'search_icon[value]!' => '',
				),
			]
		);

		$this->add_control(
			'search_icon_align',
			[
				'label'     => esc_html__( 'Icon Position', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => esc_html__( 'Before', 'mas-elementor' ),
					'right' => esc_html__( 'After', 'mas-elementor' ),
				],
				'condition'   => array(
					'search_icon[value]!' => '',
					'search_text!' => '',
				),
			]
		);
		
		$this->add_control(
			'icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'mas-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit .mas-job-search-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mas-job-search-submit .mas-job-search-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition'   => array(
					'search_icon[value]!' => '',
					'search_text!' => '',
				),
			]
		);
		

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .mas-job-search-submit button span',
				'condition'   => array(
					'search_text!' => '',
				),
			]
		);

		$this->add_responsive_control(
			'search_button_padding', [
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .mas-job-search-submit button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
        	] 
		);

		$this->add_responsive_control(
			'search_button_margin', [
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .mas-job-search-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
        	] 
		);

		$this->start_controls_tabs( 'form_search_border' );
		$this->start_controls_tab(
			'search_border_normal',
			[
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'search_border',
			[
				'label' => esc_html__( 'Border Type', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mas-elementor' ),
					'solid' => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_border_width',
			[
				'label' => esc_html__( 'Width', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'search_border!' => '',
				],
			]
		);

		$this->add_control(
			'search_border_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'search_border!' => '',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'search_border_hover',
			[
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'search_hover_border',
			[
				'label' => esc_html__( 'Border Type', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'mas-elementor' ),
					'solid' => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button:hover' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_hover_border_width',
			[
				'label' => esc_html__( 'Width', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'search_hover_border!' => '',
				],
			]
		);

		$this->add_control(
			'search_hover_border_color',
			[
				'label' => esc_html__( 'Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'search_hover_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'search_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mas-job-search-submit button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'search_box_shadow',
				'selector' => '{{WRAPPER}} .mas-job-search-submit button',
			]
		);
	}

	 /**
     * Render.
     */
    public function render(){
        $jobs_page_url = get_page_link( get_option( 'job_manager_jobs_page_id' ) );
        $settings  = $this->get_settings_for_display();

        $this->add_render_attribute( 
			'icon-align',
			'class', 
            [
                'mas-job-search-icon',
                'mas-job-search-icon-' . $settings['search_icon_align'],
                $settings['search_icon']['value']
            ]
		);

        ?>
        <form method="GET" action="<?php echo esc_url( $jobs_page_url ); ?>" class="mas-search-form" style="display:flex;">
            <div class="mas-job-search-keywords">
                <label class="sr-only" for="search_keywords"><?php echo esc_html( 'keyword' ); ?></label>
                <input type="text" id="search_keywords" name="search_keywords" placeholder="<?php echo esc_html( $settings['keyword_placeholder'] ); ?>" class="mas-elementor-search-keywords ui-autocomplete-input" autocomplete="off">
                <?php if( ! empty( $settings['job_title_icon']['value'] ) ):
                    ?><i class="<?php echo esc_attr( $settings['job_title_icon']['value'] ); ?>"></i>
                <?php endif;?>
            </div>
            <?php
            if( 'yes' === $settings['show_category'] && '2' === $settings['category_position'] ):?>
                <div class="mas-job-search-category">
                    <label class="sr-only" for="search_category"><?php echo esc_html__( 'Category', 'mas-elementor' ); ?></label>
                    <select id="search_category" name="search_category">
                        <option value=""><?php echo esc_html( $settings['category_placeholder'] ); ?></option>
                        <?php foreach ( get_job_listing_categories() as $cat ) : ?>
                        <option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif;?>
            <div class="mas-job-search-location">
                <label class="sr-only" for="search_location"><?php echo esc_html( 'location'); ?></label>
                <input type="text" id="search_location" name="search_location" placeholder="<?php echo esc_html( $settings['location_placeholder'] ); ?>" class="mas-elementor-search-location pac-target-input">
                <?php if( ! empty( $settings['location_icon']['value'] ) ):
                    ?><i class="<?php echo esc_attr( $settings['location_icon']['value'] ); ?>"></i>
                <?php endif;?>
            </div>
            <?php
            if( 'yes' === $settings['show_category'] && '3' === $settings['category_position'] ):?>
                <div class="mas-job-search-category">
                    <label class="sr-only" for="search_category"><?php echo esc_html__( 'Category', 'mas-elementor' ); ?></label>
                    <select id="search_category" name="search_category">
                        <option value=""><?php echo esc_html( $settings['category_placeholder'] ); ?></option>
                        <?php foreach ( get_job_listing_categories() as $cat ) : ?>
                        <option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif;?>
            <div class="mas-job-search-submit job-search-submit">
                <button type="submit" value="Search">
                <?php if( ! empty( $settings['search_icon']['value'] ) ):
                    ?>
                    <i <?php $this->print_render_attribute_string( 'icon-align' ); ?>></i>
                <?php endif;?>
                <?php if( ! empty( $settings['search_text'] ) ):
                    ?><span class="mas-job-search-text"><?php echo esc_html( $settings['search_text'] ); ?></span>
                <?php endif;?>
                </button>
            </div>
            <input type="hidden" name="post_type" value="job_listing">
        </form><?php
    }

}