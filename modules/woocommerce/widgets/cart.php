<?php
/**
 * The Cart Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use MASElementor\Modules\Woocommerce\Module;
use MASElementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use MASElementor\Modules\QueryControl\Module as QueryControlModule;
use Elementor\Core\Base\Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Cart
 */
class Cart extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-cart';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Cart', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-woo-cart';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'cart' );
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
	 * Return the script dependencies of the module.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'wc-cart',
			'selectWoo',
		);
	}

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'mas-woocommerce-cart', 'select2' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'General', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'cart_layout',
			array(
				'label'        => esc_html__( 'Layout', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'two-column' => esc_html__( 'Two columns', 'mas-addons-for-elementor' ),
					'one-column' => esc_html__( 'One column', 'mas-addons-for-elementor' ),
				),
				'default'      => 'two-column',
				'prefix_class' => 'e-cart-layout-',
			)
		);

		$this->add_control(
			'sticky_right_column',
			array(
				'label'              => esc_html__( 'Sticky Right Column', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'mas-addons-for-elementor' ),
				'label_off'          => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'description'        => esc_html__( 'This option will allow the right column (e.g, Cart Totals) to be sticky while scrolling.', 'mas-addons-for-elementor' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => array(
					'cart_layout!' => 'one-column',
				),
			)
		);

		$this->add_control(
			'sticky_right_column_offset',
			array(
				'label'              => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'frontend_available' => true,
				'conditions'         => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'sticky_right_column',
							'operator' => '!==',
							'value'    => '',
						),
						array(
							'name'     => 'cart_layout',
							'operator' => '!==',
							'value'    => 'one-column',
						),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_order_summary',
			array(
				'label'     => esc_html__( 'Order Summary', 'mas-addons-for-elementor' ),
				'condition' => array(
					'update_cart_automatically' => '',
				),
			)
		);

		$this->add_control(
			'update_cart_button_heading',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Update Cart Button', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'update_cart_button_text',
			array(
				'label'       => esc_html__( 'Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Update Cart', 'mas-addons-for-elementor' ),
				'default'     => esc_html__( 'Update Cart', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'update_cart_button_alignment',
			array(
				'label'                => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'   => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'     => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justify', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} .woocommerce-cart-form' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'start'   => '--update-cart-button-alignment: start; --update-cart-button-width: auto;',
					'center'  => '--update-cart-button-alignment: center; --update-cart-button-width: auto;',
					'end'     => '--update-cart-button-alignment: end; --update-cart-button-width: auto;',
					'justify' => '--update-cart-button-alignment: justify; --update-cart-button-width: 100%;',
				),
			)
		);

		$this->end_controls_section();

		if ( $this->is_wc_feature_active( 'coupons' ) ) {

			$this->start_controls_section(
				'section_coupon',
				array(
					'label' => esc_html__( 'Coupon', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'section_coupon_display',
				array(
					'label'     => esc_html__( 'Coupon', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
					'default'   => 'yes',
				)
			);

			$this->add_control(
				'apply_coupon_heading',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => esc_html__( 'Apply Coupon Button', 'mas-addons-for-elementor' ),
					'condition' => array(
						'section_coupon_display' => 'yes',
					),
				)
			);

			$this->add_control(
				'apply_coupon_button_text',
				array(
					'label'       => esc_html__( 'Text', 'mas-addons-for-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active' => true,
					),
					'placeholder' => esc_html__( 'Apply coupon', 'mas-addons-for-elementor' ),
					'default'     => esc_html__( 'Apply coupon', 'mas-addons-for-elementor' ),
					'condition'   => array(
						'section_coupon_display' => 'yes',
					),
				)
			);

			$this->add_responsive_control(
				'apply_coupon_button_alignment',
				array(
					'label'                => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
					'type'                 => Controls_Manager::CHOOSE,
					'options'              => array(
						'start'   => array(
							'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center'  => array(
							'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-text-align-center',
						),
						'end'     => array(
							'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-text-align-right',
						),
						'justify' => array(
							'title' => esc_html__( 'Justify', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-text-align-justify',
						),
					),
					'selectors'            => array(
						'{{WRAPPER}} .coupon' => '{{VALUE}}',
					),
					'selectors_dictionary' => array(
						'start'   => '--apply-coupon-button-alignment: start; --apply-coupon-button-width: auto;',
						'center'  => '--apply-coupon-button-alignment: center;  --apply-coupon-button-width: auto;',
						'end'     => '--apply-coupon-button-alignment: end;  --apply-coupon-button-width: auto;',
						'justify' => '--apply-coupon-button-alignment: center; --apply-coupon-button-width: 100%;',
					),
					'condition'            => array(
						'section_coupon_display' => 'yes',
					),
				)
			);

			$this->add_control(
				'coupon_button_alignment_note',
				array(
					'raw'             => esc_html__( 'Note: This control will only affect screen sizes Tablet and below', 'mas-addons-for-elementor' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-descriptor',
					'condition'       => array(
						'section_coupon_display' => 'yes',
					),
				)
			);

			$this->end_controls_section();
		}

		$this->start_controls_section(
			'section_totals',
			array(
				'label' => esc_html__( 'Totals', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'totals_section_title',
			array(
				'label'       => esc_html__( 'Section Title', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Cart Totals', 'mas-addons-for-elementor' ),
				'default'     => esc_html__( 'Cart Totals', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'totals_section_title_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--totals-title-alignment: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'update_shipping_button_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Update Shipping Button', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'update_shipping_button_text',
			array(
				'label'       => esc_html__( 'Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Update', 'mas-addons-for-elementor' ),
				'default'     => esc_html__( 'Update', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'update_shipping_button_alignment',
			array(
				'label'                => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'   => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'     => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justify', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} .shipping-calculator-form' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'start'   => '--update-shipping-button-alignment: start; --update-shipping-button-width: auto;',
					'center'  => '--update-shipping-button-alignment: center;  --update-shipping-button-width: auto;',
					'end'     => '--update-shipping-button-alignment: end;  --update-shipping-button-width: auto;',
					'justify' => '--update-shipping-button-alignment: center; --update-shipping-button-width: 100%;',
				),
			)
		);

		$this->add_control(
			'checkout_button_heading',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Checkout Button', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'checkout_button_text',
			array(
				'label'       => esc_html__( 'Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Proceed to Checkout', 'mas-addons-for-elementor' ),
				'default'     => esc_html__( 'Proceed to Checkout', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'checkout_button_alignment',
			array(
				'label'                => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'   => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'     => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justify', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} .wc-proceed-to-checkout' => '{{VALUE}};',
				),
				'selectors_dictionary' => array(
					'start'   => '--place-order-title-alignment: flex-start; --checkout-button-width: fit-content;',
					'center'  => '--place-order-title-alignment: center; --checkout-button-width: fit-content;',
					'end'     => '--place-order-title-alignment: flex-end; --checkout-button-width: fit-content;',
					'justify' => '--place-order-title-alignment: stretch; --checkout-button-width: 100%;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			array(
				'label' => esc_html__( 'Additional Options', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'update_cart_automatically',
			array(
				'label'                => esc_html__( 'Update Cart Automatically', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SWITCHER,
				'label_on'             => esc_html__( 'Yes', 'mas-addons-for-elementor' ),
				'label_off'            => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}};',
				),
				'selectors_dictionary' => array(
					'yes' => '--update-cart-automatically-display: none;',
				),
				'frontend_available'   => true,
				'render_type'          => 'template',
			)
		);

		$this->add_control(
			'update_cart_automatically_description',
			array(
				'raw'             => esc_html__( 'Changes to the cart will update automatically.', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->add_control(
			'additional_template_switch',
			array(
				'label'        => esc_html__( 'Customize empty cart', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mas-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'return_value' => 'active',
				'default'      => '',
				'render_type'  => 'template',
				'prefix_class' => 'e-cart-empty-template-',
			)
		);

		$this->add_control(
			'additional_template_description',
			array(
				'raw'             => sprintf(
					/* translators: 1: Saved templates link opening tag, 2: Link closing tag. */
					esc_html__( 'Replaces the default WooCommerce Empty Cart screen with a custom template. (Don’t have one? Head over to %1$sSaved Templates%2$s)', 'mas-addons-for-elementor' ),
					sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=elementor_library&tabs_group=library#add_new' ) ),
					'</a>'
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor elementor-descriptor-subtle',
				'condition'       => array(
					'additional_template_switch' => 'active',
				),
			)
		);

		$this->add_control(
			'additional_template_select_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Choose template', 'mas-addons-for-elementor' ),
				'condition' => array(
					'additional_template_switch' => 'active',
				),
			)
		);

		$document_types = Plugin::elementor()->documents->get_document_types(
			array(
				'show_in_library' => true,
			)
		);

		$this->add_control(
			'additional_template_select',
			array(
				'type'               => QueryControlModule::QUERY_CONTROL_ID,
				'label_block'        => true,
				'show_label'         => false,
				'autocomplete'       => array(
					'object' => QueryControlModule::QUERY_OBJECT_LIBRARY_TEMPLATE,
					'query'  => array(
						'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
							array(
								'key'     => Document::TYPE_META_KEY,
								'value'   => array_keys( $document_types ),
								'compare' => 'IN',
							),
						),
					),
				),
				'frontend_available' => true,
				'condition'          => array(
					'additional_template_switch' => 'active',
				),
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'edit_button',
			array(
				'raw'             => sprintf( '<a href="#" target="_blank" class="elementor-button elementor-edit-template" style="margin-top:0px;"><i class="eicon-pencil"> %s</i></a>', esc_html__( 'Edit Template', 'mas-addons-for-elementor' ) ),
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-edit-template-wrapper',
				'condition'       => array(
					'additional_template_switch' => 'active',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_style',
			array(
				'label' => esc_html__( 'Sections', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'sections_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .e-cart-section',
			)
		);

		$this->add_control(
			'sections_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'sections_border_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-cart-section' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'sections_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'sections_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-border-color: {{VALUE}};',
				),
				'condition' => array(
					'sections_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'sections_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sections_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sections_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--sections-margin: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_typography',
			array(
				'label' => esc_html__( 'Typography', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'sections_typography',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Titles', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'sections_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sections_titles_typography',
				'selector' => '{{WRAPPER}} .cart_totals h2',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'sections_titles_text_shadow',
				'selector' => '{{WRAPPER}} .cart_totals h2',
			)
		);

		$this->add_responsive_control(
			'sections_title_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array( 'px' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--sections-title-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sections_descriptions_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Descriptions', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'sections_descriptions_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' => '--sections-descriptions-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sections_descriptions_typography',
				'selector' => '{{WRAPPER}} .e-cart-content, {{WRAPPER}} .woocommerce-shipping-destination, {{WRAPPER}} .shipping-calculator-button',
			)
		);

		$this->add_responsive_control(
			'sections_descriptions_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array( 'px' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--sections-descriptions-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sections_links_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Links', 'mas-addons-for-elementor' ),
			)
		);

		$this->start_controls_tabs( 'links_colors' );

		$this->start_controls_tab( 'links_normal_colors', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'links_normal_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--links-normal-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'links_hover_colors', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'links_hover_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--links-hover-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sections_radio_buttons_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Radio Buttons', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sections_radio_buttons_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-radio-buttons-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sections_radio_buttons_typography',
				'selector' => '{{WRAPPER}} #shipping_method li label',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_forms',
			array(
				'label' => esc_html__( 'Forms', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'forms_rows_gap',
			array(
				'label'      => esc_html__( 'Rows Gap', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array( 'px' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--forms-rows-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'forms_field_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Field', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forms_field_typography',
				'selector' => '{{WRAPPER}} .coupon .input-text, {{WRAPPER}} .cart-collaterals .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-selection--single',
			)
		);

		$this->start_controls_tabs( 'forms_fields_styles' );

		$this->start_controls_tab( 'forms_fields_normal_styles', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'forms_fields_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-fields-normal-color: {{VALUE}};',
					'.e-woo-select2-wrapper .select2-results__option' => 'color: {{VALUE}};',
					// style select2 arrow.
					'{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__arrow b' => 'border-color: {{VALUE}} transparent transparent transparent;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'forms_fields_normal_background',
				'selector' => '{{WRAPPER}} .coupon .input-text, {{WRAPPER}} .e-cart-totals .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-selection--single',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'forms_fields_normal_box_shadow',
				'selector' => '{{WRAPPER}} .coupon .input-text, {{WRAPPER}} .e-cart-totals .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-selection--single',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'forms_fields_focus_styles', array( 'label' => esc_html__( 'Focus', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'forms_fields_focus_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-fields-focus-color: {{VALUE}}',
					'.e-woo-select2-wrapper .select2-results__option:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'forms_fields_focus_background',
				'selector' => '{{WRAPPER}} .coupon .input-text:focus, {{WRAPPER}} .e-cart-totals .input-text:focus, {{WRAPPER}} select:focus, {{WRAPPER}} .select2-selection--single:focus',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'forms_fields_focus_box_shadow',
				'selector' => '{{WRAPPER}} .coupon .input-text:focus, {{WRAPPER}} .e-cart-totals .input-text:focus, {{WRAPPER}} select:focus, {{WRAPPER}} .select2-selection--single:focus',
			)
		);

		$this->add_control(
			'forms_fields_focus_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-fields-focus-border-color: {{VALUE}}',
				),
				'condition' => array(
					'forms_fields_border_border!' => '',
				),
			)
		);

		$this->add_control(
			'forms_fields_focus_transition_duration',
			array(
				'label'     => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ) . ' (ms)',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-fields-focus-transition-duration: {{SIZE}}ms',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'forms_fields_border',
				'selector'  => '{{WRAPPER}} .coupon .input-text, {{WRAPPER}} .cart-collaterals .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-selection--single',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'forms_fields_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--forms-fields-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'forms_fields_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' => '--forms-fields-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// style select2.
					'{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__rendered' => 'line-height: calc( ({{TOP}}{{UNIT}}*2) + 16px ); padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__arrow' => 'height: calc( ({{TOP}}{{UNIT}}*2) + 16px ); right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .select2-container--default .select2-selection--single' => 'height: auto;',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'forms_button_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Buttons', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forms_button_typography',
				'selector' => '{{WRAPPER}} .shop_table .button',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'forms_button_text_shadow',
				'selector' => '{{WRAPPER}} .shop_table .button',
			)
		);

		$this->start_controls_tabs( 'forms_buttons_styles' );

		$this->start_controls_tab( 'forms_buttons_normal_styles', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'forms_buttons_normal_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-buttons-normal-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'forms_buttons_normal_background',
				'selector' => '{{WRAPPER}} .shop_table .button',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'forms_buttons_normal_box_shadow',
				'selector' => '{{WRAPPER}} .shop_table .button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'forms_buttons_hover_styles', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'forms_buttons_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-buttons-hover-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'forms_buttons_hover_background',
				'selector' => '{{WRAPPER}} .shop_table .button:hover, {{WRAPPER}} .shop_table .button:disabled[disabled]:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'forms_buttons_focus_box_shadow',
				'selector' => '{{WRAPPER}} .shop_table .button:hover',
			)
		);

		$this->add_control(
			'forms_buttons_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-buttons-hover-border-color: {{VALUE}}',
				),
				'condition' => array(
					'forms_buttons_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'forms_buttons_hover_transition_duration',
			array(
				'label'     => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ) . ' (ms)',
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-buttons-hover-transition-duration: {{SIZE}}ms',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
			)
		);

		$this->add_control(
			'forms_buttons_hover_animation',
			array(
				'label'              => esc_html__( 'Hover Animation', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::HOVER_ANIMATION,
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'forms_buttons_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-buttons-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'forms_buttons_border_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .shop_table .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'forms_buttons_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'forms_buttons_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-buttons-border-color: {{VALUE}};',
				),
				'condition' => array(
					'forms_buttons_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'forms_buttons_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--forms-buttons-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'forms_buttons_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--forms-buttons-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; --forms-buttons-width: auto;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tabs_order_summary',
			array(
				'label' => esc_html__( 'Order Summary', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'order_summary_rows_gap',
			array(
				'label'      => esc_html__( 'Rows Gap', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array( 'px' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--order-summary-rows-gap-top: calc( {{SIZE}}{{UNIT}}/2 ); --order-summary-rows-gap-bottom: calc( {{SIZE}}{{UNIT}}/2 );',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'order_summary_titles_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Titles', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-cart-form' => '--order-summary-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_summary_title_typography',
				'selector' => '{{WRAPPER}} .e-shop-table .cart th, {{WRAPPER}} .e-shop-table .cart td:before',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'order_summary_title_text_shadow',
				'selector' => '{{WRAPPER}} .e-shop-table .cart th, {{WRAPPER}} .e-shop-table .cart td:before',
			)
		);

		$this->add_responsive_control(
			'order_summary_title_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array( 'px' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--order-summary-title-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'order_summary_items_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Items', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-summary-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_summary_items_typography',
				'selector' => '{{WRAPPER}} .cart td span, {{WRAPPER}} .cart td, {{WRAPPER}} .input-text.qty',
			)
		);

		$this->add_control(
			'order_summary_variations_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Variations', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_variations_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-summary-variations-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_summary_variations_typography',
				'selector' => '{{WRAPPER}} .product-name .variation',
			)
		);

		$this->add_control(
			'order_summary_product_link_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Link', 'mas-addons-for-elementor' ),
			)
		);

		$this->start_controls_tabs( 'order_summary' );

		$this->start_controls_tab( 'order_summary_normal', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'product_link_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--product-link-normal-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'order_summary_hover', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'product_link_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--product-link-hover-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'order_summary_divider_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Dividers', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'order_summary_items_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-summary-items-divider-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_items_divider_weight',
			array(
				'label'      => esc_html__( 'Weight', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--order-summary-items-divider-weight: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'order_summary_quantity_border_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Quantity Borders', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'order_summary_quantity_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-summary-quantity-border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_quantity_border_weight',
			array(
				'label'      => esc_html__( 'Weight', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--order-summary-quantity-border-weight: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'order_summary_remove_icon_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Remove icon', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'order_summary_remove_icon' );

		$this->start_controls_tab( 'order_summary_remove_icon_normal', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'order_summary_remove_icon_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-summary-remove-icon-normal-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'order_summary_remove_icon_hover', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'order_summary_remove_icon_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-summary-remove-icon-hover-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_totals',
			array(
				'label' => esc_html__( 'Totals', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'totals_rows_gap',
			array(
				'label'      => esc_html__( 'Rows Gap', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array( 'px' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--totals-rows-gap-top: calc( {{SIZE}}{{UNIT}}/2 ); --totals-rows-gap-bottom: calc( {{SIZE}}{{UNIT}}/2 );',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'totals_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Titles & Totals', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'totals_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--totals-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'totals_typography',
				'selector' => '{{WRAPPER}} .cart_totals .shop_table td:before, {{WRAPPER}} .cart_totals .shop_table td .woocommerce-Price-amount',
			)
		);

		$this->add_control(
			'totals_divider_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Divider Total', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'totals_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--totals-divider-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'totals_divider_weight',
			array(
				'label'      => esc_html__( 'Weight', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--totals-divider-weight: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_checkout_button',
			array(
				'label' => esc_html__( 'Checkout Button', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'checkout_button_typography',
				'selector' => '{{WRAPPER}} .checkout-button',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'checkout_button_text_shadow',
				'selector' => '{{WRAPPER}} .checkout-button',
			)
		);

		$this->start_controls_tabs( 'checkout_button_styles' );

		$this->start_controls_tab( 'checkout_button_normal_styles', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'checkout_button_normal_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-normal-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'checkout_button_normal_background',
				'selector' => '{{WRAPPER}} .woocommerce .wc-proceed-to-checkout .checkout-button',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'checkout_button_normal_box_shadow',
				'selector' => '{{WRAPPER}} .checkout-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'checkout_button_hover_styles', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'checkout_button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-hover-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'checkout_button_hover_background',
				'selector' => '{{WRAPPER}} .woocommerce .wc-proceed-to-checkout .checkout-button:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'checkout_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .checkout-button:hover',
			)
		);

		$this->add_control(
			'checkout_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-hover-border-color: {{VALUE}}',
				),
				'condition' => array(
					'checkout_button_border_border!' => '',
				),
			)
		);

		$this->add_control(
			'checkout_button_hover_transition_duration',
			array(
				'label'     => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ) . ' (ms)',
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-hover-transition-duration: {{SIZE}}ms',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
			)
		);

		$this->add_control(
			'checkout_button_hover_animation',
			array(
				'label'              => esc_html__( 'Hover Animation', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::HOVER_ANIMATION,
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'checkout_button_border',
				'selector'  => '{{WRAPPER}} .checkout-button',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'checkout_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--checkout-button-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'checkout_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--checkout-button-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; --checkout-button-width: fit-content;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_customize',
			array(
				'label' => esc_html__( 'Customize', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$customize_options = array();

		$customize_options += array(
			'customize_order_summary' => esc_html__( 'Order Summary', 'mas-addons-for-elementor' ),
		);

		if ( $this->is_wc_feature_active( 'coupons' ) ) {
			$customize_options += array(
				'customize_coupon' => esc_html__( 'Coupon', 'mas-addons-for-elementor' ),
			);
		}

		$customize_options += array(
			'customize_totals' => esc_html__( 'Totals', 'mas-addons-for-elementor' ),
		);

		$this->add_control(
			'section_cart_show_customize_elements',
			array(
				'label'       => esc_html__( 'Select sections of the cart to customize:', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $customize_options,
				'render_type' => 'ui',
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_customize_order_summary',
			array(
				'label'     => esc_html__( 'Customize: Order Summary', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_cart_show_customize_elements' => 'customize_order_summary',
				),
			)
		);

		$this->add_control(
			'order_summary_section_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_section_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-shop-table' => '--sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'order_summary_section_normal_box_shadow',
				'selector'  => '{{WRAPPER}} .e-shop-table',
				'separator' => 'after',
			)
		);

		$this->add_control(
			'order_summary_section_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .e-shop-table' => '--sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'order_summary_section_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-shop-table' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'order_summary_section_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'order_summary_section_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-shop-table' => '--sections-border-color: {{VALUE}};',
				),
				'condition' => array(
					'order_summary_section_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_section_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-shop-table' => '--sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_section_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-shop-table' => '--sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_section_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-shop-table' => '--sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_customize_totals',
			array(
				'label'     => esc_html__( 'Customize: Totals', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_cart_show_customize_elements' => 'customize_totals',
				),
			)
		);

		$this->add_control(
			'customize_totals_section_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'sections_totals_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-cart-totals' => '--sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'totals_section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .e-cart-totals',
			)
		);

		$this->add_control(
			'totals_section_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .e-cart-totals' => '--sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'totals_section_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-cart-totals' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'totals_section_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'totals_section_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-cart-totals' => '--sections-border-color: {{VALUE}};',
				),
				'condition' => array(
					'totals_section_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'totals_section_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-cart-totals' => '--sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'checkout_sections_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-cart-totals' => '--sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'totals_section_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-cart-totals' => '--sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'totals_section_titles_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'totals_section_titles_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart_totals' => '--sections-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'totals_section_titles_typography',
				'selector' => '{{WRAPPER}} .cart_totals h2',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'totals_section_titles_text_shadow',
				'selector'  => '{{WRAPPER}} .cart_totals h2',
				'separator' => 'after',
			)
		);

		$this->add_control(
			'totals_section_content_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Description', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'totals_section_content_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-cart-totals' => '--sections-descriptions-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'totals_section_content_typography',
				'selector' => '{{WRAPPER}} .e-cart-totals .e-cart-content, {{WRAPPER}} .e-cart-totals .woocommerce-shipping-destination, {{WRAPPER}} .e-cart-totals .shipping-calculator-button',
			)
		);

		$this->add_control(
			'totals_section_link_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Link', 'mas-addons-for-elementor' ),
			)
		);

		$this->start_controls_tabs( 'totals_section_links_colors' );

		$this->start_controls_tab( 'totals_section_links_normal_colors', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'totals_section_links_normal_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-cart-totals' => '--links-normal-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'totals_section_links_hover_colors', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'totals_section_links_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-cart-totals' => '--links-hover-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_customize_coupon',
			array(
				'label'     => esc_html__( 'Customize: Coupon', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_cart_show_customize_elements' => 'customize_coupon',
				),
			)
		);

		$this->add_control(
			'coupon_section_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'customize_coupon_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .coupon' => '--sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'customize_coupon_section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .coupon',
			)
		);

		$this->add_control(
			'customize_coupon_section_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .coupon' => '--sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'customize_coupon_section_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .coupon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'customize_coupon_section_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'customize_coupon_section_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .coupon' => '--sections-border-color: {{VALUE}};',
				),
				'condition' => array(
					'customize_coupon_section_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'customize_coupon_section_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .coupon' => '--sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'customize_coupon_section_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .coupon' => '--sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'customize_coupon_section_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .coupon'            => '--sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .e-cart__container' => 'grid-row-gap: {{BOTTOM}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Init Gettext Modifications
	 *
	 * Sets the `$gettext_modifications` property used with the `filter_gettext()` in the extended Base_Widget.
	 *
	 * @since 3.5.0
	 */
	protected function init_gettext_modifications() {
		$instance = $this->get_settings_for_display();

		$this->gettext_modifications = array(
			'Update cart'         => isset( $instance['update_cart_button_text'] ) ? $instance['update_cart_button_text'] : '',
			'Cart totals'         => isset( $instance['totals_section_title'] ) ? $instance['totals_section_title'] : '',
			'Proceed to checkout' => isset( $instance['checkout_button_text'] ) ? $instance['checkout_button_text'] : '',
			'Update'              => isset( $instance['update_shipping_button_text'] ) ? $instance['update_shipping_button_text'] : '',
			'Apply coupon'        => isset( $instance['apply_coupon_button_text'] ) ? $instance['apply_coupon_button_text'] : '',
		);
	}

	/**
	 * Check if an Elementor template has been selected to display the empty cart notification
	 *
	 * @since 3.7.0
	 * @return boolean
	 */
	protected function has_empty_cart_template() {
		$additional_template_select = $this->get_settings_for_display( 'additional_template_select' );
		return ! empty( $additional_template_select ) && 0 < $additional_template_select;
	}

	/**
	 * Render Woocommerce Cart Coupon Form
	 *
	 * A custom function to render a coupon form on the Cart widget. The default WC coupon form
	 * was removed in this file's render() method.
	 *
	 * We are doing this in order to match the placement of the coupon form to the provided design.
	 *
	 * @since 3.5.0
	 */
	private function render_woocommerce_cart_coupon_form() {
		$settings       = $this->get_settings_for_display();
		$button_classes = array( 'button', 'e-apply-coupon' );
		if ( $settings['forms_buttons_hover_animation'] ) {
			$button_classes[] = 'elementor-animation-' . $settings['forms_buttons_hover_animation'];
		}
		$this->add_render_attribute(
			'button_coupon',
			array(
				'class' => $button_classes,
				'name'  => 'apply_coupon',
				'type'  => 'submit',
			)
		);
		?>
		<div class="coupon e-cart-section shop_table">
			<div class="form-row coupon-col">
				<div class="coupon-col-start">
					<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'mas-addons-for-elementor' ); ?>" />
				</div>
				<div class="coupon-col-end">
					<button <?php $this->print_render_attribute_string( 'button_coupon' ); ?> value="<?php esc_attr_e( 'Apply coupon', 'mas-addons-for-elementor' ); ?>"><?php esc_attr_e( 'Apply coupon', 'mas-addons-for-elementor' ); ?></button>
				</div>
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Hide coupon field on cart.
	 *
	 * @param bool $enabled enabled.
	 *
	 * @return bool
	 */
	public function hide_coupon_field_on_cart( $enabled ) {
		return is_cart() ? false : $enabled;
	}

	/**
	 * Woocommerce Before Cart
	 *
	 * Output containing elements. Callback function for the woocommerce_before_cart hook
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_before_cart() {
		?>
		<div class="e-cart__container">
						<!--open container-->
						<div class="e-cart__column e-cart__column-start">
							<!--open column-1-->
		<?php
	}

	/**
	 * Should Render Coupon
	 *
	 * Decide if the coupon form should be rendered.
	 * The coupon form should be rendered if:
	 * 1) The WooCommerce setting is enabled
	 * 2) And the Coupon Display toggle hasn't been set to 'no'
	 *
	 * @since 3.6.0
	 *
	 * @return boolean
	 */
	private function should_render_coupon() {
		$settings               = $this->get_settings_for_display();
		$coupon_display_control = true;

		if ( '' === $settings['section_coupon_display'] ) {
			$coupon_display_control = false;
		}

		return wc_coupons_enabled() && $coupon_display_control;
	}

	/**
	 * Woocommerce Before Cart Table
	 *
	 * Output containing elements. Callback function for the woocommerce_before_cart_table hook
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_before_cart_table() {
		$section_classes = array( 'e-shop-table', 'e-cart-section' );

		if ( ! $this->should_render_coupon() ) {
			$section_classes[] = 'e-cart-section--no-coupon';
		}

		$this->add_render_attribute(
			'before_cart_table',
			array(
				'class' => $section_classes,
			)
		);
		?>
		<div <?php $this->print_render_attribute_string( 'before_cart_table' ); ?>>
						<!--open shop table div -->
		<?php
	}

	/**
	 * Woocommerce After Cart Table
	 *
	 * Output containing elements. Callback function for the woocommerce_after_cart_table hook
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_after_cart_table() {
		?>
						</div>
					<!--close shop table div -->
					<div class="e-clear"></div>
		<?php
		if ( $this->should_render_coupon() ) {
			$this->render_woocommerce_cart_coupon_form();
		}
	}

	/**
	 * Woocommerce Before Cart Collaterals
	 *
	 * Output containing elements. * Callback function for the woocommerce_before_cart_collaterals hook
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_before_cart_collaterals() {
		?>
						<!--close column-1-->
						</div>
						<div class="e-cart__column e-cart__column-end">
							<!--open column-2-->
								<div class="e-cart__column-inner e-sticky-right-column">
									<!--open column-inner-->
									<div class="e-cart-totals e-cart-section">
										<!--open cart-totals-->
		<?php
	}

	/**
	 * Woocommerce After Cart
	 *
	 * Output containing elements. Callback function for the woocommerce_after_cart hook.
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_after_cart() {
		?>
									<!--close cart-totals-->
									</div>
									<!--close column-inner-->
								</div>
							<!--close column-2-->
						</div>
						<!--close container-->
					</div>
		<?php
	}

	/**
	 * WooCommerce Get Remove URL.
	 *
	 * When in the Editor or (wp preview) and the uer clicks to remove an item from the cart, WooCommerce uses
	 * the`_wp_http_referer` url during the ajax call to generate the new cart html. So when we're in the Editor
	 * or (wp preview) we modify the `_wp_http_referer` to use the `get_wp_preview_url()` which will have
	 * the new cart content.
	 *
	 * @since 3.5.0
	 * @deprecated 3.7.0
	 *
	 * @param string $url url.
	 * @return string
	 */
	public function woocommerce_get_remove_url( $url ) {
		Plugin::elementor()->modules_manager->get_modules( 'dev-tools' )->deprecation->deprecated_function( __METHOD__, '3.7.0' );

		$url_components = wp_parse_url( $url );

		if ( ! isset( $url_components['query'] ) ) {
			return $url;
		}

		$params = array();
		parse_str( html_entity_decode( $url_components['query'] ), $params );

		$params['_wp_http_referer'] = rawurlencode( Plugin::elementor()->documents->get_current()->get_wp_preview_url() );

		return add_query_arg( $params, get_site_url() );
	}

	/**
	 * WooCommerce Get Cart Url
	 *
	 * Used with the `woocommerce_get_cart_url`. This sets the url to the current page, so links like the `remove_url`
	 * are set to the current page, and not the existing WooCommerce cart endpoint.
	 *
	 * @since 3.7.0
	 *
	 * @param string $url url.
	 * @return string
	 */
	public function woocommerce_get_cart_url( $url ) {
		global $post;

		if ( ! $post ) {
			return $url;
		}

		if ( Module::is_preview() || Plugin::elementor()->editor->is_edit_mode() ) {
			return Plugin::elementor()->documents->get_current()->get_wp_preview_url();
		}

		return get_permalink( $post->ID );
	}

	/**
	 * The following disabling of cart coupon needs to be done this way so that
	 * we only disable the display of coupon interface in our cart widget and
	 * `wc_coupons_enabled()` can still be reliably used elsewhere.
	 */
	public function disable_cart_coupon() {
		add_filter( 'woocommerce_coupons_enabled', array( $this, 'cart_coupon_return_false' ), 90 );
	}
	/**
	 * Enable Cart Coupon.
	 */
	public function enable_cart_coupon() {
		remove_filter( 'woocommerce_coupons_enabled', array( $this, 'cart_coupon_return_false' ), 90 );
	}
	/**
	 * Cart Coupon Return.
	 */
	public function cart_coupon_return_false() {
		return false;
	}

	/**
	 * Add Render Hooks
	 *
	 * Add actions & filters before displaying our widget.
	 *
	 * @since 3.7.0
	 */
	public function add_render_hooks() {
		$is_editor  = Plugin::elementor()->editor->is_edit_mode();
		$is_preview = Module::is_preview();

		/**
		 * Add actions & filters before displaying our Widget.
		 */
		add_filter( 'gettext', array( $this, 'filter_gettext' ), 20, 3 );

		add_action( 'woocommerce_before_cart', array( $this, 'woocommerce_before_cart' ) );
		add_action( 'woocommerce_after_cart_table', array( $this, 'woocommerce_after_cart_table' ) );
		add_action( 'woocommerce_before_cart_table', array( $this, 'woocommerce_before_cart_table' ) );
		add_action( 'woocommerce_before_cart_collaterals', array( $this, 'woocommerce_before_cart_collaterals' ) );
		add_action( 'woocommerce_after_cart', array( $this, 'woocommerce_after_cart' ) );
		// The following disabling of cart coupon needs to be done this way so that
		// we only disable the display of coupon interface in our cart widget and
		// `wc_coupons_enabled()` can still be reliably used elsewhere.
		add_action( 'woocommerce_cart_contents', array( $this, 'disable_cart_coupon' ) );
		add_action( 'woocommerce_after_cart_contents', array( $this, 'enable_cart_coupon' ) );
		add_filter( 'woocommerce_get_cart_url', array( $this, 'woocommerce_get_cart_url' ) );

		if ( $this->has_empty_cart_template() ) {
			remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
		}

		// Remove cross-sells in cart.
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	}

	/**
	 * Remove Render Hooks
	 *
	 * Remove actions & filters after displaying our widget.
	 *
	 * @since 3.7.0
	 */
	public function remove_render_hooks() {
		remove_filter( 'gettext', array( $this, 'filter_gettext' ), 20 );

		remove_action( 'woocommerce_before_cart', array( $this, 'woocommerce_before_cart' ) );
		remove_action( 'woocommerce_after_cart_table', array( $this, 'woocommerce_after_cart_table' ) );
		remove_action( 'woocommerce_before_cart_table', array( $this, 'woocommerce_before_cart_table' ) );
		remove_action( 'woocommerce_before_cart_collaterals', array( $this, 'woocommerce_before_cart_collaterals' ) );
		remove_action( 'woocommerce_after_cart', array( $this, 'woocommerce_after_cart' ) );
		remove_filter( 'woocommerce_coupons_enabled', array( $this, 'hide_coupon_field_on_cart' ) );
		remove_filter( 'woocommerce_get_remove_url', array( $this, 'woocommerce_get_remove_url' ) );
		remove_action( 'woocommerce_cart_contents', array( $this, 'disable_cart_coupon' ) );
		remove_action( 'woocommerce_after_cart_contents', array( $this, 'enable_cart_coupon' ) );
		remove_action( 'woocommerce_get_cart_url', array( $this, 'woocommerce_get_cart_url' ) );
		add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

		if ( $this->has_empty_cart_template() ) {
			add_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
		}
	}

	/**
	 * Render
	 */
	public function render() {
		// Add actions & filters before displaying our Widget.
		$this->add_render_hooks();

		// Display our Widget.
		if ( $this->has_empty_cart_template() && WC()->cart->get_cart_contents_count() === 0 ) {
			$template_id = intval( $this->get_settings_for_display( 'additional_template_select' ) );
			echo do_shortcode( '[elementor-template id="' . $template_id . '"]' );
		} else {
			echo do_shortcode( '[woocommerce_cart]' );
		}

		// Remove actions & filters after displaying our Widget.
		$this->remove_render_hooks();
	}

	/**
	 * Ger Group name
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
