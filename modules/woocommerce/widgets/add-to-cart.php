<?php
/**
 * The Base Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Button;
use MASElementor\Base\Base_Widget_Trait;
use MASElementor\Modules\QueryControl\Module;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Add_To_Cart
 */
class Add_To_Cart extends Widget_Button {

	use Base_Widget_Trait;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-add-to-cart';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Custom Add To Cart', 'mas-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-woocommerce';
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
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' );
	}

	/**
	 * On Export.
	 *
	 * @param array $element element.
	 * @return array
	 */
	public function on_export( $element ) {
		unset( $element['settings']['product_id'] );

		return $element;
	}

	/**
	 * Unescape HTML.
	 *
	 * @param string $safe_text safe_text.
	 * @param string $text text.
	 * @return string
	 */
	public function unescape_html( $safe_text, $text ) {
		return $text;
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_product',
			array(
				'label' => esc_html__( 'Product', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'   => esc_html__( 'Product ID', 'mas-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'show_quantity',
			array(
				'label'       => esc_html__( 'Show Quantity', 'mas-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'    => esc_html__( 'Show', 'mas-elementor' ),
				'description' => esc_html__( 'Please note that switching on this option will disable some of the design controls.', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'quantity',
			array(
				'label'     => esc_html__( 'Quantity', 'mas-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'condition' => array(
					'show_quantity' => '',
				),
			)
		);

		$this->end_controls_section();

		parent::register_controls();

		$this->register_button_cart_update_controls();

		$this->remove_control( 'section_style' );

		$this->start_controls_section(
			'section_style_cart',
			array(
				'label' => esc_html__( 'Button', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_button_cart_style_controls();

		$this->end_controls_section();

		$this->start_injection(
			array(
				'of' => 'section_style_cart',
				'at' => 'before',
			)
		);

		$this->register_quantity_controls();

		$this->end_injection();

		$this->update_control(
			'link',
			array(
				'type'    => Controls_Manager::HIDDEN,
				'default' => array(
					'url' => '',
				),
			)
		);

		$this->update_control(
			'text',
			array(
				'default'     => esc_html__( 'Add to Cart', 'mas-elementor' ),
				'placeholder' => esc_html__( 'Add to Cart', 'mas-elementor' ),
			)
		);

		$this->update_responsive_control(
			'align',
			array(
				'prefix_class' => 'elementor%s-align-',
			)
		);

		$this->update_control(
			'selected_icon',
			array(
				'default' => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
			)
		);

		$this->update_control(
			'size',
			array(
				'condition' => array(
					'show_quantity!' => 'yes',
				),
			)
		);
	}

	/**
	 * Register controls for this widget.
	 */
	public function register_quantity_controls() {
		$this->start_controls_section(
			'section_quantity_style',
			array(
				'label'     => __( 'Quantity', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_quantity' => 'yes',
				),
			)
		);

		$this->add_control(
			'quantity_max_width',
			array(
				'label'      => esc_html__( 'Max Width', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => '80',
				),
				'size_units' => array( '%', 'px', 'vw' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-add-to-cart .quantity .input-text' => 'max-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'quantity_space',
			array(
				'label'     => esc_html__( 'Bottom Spacing', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
					'size' => '20',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-add-to-cart .quantity .input-text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'quantity_quantity_padding',
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
					'{{WRAPPER}} .mas-add-to-cart .quantity .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'quantity_align',
			array(
				'label'     => __( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-add-to-cart .quantity' => 'display: flex;justify-content: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs(
			'quantity_style_tabs',
			array(
				'separator' => 'before',
			)
		);

		$this->start_controls_tab(
			'quantity_style_normal',
			array(
				'label' => __( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'normal_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'separator'      => 'before',
				'selector'       => '{{WRAPPER}} .mas-add-to-cart .quantity .input-text',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '400',
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

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'quantity_border',
				'selector'       => '{{WRAPPER}} .mas-add-to-cart .quantity .input-text',
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
						'default' => '#d4d9dd',
					),
				),
			)
		);

		$this->add_control(
			'quantity_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'default'   => '##f9fafa',
				'selectors' => array(
					'{{WRAPPER}} .mas-add-to-cart .quantity .input-text' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'quantity_style_hover',
			array(
				'label' => __( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'hover_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'separator'      => 'before',
				'selector'       => '{{WRAPPER}} .mas-add-to-cart .quantity .input-text:hover',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '400',
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

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'hover_quantity_border',
				'selector'       => '{{WRAPPER}} .mas-add-to-cart .quantity .input-text:hover',
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
						'default' => '#d4d9dd',
					),
				),
			)
		);

		$this->add_control(
			'hover_quantity_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'default'   => '##f9fafa',
				'selectors' => array(
					'{{WRAPPER}} .mas-add-to-cart .quantity .input-text:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register button content update controls for this widget.
	 */
	protected function register_button_cart_update_controls() {
		$this->update_control(
			'button_type',
			array(
				'condition' => array(
					'show_quantity!' => 'yes',
				),
			),
		);
		$this->update_control(
			'icon_align',
			array(
				'condition' => array(
					'show_quantity!' => 'yes',
				),
			),
		);
		$this->update_control(
			'icon_indent',
			array(
				'selectors' => array(
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .cart .elementor-button-content-wrapper .elementor-button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			),
		);

	}

	/**
	 * Register button style controls for this widget.
	 *
	 * @param array $args conditional arguments.
	 */
	protected function register_button_cart_style_controls( $args = array() ) {
		$default_args = array(
			'section_condition' => array(),
		);

		$args = wp_parse_args( $args, $default_args );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cart_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'cart_text_shadow',
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button',
				'condition' => $args['section_condition'],
			)
		);

		$this->start_controls_tabs(
			'cart_tabs_button_style',
			array(
				'condition' => $args['section_condition'],
			)
		);

		$this->start_controls_tab(
			'cart_tab_button_normal',
			array(
				'label'     => esc_html__( 'Normal', 'mas-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .cart button'      => 'fill: {{VALUE}}; color: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'cart_background',
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'global' => array(
							'default' => Global_Colors::COLOR_ACCENT,
						),
					),
				),
				'condition'      => $args['section_condition'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_tab_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .cart button:hover, {{WRAPPER}} .cart button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .cart button:hover svg, {{WRAPPER}} .cart button:focus svg' => 'fill: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'cart_button_background_hover',
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus, {{WRAPPER}} .cart button:hover, {{WRAPPER}} .cart button:focus',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
				'condition'      => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .cart button:hover, {{WRAPPER}} .cart button:focus' => 'border-color: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_hover_animation',
			array(
				'label'     => esc_html__( 'Hover Animation', 'mas-elementor' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => $args['section_condition'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'cart_border',
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button',
				'separator' => 'before',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .cart button'      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'cart_button_box_shadow',
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_responsive_control(
			'cart_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .cart button'      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
				'condition'  => $args['section_condition'],
			)
		);
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['product_id'] ) ) {
			$product_id = $settings['product_id'];
		} elseif ( wp_doing_ajax() && ! empty( $settings['product_id'] ) ) {
			// PHPCS - No nonce is required.
			$product_id = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		} else {
			$product_id = get_queried_object_id();
		}

		global $product;
		$product = wc_get_product( $product_id );

		$settings = $this->get_settings_for_display();

		?><div class= "mas-add-to-cart">
		<?php

		if ( 'yes' === $settings['show_quantity'] ) {
			$this->render_form_button( $product );
		} else {
			$this->render_ajax_button( $product );
		}

		?>
		</div>
		<?Php
	}

	/**
	 * Before Add to Cart Quantity
	 *
	 * Added wrapper tag around the quantity input and "Add to Cart" button
	 * used to more solidly accommodate the layout when additional elements
	 * are added by 3rd party plugins.
	 *
	 * @since 3.6.0
	 */
	public function before_add_to_cart_quantity() {
		?>
		<div class="e-atc-qty-button-holder">
		<?php
	}

	/**
	 * After Add to Cart Quantity
	 *
	 * @since 3.6.0
	 */
	public function after_add_to_cart_button() {
		?>
		</div>
		<?php
	}

	/**
	 * Render Ajax Button.
	 *
	 * @param \WC_Product $product product.
	 */
	private function render_ajax_button( $product ) {
		$settings = $this->get_settings_for_display();

		if ( $product ) {
			if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				$product_type = $product->get_type();
			} else {
				$product_type = $product->product_type;
			}

			$class = implode(
				' ',
				array_filter(
					array(
						'product_type_' . $product_type,
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
					)
				)
			);

			$this->add_render_attribute(
				'button',
				array(
					'rel'             => 'nofollow',
					'href'            => $product->add_to_cart_url(),
					'data-quantity'   => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
					'data-product_id' => $product->get_id(),
					'class'           => $class,
				)
			);

		} elseif ( current_user_can( 'manage_options' ) ) {
			$settings['text'] = esc_html__( 'Please set a valid product', 'mas-elementor' );
			$this->set_settings( $settings );
		}

		parent::render();
	}

	/**
	 * Render Form Button.
	 *
	 * @param \WC_Product $product product.
	 */
	private function render_form_button( $product ) {
		if ( ! $product && current_user_can( 'manage_options' ) ) {
			echo esc_html__( 'Please set a valid product', 'mas-elementor' );

			return;
		}

		$text_callback = function() {
			ob_start();
			$this->render_text();

			return ob_get_clean();
		};

		add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		add_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		add_filter( 'esc_html', array( $this, 'unescape_html' ), 10, 2 );

		woocommerce_template_single_add_to_cart();

		remove_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		remove_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		remove_filter( 'esc_html', array( $this, 'unescape_html' ) );
	}

	/**
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 */
	protected function content_template() {}

	/**
	 * Render
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
