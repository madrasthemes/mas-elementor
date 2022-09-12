<?php
/**
 * The Posts Base.
 *
 * @package MASElementor/Modules/Posts/Widgets
 */

namespace MASElementor\Modules\Posts\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use MASElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use MASElementor\Modules\QueryControl\Controls\Group_Control_Related;
use Elementor\Plugin;
use MASElementor\Modules\CarouselAttributes\Traits\Button_Widget_Trait;
use MASElementor\Modules\CarouselAttributes\Traits\Pagination_Trait;
use Elementor\Group_Control_Border;
use MASElementor\Modules\Posts\Traits\Load_Button_Widget_Trait as Load_More_Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Posts
 */
abstract class Posts_Base extends Base_Widget {

	use Load_More_Button_Trait;
	use Button_Widget_Trait;
	use Pagination_Trait;

	/**
	 * Query.
	 *
	 *  @var \WP_Query
	 */
	protected $query = null;

	const LOAD_MORE_ON_CLICK        = 'load_more_on_click';
	const LOAD_MORE_INFINITE_SCROLL = 'load_more_infinite_scroll';

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'imagesloaded', 'load-more' );
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the element requires.
	 *
	 * @since 1.9.0
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'load-more' );
	}

	/**
	 * Get the query.
	 *
	 * @return array
	 */
	public function get_query() {
		return $this->query;
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->register_query_section_controls();
		$this->register_carousel_attributes_controls();
	}

	/**
	 * Render
	 */
	public function render() {}

	/**
	 * Register pagination section controls
	 */
	public function register_pagination_section_controls() {
		$this->start_controls_section(
			'section_pagination',
			array(
				'label' => __( 'Pagination', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'pagination_type',
			array(
				'label'              => __( 'Pagination', 'mas-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'frontend_available' => true,
				'options'            => array(
					''                              => __( 'None', 'mas-elementor' ),
					'numbers'                       => __( 'Numbers', 'mas-elementor' ),
					'prev_next'                     => __( 'Previous/Next', 'mas-elementor' ),
					'numbers_and_prev_next'         => __( 'Numbers', 'mas-elementor' ) . ' + ' . __( 'Previous/Next', 'mas-elementor' ),
					self::LOAD_MORE_ON_CLICK        => esc_html__( 'Load on Click', 'mas-elementor' ),
					self::LOAD_MORE_INFINITE_SCROLL => esc_html__( 'Infinite Scroll', 'mas-elementor' ),
				),
			)
		);

		$this->add_control(
			'pagination_page_limit',
			array(
				'label'     => __( 'Page Limit', 'mas-elementor' ),
				'default'   => '5',
				'condition' => array(
					'pagination_type!' => '',
				),
			)
		);

		$this->add_control(
			'pagination_numbers_shorten',
			array(
				'label'     => __( 'Shorten', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'pagination_type' => array(
						'numbers',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_prev_label',
			array(
				'label'     => __( 'Previous Label', 'mas-elementor' ),
				'default'   => __( '&laquo; Previous', 'mas-elementor' ),
				'condition' => array(
					'pagination_type' => array(
						'prev_next',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_next_label',
			array(
				'label'     => __( 'Next Label', 'mas-elementor' ),
				'default'   => __( 'Next &raquo;', 'mas-elementor' ),
				'condition' => array(
					'pagination_type' => array(
						'prev_next',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_align',
			array(
				'label'     => __( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => '',
				),
			)
		);

		$this->add_control(
			'load_more_spinner',
			array(
				'label'                  => esc_html__( 'Spinner', 'mas-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'icon',
				'default'                => array(
					'value'   => 'fas fa-spinner',
					'library' => 'fa-solid',
				),
				'exclude_inline_options' => array( 'svg' ),
				'recommended'            => array(
					'fa-solid' => array(
						'spinner',
						'cog',
						'sync',
						'sync-alt',
						'asterisk',
						'circle-notch',
					),
				),
				'skin'                   => 'inline',
				'label_block'            => false,
				'condition'              => array(
					'pagination_type' => array(
						'load_more_on_click',
						'load_more_infinite_scroll',
					),
				),
				'frontend_available'     => true,
			)
		);

		$this->add_control(
			'heading_load_more_button',
			array(
				'label'     => esc_html__( 'Button', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'pagination_type' => 'load_more_on_click',
				),
			)
		);

		$this->load_more_register_button_content_controls(
			array(
				'button_text'            => esc_html__( 'Load More', 'mas-elementor' ),
				'control_label_name'     => esc_html__( 'Button Text', 'mas-elementor' ),
				'prefix_class'           => 'load-more-align-',
				'alignment_default'      => 'center',
				'section_condition'      => array(
					'pagination_type' => 'load_more_on_click',
				),
				'exclude_inline_options' => array( 'svg' ),
			)
		);

		$this->remove_control( 'button_type' );
		$this->remove_control( 'link' );
		$this->remove_control( 'size' );

		$this->add_control(
			'heading_load_more_no_posts_message',
			array(
				'label'     => esc_html__( 'No More Posts Message', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'pagination_type' => array(
						'load_more_on_click',
						'load_more_infinite_scroll',
					),
				),
			)
		);

		$this->add_responsive_control(
			'load_more_no_posts_message_align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--load-more-message-alignment: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type' => array(
						'load_more_on_click',
						'load_more_infinite_scroll',
					),
				),
			)
		);

		$this->add_control(
			'load_more_no_posts_message_switcher',
			array(
				'label'     => esc_html__( 'Custom Messages', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'pagination_type' => array(
						'load_more_on_click',
						'load_more_infinite_scroll',
					),
				),
			)
		);

		$this->add_control(
			'load_more_no_posts_custom_message',
			array(
				'label'       => esc_html__( 'No more posts message', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'No more posts to show', 'mas-elementor' ),
				'condition'   => array(
					'pagination_type'                     => array(
						'load_more_on_click',
						'load_more_infinite_scroll',
					),
					'load_more_no_posts_message_switcher' => 'yes',
				),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_load_more',
			array(
				'label'     => __( 'Load More Button', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'pagination_type' => 'load_more_on_click',
				),
			)
		);

		$this->load_more_register_button_style_controls(
			array(
				'section_condition' => array(
					'pagination_type' => 'load_more_on_click',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => __( 'Pagination', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'pagination_type!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'product_border',
				'selector'       => '{{WRAPPER}} .elementor-pagination',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#FFFFFF',
					),
				),
			)
		);

		$this->add_control(
			'pag_heading',
			array(
				'label'     => __( 'Spacing', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'pagination_spacing',
			array(
				'label'      => __( 'Top Spacing', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'size' => 15,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'product_pagination_spacing',
			array(
				'label'      => __( 'Space Between', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'size' => 8,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul li' => 'border:0',
					'body:not(.rtl) {{WRAPPER}} nav.woocommerce-pagination ul li:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} nav.woocommerce-pagination ul li:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} nav.woocommerce-pagination ul li:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} nav.woocommerce-pagination ul li:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->add_control(
			'pagination_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 8,
					'right'    => 12,
					'bottom'   => 8,
					'left'     => 12,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'pagination_style_tabs',
			array(
				'separator' => 'before',
			)
		);

		$this->start_controls_tab(
			'pagination_style_normal',
			array(
				'label' => __( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'normal_pagination_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .elementor-pagination .page-numbers',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '600',
					),
					'font_family' => array(
						'default' => 'Quicksand',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
				),
			)
		);

		$this->add_control(
			'pagination_link_color',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'normal_single_pag_border',
				'selector'       => '{{WRAPPER}} .elementor-pagination .page-numbers',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#d3d9dd',
					),
				),
			)
		);

		$this->add_control(
			'normal_single_pag_border_red',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 5,
					'right'    => 5,
					'bottom'   => 5,
					'left'     => 5,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_hover',
			array(
				'label' => __( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'hover_pagination_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .elementor-pagination .page-numbers:hover',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '600',
					),
					'font_family' => array(
						'default' => 'Quicksand',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
				),
			)
		);

		$this->add_control(
			'pagination_link_color_hover',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00000000',
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'hover_single_pag_border',
				'selector'       => '{{WRAPPER}} .elementor-pagination .page-numbers:hover',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#b9c1c8',
					),
				),
			)
		);

		$this->add_control(
			'hover_single_pag_border_red',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 5,
					'right'    => 5,
					'bottom'   => 5,
					'left'     => 5,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_active',
			array(
				'label' => __( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'active_pagination_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .elementor-pagination .page-numbers.current',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '600',
					),
					'font_family' => array(
						'default' => 'Quicksand',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
				),
			)
		);

		$this->add_control(
			'pagination_link_color_active',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'active_single_pag_border',
				'selector'       => '{{WRAPPER}} .elementor-pagination .page-numbers.current',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#f2f4f5',
					),
				),
			)
		);

		$this->add_control(
			'active_single_pag_border_red',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 5,
					'right'    => 5,
					'bottom'   => 5,
					'left'     => 5,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add carousel controls to the column element.
	 */
	public function register_carousel_attributes_controls() {

		$this->start_injection(
			array(
				'at' => 'before',
				'of' => 'section_query',
			)
		);
		$this->start_controls_section(
			'section_carousel_attributes',
			array(
				'label'     => __( 'Carousel', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array( 'enable_carousel' => 'yes' ),
			)
		);

		$this->add_control(
			'carousel_effect',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => esc_html__( 'Effect', 'mas-elementor' ),
				'default'            => 'slide',
				'options'            => array(
					''      => esc_html__( 'None', 'mas-elementor' ),
					'slide' => esc_html__( 'Slide', 'mas-elementor' ),
					'fade'  => esc_html__( 'Fade', 'mas-elementor' ),
				),
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'slides_per_view',
			array(
				'type'               => Controls_Manager::NUMBER,
				'label'              => esc_html__( 'Slides Per View', 'mas-elementor' ),
				'min'                => 1,
				'max'                => 10,
				'default'            => 1,
				'condition'          => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
				),
				'devices'            => array( 'desktop', 'tablet', 'mobile' ),
				'default'            => 1,
				'tablet_default'     => 1,
				'mobile_default'     => 1,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'enable_space_between',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Space Between', 'mas-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'condition' => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'space_between',
			array(
				'type'           => Controls_Manager::NUMBER,
				'label'          => esc_html__( 'Space Between', 'mas-elementor' ),
				'description'    => esc_html__( 'Set Space between each Slides', 'mas-elementor' ),
				'min'            => 0,
				'max'            => 100,
				'devices'        => array( 'desktop', 'tablet', 'mobile' ),
				'default'        => '0',
				'tablet_default' => '0',
				'mobile_default' => '0',
				'condition'      => array(
					'carousel_effect'      => 'slide',
					'enable_carousel'      => 'yes',
					'enable_space_between' => 'yes',
				),
			)
		);

		$this->add_control(
			'center_slides',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Center Slides', 'mas-elementor' ),
				'default'            => 'no',
				'label_off'          => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'           => esc_html__( 'Show', 'mas-elementor' ),
				'condition'          => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Arrows', 'mas-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'condition' => array(
					'enable_carousel'     => 'yes',
					'show_custom_arrows!' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_custom_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Custom Arrows', 'mas-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_arrows!'    => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_prev_id',
			array(
				'label'     => esc_html__( 'Previous Arrow ID', 'mas-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_next_id',
			array(
				'label'     => __( 'Next Arrow ID', 'mas-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => esc_html__( 'Transition Duration', 'mas-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'step'               => 100,
				'render_type'        => 'none',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => esc_html__( 'Autoplay', 'mas-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'separator'          => 'before',
				'render_type'        => 'none',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => esc_html__( 'Autoplay Speed', 'mas-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay'        => 'yes',
					'enable_carousel' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'              => esc_html__( 'Infinite Loop', 'mas-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => esc_html__( 'Pause on Hover', 'mas-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'autoplay'        => 'yes',
					'enable_carousel' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'show_pagination',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Enable Pagination', 'mas-elementor' ),
				'default'            => 'yes',
				'label_off'          => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'           => esc_html__( 'Show', 'mas-elementor' ),
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bullets',
				'options'   => array(
					'bullets'     => esc_html__( 'Dots', 'mas-elementor' ),
					'fraction'    => esc_html__( 'Fraction', 'mas-elementor' ),
					'progressbar' => esc_html__( 'Progress', 'mas-elementor' ),
				),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_pagination' => 'yes',
				),
			)
		);

		$this->end_controls_section();
		$this->end_injection();

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
	 * Register controls in the Query Section
	 */
	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			array(
				'name'    => $this->get_name(),
				'presets' => array( 'full' ),
				'exclude' => array(
					'posts_per_page', // use the one from Layout section.
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Query Posts
	 */
	abstract public function query_posts();

	/**
	 * Get the current page.
	 *
	 * @return int
	 */
	public function get_current_page() {
		if ( '' === $this->get_settings( 'pagination_type' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	/**
	 * Get WP Link page
	 *
	 * @param  int $i Page Number.
	 * @return string
	 */
	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post       = get_post();
		$query_args = array();
		$url        = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ), true ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) === 'page' && (int) get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) { //phpcs:ignore
				$query_args['preview_id']    = wp_unslash( $_GET['preview_id'] ); //phpcs:ignore
				$query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] ); //phpcs:ignore
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	/**
	 * Get posts nav link.
	 *
	 * @param int|null $page_limit The max number of pages.
	 * @return array
	 */
	public function get_posts_nav_link( $page_limit = null ) {
		if ( ! $page_limit ) {
			$page_limit = $this->query->max_num_pages;
		}

		$return = array();

		$paged = $this->get_current_page();

		$link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_prev_label' ) );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $this->get_settings( 'pagination_prev_label' ) );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_next_label' ) );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $this->get_settings( 'pagination_next_label' ) );
		}

		return $return;
	}

	/**
	 * Render plain content.
	 */
	public function render_plain_content() {}

	/**
	 * Render loop footer for pagination.
	 */
	protected function render_loop_footer() {
		$parent_settings       = $this->get_settings();
		$using_ajax_pagination = in_array(
			$parent_settings['pagination_type'],
			array(
				self::LOAD_MORE_ON_CLICK,
				self::LOAD_MORE_INFINITE_SCROLL,
			),
			true
		);
		?>


		<?php if ( $using_ajax_pagination && ! empty( $parent_settings['load_more_spinner']['value'] ) ) : ?>
			<span class="e-load-more-spinner">
				<?php Icons_Manager::render_icon( $parent_settings['load_more_spinner'], array( 'aria-hidden' => 'true' ) ); ?>
			</span>
		<?php endif; ?>

		<?php

		if ( '' === $parent_settings['pagination_type'] ) {
			return;
		}

		$page_limit = $this->get_query()->max_num_pages;

		if ( '' !== $parent_settings['pagination_page_limit'] ) {
			$page_limit = min( $parent_settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$this->add_render_attribute( 'pagination', 'class', 'elementor-pagination' );

		$has_numbers   = in_array( $parent_settings['pagination_type'], array( 'numbers', 'numbers_and_prev_next' ), true );
		$has_prev_next = in_array( $parent_settings['pagination_type'], array( 'prev_next', 'numbers_and_prev_next' ), true );

		$load_more_type = $parent_settings['pagination_type'];

		$current_page = $this->get_current_page();
		$next_page    = intval( $current_page ) + 1;

		$this->add_render_attribute(
			'load_more_anchor',
			array(
				'data-page'      => intval( $current_page ),
				'data-max-page'  => $page_limit,
				'data-next-page' => $this->get_wp_link_page( $next_page ),
			)
		);
		?>
		<div class="e-load-more-anchor" <?php $this->print_render_attribute_string( 'load_more_anchor' ); ?>></div>
		<?php

		if ( $using_ajax_pagination ) {
			if ( 'load_more_on_click' === $load_more_type ) {
				// The link-url control is hidden, so default value is added to keep the same style like button widget.
				$this->set_settings( 'link', array( 'url' => '#' ) );

				$this->load_more_render_button( $this );
			}

			$this->load_more_render_message();
			return;
		}

		$links = array();

		if ( $has_numbers ) {
			$paginate_args = array(
				'type'               => 'array',
				'current'            => $this->get_current_page(),
				'total'              => $page_limit,
				'prev_next'          => false,
				'show_all'           => 'yes' !== $parent_settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="elementor-screen-only">' . esc_html__( 'Page', 'mas-elementor' ) . '</span>',
			);

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$prev_next = $this->get_posts_nav_link( $page_limit );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}

		// PHPCS - Seems that `$links` is safe.
		?>
		<nav class="elementor-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'mas-elementor' ); ?>">
			<?php echo implode( PHP_EOL, $links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</nav>
		<?php
	}

	/**
	 * Carousel Loop Footer.
	 *
	 * @param array $settings Settings of this widget.
	 */
	public function carousel_loop_footer( array $settings = array() ) {
		if ( 'yes' === $settings['enable_carousel'] ) {
			?>
			</div>
			<?php
			$widget_id = $this->get_id();
			if ( ! empty( $widget_id ) && 'yes' === $settings['show_pagination'] ) {
				$this->add_render_attribute( 'swiper-pagination', 'id', 'pagination-' . $widget_id );
			}
			$this->add_render_attribute( 'swiper-pagination', 'class', 'swiper-pagination' );
			$this->add_render_attribute( 'swiper-pagination', 'style', 'position: ' . $settings['mas_swiper_pagination_position'] . ';' );
			if ( 'yes' === $settings['show_pagination'] ) :
				?>
			<div <?php $this->print_render_attribute_string( 'swiper-pagination' ); ?>></div>
				<?php
			endif;
			if ( 'yes' === $settings['show_arrows'] ) :
				$prev_id = ! empty( $widget_id ) ? 'prev-' . $widget_id : '';
				$next_id = ! empty( $widget_id ) ? 'next-' . $widget_id : '';
				?>
				<!-- If we need navigation buttons -->
				<div class="d-flex mas-swiper-arrows">
				<?php
				$this->render_button( $this, $prev_id, $next_id );
				?>
				</div>
				<?php
			endif;
			?>
			</div>
			<?php
		}
	}

	/**
	 * Get carousel settings
	 *
	 * @param array $settings The widget settings.
	 * @return array
	 */
	public function get_swiper_carousel_options( array $settings ) {
		$section_id      = $this->get_id();
		$swiper_settings = array();
		if ( 'yes' === $settings['show_pagination'] ) {
			$swiper_settings['pagination'] = array(
				'el' => '#pagination-' . $section_id,
			);
		}

		if ( 'bullets' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type']      = 'bullets';
			$swiper_settings['pagination']['clickable'] = true;
		}
		if ( 'fraction' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'fraction';
		}
		if ( 'progressbar' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'progressbar';
		}

		if ( 'fade' === $settings['carousel_effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['carousel_effect'] ) {
			$swiper_settings['breakpoints']['1440']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['1024']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['500']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		}

		if ( 'yes' === $settings['enable_space_between'] ) {
			if ( ! empty( $settings['space_between'] ) ) {
				$swiper_settings['breakpoints']['1440']['spaceBetween'] = $settings['space_between'];

			}
			if ( ! empty( $settings['space_between_tablet'] ) ) {
				$swiper_settings['breakpoints']['1024']['spaceBetween'] = $settings['space_between_tablet'];
				$swiper_settings['breakpoints']['500']['spaceBetween']  = $settings['space_between_tablet'];

			}
			if ( ! empty( $settings['space_between_mobile'] ) ) {
				$swiper_settings['breakpoints']['0']['spaceBetween'] = $settings['space_between_mobile'];

			}
		}

		$prev_id = ! empty( $section_id ) ? 'prev-' . $section_id : '';
		$next_id = ! empty( $section_id ) ? 'next-' . $section_id : '';
		if ( 'yes' === $settings['show_arrows'] && 'yes' !== $settings['show_custom_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}
		if ( 'yes' === $settings['show_custom_arrows'] && 'yes' !== $settings['show_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $settings['custom_prev_id'],
				'nextEl' => '#' . $settings['custom_next_id'],

			);
		}

		if ( 'yes' === $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = true;
		}

		if ( $settings['loop'] ) {
			$swiper_settings['loop'] = 'true';
		}
		if ( $settings['autoplay'] && $settings['autoplay_speed'] ) {
			$swiper_settings['autoplay']['delay'] = $settings['autoplay_speed'];
		}
		if ( $settings['autoplay'] && $settings['pause_on_hover'] ) {
			$swiper_settings['autoplay']['pauseOnMouseEnter']    = true;
			$swiper_settings['autoplay']['disableOnInteraction'] = false;
		}
		if ( $settings['speed'] ) {
			$swiper_settings['speed'] = $settings['speed'];
		}

		return $swiper_settings;
	}

	/**
	 * Render message
	 */
	protected function load_more_render_message() {
		$settings = $this->get_settings();
		?>
		<div class="e-load-more-message mt-5"><?php echo esc_html( $settings['load_more_no_posts_custom_message'] ); ?></div>
		<?php
	}

	/**
	 * Render script in the editor.
	 *
	 * @param string $key widget ID.
	 */
	public function render_script( $key = '' ) {
		$key = '.' . $key;
		if ( Plugin::$instance->editor->is_edit_mode() ) :
			?>
			<script type="text/javascript">
			var swiperCarousel = (() => {
				// forEach function
				let forEach = (array, callback, scope) => {
				for (let i = 0; i < array.length; i++) {
					callback.call(scope, i, array[i]); // passes back stuff we need
				}
				};

				// Carousel initialisation
				let swiperCarousels = document.querySelectorAll("<?php echo esc_attr( $key ); ?>");
				forEach(swiperCarousels, (index, value) => {
					let postUserOptions,
					postsPagerOptions;
				if(value.dataset.swiperOptions != undefined) postUserOptions = JSON.parse(value.dataset.swiperOptions);


				// Pager
				if(postUserOptions.pager) {
					postsPagerOptions = {
					pagination: {
						el: postUserOptions.pager,
						clickable: true,
						bulletActiveClass: 'active',
						bulletClass: 'page-item',
						renderBullet: function (index, className) {
						return '<li class="' + className + '"><a href="#" class="page-link btn-icon btn-sm">' + (index + 1) + '</a></li>';
						}
					}
					}
				}

				// Slider init
				let options = {...postUserOptions, ...postsPagerOptions};
				let swiper = new Swiper(value, options);

				// Tabs (linked content)
				if(postUserOptions.tabs) {

					swiper.on('activeIndexChange', (e) => {
					let targetTab = document.querySelector(e.slides[e.activeIndex].dataset.swiperTab),
						previousTab = document.querySelector(e.slides[e.previousIndex].dataset.swiperTab);

					previousTab.classList.remove('active');
					targetTab.classList.add('active');
					});
				}

				});

				})();
			</script>
			<?php
		endif;
	}
}
