<?php
/**
 * The Product meta Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Product Price
 */
class Product_Price extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-product-price';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Product Price', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-price';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'price', 'product', 'sale' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_price_style',
			array(
				'label' => esc_html__( 'Price', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'mas-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_responsive_control(
			'text_align',
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
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'mas_price_margin',
			array(
				'label'      => esc_html__( 'Price Margin', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Used for margin in p tag', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} p.price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .price' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '.woocommerce {{WRAPPER}} .price',
			)
		);

		$this->add_control(
			'sale_heading',
			array(
				'label'     => esc_html__( 'Sale Price', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .price ins' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sale_price_typography',
				'selector' => '.woocommerce {{WRAPPER}} .price ins',
			)
		);

		$this->add_control(
			'price_block',
			array(
				'label'        => esc_html__( 'Stacked', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'elementor-product-price-block-',
			)
		);

		$this->add_responsive_control(
			'sale_price_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}}:not(.elementor-product-price-block-yes) del' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}}:not(.elementor-product-price-block-yes) del' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-product-price-block-yes del' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->register_onsale_flex_style_controls();

		$this->add_control(
			'striked_heading',
			array(
				'label'     => esc_html__( 'OnSale Striked Price', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'striked_price_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '.woocommerce {{WRAPPER}} .price:has(ins) del',
				'separator' => 'after',
			)
		);

		$this->add_control(
			'striked_price_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .price:has(ins) del' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register onsale flex style controls for this widget.
	 */
	protected function register_onsale_flex_style_controls() {

		$start   = is_rtl() ? 'right' : 'left';
		$end     = is_rtl() ? 'left' : 'right';
		$wrapper = '{{WRAPPER}} .price:has(ins), {{WRAPPER}} .electro-price:has(ins)';

		$this->add_responsive_control(
			'enable_flex',
			array(
				'label'     => esc_html__( 'Enable Flex', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'block' => array(
						'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'flex'  => array(
						'title' => esc_html__( 'Flex', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
				),
				'default'   => 'flex',
				'selectors' => array(
					$wrapper => 'display: {{VALUE}};',
				),
				// 'responsive' => true,

			)
		);

		$this->add_responsive_control(
			'flex_direction',
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
				'default'   => '',
				'selectors' => array(
					$wrapper => 'flex-direction:{{VALUE}};',
				),
				'condition' => array(
					'enable_flex' => 'flex',
				),
				'default'   => 'column',
			)
		);

		$this->add_responsive_control(
			'justify_content',
			array(
				'label'       => esc_html__( 'Justify Content', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'description' => esc_html__( 'Used for alignment in Flex Direction row and row-reversed', 'mas-addons-for-elementor' ),
				'default'     => '',
				'options'     => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-start-h',
					),
					'center'        => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-center-h',
					),
					'flex-end'      => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
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
				'default'     => 'center',
			)
		);

		$this->add_responsive_control(
			'align_items',
			array(
				'label'       => esc_html__( 'Align Items', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => '',
				'description' => esc_html__( 'Used for alignment in Flex Direction column and column-reversed', 'mas-addons-for-elementor' ),
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'selectors'   => array(
					$wrapper => 'align-items: {{VALUE}};',
				),
				'default'     => 'stretch',
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'vw' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					$wrapper => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

	}

	/**
	 * Render.
	 */
	protected function render() {
		global $product;
		$product = $this->get_product();

		if ( ! $product ) {
			return;
		}

		wc_get_template( '/single-product/price.php' );
	}

	/**
	 * Render Plain Content.
	 */
	public function render_plain_content() {}

	/**
	 * Get group name.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
