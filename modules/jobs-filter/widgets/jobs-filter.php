<?php
/**
 * Jobs Filter Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\JobsFilter\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use MASElementor\Base\Base_Widget;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Hivepress Advanced mas-elementor Elementor Widget.
 */
class Jobs_Filter extends Base_Widget {

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
		return esc_html__( 'Jobs Filter', 'mas-addons-for-elementor' );
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
		return array( 'jobs', 'attributes', 'attribute' );
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mas-jobs-filter-scripts', 'jquery-ui-autocomplete', 'mas-jobs-jquery-geocomplete', 'mas-jobs-google-maps' );
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
				'label' => esc_html__( 'Keyword', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'keyword_placeholder',
			array(
				'label'       => esc_html__( 'Keyword Placeholder', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Job title, keywords or company name', 'mas-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'job_title_icon',
			array(
				'label'            => esc_html__( 'Keyword Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'far fa-keyboard',
					'library' => 'fa-solid',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_location',
			array(
				'label' => esc_html__( 'Location', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'location_placeholder',
			array(
				'label'       => esc_html__( 'Location Placeholder', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'City, province or region', 'mas-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'location_icon',
			array(
				'label'            => esc_html__( 'Location Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-map-marker-alt',
					'library' => 'fa-solid',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_category',
			array(
				'label' => esc_html__( 'Category', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_category',
			array(
				'label'     => esc_html__( 'Show Category', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'no',
			)
		);

		$this->add_control(
			'category_position',
			array(
				'label'     => esc_html__( 'Position', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '3',
				'options'   => array(
					'2' => esc_html__( '2', 'mas-addons-for-elementor' ),
					'3' => esc_html__( '3', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_placeholder',
			array(
				'label'       => esc_html__( 'Category Placeholder', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Any Category', 'mas-addons-for-elementor' ),
				'label_block' => true,
				'condition'   => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_search',
			array(
				'label' => esc_html__( 'Search', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'search_text',
			array(
				'label'       => esc_html__( 'Search Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Search', 'mas-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_responsive_control(
			'hide_search_text_responsive',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Text Responsive', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Hidden', 'mas-addons-for-elementor' ),
				'return_value' => 'hide',
				'prefix_class' => 'mas-job-filter-search-text%s-',
				'condition'    => array(
					'search_text!' => '',
				),
			)
		);

		$this->add_control(
			'search_icon',
			array(
				'label'            => esc_html__( 'Search Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-search',
					'library' => 'fa-solid',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_form_style',
			array(
				'label' => esc_html__( 'Form', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'enable_flex_wrap_responsive',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Flex Wrap', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Wrap', 'mas-addons-for-elementor' ),
				'return_value' => 'wrap',
				'prefix_class' => 'mas-job-filter-flex%s-',
			)
		);

		$this->add_responsive_control(
			'form_wrap_align',
			array(
				'label'     => esc_html__( 'Form Wrap Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-form' => 'display:flex; justify-content: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'form_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-search-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'form_border',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-search-form' => 'border-style: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'form_border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .mas-search-form' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'condition' => array(
					'form_border!' => '',
				),
			)
		);

		$this->add_control(
			'form_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-search-form' => 'border-color: {{VALUE}} !important;',
				),
				'condition' => array(
					'form_border!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'form_align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-search-form' => 'justify-content: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_keyword_style',
			array(
				'label' => esc_html__( 'Keyword', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'keyword_size',
			array(
				'label'          => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 10,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 800,
						'step' => 50,
					),
				),
				'default'        => array(
					'unit' => 'px',
					'size' => 500,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'selectors'      => array(
					'{{WRAPPER}} .mas-job-search-keywords input' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'keyword_wrap_height',
			array(
				'label'      => esc_html__( 'Keyword Wrapper Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min' => 0,
						'max' => 10000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-keywords' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'keyword_height',
			array(
				'label'      => esc_html__( 'Keyword Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min' => 0,
						'max' => 10000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-keywords input' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'keyword_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'keyword_placeholder_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .mas-job-search-keywords input::placeholder',
			)
		);

		$this->add_control(
			'keyword_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-keywords input::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'keyword_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-keywords i' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'keyword_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-keywords i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'keyword_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'keyword_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-keywords' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'form_keyword_border' );
		$this->start_controls_tab(
			'keyword_border_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'keyword_border',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'border-style: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'keyword_border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'condition' => array(
					'keyword_border!' => '',
				),
			)
		);

		$this->add_control(
			'keyword_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'border-color: {{VALUE}} !important;',
				),
				'condition' => array(
					'keyword_border!' => '',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'keyword_border_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'keyword_hover_border',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-keywords:hover' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'keyword_hover_border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-keywords:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'keyword_hover_border!' => '',
				),
			)
		);

		$this->add_control(
			'keyword_hover_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-keywords:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'keyword_hover_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'keyword_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-search-keywords' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'keyword_box_shadow',
				'selector' => '{{WRAPPER}} .mas-elementor-search-keywords',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_location_style',
			array(
				'label' => esc_html__( 'Location', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'location_size',
			array(
				'label'          => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 10,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 800,
						'step' => 50,
					),
				),
				'default'        => array(
					'unit' => 'px',
					'size' => 500,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'selectors'      => array(
					'{{WRAPPER}} .mas-job-search-location input' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'location_wrap_height',
			array(
				'label'      => esc_html__( 'Location Wrapper Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min' => 0,
						'max' => 10000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-location' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'location_height',
			array(
				'label'      => esc_html__( 'Location Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min' => 0,
						'max' => 10000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-location input' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'location_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-location' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'location_placeholder_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .mas-job-search-location input::placeholder',
			)
		);

		$this->add_control(
			'location_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-location input::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'location_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-location i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'location_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-location i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'location_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-search-location' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'location_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-location' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'form_location_border' );
		$this->start_controls_tab(
			'location_border_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'location_border',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-location' => 'border-style: {{VALUE}} !important;;',
				),
			)
		);

		$this->add_control(
			'location_border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-location' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;;',
				),
				'condition' => array(
					'location_border!' => '',
				),
			)
		);

		$this->add_control(
			'location_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-location' => 'border-color: {{VALUE}} !important;;',
				),
				'condition' => array(
					'location_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'location_border_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'location_hover_border',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-location:hover' => 'border-style: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'location_hover_border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-location:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'condition' => array(
					'location_hover_border!' => '',
				),
			)
		);

		$this->add_control(
			'location_hover_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-search-location:hover' => 'border-color: {{VALUE}} !important;',
				),
				'condition' => array(
					'location_hover_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'location_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-search-location' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'location_box_shadow',
				'selector' => '{{WRAPPER}} .mas-elementor-search-location',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_category_style',
			array(
				'label'     => esc_html__( 'Category', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'category_size',
			array(
				'label'          => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 10,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 800,
						'step' => 50,
					),
				),
				'default'        => array(
					'unit' => 'px',
					'size' => 500,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'selectors'      => array(
					'{{WRAPPER}} .mas-job-search-category' => 'min-width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'category_wrap_height',
			array(
				'label'      => esc_html__( 'Category Wrapper Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min' => 0,
						'max' => 10000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-category' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'category_height',
			array(
				'label'      => esc_html__( 'Category Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min' => 0,
						'max' => 10000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-category select' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'category_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-category select' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'category_placeholder_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'  => '{{WRAPPER}} .mas-job-search-category #search_category',
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-category #search_category' => 'color: {{VALUE}} !important;',
				),
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'category_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-category select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'category_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'form_category_border' );
		$this->start_controls_tab(
			'category_border_normal',
			array(
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_border',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-category select' => 'border-style: {{VALUE}} !important;',
				),
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-category select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'condition' => array(
					'category_border!' => '',
					'show_category'    => 'yes',
				),
			)
		);

		$this->add_control(
			'category_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-category select' => 'border-color: {{VALUE}} !important;',
				),
				'condition' => array(
					'category_border!' => '',
					'show_category'    => 'yes',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'category_border_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_hover_border',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-category select:hover' => 'border-style: {{VALUE}};',
				),
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_hover_border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-category select:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'category_hover_border!' => '',
					'show_category'          => 'yes',
				),
			)
		);

		$this->add_control(
			'category_hover_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-category select:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'category_hover_border!' => '',
					'show_category'          => 'yes',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'category_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-category select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_category' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'category_box_shadow',
				'selector'  => '{{WRAPPER}} .mas-job-search-category',
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_search_button_style',
			array(
				'label' => esc_html__( 'Search', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'search_size',
			array(
				'label'          => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 10,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 800,
						'step' => 50,
					),
				),
				'default'        => array(
					'unit' => 'px',
					'size' => 500,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'selectors'      => array(
					'{{WRAPPER}} .mas-job-search-submit button' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'search_button_content_align',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button' => 'display:flex; align-items: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'search_button' );
		$this->start_controls_tab(
			'search_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'search_button_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'search_button_text_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .mas-job-search-submit button span' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'search_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'search_button_background_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button:hover' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'search_button_text_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button:hover .mas-job-search-icon' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .mas-job-search-submit button:hover .mas-job-search-text' => 'color: {{VALUE}} !important;',

				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'search_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-icon ' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'search_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'search_icon_align',
			array(
				'label'     => esc_html__( 'Icon Position', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => esc_html__( 'Before', 'mas-addons-for-elementor' ),
					'right' => esc_html__( 'After', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'search_icon[value]!' => '',
					'search_text!'        => '',
				),
			)
		);

		$this->add_control(
			'icon_indent',
			array(
				'label'     => esc_html__( 'Icon Spacing', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit .mas-job-search-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mas-job-search-submit .mas-job-search-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'search_icon[value]!' => '',
					'search_text!'        => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'  => '{{WRAPPER}} .mas-job-search-submit button span',
				'condition' => array(
					'search_text!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'search_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-submit button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'search_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'form_search_border' );
		$this->start_controls_tab(
			'search_border_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'search_border',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'search_border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'search_border!' => '',
				),
			)
		);

		$this->add_control(
			'search_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'search_border!' => '',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'search_border_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'search_hover_border',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button:hover' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'search_hover_border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'search_hover_border!' => '',
				),
			)
		);

		$this->add_control(
			'search_hover_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-job-search-submit button:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'search_hover_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'search_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-job-search-submit button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'search_box_shadow',
				'selector' => '{{WRAPPER}} .mas-job-search-submit button',
			)
		);
	}

	/**
	 * Render.
	 */
	public function render() {
		$jobs_page_url = get_page_link( get_option( 'job_manager_jobs_page_id' ) );
		$settings      = $this->get_settings_for_display();

		$this->add_render_attribute(
			'icon-align',
			'class',
			array(
				'mas-job-search-icon',
				'mas-job-search-icon-' . $settings['search_icon_align'],
				$settings['search_icon']['value'],
			)
		);

		?>
		<div class="mas-job-search-form">
			<form method="GET" action="<?php echo esc_url( $jobs_page_url ); ?>" class="mas-search-form" style="display:flex;">
				<div class="mas-job-search-keywords">
					<label class="sr-only" for="search_keywords"><?php echo esc_html( 'keyword' ); ?></label>
					<input type="text" id="search_keywords" name="search_keywords" placeholder="<?php echo esc_html( $settings['keyword_placeholder'] ); ?>" class="mas-elementor-search-keywords ui-autocomplete-input" autocomplete="off">
					<?php
					if ( ! empty( $settings['job_title_icon']['value'] ) ) :
						?>
						<i class="<?php echo esc_attr( $settings['job_title_icon']['value'] ); ?>"></i>
					<?php endif; ?>
				</div>
				<?php
				if ( 'yes' === $settings['show_category'] && '2' === $settings['category_position'] ) :
					?>
					<div class="mas-job-search-category">
						<label class="sr-only" for="search_category"><?php echo esc_html__( 'Category', 'mas-addons-for-elementor' ); ?></label>
						<select id="search_category" name="search_category">
							<option value=""><?php echo esc_html( $settings['category_placeholder'] ); ?></option>
							<?php foreach ( get_job_listing_categories() as $cat ) : ?>
							<option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
				<div class="mas-job-search-location">
					<label class="sr-only" for="search_location"><?php echo esc_html( 'location' ); ?></label>
					<input type="text" id="search_location" name="search_location" placeholder="<?php echo esc_html( $settings['location_placeholder'] ); ?>" class="mas-elementor-search-location pac-target-input">
					<?php
					if ( ! empty( $settings['location_icon']['value'] ) ) :
						?>
						<i class="<?php echo esc_attr( $settings['location_icon']['value'] ); ?>"></i>
					<?php endif; ?>
				</div>
				<?php
				if ( 'yes' === $settings['show_category'] && '3' === $settings['category_position'] ) :
					?>
					<div class="mas-job-search-category">
						<label class="sr-only" for="search_category"><?php echo esc_html__( 'Category', 'mas-addons-for-elementor' ); ?></label>
						<select id="search_category" name="search_category">
							<option value=""><?php echo esc_html( $settings['category_placeholder'] ); ?></option>
							<?php foreach ( get_job_listing_categories() as $cat ) : ?>
							<option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
				<div class="mas-job-search-submit job-search-submit">
					<button type="submit" value="Search">
					<?php
					if ( ! empty( $settings['search_icon']['value'] ) ) :
						?>
						<i <?php $this->print_render_attribute_string( 'icon-align' ); ?>></i>
					<?php endif; ?>
					<?php
					if ( ! empty( $settings['search_text'] ) ) :
						?>
						<span class="mas-job-search-text"><?php echo esc_html( $settings['search_text'] ); ?></span>
					<?php endif; ?>
					</button>
				</div>
				<!-- <input type="hidden" name="post_type" value="job_listing"> -->
			</form>
		</div>
		<?php
	}

}
