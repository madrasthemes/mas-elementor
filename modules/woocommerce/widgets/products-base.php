<?php
/**
 * The Products Base.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use MASElementor\Modules\Woocommerce\Classes\Products_Renderer;
use MASElementor\Modules\Woocommerce\Classes\Current_Query_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



/**
 * Class Products_Base
 */
abstract class Products_Base extends Base_Widget {

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_mas_product_style',
			array(
				'label' => __( 'Product', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'mas_product_grid_margin',
			array(
				'label'      => esc_html__( 'Grid Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-products.mas-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'products_gap',
			array(
				'label'      => __( 'Gap', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 13,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-products.mas-grid' => 'gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'products_row_gap',
			array(
				'label'      => __( 'Row Gap', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-products.mas-grid' => 'row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'loop_product_height',
			array(
				'label'      => __( 'Product Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'custom' ),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-product,{{WRAPPER}} .mas-product [data-elementor-type="mas-post"]' => 'height: {{SIZE}}{{UNIT}} !important',
				),
			)
		);

		$this->add_responsive_control(
			'loop_product_width',
			array(
				'label'      => __( 'Product Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-product,{{WRAPPER}} .mas-product [data-elementor-type="mas-post"]' => 'width: {{SIZE}}{{UNIT}} !important',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => __( 'Pagination', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginate'         => 'yes',
					'enable_carousel!' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_width',
			array(
				'label'      => __( 'Pagination Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce nav.woocommerce-pagination' => 'width: {{SIZE}}{{UNIT}} !important',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_text_align',
			array(
				'label'           => esc_html__( 'Text Alignment', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::CHOOSE,
				'options'         => array(
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
				'devices'         => array( 'desktop', 'tablet', 'mobile' ),
				'desktop_default' => 'center',
				'tablet_default'  => 'center',
				'mobile_default'  => 'center',
				'selectors'       => array(
					'{{WRAPPER}} .woocommerce nav.woocommerce-pagination' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'product_border',
				'selector'       => '{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul',
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
				'label'     => __( 'Spacing', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'pagination_spacing',
			array(
				'label'      => __( 'Top Spacing', 'mas-addons-for-elementor' ),
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
				),
			)
		);

		$this->add_responsive_control(
			'product_pagination_spacing',
			array(
				'label'      => __( 'Space Between', 'mas-addons-for-elementor' ),
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
				),
			)
		);

		$this->add_control(
			'pagination_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
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
				'label' => __( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'normal_pagination_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul li a',
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
				'label'     => __( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'normal_single_pag_border',
				'selector'       => '{{WRAPPER}} nav.woocommerce-pagination ul li a',
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
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_hover',
			array(
				'label' => __( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'hover_pagination_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul li a:hover',
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
				'label'     => __( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00000000',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'hover_single_pag_border',
				'selector'       => '{{WRAPPER}} nav.woocommerce-pagination ul a:hover',
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
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_active',
			array(
				'label' => __( 'Active', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'active_pagination_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul li span',
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
				'label'     => __( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			array(
				'label'     => __( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'active_single_pag_border',
				'selector'       => '{{WRAPPER}} nav.woocommerce-pagination ul li span.current',
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
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}} nav.woocommerce-pagination ul li span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter_style',
			array(
				'label'     => __( 'Filter', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'allow_order'      => 'yes',
					'enable_carousel!' => 'yes',
				),
			)
		);

		$this->add_control(
			'pagination_filter_color',
			array(
				'label'     => __( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-ordering select' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pagination_filter_typo',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .woocommerce-ordering select',
			)
		);

		// Anchor Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'mas_filter_border_type',
				'selector'       => '{{WRAPPER}} .woocommerce-ordering select',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => false,
						),
					),
					'color'  => array(
						'default' => '#f2f4f5',
					),
				),
			)
		);

		$this->add_responsive_control(
			'mas_filter_border_radius',
			array(
				'label'           => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::DIMENSIONS,
				'size_units'      => array( 'px', '%', 'em' ),
				'devices'         => array( 'desktop', 'tablet', 'mobile' ),
				'desktop_default' => array(
					'top'      => '3',
					'right'    => '3',
					'bottom'   => '3',
					'left'     => '3',
					'isLinked' => false,
				),
				'tablet_default'  => array(
					'top'      => '3',
					'right'    => '3',
					'bottom'   => '3',
					'left'     => '3',
					'isLinked' => false,
				),
				'mobile_default'  => array(
					'top'      => '3',
					'right'    => '3',
					'bottom'   => '3',
					'left'     => '3',
					'isLinked' => false,
				),
				'selectors'       => array(
					'{{WRAPPER}} .woocommerce-ordering select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mas_product_filter_spacing_heading',
			array(
				'label'     => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
		);

		$this->add_responsive_control(
			'mas_product_filter_padding',
			array(
				'label'           => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::DIMENSIONS,
				'size_units'      => array( 'px', 'em', '%', 'rem' ),
				'selectors'       => array(
					'{{WRAPPER}} .woocommerce-ordering select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'desktop_default' => array(
					'top'      => '8',
					'right'    => '16',
					'bottom'   => '8',
					'left'     => '16',
					'isLinked' => false,
				),
				'tablet_default'  => array(
					'top'      => '8',
					'right'    => '16',
					'bottom'   => '8',
					'left'     => '16',
					'isLinked' => false,
				),
				'mobile_default'  => array(
					'top'      => '8',
					'right'    => '16',
					'bottom'   => '8',
					'left'     => '16',
					'isLinked' => false,
				),
			)
		);

		$this->add_responsive_control(
			'mas_product_filter_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-ordering' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Flex Controls.
	 *
	 * @param string $name name of the control.
	 * @param string $wrapper wrapper for control.
	 */
	public function flex_controls( $name, $wrapper ) {
		$start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';
		$this->add_control(
			$name . 'items',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Items', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			$name . 'product_wrap_direction',
			array(
				'label'     => esc_html__( 'Direction', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'row'            => array(
						'title' => esc_html__( 'Row - horizontal', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-' . $end,
					),
					'column'         => array(
						'title' => esc_html__( 'Column - vertical', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-down',
					),
					'row-reverse'    => array(
						'title' => esc_html__( 'Row - reversed', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-' . $start,
					),
					'column-reverse' => array(
						'title' => esc_html__( 'Column - reversed', 'mas-addons-for-elementor' ),
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
			$name . 'product_wrap_justify_content',
			array(
				'label'       => esc_html__( 'Justify Content', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
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
						'title' => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-between-h',
					),
					'space-around'  => array(
						'title' => esc_html__( 'Space Around', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-around-h',
					),
					'space-evenly'  => array(
						'title' => esc_html__( 'Space Evenly', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-evenly-h',
					),
				),
				'selectors'   => array(
					$wrapper => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'product_wrap_align_items',
			array(
				'label'     => esc_html__( 'Align Items', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'selectors' => array(
					$wrapper => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'product_wrap_gap',
			array(
				'label'                  => esc_html__( 'Gaps', 'mas-addons-for-elementor' ),
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
			$name . 'product_wrapper_wrap',
			array(
				'label'       => esc_html_x( 'Wrap', 'Flex Container Control', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'nowrap' => array(
						'title' => esc_html_x( 'No Wrap', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-nowrap',
					),
					'wrap'   => array(
						'title' => esc_html_x( 'Wrap', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
				),
				'description' => esc_html__( 'Items within the container can stay in a single line (No wrap), or break into multiple lines (Wrap).', 'mas-addons-for-elementor' ),
				'default'     => '',
				'selectors'   => array(
					$wrapper => 'flex-wrap: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'product_wrapper_align_content',
			array(
				'label'     => esc_html__( 'Align Content', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''              => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'center'        => esc_html__( 'Center', 'mas-addons-for-elementor' ),
					'flex-start'    => esc_html__( 'Start', 'mas-addons-for-elementor' ),
					'flex-end'      => esc_html__( 'End', 'mas-addons-for-elementor' ),
					'space-between' => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
					'space-around'  => esc_html__( 'Space Around', 'mas-addons-for-elementor' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					$wrapper => 'align-content: {{VALUE}};',
				),
				'condition' => array(
					'product_wrapper_wrap' => 'wrap',
				),
			)
		);
	}

	/**
	 * Get shortcode object
	 *
	 * @param array $settings settings of the widget.
	 */
	public function get_shortcode_object( $settings ) {
		if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';
			return new Current_Query_Renderer( $settings, $type );
		}
		$type = 'products';
		return new Products_Renderer( $settings, $type );
	}

	/**
	 * Render
	 */
	protected function render() {
		if ( WC()->session ) {
			wc_print_notices();
		}

		// For Products_Renderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		$settings = $this->get_settings();

		$shortcode = $this->get_shortcode_object( $settings );

		if ( $shortcode->get_content() ) {
			echo $shortcode->get_content(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} elseif ( $this->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="elementor-nothing-found mas-elementor-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
		}

		$this->render_script( 'swiper-products-' . $this->get_id() );
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
