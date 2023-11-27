<?php
/**
 * The Categories Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use Elementor\Group_Control_Box_Shadow;
use MASElementor\Modules\CarouselAttributes\Traits\Button_Widget_Trait;
use MASElementor\Modules\CarouselAttributes\Traits\Pagination_Trait;
use MASElementor\Modules\CarouselAttributes\Traits\Swiper_Options_Trait;
use ELementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Categories
 */
class Product_Categories extends Base_Widget {

	use Button_Widget_Trait;
	use Pagination_Trait;
	use Swiper_Options_Trait;

	/**
	 * Has content template
	 *
	 * @var _has_template_content
	 */
	protected $_has_template_content = false; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-product-categories-sub';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Product Categories & Sub', 'mas-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-categories';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce-elements', 'shop', 'store', 'categories', 'product' );
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array(
			'woocommerce-elements',
		);
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->register_carousel_attributes_controls();
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => esc_html__( 'Categories Count', 'mas-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
			)
		);

		$this->add_control(
			'sub_cat_count',
			array(
				'label'   => esc_html__( 'SubCategories Count', 'mas-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
			)
		);

		$this->register_enable_carousel_control( $this );

		$this->add_control(
			'see_more',
			array(
				'label'     => esc_html__( 'See More', 'mas-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'See More',
				'condition' => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter',
			array(
				'label' => esc_html__( 'Query', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'     => esc_html__( 'Hide Empty', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'label_on'  => 'Hide',
				'label_off' => 'Show',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'name'        => esc_html__( 'Name', 'mas-elementor' ),
					'slug'        => esc_html__( 'Slug', 'mas-elementor' ),
					'description' => esc_html__( 'Description', 'mas-elementor' ),
					'count'       => esc_html__( 'Count', 'mas-elementor' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'mas-elementor' ),
					'desc' => esc_html__( 'DESC', 'mas-elementor' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_categories_image_style',
			array(
				'label' => esc_html__( 'Categories Image', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_images_controls();

		$this->add_control(
			'heading_image_style',
			array(
				'label'     => esc_html__( 'Image', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->register_transform_controls();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} a > img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} a > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'image_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => esc_html__( 'Category', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Category Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .categories > a'         => 'color: {{VALUE}}',
					'{{WRAPPER}} .categories > .cat-name' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .categories > a, {{WRAPPER}} .categories > .cat-name',
			)
		);

		$this->add_control(
			'heading_sub_cat_style',
			array(
				'label'     => esc_html__( 'Sub Category', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->add_responsive_control(
			'show_hide_sub_cat',
			array(
				'label'       => esc_html__( 'Show / Hide SubCategories', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'block' => array(
						'title' => esc_html__( 'Block', 'mas-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'none'  => array(
						'title' => esc_html__( 'None', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),

				),
				'selectors'   => array(
					'{{WRAPPER}} .sub-categories' => 'display: {{VALUE}};',
				),
				'condition'   => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->add_control(
			'sub_cat_color',
			array(
				'label'     => esc_html__( 'Category Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .sub-categories .sub-category a' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'sub_cat_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .sub-categories .sub-category, {{WRAPPER}} .sub-categories .sub-category a',
				'condition' => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_categories_see_all_style',
			array(
				'label'     => esc_html__( 'See all', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'sub_cat_count!' => 0,
					'see_more!'      => '',
				),
			)
		);

		$this->add_control(
			'heading_see_all_style',
			array(
				'label'     => esc_html__( 'See All', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'see_all_color',
			array(
				'label'     => esc_html__( 'See All Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .see-all' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'see_all_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .see-all',
			)
		);

		$this->add_responsive_control(
			'see_all_align',
			array(
				'label'     => esc_html__( 'See All Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
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
					'{{WRAPPER}} .see-all-wrapper' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'see_all_hide',
			array(
				'label'       => esc_html__( 'Show / Hide', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'block' => array(
						'title' => esc_html__( 'Block', 'mas-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'none'  => array(
						'title' => esc_html__( 'None', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),

				),
				'selectors'   => array(
					'{{WRAPPER}} .see-all-wrapper' => 'display: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_style',
			array(
				'label' => esc_html__( 'All Categories', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'enable_card_link',
			array(
				'label'     => esc_html__( 'Enable Card Link', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => 'Enabled',
				'label_off' => 'Enable',
				'condition' => array(
					'sub_cat_count' => 0,
				),
			)
		);

		$this->add_responsive_control(
			'cat_size',
			array(
				'label'          => esc_html__( 'Width', 'mas-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'default'        => array(
					'size' => 500,
				),
				'tablet_default' => array(
					'size' => 500,
				),
				'mobile_default' => array(
					'size' => 500,
				),
				'selectors'      => array(
					'{{WRAPPER}} .cat-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'categories_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'categories_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->flex_controls( '', '{{WRAPPER}} .mas-categories-wrapper' );

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_image_wrapper',
			array(
				'label' => esc_html__( 'Image & Categories', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->flex_controls( 'img_cat', '{{WRAPPER}} .img-cat-wrap' );

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_subcat',
			array(
				'label'     => esc_html__( 'Category & Sub Categories', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->flex_controls( 'cat_sub', '{{WRAPPER}} .categories' );

		$this->end_controls_section();

		$this->register_background_controls();
	}

	/**
	 * Register the Container's background controls.
	 *
	 * @return void
	 */
	protected function register_background_controls() {
		$this->start_controls_section(
			'section_background',
			array(
				'label' => esc_html__( 'Background', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_background' );

		/**
		 * Normal.
		 */
		$this->start_controls_tab(
			'tab_background_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cat_box_shadow',
				'selector' => '{{WRAPPER}} .cat-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'background',
				'types'          => array( 'classic', 'gradient', 'video', 'slideshow' ),
				'fields_options' => array(
					'background' => array(
						'frontend_available' => true,
					),
					'image'      => array(
						'background_lazyload' => array(
							'active' => true,
							'keys'   => array( 'background_image', 'url' ),
						),
					),
				),
				'selector'       => '{{WRAPPER}} .cat-wrapper',
			)
		);

		$this->end_controls_tab();

		/**
		 * Hover.
		 */
		$this->start_controls_tab(
			'tab_background_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cat_box_shadow_hover',
				'selector' => '{{WRAPPER}} .cat-wrapper:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background_hover',
				'selector' => '{{WRAPPER}} .cat-wrapper:hover',
			)
		);

		$this->add_control(
			'background_hover_transition',
			array(
				'label'       => esc_html__( 'Transition Duration', 'mas-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 0.3,
				),
				'render_type' => 'ui',
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} .cat-wrapper' => '--background-transition: {{SIZE}}s;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Transform controls for this widget.
	 */
	protected function register_transform_controls() {

		$default_unit_values_deg = array();
		$default_unit_values_ms  = array();

		// Set the default unit sizes for all active breakpoints.
		foreach ( Breakpoints_Manager::get_default_config() as $breakpoint_name => $breakpoint_config ) {
			$default_unit_values_deg[ $breakpoint_name ] = array(
				'default' => array(
					'unit' => 'deg',
				),
			);

			$default_unit_values_ms[ $breakpoint_name ] = array(
				'default' => array(
					'unit' => 'ms',
				),
			);
		}
		$this->add_responsive_control(
			'transform_rotate_image',
			array(
				'label'       => esc_html__( 'Image Rotate', 'mas-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'device_args' => $default_unit_values_deg,
				'range'       => array(
					'px' => array(
						'min' => -360,
						'max' => 360,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'transform: rotate({{SIZE}}deg)',
				),
			)
		);
	}

	/**
	 * Register controls for this widget.
	 */
	protected function add_images_controls() {

		$this->add_responsive_control(
			'image_width',
			array(
				'label'          => esc_html__( 'Width', 'mas-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'default'        => array(
					'size' => 48,
				),
				'tablet_default' => array(
					'size' => 48,
				),
				'mobile_default' => array(
					'size' => 48,
				),
				'selectors'      => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'          => esc_html__( 'Height', 'mas-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'default'        => array(
					'size' => 48,
				),
				'tablet_default' => array(
					'size' => 48,
				),
				'mobile_default' => array(
					'size' => 48,
				),
				'selectors'      => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Flex Controls.
	 *
	 * @param string $name name of the control.
	 * @param string $wrapper wrapper for control.
	 */
	public function flex_controls( $name = '', $wrapper = '{{WRAPPER}} .mas-categories-wrapper' ) {
		$start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';
		$this->add_control(
			$name . 'items',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Items', 'mas-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrap_direction',
			array(
				'label'     => esc_html__( 'Direction', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'row'            => array(
						'title' => esc_html__( 'Row - horizontal', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-' . $end,
					),
					'column'         => array(
						'title' => esc_html__( 'Column - vertical', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-down',
					),
					'row-reverse'    => array(
						'title' => esc_html__( 'Row - reversed', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-' . $start,
					),
					'column-reverse' => array(
						'title' => esc_html__( 'Column - reversed', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-up',
					),
				),
				'default'   => 'row',
				'selectors' => array(
					$wrapper => 'display:flex; flex-direction:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrap_justify_content',
			array(
				'label'       => esc_html__( 'Justify Content', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
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
						'title' => esc_html__( 'Space Between', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-between-h',
					),
					'space-around'  => array(
						'title' => esc_html__( 'Space Around', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-around-h',
					),
					'space-evenly'  => array(
						'title' => esc_html__( 'Space Evenly', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-evenly-h',
					),
				),
				'selectors'   => array(
					$wrapper => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrap_align_items',
			array(
				'label'     => esc_html__( 'Align Items', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'selectors' => array(
					$wrapper => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrap_gap',
			array(
				'label'                  => esc_html__( 'Gaps', 'mas-elementor' ),
				'type'                   => Controls_Manager::GAPS,
				'size_units'             => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'default'                => array(
					'unit' => 'px',
				),
				'separator'              => 'before',
				'selectors'              => array(
					$wrapper => 'gap: {{ROW}}{{UNIT}} {{COLUMN}}{{UNIT}}',
				),
				'conversion_map'         => array(
					'old_key' => 'size',
					'new_key' => 'column',
				),
				'upgrade_conversion_map' => array(
					'old_key'  => 'size',
					'new_keys' => array( 'column', 'row' ),
				),
				'validators'             => array(
					'Number' => array(
						'min' => 0,
					),
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrapper_wrap',
			array(
				'label'       => esc_html_x( 'Wrap', 'Flex Container Control', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'nowrap' => array(
						'title' => esc_html_x( 'No Wrap', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-nowrap',
					),
					'wrap'   => array(
						'title' => esc_html_x( 'Wrap', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
				),
				'description' => esc_html__( 'Items within the container can stay in a single line (No wrap), or break into multiple lines (Wrap).', 'mas-elementor' ),
				'default'     => '',
				'selectors'   => array(
					$wrapper => 'flex-wrap: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrapper_align_content',
			array(
				'label'     => esc_html__( 'Align Content', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''              => esc_html__( 'Default', 'mas-elementor' ),
					'center'        => esc_html__( 'Center', 'mas-elementor' ),
					'flex-start'    => esc_html__( 'Start', 'mas-elementor' ),
					'flex-end'      => esc_html__( 'End', 'mas-elementor' ),
					'space-between' => esc_html__( 'Space Between', 'mas-elementor' ),
					'space-around'  => esc_html__( 'Space Around', 'mas-elementor' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'mas-elementor' ),
				),
				'selectors' => array(
					$wrapper => 'align-content: {{VALUE}};',
				),
				'condition' => array(
					'categories_wrapper_wrap' => 'wrap',
				),
			)
		);
	}

	/**
	 * Render.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$json     = wp_json_encode( $this->get_swiper_carousel_options( $this, $settings ) );
		$this->add_render_attribute( 'mas-categories', 'class', 'mas-categories' );
		$this->add_render_attribute( 'mas-categories-wrapper', 'class', 'mas-categories-wrapper' );
		if ( 'yes' === $settings['enable_carousel'] ) {
			$this->add_render_attribute( 'mas-categories', 'class', 'swiper' );
			$this->add_render_attribute( 'mas-categories', 'data-swiper-options', $json );
			$this->add_render_attribute( 'mas-categories-wrapper', 'class', 'swiper-wrapper' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'mas-categories' ); ?>>
			<div <?php $this->print_render_attribute_string( 'mas-categories-wrapper' ); ?>>
				<?php $this->render_categories(); ?>
			</div>
			<?php echo wp_kses_post( $this->carousel_loop_footer( $this, $settings ) ); ?>
		</div>
		<?php

	}

	/**
	 * Render.
	 */
	public function render_categories() {
		$settings = $this->get_settings_for_display();
		$taxonomy = 'product_cat';
		$orderby  = $settings['orderby'];
		$order    = $settings['order'];
		$empty    = 'yes' === $settings['hide_empty'] ? true : false;

		$args           = array(
			'taxonomy'   => $taxonomy,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $empty,
		);
		$all_categories = get_terms( $args );
		$tab_count      = 0;
		if ( 'yes' === $settings['enable_card_link'] ) {
			$img_cat_wrap_tag = 'a';
			$categories_tag   = 'div';
			$img_wrap_tag     = 'div';

		} else {
			$img_cat_wrap_tag = 'div';
			$categories_tag   = 'a';
			$img_wrap_tag     = 'a';
		}

		foreach ( $all_categories as $index => $cat ) {
			if ( 0 === $cat->parent ) {
				if ( $tab_count === $settings['number'] ) {
					break;
				}
				if ( 'a' === $img_cat_wrap_tag ) {
					$this->add_render_attribute( 'img-cat-wrap' . $index, 'href', get_term_link( $cat->slug, $taxonomy ) );
				}

				$this->add_render_attribute( 'img-cat-wrap' . $index, 'class', 'img-cat-wrap' );

				$this->add_render_attribute( 'cat-wrapper' . $index, 'class', 'cat-wrapper' );

				if ( 'yes' === $settings['enable_carousel'] ) {
					$this->add_render_attribute( 'cat-wrapper' . $index, 'class', 'swiper-slide' );
				}

				?>
			<div <?php $this->print_render_attribute_string( 'cat-wrapper' . $index ); ?>>
				<<?php echo esc_html( $img_cat_wrap_tag ); ?> <?php $this->print_render_attribute_string( 'img-cat-wrap' . $index ); ?>>
					<?php
					$category_name      = $cat->name;
					$category_thumbnail = get_term_meta( $cat->term_id, 'thumbnail_id', true );
					$image              = ! empty( wp_get_attachment_image( $category_thumbnail ) ) ? wp_get_attachment_image( $category_thumbnail ) : wc_placeholder_img();

					if ( 'a' === $img_wrap_tag ) {
						$this->add_render_attribute( 'cat-img' . $index, 'href', get_term_link( $cat->slug, $taxonomy ) );
					}
					$this->add_render_attribute( 'cat-img' . $index, 'class', 'image-wrap' );
					?>
					<<?php echo esc_html( $img_wrap_tag ); ?> <?php $this->print_render_attribute_string( 'cat-img' . $index ); ?>><?php echo wp_kses_post( $image ); ?></<?php echo esc_html( $img_wrap_tag ); ?>>
					<?php

					$category_id = $cat->term_id;
					?>
					<div class="categories">
						<?php
						if ( 'a' === $categories_tag ) {
							$this->add_render_attribute( 'categories' . $index, 'href', get_term_link( $cat->slug, $taxonomy ) );
						}
						$this->add_render_attribute( 'categories' . $index, 'class', 'cat-name' );
						?>
						<<?php echo esc_html( $categories_tag ); ?> <?php $this->print_render_attribute_string( 'categories' . $index ); ?>><?php echo esc_html( $cat->name ); ?></<?php echo esc_html( $categories_tag ); ?>>
						<?php
						$args2    = array(
							'taxonomy'   => $taxonomy,
							'child_of'   => 0,
							'parent'     => $category_id,
							'orderby'    => $orderby,
							'hide_empty' => $empty,
						);
						$sub_cats = get_categories( $args2 );
						if ( $sub_cats && 0 < $settings['sub_cat_count'] ) {
							$sub_tab_count = 0;
							?>
						<div class="sub-categories">
							<?php
							foreach ( $sub_cats as $sub_category ) {
								if ( $sub_tab_count === $settings['sub_cat_count'] ) {
									break;
								}
								?>
								<div class="sub-category"><?php echo wp_kses_post( '<a href="' . get_term_link( $sub_category->slug, 'product_cat' ) . '">' . $sub_category->name . '</a>' ); ?></div>
								<?php
								$sub_tab_count++;
							}
							?>
						</div>
							<?php
						}
						?>
					</div>
				</<?php echo esc_html( $img_cat_wrap_tag ); ?>>
				<?php

				if ( ! empty( $settings['see_more'] && ( ! empty( $sub_cats ) && ( count( $sub_cats ) > $settings['sub_cat_count'] ) ) ) ) {
					?>
				<div class="see-all-wrapper">
					<?php
						echo wp_kses_post( '<a class="see-all" href="' . get_term_link( $cat->slug, 'product_cat' ) . '">' . $settings['see_more'] . '</a>' );
					?>
				</div>
					<?php
				}

				?>
			</div>
				<?php
				$tab_count++;
			}
		}

	}

	/**
	 * Render Plain Content.
	 */
	public function render_plain_content() {
	}

	/**
	 * Get group name.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}

	/**
	 * Add carousel arrow and pagination controls to the product categories element.
	 */
	public function register_carousel_arrow_pagination_controls() {

		$args = array(
			'concat'        => '',
			'button_concat' => '',
		);

		$this->register_pagination_style_controls( $this, $args );

		$this->start_controls_section(
			'masposts_swiper_button',
			array(
				'label'     => esc_html__( 'Button', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_arrows'     => 'yes',
				),
			)
		);

			$this->register_button_content_controls( $this, $args );

		$this->end_controls_section();

		$this->register_button_style_controls( $this, $args );

	}

	/**
	 * Add carousel controls to the product categories element.
	 */
	public function register_carousel_attributes_controls() {

		$this->start_injection(
			array(
				'at' => 'before',
				'of' => 'section_query',
			)
		);

		$this->register_swiper_controls( $this );

		$this->end_injection();

		$this->register_carousel_arrow_pagination_controls();
	}

	/**
	 * Carousel Loop Footer.
	 *
	 * @param array $widget widget.
	 * @param array $settings Settings of this widget.
	 * @return string
	 */
	public function carousel_loop_footer( $widget, array $settings = array() ) {
		ob_start();
		if ( 'yes' === $settings['enable_carousel'] ) {
			?>
			<?php
			$widget_id = $widget->get_id();
			if ( ! empty( $widget_id ) && 'yes' === $settings['show_pagination'] ) {
				$widget->add_render_attribute( 'swiper-pagination', 'id', 'pagination-' . $widget_id );
			}
			$widget->add_render_attribute( 'swiper-pagination', 'class', 'swiper-pagination' );
			$widget->add_render_attribute( 'swiper-pagination', 'style', 'position: ' . $settings['mas_swiper_pagination_position'] . ';' );
			if ( 'yes' === $settings['show_pagination'] ) :
				?>
			<div <?php $widget->print_render_attribute_string( 'swiper-pagination' ); ?>></div>
				<?php
			endif;
			if ( 'yes' === $settings['show_arrows'] ) :
				$prev_id = ! empty( $widget_id ) ? 'prev-' . $widget_id : '';
				$next_id = ! empty( $widget_id ) ? 'next-' . $widget_id : '';
				?>
				<!-- If we need navigation buttons -->
				<div class="d-flex mas-swiper-arrows">
					<?php
					$widget->render_button( $widget, $prev_id, $next_id );
					?>
				</div>
				<?php
			endif;
			?>
			<?php
		}
		return ob_get_clean();
	}
}
