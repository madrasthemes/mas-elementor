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
			'section_products_style',
			array(
				'label' => __( 'Products', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'mas-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'products_class',
			array(
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'wc-products',
				'prefix_class' => 'mas-elementorducts-grid elementor-',
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'          => __( 'Columns Gap', 'mas-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 20,
				),
				'tablet_default' => array(
					'size' => 20,
				),
				'mobile_default' => array(
					'size' => 20,
				),
				'range'          => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'          => __( 'Rows Gap', 'mas-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 40,
				),
				'tablet_default' => array(
					'size' => 40,
				),
				'mobile_default' => array(
					'size' => 40,
				),
				'range'          => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => __( 'Alignment', 'mas-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'prefix_class' => 'mas-elementorduct-loop-item--align-',
				'selectors'    => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_image_style',
			array(
				'label'     => __( 'Image', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}}.elementor-wc-products .attachment-woocommerce_thumbnail',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products .attachment-woocommerce_thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => __( 'Spacing', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products .attachment-woocommerce_thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => __( 'Title', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-category__title' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title, ' .
								'{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-category__title',

			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'      => __( 'Spacing', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-category__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_rating_style',
			array(
				'label'     => __( 'Rating', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => __( 'Star Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'empty_star_color',
			array(
				'label'     => __( 'Empty Star Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'star_size',
			array(
				'label'     => __( 'Star Size', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'em',
				),
				'range'     => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rating_spacing',
			array(
				'label'      => __( 'Spacing', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_price_style',
			array(
				'label'     => __( 'Price', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price' => 'color: {{VALUE}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price ins' => 'color: {{VALUE}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price ins .amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .price',
			)
		);

		$this->add_control(
			'heading_old_price_style',
			array(
				'label'     => __( 'Regular Price', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'old_price_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price del' => 'color: {{VALUE}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price del .amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'old_price_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .price del .amount  ',
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .price del ',
			)
		);

		$this->add_control(
			'heading_button_style',
			array(
				'label'     => __( 'Button', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => __( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'exclude'   => array( 'color' ),
				'selector'  => '{{WRAPPER}}.elementor-wc-products ul.products li.product .button',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_text_padding',
			array(
				'label'      => __( 'Text Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_spacing',
			array(
				'label'      => __( 'Spacing', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_view_cart_style',
			array(
				'label'     => __( 'View Cart', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'view_cart_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products .added_to_cart' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'view_cart_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}}.elementor-wc-products .added_to_cart',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_box',
			array(
				'label' => __( 'Box', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'box_border_width',
			array(
				'label'      => __( 'Border Width', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'box_style_tabs' );

		$this->start_controls_tab(
			'classic_style_normal',
			array(
				'label' => __( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product',
			)
		);

		$this->add_control(
			'box_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_border_color',
			array(
				'label'     => __( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'classic_style_hover',
			array(
				'label' => __( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow_hover',
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover',
			)
		);

		$this->add_control(
			'box_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => __( 'Pagination', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginate' => 'yes',
				),
			)
		);

		$this->add_control(
			'pagination_spacing',
			array(
				'label'     => __( 'Spacing', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'show_pagination_border',
			array(
				'label'        => __( 'Border', 'mas-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Hide', 'mas-elementor' ),
				'label_on'     => __( 'Show', 'mas-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'elementor-show-pagination-border-',
			)
		);

		$this->add_control(
			'pagination_border_color',
			array(
				'label'     => __( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} nav.woocommerce-pagination ul li' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}',
				),
				'condition' => array(
					'show_pagination_border' => 'yes',
				),
			)
		);

		$this->add_control(
			'pagination_padding',
			array(
				'label'      => __( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'size_units' => array( 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span' => 'padding: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} nav.woocommerce-pagination',
			)
		);

		$this->start_controls_tabs( 'pagination_style_tabs' );

		$this->start_controls_tab(
			'pagination_style_normal',
			array(
				'label' => __( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'pagination_link_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
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

		$this->add_control(
			'pagination_link_color_hover',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
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

		$this->add_control(
			'pagination_link_color_active',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'sale_flash_style',
			array(
				'label' => __( 'Sale Flash', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_onsale_flash',
			array(
				'label'        => __( 'Sale Flash', 'mas-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Hide', 'mas-elementor' ),
				'label_on'     => __( 'Show', 'mas-elementor' ),
				'separator'    => 'before',
				'default'      => 'yes',
				'return_value' => 'yes',
				'selectors'    => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'display: block',
				),
			)
		);

		$this->add_control(
			'onsale_text_color',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_onsale_flash' => 'yes',
				),
			)
		);

		$this->add_control(
			'onsale_text_background_color',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_onsale_flash' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'onsale_typography',
				'selector'  => '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale',
				'condition' => array(
					'show_onsale_flash' => 'yes',
				),
			)
		);

		$this->add_control(
			'onsale_border_radius',
			array(
				'label'      => __( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'show_onsale_flash' => 'yes',
				),
			)
		);

		$this->add_control(
			'onsale_width',
			array(
				'label'      => __( 'Width', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'min-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_onsale_flash' => 'yes',
				),
			)
		);

		$this->add_control(
			'onsale_height',
			array(
				'label'      => __( 'Height', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_onsale_flash' => 'yes',
				),
			)
		);

		$this->add_control(
			'onsale_horizontal_position',
			array(
				'label'                => __( 'Position', 'mas-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'left'  => array(
						'title' => __( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'left'  => 'right: auto; left: 0',
					'right' => 'left: auto; right: 0',
				),
				'condition'            => array(
					'show_onsale_flash' => 'yes',
				),
			)
		);

		$this->add_control(
			'onsale_distance',
			array(
				'label'      => __( 'Distance', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -20,
						'max' => 20,
					),
					'em' => array(
						'min' => -2,
						'max' => 2,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'margin: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_onsale_flash' => 'yes',
				),
			)
		);

		$this->end_controls_section();
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

		$content = $shortcode->get_content();

		if ( $content ) {
			echo $content; //phpcs:ignore
		} elseif ( $this->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>'; //phpcs:ignore
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
