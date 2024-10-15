<?php
/**
 * The Checkout Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use MASElementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Checkout
 */
class Checkout extends Base_Widget {

	/**
	 * Reformatted form fields
	 *
	 * @var reformatted_form_fields
	 */
	private $reformatted_form_fields;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-checkout-page';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Checkout', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-checkout';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'checkout' );
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	/**
	 * Return the script dependencies of the module.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'wc-checkout',
			'wc-password-strength-meter',
			'selectWoo',
		);
	}

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'select2', 'mas-woocommerce-checkout' );
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
			'checkout_layout',
			array(
				'label'        => esc_html__( 'Layout', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'two-column' => esc_html__( 'Two columns', 'mas-addons-for-elementor' ),
					'one-column' => esc_html__( 'One column', 'mas-addons-for-elementor' ),
				),
				'default'      => 'two-column',
				'prefix_class' => 'e-checkout-layout-',
			)
		);

		$this->add_control(
			'sticky_right_column',
			array(
				'label'              => esc_html__( 'Sticky Right Column', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'mas-addons-for-elementor' ),
				'label_off'          => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'return_value'       => 'yes',
				'description'        => esc_html__( 'The Order Summary and Payment sections will remain in place while scrolling.', 'mas-addons-for-elementor' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => array(
					'checkout_layout' => 'two-column',
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
							'name'     => 'checkout_layout',
							'operator' => '=',
							'value'    => 'two-column',
						),
					),
				),
			)
		);

		$this->end_controls_section();

		if ( $this->is_wc_feature_active( 'checkout_login_reminder' ) ) {
			$this->add_checkout_login_reminder_controls();
		}

		$this->start_controls_section(
			'billing_details_section',
			array(
				'label' => $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ? esc_html__( 'Billing and Shipping Details', 'mas-addons-for-elementor' ) : esc_html__( 'Billing Details', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'billing_details_section_title',
			array(
				'label'       => esc_html__( 'Section Title', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ? esc_html__( 'Billing and Shipping Details', 'mas-addons-for-elementor' ) : esc_html__( 'Billing Details', 'mas-addons-for-elementor' ),
				'default'     => $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ? esc_html__( 'Billing and Shipping Details', 'mas-addons-for-elementor' ) : esc_html__( 'Billing Details', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_responsive_control(
			'billing_details_alignment',
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
					'{{WRAPPER}}' => '--billing-details-title-alignment: {{VALUE}};',
				),
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs(
			'tabs',
			array(
				'condition' => array(
					'repeater_state' => '',
				),
			)
		);

		$repeater->start_controls_tab(
			'content_tab',
			array(
				'label' => esc_html__( 'Content', 'mas-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label' => esc_html__( 'Label', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'placeholder',
			array(
				'label' => esc_html__( 'Placeholder', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'advanced_tab',
			array(
				'label' => esc_html__( 'Advanced', 'mas-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'default',
			array(
				'label'   => esc_html__( 'Default Value', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$repeater->add_control(
			'repeater_state',
			array(
				'label' => esc_html__( 'Repeater State - hidden', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HIDDEN,
			)
		);

		$repeater->add_control(
			'locale_notice',
			array(
				'raw'             => __( 'Note: This content cannot be changed due to local regulations.', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'repeater_state' => 'locale',
				),
			)
		);

		$repeater->add_control(
			'from_billing_notice',
			array(
				'raw'             => __( 'Note: This label and placeholder are taken from the Billing section. You can change it there.', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'repeater_state' => 'from_billing',
				),
			)
		);

		$this->add_control(
			'billing_details_form_fields',
			array(
				'label'        => esc_html__( 'Form Items', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $repeater->get_controls(),
				'item_actions' => array(
					'add'       => false,
					'duplicate' => false,
					'remove'    => false,
					'sort'      => false,
				),
				'default'      => $this->get_billing_field_defaults(),
				'title_field'  => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		if ( $this->is_wc_feature_active( 'shipping' ) && ! $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ) {
			$this->add_shipping_controls();
		}

		$this->start_controls_section(
			'additional_information_section',
			array(
				'label' => esc_html__( 'Additional Information', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'additional_information_active',
			array(
				'label'     => esc_html__( 'Additional Information', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-additional-information-display: block;',
				),
			)
		);

		if ( $this->is_wc_feature_active( 'ship_to_billing_address_only' ) ) {
			$this->add_control(
				'additional_information_section_title',
				array(
					'label'       => esc_html__( 'Section Title', 'mas-addons-for-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Additional Information', 'mas-addons-for-elementor' ),
					'default'     => esc_html__( 'Additional Information', 'mas-addons-for-elementor' ),
					'dynamic'     => array(
						'active' => true,
					),
					'condition'   => array(
						'additional_information_active!' => '',
					),
				)
			);

			$this->add_responsive_control(
				'additional_information_alignment',
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
						'{{WRAPPER}}' => '--additional-fields-title-alignment: {{VALUE}};',
					),
					'condition' => array(
						'additional_information_active!' => '',
					),
				)
			);
		}

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'additional_information_form_fields_tabs' );

		$repeater->start_controls_tab(
			'additional_information_form_fields_content_tab',
			array(
				'label' => esc_html__( 'Content', 'mas-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => esc_html__( 'Label', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'placeholder',
			array(
				'label'   => esc_html__( 'Placeholder', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'additional_information_form_fields_advanced_tab',
			array(
				'label' => esc_html__( 'Advanced', 'mas-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'default',
			array(
				'label'   => esc_html__( 'Default Value', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'additional_information_form_fields',
			array(
				'label'        => esc_html__( 'Items', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $repeater->get_controls(),
				'item_actions' => array(
					'add'       => false,
					'duplicate' => false,
					'remove'    => false,
					'sort'      => false,
				),
				'default'      => array(
					array(
						'field_key'   => 'order_comments',
						'field_label' => esc_html__( 'Order Notes', 'mas-addons-for-elementor' ),
						'label'       => esc_html__( 'Order Notes', 'mas-addons-for-elementor' ),
						'placeholder' => esc_html__( 'Notes about your order, e.g. special notes for delivery.', 'mas-addons-for-elementor' ),
					),
				),
				'title_field'  => '{{{ label }}}',
				'condition'    => array(
					'additional_information_active!' => '',
				),
			)
		);

		$this->end_controls_section();

		if ( $this->is_wc_feature_active( 'signup_and_login_from_checkout' ) ) {
			$this->add_signup_and_login_from_checkout_controls();
		}

		$this->start_controls_section(
			'order_summary_section',
			array(
				'label' => esc_html__( 'Your Order', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_section_title',
			array(
				'label'   => esc_html__( 'Section Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Your Order', 'mas-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_alignment',
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
					'{{WRAPPER}}' => '--mas-order-review-title-alignment: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		if ( $this->is_wc_feature_active( 'coupons' ) ) {
			$this->add_coupon_controls();
		}

		$this->start_controls_section(
			'payment_section',
			array(
				'label' => esc_html__( 'Payment', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'terms_conditions_heading',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Terms &amp; Conditions', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'terms_conditions_message_text',
			array(
				'label'       => esc_html__( 'Message', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'I have read and agree to the website', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'terms_conditions_link_text',
			array(
				'label'       => esc_html__( 'Link Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'terms and conditions', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'purchase_buttom_heading',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Purchase Button', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'purchase_button_alignment',
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
					'{{WRAPPER}} .woocommerce-checkout' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'start'   => '--mas-place-order-title-alignment: flex-start; --purchase-button-width: fit-content;',
					'center'  => '--mas-place-order-title-alignment: center; --purchase-button-width: fit-content;',
					'end'     => '--mas-place-order-title-alignment: flex-end; --purchase-button-width: fit-content;',
					'justify' => '--mas-place-order-title-alignment: stretch; --purchase-button-width: 100%;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_style',
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
					'{{WRAPPER}}' => '--mas-sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'section_normal_box_shadow',
				'selector' => $this->get_main_woocommerce_sections_selectors(),
			)
		);

		$this->add_control(
			'sections_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-sections-border-type: {{VALUE}};',
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
					$this->get_main_woocommerce_sections_selectors() => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}}' => '--mas-sections-border-color: {{VALUE}};',
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
					'{{WRAPPER}}' => '--mas-sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}}' => '--mas-sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// move the 'Ship to a different address?' checkbox.
					'{{WRAPPER}} .woocommerce-shipping-fields' => '--mas-shipping-heading-padding-start: {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}}' => '--mas-sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_typography',
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
					'{{WRAPPER}}' => '--mas-sections-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sections_titles_typography',
				'selector' => $this->get_main_woocommerce_sections_title_selectors(),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'sections_titles_text_shadow',
				'selector' => $this->get_main_woocommerce_sections_title_selectors(),
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
					'{{WRAPPER}}' => '--mas-sections-title-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sections_secondary_typography',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Secondary Titles', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sections_secondary_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-sections-secondary-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sections_secondary_titles_typography',
				'selector' => '{{WRAPPER}} .e-mas-checkout-secondary-title',
			)
		);

		$this->add_responsive_control(
			'sections_secondary_title_spacing',
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
					'{{WRAPPER}}' => '--mas-sections-secondary-title-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sections_descriptions_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Descriptions', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sections_descriptions_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-sections-descriptions-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sections_descriptions_typography',
				'selector' => '{{WRAPPER}} .e-description',
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
					'{{WRAPPER}}' => '--mas-sections-descriptions-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sections_messages_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Messages', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sections_messages_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-sections-messages-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sections_messages_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout #payment .payment_box, {{WRAPPER}} .woocommerce-privacy-policy-text p, {{WRAPPER}} .e-checkout-message',
			)
		);

		$this->add_control(
			'sections_checkboxes_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Checkboxes', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sections_checkboxes_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-sections-checkboxes-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sections_checkboxes_typography',
				'selector' => '{{WRAPPER}} .woocommerce-form__label-for-checkbox span',
			)
		);

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
					'{{WRAPPER}}' => '--mas-sections-radio-buttons-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sections_radio_buttons_typography',
				'selector' => '{{WRAPPER}} .wc_payment_method label, {{WRAPPER}} #shipping_method li label',
			)
		);

		// Links.
		$this->add_control(
			'sections_links_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Links', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'links_colors' );

		$this->start_controls_tab(
			'links_normal_colors',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'links_normal_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-links-normal-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'links_hover_colors',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_forms',
			array(
				'label' => esc_html__( 'Forms', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'forms_columns_gap',
			array(
				'label'      => esc_html__( 'Columns Gap', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}}' => '--mas-forms-columns-gap-padding: calc( {{SIZE}}{{UNIT}}/2 ); --forms-columns-gap-margin: calc( -{{SIZE}}{{UNIT}}/2 );',
				),
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
					'{{WRAPPER}}' => '--mas-forms-rows-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'forms_label_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Labels', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'forms_label_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-forms-labels-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forms_label_typography',
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields .form-row label, {{WRAPPER}} .woocommerce-shipping-fields .form-row label, {{WRAPPER}} .woocommerce-additional-fields .form-row label, {{WRAPPER}} .e-woocommerce-login-anchor .form-row label, {{WRAPPER}} .e-coupon-anchor-description',
			)
		);

		$this->add_responsive_control(
			'forms_label_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}}' => '--mas-forms-label-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'forms_field_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Fields', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forms_field_typography',
				'selector' => '{{WRAPPER}} #customer_details .input-text, {{WRAPPER}} #customer_details .form-row textarea, {{WRAPPER}} #customer_details .form-row select, {{WRAPPER}} .e-woocommerce-login-anchor .input-text, {{WRAPPER}} #coupon_code, {{WRAPPER}} ::placeholder, {{WRAPPER}} .select2-container--default .select2-selection--single, .select2-results__option',
			)
		);

		$this->start_controls_tabs( 'forms_fields_styles' );

		$this->start_controls_tab(
			'forms_fields_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'forms_fields_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-forms-fields-normal-color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .woocommerce #customer_details .form-row .input-text, {{WRAPPER}}  .woocommerce #customer_details .form-row textarea, {{WRAPPER}} .woocommerce form #customer_details select, {{WRAPPER}} .woocommerce .e-woocommerce-login-anchor .form-row .input-text, {{WRAPPER}} #coupon_code, {{WRAPPER}} .select2-container--default .select2-selection--single, {{WRAPPER}} .woocommerce-checkout #payment .payment_methods .payment_box',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'forms_fields_normal_box_shadow',
				'selector' => '{{WRAPPER}} #customer_details .input-text, {{WRAPPER}}  #customer_details .form-row textarea, {{WRAPPER}} .woocommerce form #customer_details select, {{WRAPPER}} .e-woocommerce-login-anchor .input-text, {{WRAPPER}} #coupon_code, {{WRAPPER}} .select2-container--default .select2-selection--single',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'forms_fields_focus_styles',
			array(
				'label' => esc_html__( 'Focus', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'forms_fields_focus_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-forms-fields-focus-color: {{VALUE}}',
					'.e-woo-select2-wrapper .select2-results__option:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'forms_fields_focus_background',
				'selector' => '{{WRAPPER}} .woocommerce #customer_details .form-row .input-text:focus, {{WRAPPER}}  .woocommerce #customer_details .form-row textarea:focus, {{WRAPPER}} #customer_details select:focus, {{WRAPPER}} .woocommerce .e-woocommerce-login-anchor .form-row .input-text:focus, {{WRAPPER}} #coupon_code:focus, {{WRAPPER}} .select2-container--default .select2-selection--single:focus',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'forms_fields_focus_box_shadow',
				'selector' => '{{WRAPPER}} #customer_details .input-text:focus, {{WRAPPER}} #customer_details textarea:focus, {{WRAPPER}} #customer_details select:focus, {{WRAPPER}} .e-woocommerce-login-anchor .input-text:focus, {{WRAPPER}} #coupon_code:focus, {{WRAPPER}} .select2-container--default .select2-selection--single:focus',
			)
		);

		$this->add_control(
			'forms_fields_focus_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce #customer_details .form-row .input-text:focus, {{WRAPPER}}  .woocommerce #customer_details .form-row textarea:focus, {{WRAPPER}} #customer_details select:focus, {{WRAPPER}} .woocommerce .e-woocommerce-login-anchor .form-row .input-text:focus, {{WRAPPER}} #coupon_code:focus, {{WRAPPER}} .select2-container--default .select2-selection--single:focus' => 'border-color: {{VALUE}}',
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
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-forms-fields-focus-transition-duration: {{SIZE}}ms',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'forms_fields_border',
				'selector'  => '{{WRAPPER}} .woocommerce #customer_details .form-row .input-text, {{WRAPPER}}  .woocommerce #customer_details .form-row textarea, {{WRAPPER}} .woocommerce form #customer_details select, {{WRAPPER}} .woocommerce .e-woocommerce-login-anchor .form-row .input-text, {{WRAPPER}} #coupon_code, {{WRAPPER}} .select2-container--default .select2-selection--single',
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
					'{{WRAPPER}}' => '--mas-forms-fields-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}}' => '--mas-forms-fields-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => esc_html__( 'Button', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forms_button_typography',
				'selector' => '{{WRAPPER}} .woocommerce-button',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'forms_button_text_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-button',
			)
		);

		$this->start_controls_tabs( 'forms_buttons_styles' );

		$this->start_controls_tab(
			'forms_buttons_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'forms_buttons_normal_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-forms-buttons-normal-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'forms_buttons_normal_background',
				'selector' => '{{WRAPPER}} .woocommerce-button',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'forms_buttons_normal_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'forms_buttons_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'forms_buttons_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-forms-buttons-hover-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'forms_buttons_hover_background',
				'selector' => '{{WRAPPER}} .woocommerce-button:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'forms_buttons_focus_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-button:hover',
			)
		);

		$this->add_control(
			'forms_buttons_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-apply-coupon:hover, {{WRAPPER}} .woocommerce-form-login__submit:hover' => 'border-color: {{VALUE}}',
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
					'{{WRAPPER}}' => '--mas-forms-buttons-hover-transition-duration: {{SIZE}}ms',
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
				'label' => esc_html__( 'Hover Animation', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
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
					'{{WRAPPER}}' => '--mas-forms-buttons-border-type: {{VALUE}};',
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
					'{{WRAPPER}} .e-apply-coupon, {{WRAPPER}} .woocommerce-form-login__submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} ' => '--mas-forms-buttons-border-color: {{VALUE}};',
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
					'{{WRAPPER}}' => '--mas-forms-buttons-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .woocommerce-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: auto;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_order_summary',
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
					'{{WRAPPER}}' => '--mas-order-summary-rows-gap-top: calc( {{SIZE}}{{UNIT}}/2 ); --order-summary-rows-gap-bottom: calc( {{SIZE}}{{UNIT}}/2 );',
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
			'order_summary_items_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-order-summary-items-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_summary_items_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table .cart_item td',
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
					'{{WRAPPER}}' => '--mas-order-summary-variations-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_summary_variations_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .product-name .variation',
			)
		);

		$this->add_control(
			'order_summary_items_divider_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Dividers', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_items_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-order-summary-items-divider-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_items_divider_weight',
			array(
				'label'      => esc_html__( 'Weight', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--mas-order-summary-items-divider-weight: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'order_summary_totals_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Titles & Totals', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_totals_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-order-summary-totals-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_summary_totals_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table thead tr th, {{WRAPPER}} .woocommerce-checkout-review-order-table tfoot tr th, {{WRAPPER}} .woocommerce-checkout-review-order-table tfoot tr td',
			)
		);

		$this->add_control(
			'order_summary_dividers_total_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Divider Total', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_totals_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-order-summary-totals-divider-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_totals_divider_weight',
			array(
				'label'      => esc_html__( 'Weight', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--mas-order-summary-totals-divider-weight: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_purchase_button',
			array(
				'label' => esc_html__( 'Purchase Button', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'purchase_button_typography',
				'selector' => '{{WRAPPER}} .woocommerce #payment #place_order',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'purchase_button_text_shadow',
				'selector' => '{{WRAPPER}} .woocommerce #payment #place_order',
			)
		);

		$this->start_controls_tabs( 'purchase_button_styles' );

		$this->start_controls_tab(
			'purchase_button_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'purchase_button_normal_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-purchase-button-normal-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'purchase_button_normal_background',
				'selector' => '{{WRAPPER}} #payment #place_order',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'purchase_button_normal_box_shadow',
				'selector' => '{{WRAPPER}} #place_order',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'purchase_button_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'purchase_button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-purchase-button-hover-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'purchase_button_hover_background',
				'selector' => '{{WRAPPER}} #payment #place_order:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'purchase_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} #place_order:hover',
			)
		);

		$this->add_control(
			'purchase_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-purchase-button-hover-border-color: {{VALUE}}',
				),
				'condition' => array(
					'purchase_button_border_border!' => '',
				),
			)
		);

		$this->add_control(
			'purchase_button_hover_transition_duration',
			array(
				'label'     => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ) . ' (ms)',
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--mas-purchase-button-hover-transition-duration: {{SIZE}}ms',
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
			'purchase_button_hover_animation',
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
				'name'      => 'purchase_button_border',
				'selector'  => '{{WRAPPER}} #place_order',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'purchase_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--mas-purchase-button-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'purchase_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--mas-purchase-button-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; --purchase-button-width: fit-content;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_customize',
			array(
				'label' => esc_html__( 'Customize', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$customize_options = array();

		if ( $this->is_wc_feature_active( 'checkout_login_reminder' ) ) {
			$customize_options += array(
				'customize_returning_customer' => esc_html__( 'Returning Customer', 'mas-addons-for-elementor' ),
			);
		}

		$customize_options += array(
			'customize_billing_details' => esc_html__( 'Billing Details', 'mas-addons-for-elementor' ),
			'customize_additional_info' => esc_html__( 'Additional Information', 'mas-addons-for-elementor' ),
		);

		if ( $this->is_wc_feature_active( 'shipping' ) ) {
			$customize_options += array(
				'customize_shipping_address' => esc_html__( 'Shipping Address', 'mas-addons-for-elementor' ),
			);
		}

		$customize_options += array(
			'customize_order_summary' => esc_html__( 'Order Summary', 'mas-addons-for-elementor' ),
		);

		if ( $this->is_wc_feature_active( 'coupons' ) ) {
			$customize_options += array(
				'customize_coupon' => esc_html__( 'Coupon', 'mas-addons-for-elementor' ),
			);
		}

		$customize_options += array(
			'customize_payment' => esc_html__( 'Payment', 'mas-addons-for-elementor' ),
		);

		$this->add_control(
			'section_checkout_show_customize_elements',
			array(
				'label'       => esc_html__( 'Select sections of the checkout to customize:', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $customize_options,
				'render_type' => 'ui',
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_customize_returning_customer',
			array(
				'label'     => esc_html__( 'Customize: Returning Customer', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_checkout_show_customize_elements' => 'customize_returning_customer',
				),
			)
		);

		$this->add_control(
			'returning_customers_section_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'customize_returning_customer_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => '--mas-sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'returning_customers_section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .e-mas-woocommerce-login-section',
			)
		);

		$this->add_control(
			'returning_customers_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => '--mas-sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'returning_customers_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'returning_customers_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'returning_customers_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => '--mas-sections-border-color: {{VALUE}};',
				),
				'condition' => array(
					'returning_customers_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'returning_customers_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => '--mas-sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'returning_customers_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => '--mas-sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'returning_customers_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => '--mas-sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'returning_customers_secondary_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Secondary Title', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'returning_customers_secondary_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-form-login-toggle' => '--mas-sections-secondary-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'returning_customers_content_typography',
				'selector' => '{{WRAPPER}} .woocommerce-form-login-toggle',
			)
		);

		$this->add_control(
			'returning_customers_description_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Description', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'returning_customers_description_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-woocommerce-login-nudge' => '--mas-sections-descriptions-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'returning_customers_description_typography',
				'selector' => '{{WRAPPER}} .e-woocommerce-login-nudge.e-description',
			)
		);

		$this->add_control(
			'returning_customers_checkboxes_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Checkbox', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'returning_customers_checkboxes_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => '--mas-sections-checkboxes-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'returning_customers_checkboxes_typography',
				'selector' => '{{WRAPPER}} .e-mas-woocommerce-login-section .woocommerce-form__label-for-checkbox span',
			)
		);

		$this->add_control(
			'returning_customers_link_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Link', 'mas-addons-for-elementor' ),
			)
		);

		$this->start_controls_tabs( 'returning_customers_links' );

		$this->start_controls_tab(
			'returning_customers_normal_links',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'returning_customers_normal_links_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => '--mas-links-normal-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'returning_customers_hover_links',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'returning_customers_hover_links_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-mas-woocommerce-login-section' => '--links-hover-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_customize_billing_details',
			array(
				'label'     => esc_html__( 'Customize: Billing Details', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_checkout_show_customize_elements' => 'customize_billing_details',
				),
			)
		);

		$this->add_control(
			'customize_billing_details_section_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'customize_billing_details_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .col2-set .col-1' => '--mas-sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'billing_details_section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .e-checkout__column-start .col2-set .col-1',
			)
		);

		$this->add_control(
			'billing_details_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .col2-set .col-1' => '--mas-sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'billing_details_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-checkout__column-start #customer_details .col-1' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'billing_details_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'billing_details_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .col2-set .col-1' => '--mas-sections-border-color: {{VALUE}}',
				),
				'condition' => array(
					'billing_details_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'billing_details_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .col2-set .col-1' => '--mas-sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'billing_details_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .col2-set .col-1' => '--mas-sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'billing_details_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .col2-set .col-1' => '--mas-sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'billing_details_titles_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'billing_details_titles_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .col2-set .col-1' => '--mas-sections-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'billing_details_titles_typography',
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields h3',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'billing_details_titles_text_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields h3',
			)
		);

		$this->add_control(
			'billing_details_checkboxes_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Checkbox', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'billing_details_checkboxes_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .col2-set .col-1' => '--mas-sections-checkboxes-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'billing_details_checkboxes_typography',
				'selector' => '{{WRAPPER}} .col2-set .col-1 .woocommerce-form__label-for-checkbox span',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_customize_additional_info',
			array(
				'label'     => esc_html__( 'Customize: Additional Information', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_checkout_show_customize_elements' => 'customize_additional_info',
				),
			)
		);

		$this->add_control(
			'customize_additional_information_section_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'customize_additional_information_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-additional-fields' => '--mas-sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'additional_information_section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-additional-fields',
			)
		);

		$this->add_control(
			'additional_information_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-additional-fields' => '--mas-sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'additional_information_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-additional-fields' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'additional_information_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'additional_information_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-additional-fields' => '--mas-sections-border-color: {{VALUE}};',
				),
				'condition' => array(
					'additional_information_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'additional_information_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-additional-fields' => '--mas-sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'additional_information_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-additional-fields' => '--mas-sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'additional_information_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-additional-fields' => '--mas-sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.e-checkout-layout-one-column .e-checkout__container' => 'grid-row-gap: {{BOTTOM}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'additional_information_titles_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'additional_information_titles_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-additional-fields' => '--mas-sections-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'additional_information_titles_typography',
				'selector' => '{{WRAPPER}} .woocommerce-additional-fields h3',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'additional_information_titles_text_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-additional-fields h3',
			)
		);

		$this->end_controls_section();

		if ( $this->is_wc_feature_active( 'shipping' ) ) {
			$this->add_shipping_style_controls();
		}

		if ( $this->is_wc_feature_active( 'coupons' ) ) {
			$this->add_coupons_style_controls();
		}

		$this->start_controls_section(
			'section_checkout_tabs_customize_order_summary',
			array(
				'label'     => esc_html__( 'Customize: Order Summary', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_checkout_show_customize_elements' => 'customize_order_summary',
				),
			)
		);

		$this->add_control(
			'customize_order_summary_section_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'customize_order_summary_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-checkout__order_review' => '--mas-sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'order_summary_section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .e-checkout__order_review',
			)
		);

		$this->add_control(
			'order_summary_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .e-checkout__order_review' => '--mas-sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'order_summary_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-checkout__order_review' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'order_summary_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'order_summary_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-checkout__order_review' => '--mas-sections-border-color: {{VALUE}}',
				),
				'condition' => array(
					'order_summary_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-checkout__order_review' => '--mas-sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-checkout__order_review' => '--mas-sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_summary_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-checkout__order_review' => '--mas-sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'order_summary_titles_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_titles_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-checkout__order_review' => '--mas-sections-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_summary_titles_typography',
				'selector' => '{{WRAPPER}} h3#order_review_heading',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'order_summary_titles_text_shadow',
				'selector' => '{{WRAPPER}} h3#order_review_heading',
			)
		);

		$this->add_control(
			'order_summary_descriptions_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Message', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_descriptions_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-checkout__order_review' => '--mas-sections-descriptions-color: {{VALUE}}; --sections-messages-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_summary_descriptions_typography',
				'selector' => '{{WRAPPER}} .woocommerce-no-shipping-available-html.e-description, {{WRAPPER}} .woocommerce-no-shipping-available-html.e-checkout-message',
			)
		);

		$this->add_control(
			'order_summary_radios_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Radio Button', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_radios_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-checkout__order_review' => '--mas-sections-radio-buttons-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_summary_radio_typography',
				'selector' => '{{WRAPPER}} .woocommerce .e-checkout__order_review ul#shipping_method li label',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkout_tabs_customize_payment',
			array(
				'label'     => esc_html__( 'Customize: Payment', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_checkout_show_customize_elements' => 'customize_payment',
				),
			)
		);

		$this->add_control(
			'customize_payment_section_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'customize_payment_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout #payment' => '--mas-sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'payment_section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-checkout #payment',
			)
		);

		$this->add_control(
			'payment_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout #payment' => '--mas-sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'payment_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'payment_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'payment_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout #payment' => '--mas-sections-border-color: {{VALUE}};',
				),
				'condition' => array(
					'payment_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'payment_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-checkout #payment' => '--mas-sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'payment_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-checkout #payment' => '--mas-sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'payment_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-checkout #payment' => '--mas-sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'payment_info_box_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Info Box', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'payment_info_box_title_background',
				'selector' => '{{WRAPPER}} .woocommerce-checkout #payment .payment_methods .payment_box',
			)
		);

		$this->add_control(
			'payment_description_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Description', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_description_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-payment' => '--mas-sections-descriptions-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'payment_description_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-payment .e-description',
			)
		);

		$this->add_control(
			'payment_messages_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Message', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_messages_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-payment' => '--mas-sections-messages-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'payment_messages_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout #payment .payment_box, {{WRAPPER}} .woocommerce-privacy-policy-text p',
			)
		);

		$this->add_control(
			'payment_checkboxes_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Checkbox', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_checkboxes_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => '--mas-sections-checkboxes-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'payment_checkboxes_typography',
				'selector' => '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper .woocommerce-form__label-for-checkbox span',
			)
		);

		$this->add_control(
			'payment_radio_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Radio Button', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_radio_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-payment' => '--mas-sections-radio-buttons-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'payment_radio_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-payment .wc_payment_method label',
			)
		);

		$this->add_control(
			'payment_links_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Links', 'mas-addons-for-elementor' ),
			)
		);

		$this->start_controls_tabs( 'payment_colors' );

		$this->start_controls_tab(
			'payment_normal_colors',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_normal_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-payment' => '--mas-links-normal-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'payment_hover_colors',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_hover_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-payment' => '--links-hover-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Add checkout login reminder controls.
	 */
	private function add_checkout_login_reminder_controls() {
		$this->start_controls_section(
			'returning_customer_heading',
			array(
				'label' => esc_html__( 'Returning Customer', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'returning_customer_section_title',
			array(
				'label'   => esc_html__( 'Section Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Returning customer?', 'mas-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'returning_customer_link_text',
			array(
				'label'   => esc_html__( 'Link Text', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Click here to login', 'mas-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_responsive_control(
			'returning_customer_title_alignment',
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
					'{{WRAPPER}}' => '--mas-login-title-alignment: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'login_button_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Login Button', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'login_button_alignment',
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
					'{{WRAPPER}} .e-login-wrap' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'start'   => '--mas-login-button-alignment: start; --login-button-width: 35%;',
					'center'  => '--mas-login-button-alignment: center;  --login-button-width: 35%;',
					'end'     => '--mas-login-button-alignment: end;  --login-button-width: 35%;',
					'justify' => '--mas-login-button-alignment: center; --login-button-width: 100%;',
				),
			)
		);

		$this->add_control(
			'login_button_alignment_note',
			array(
				'raw'             => esc_html__( 'Note: This control will only affect screen sizes Tablet and below', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add shipping controls.
	 */
	private function add_shipping_controls() {
		$this->start_controls_section(
			'shipping_details_section',
			array(
				'label' => esc_html__( 'Shipping Details', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'shipping_details_section_title',
			array(
				'label'   => esc_html__( 'Section Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Ship to a different address?', 'mas-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'repeater_state',
			array(
				'label' => esc_html__( 'Repeater State - hidden', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HIDDEN,
			)
		);

		$repeater->add_control(
			'label_placeholder_notification',
			array(
				'raw'             => __( 'Note: This label and placeholder are taken from the Billing section. You can change it there.', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'repeater_state' => 'from_billing',
				),
			)
		);

		$repeater->start_controls_tabs(
			'tabs',
			array(
				'condition' => array(
					'repeater_state' => '',
				),
			)
		);

		$repeater->start_controls_tab(
			'content_tab',
			array(
				'label' => esc_html__( 'Content', 'mas-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label' => esc_html__( 'Label', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'placeholder',
			array(
				'label' => esc_html__( 'Placeholder', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'advanced_tab',
			array(
				'label' => esc_html__( 'Advanced', 'mas-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'default',
			array(
				'label'   => esc_html__( 'Default Value', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$repeater->add_control(
			'locale_notice',
			array(
				'raw'             => __( 'Note: This content cannot be changed due to local regulations.', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'repeater_state' => 'locale',
				),
			)
		);

		$repeater->add_control(
			'from_billing_notice',
			array(
				'raw'             => __( 'Note: This label and placeholder are taken from the Billing section. You can change it there.', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'repeater_state' => 'from_billing',
				),
			)
		);

		$this->add_control(
			'shipping_details_form_fields',
			array(
				'label'        => esc_html__( 'Form Items', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $repeater->get_controls(),
				'item_actions' => array(
					'add'       => false,
					'duplicate' => false,
					'remove'    => false,
					'sort'      => false,
				),
				'default'      => $this->get_shipping_field_defaults(),
				'title_field'  => '{{{ label }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add signup and login from checkout controls.
	 */
	private function add_signup_and_login_from_checkout_controls() {
		$this->start_controls_section(
			'create_account_section',
			array(
				'label' => esc_html__( 'Create an Account', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'create_account_text',
			array(
				'label'   => esc_html__( 'Section Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Create an account?', 'mas-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add coupon controls.
	 */
	private function add_coupon_controls() {
		$this->start_controls_section(
			'coupon_section',
			array(
				'label' => esc_html__( 'Coupon', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'coupon_section_display',
			array(
				'label'     => esc_html__( 'Coupon', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'coupon_section_title_text',
			array(
				'label'     => esc_html__( 'Section Title', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Have a coupon?', 'mas-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'coupon_section_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'coupon_section_title_link_text',
			array(
				'label'     => esc_html__( 'Link Text', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Click here to enter your coupon code', 'mas-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'coupon_section_display' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_alignment',
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
					'{{WRAPPER}}' => '--mas-coupon-title-alignment: {{VALUE}};',
				),
				'condition' => array(
					'coupon_section_display' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_button_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Apply Button', 'mas-addons-for-elementor' ),
				'separator' => 'before',
				'condition' => array(
					'coupon_section_display' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_button_alignment',
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
					'{{WRAPPER}} .coupon-container-grid' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'start'   => '--mas-coupon-button-alignment: start;',
					'center'  => '--mas-coupon-button-alignment: center;',
					'end'     => '--mas-coupon-button-alignment: end;',
					'justify' => '--mas-coupon-button-alignment: justify; --mas-coupon-button-width: 100%;',
				),
				'condition'            => array(
					'coupon_section_display' => 'yes',
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
					'coupon_section_display' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add shipping style controls.
	 */
	private function add_shipping_style_controls() {
		$this->start_controls_section(
			'section_checkout_tabs_customize_shipping_address',
			array(
				'label'     => esc_html__( 'Customize: Shipping Address', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_checkout_show_customize_elements' => 'customize_shipping_address',
				),
			)
		);

		$this->add_control(
			'shipping_address_section_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'customize_shipping_address_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-shipping-fields .shipping_address' => '--mas-sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'shipping_address_section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-shipping-fields .shipping_address',
			)
		);

		$this->add_control(
			'shipping_address_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-shipping-fields .shipping_address' => '--mas-sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'shipping_address_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-shipping-fields .shipping_address' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'shipping_address_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'shipping_address_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-shipping-fields .shipping_address' => '--mas-sections-border-color: {{VALUE}}',
				),
				'condition' => array(
					'shipping_address_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'shipping_address_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-shipping-fields .shipping_address' => '--mas-sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'shipping_address_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-shipping-fields .shipping_address' => '--mas-sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'shipping_address_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-shipping-fields .shipping_address' => '--mas-sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'shipping_address_checkboxes_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Checkboxes', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'shipping_address_checkboxes_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-shipping-fields' => '--mas-sections-checkboxes-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'shipping_address_checkboxes_typography',
				'selector' => '{{WRAPPER}} .woocommerce-shipping-fields .woocommerce-form__label-for-checkbox span',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add coupon style controls.
	 */
	private function add_coupons_style_controls() {
		$this->start_controls_section(
			'section_checkout_tabs_customize_coupon',
			array(
				'label'     => esc_html__( 'Customize: Coupon', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'section_checkout_show_customize_elements' => 'customize_coupon',
				),
			)
		);

		$this->add_control(
			'customize_coupon_section_title',
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
					'{{WRAPPER}} .e-coupon-box' => '--mas-sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'coupon_section_normal_box_shadow',
				'selector' => '{{WRAPPER}} .e-coupon-box',
			)
		);

		$this->add_control(
			'coupon_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}} .e-coupon-box' => '--mas-sections-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'coupon_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-coupon-box' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'coupon_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'coupon_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-coupon-box' => '--mas-sections-border-color: {{VALUE}};',
				),
				'condition' => array(
					'coupon_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-coupon-box' => '--mas-sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-coupon-box' => '--mas-sections-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .e-coupon-box' => '--mas-sections-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'coupon_secondary_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Secondary Title', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'coupon_secondary_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-woocommerce-coupon-nudge' => '--mas-sections-secondary-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'coupon_content_typography',
				'selector' => '{{WRAPPER}} .e-woocommerce-coupon-nudge.e-mas-checkout-secondary-title',
			)
		);

		$this->add_control(
			'coupon_link_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Link', 'mas-addons-for-elementor' ),
			)
		);

		$this->start_controls_tabs( 'coupon_links' );

		$this->start_controls_tab(
			'coupon_normal_links',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'coupon_normal_links_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-coupon-box' => '--mas-links-normal-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'coupon_hover_links',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'coupon_hover_links_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .e-coupon-box' => '--links-hover-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Get Billing Field Defaults
	 *
	 * Get defaults used for the billing details repeater control.
	 *
	 * @since 3.5.0
	 *
	 * @return array
	 */
	private function get_billing_field_defaults() {
		$fields = array(
			'billing_first_name' => array(
				'label'          => esc_html__( 'First Name', 'mas-addons-for-elementor' ),
				'repeater_state' => '',
			),
			'billing_last_name'  => array(
				'label'          => esc_html__( 'Last Name', 'mas-addons-for-elementor' ),
				'repeater_state' => '',
			),
			'billing_company'    => array(
				'label'          => esc_html__( 'Company Name', 'mas-addons-for-elementor' ),
				'repeater_state' => '',
			),
			'billing_country'    => array(
				'label'          => esc_html__( 'Country / Region', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
			'billing_address_1'  => array(
				'label'          => esc_html__( 'Street Address', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
			'billing_postcode'   => array(
				'label'          => esc_html__( 'Post Code', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
			'billing_city'       => array(
				'label'          => esc_html__( 'Town / City', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
			'billing_state'      => array(
				'label'          => esc_html__( 'State', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
			'billing_phone'      => array(
				'label'          => esc_html__( 'Phone', 'mas-addons-for-elementor' ),
				'repeater_state' => '',
			),
			'billing_email'      => array(
				'label'          => esc_html__( 'Email Address', 'mas-addons-for-elementor' ),
				'repeater_state' => '',
			),
		);

		return $this->reformat_address_field_defaults( $fields );
	}

	/**
	 * Get Shipping Field Defaults
	 *
	 * Get defaults used for the shipping details repeater control.
	 *
	 * @since 3.5.0
	 *
	 * @return array
	 */
	private function get_shipping_field_defaults() {
		$fields = array(
			'shipping_first_name' => array(
				'label'          => esc_html__( 'First Name', 'mas-addons-for-elementor' ),
				'repeater_state' => '',
			),
			'shipping_last_name'  => array(
				'label'          => esc_html__( 'Last Name', 'mas-addons-for-elementor' ),
				'repeater_state' => '',
			),
			'shipping_company'    => array(
				'label'          => esc_html__( 'Company Name', 'mas-addons-for-elementor' ),
				'repeater_state' => '',
			),
			'shipping_country'    => array(
				'label'          => esc_html__( 'Country / Region', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
			'shipping_address_1'  => array(
				'label'          => esc_html__( 'Street Address', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
			'shipping_postcode'   => array(
				'label'          => esc_html__( 'Post Code', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
			'shipping_city'       => array(
				'label'          => esc_html__( 'Town / City', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
			'shipping_state'      => array(
				'label'          => esc_html__( 'State', 'mas-addons-for-elementor' ),
				'repeater_state' => 'locale',
			),
		);

		return $this->reformat_address_field_defaults( $fields );
	}

	/**
	 * Reformat Address Field Defaults
	 *
	 * Used with the `get_..._field_defaults()` methods.
	 * Takes the address array and converts it into the format expected by the repeater controls.
	 *
	 * @since 3.5.0
	 *
	 * @param array $address address.
	 * @return array
	 */
	private function reformat_address_field_defaults( $address ) {
		$defaults = array();
		foreach ( $address as $key => $value ) {
			$defaults[] = array(
				'field_key'      => $key,
				'field_label'    => $value['label'],
				'label'          => $value['label'],
				'placeholder'    => $value['label'],
				'repeater_state' => $value['repeater_state'],
			);
		}

		return $defaults;
	}

	/**
	 * Get Main Woocommerce Sections Selectors
	 *
	 * Get all the 'Sections' selectors. There are numerous controls that need these selectors so it was easier
	 * to consolidate them into one function. Especially when updates need to be made.
	 *
	 * @since 3.5.0
	 *
	 * @return string
	 */
	private function get_main_woocommerce_sections_selectors() {
		$selector = '{{WRAPPER}} .e-mas-woocommerce-login-section, {{WRAPPER}} .woocommerce-checkout #customer_details .col-1, {{WRAPPER}} .woocommerce-additional-fields, {{WRAPPER}} .e-checkout__order_review, {{WRAPPER}} .e-coupon-box, {{WRAPPER}} .woocommerce-checkout #payment';
		if ( $this->is_wc_feature_active( 'shipping' ) ) {
			$selector .= ', {{WRAPPER}} .woocommerce-shipping-fields .shipping_address';
		}
		return $selector;
	}

	/**
	 * Get Main Woocommerce Sections Title Selectors
	 *
	 * Get all the 'Title' selectors. There are numerous controls that need these selectors so it was easier to
	 * consolidate them into one function. Especially when updates need to be made.
	 *
	 * @since 3.5.0
	 *
	 * @return string
	 */
	private function get_main_woocommerce_sections_title_selectors() {
		return '{{WRAPPER}} h3#order_review_heading, {{WRAPPER}} .woocommerce-billing-fields h3, {{WRAPPER}} .woocommerce-additional-fields h3';
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
			'Billing details'                      => isset( $instance['billing_details_section_title'] ) ? $instance['billing_details_section_title'] : '',
			'Billing &amp; Shipping'               => isset( $instance['billing_details_section_title'] ) ? $instance['billing_details_section_title'] : '',
			'Ship to a different address?'         => isset( $instance['shipping_details_section_title'] ) ? $instance['shipping_details_section_title'] : '',
			'Additional information'               => isset( $instance['additional_information_section_title'] ) ? $instance['additional_information_section_title'] : '',
			'Your order'                           => isset( $instance['order_summary_section_title'] ) ? $instance['order_summary_section_title'] : '',
			'Have a coupon?'                       => isset( $instance['coupon_section_title_text'] ) ? $instance['coupon_section_title_text'] : '',
			'Click here to enter your coupon code' => isset( $instance['coupon_section_title_link_text'] ) ? $instance['coupon_section_title_link_text'] : '',
			'Returning customer?'                  => isset( $instance['returning_customer_section_title'] ) ? $instance['returning_customer_section_title'] : '',
			'Click here to login'                  => isset( $instance['returning_customer_link_text'] ) ? $instance['returning_customer_link_text'] : '',
			'Create an account?'                   => isset( $instance['create_account_text'] ) ? $instance['create_account_text'] : '',
		);
	}

	/**
	 * WooCommerce Terms and Conditions Checkbox Text.
	 *
	 * WooCommerce filter is used to apply widget settings to Checkout Terms & Conditions text and link text.
	 *
	 * @since 3.5.0
	 *
	 * @param string $text text.
	 * @return string
	 */
	public function woocommerce_terms_and_conditions_checkbox_text( $text ) {
		$instance = $this->get_settings_for_display();

		if ( ! isset( $instance['terms_conditions_message_text'] ) || ! isset( $instance['terms_conditions_link_text'] ) ) {
			return $text;
		}

		$message = $instance['terms_conditions_message_text'];
		$link    = $instance['terms_conditions_link_text'];

		$terms_page_id = wc_terms_and_conditions_page_id();
		if ( $terms_page_id ) {
			$message .= ' <a href="' . esc_url( get_permalink( $terms_page_id ) ) . '" class="woocommerce-terms-and-conditions-link" target="_blank">' . $link . '</a>';
		}

		return $message;
	}

	/**
	 * Modify Form Field.
	 *
	 * WooCommerce filter is used to apply widget settings to the Checkout forms address fields
	 * from the Billing and Shipping Details widget sections, e.g. label, placeholder, default.
	 *
	 * @since 3.5.0
	 *
	 * @param array  $args args.
	 * @param string $key key.
	 * @param string $value value.
	 * @return array
	 */
	public function modify_form_field( $args, $key, $value ) {
		$reformatted_form_fields = $this->get_reformatted_form_fields();

		// Check if we need to modify the args of this form field.
		if ( isset( $reformatted_form_fields[ $key ] ) ) {
			$apply_fields = array(
				'label',
				'placeholder',
				'default',
			);

			foreach ( $apply_fields as $field ) {
				if ( ! empty( $reformatted_form_fields[ $key ][ $field ] ) ) {
					$args[ $field ] = $reformatted_form_fields[ $key ][ $field ];
				}
			}
		}

		return $args;
	}

	/**
	 * Get Reformatted Form Fields.
	 *
	 * Combines the 3 relevant repeater settings arrays into a one level deep associative array
	 * with the keys that match those that WooCommerce uses for its form fields.
	 *
	 * The result is cached so the conversion only ever happens once.
	 *
	 * @since 3.5.0
	 *
	 * @return array
	 */
	private function get_reformatted_form_fields() {
		if ( ! isset( $this->reformatted_form_fields ) ) {
			$instance = $this->get_settings_for_display();

			// Reformat form repeater field into one usable array.
			$repeater_fields = array(
				'billing_details_form_fields',
				'shipping_details_form_fields',
				'additional_information_form_fields',
			);

			$this->reformatted_form_fields = array();

			// Apply other modifications to inputs.
			foreach ( $repeater_fields as $repeater_field ) {
				if ( isset( $instance[ $repeater_field ] ) ) {
					foreach ( $instance[ $repeater_field ] as $item ) {
						if ( ! isset( $item['field_key'] ) ) {
							continue;
						}
						$this->reformatted_form_fields[ $item['field_key'] ] = $item;
					}
				}
			}
		}

		return $this->reformatted_form_fields;
	}

	/**
	 * Render Woocommerce Checkout Login Form
	 *
	 * A custom function to render a login form on the Checkout widget. The default WC Login form
	 * was removed in this file's render() method with:
	 * remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form' );
	 *
	 * And then we are adding this form into the widget at the
	 * 'woocommerce_checkout_before_customer_details' hook.
	 *
	 * We are doing this in order to match the placement of the Login form to the provided design.
	 * WC places these forms ABOVE the checkout form section where as we needed to place them inside the
	 * checkout form section. So we removed the default login form and added our own form.
	 *
	 * @since 3.5.0
	 */
	private function render_woocommerce_checkout_login_form() {
		$settings       = $this->get_settings_for_display();
		$button_classes = array( 'woocommerce-button', 'button', 'woocommerce-form-login__submit', 'e-woocommerce-form-login-submit' );
		if ( $settings['forms_buttons_hover_animation'] ) {
			$button_classes[] = 'elementor-animation-' . $settings['forms_buttons_hover_animation'];
		}
		$this->add_render_attribute(
			'button_login',
			array(
				'class' => $button_classes,
				'name'  => 'login',
				'type'  => 'submit',
			)
		);
		?>
		<div class="e-mas-woocommerce-login-section">
			<div class="elementor-woocommerce-login-messages"></div>
			<div class="woocommerce-form-login-toggle e-mas-checkout-secondary-title">
				<?php echo esc_html__( 'Returning customer?', 'mas-addons-for-elementor' ) . ' <a href="#" class="e-show-login">' . esc_html__( 'Click here to login', 'mas-addons-for-elementor' ) . '</a>'; ?>
			</div>
			<div class="e-woocommerce-login-anchor" style="display:none;">
				<p class="e-woocommerce-login-nudge e-description"><?php echo esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'mas-addons-for-elementor' ); ?></p>

				<div class="e-login-wrap">
					<div class="e-login-wrap-start">
						<p class="form-row form-row-first">
							<label for="username"><?php esc_html_e( 'Email', 'mas-addons-for-elementor' ); ?> <span class="required">*</span></label>
							<input type="text" class="input-text" name="username" id="username" autocomplete="username" />
						</p>
						<p class="form-row form-row-last">
							<label for="password"><?php esc_html_e( 'Password', 'mas-addons-for-elementor' ); ?> <span class="required">*</span></label>
							<input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
						</p>
						<div class="clear"></div>
					</div>

					<div class="e-login-wrap-end">
						<p class="form-row">
							<label for="login" class="e-login-label">&nbsp;</label>
							<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
							<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>" />
							<button <?php $this->print_render_attribute_string( 'button_login' ); ?> value="<?php esc_attr_e( 'Login', 'mas-addons-for-elementor' ); ?>"><?php esc_html_e( 'Login', 'mas-addons-for-elementor' ); ?></button>
						</p>
						<div class="clear"></div>
					</div>
				</div>

				<div class="e-login-actions-wrap">
					<div class="e-login-actions-wrap-start">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span class="elementor-woocomemrce-login-rememberme"><?php esc_html_e( 'Remember me', 'mas-addons-for-elementor' ); ?></span>
						</label>
					</div>

					<div class="e-login-actions-wrap-end">
						<p class="lost_password">
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'mas-addons-for-elementor' ); ?></a>
						</p>
					</div>
				</div>

			</div>
		</div>
		<?php
	}

	/**
	 * Render Woocommerce Checkout Coupon Form
	 *
	 * A custom function to render a coupon form on the Checkout widget. The default WC coupon form
	 * was removed in this file's render() method with:
	 * remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form' );
	 *
	 * And then we are adding this form into the widget at the
	 * 'woocommerce_checkout_order_review' hook.
	 *
	 * We are doing this in order to match the placement of the coupon form to the provided design.
	 * WC places these forms ABOVE the checkout form section where as we needed to place them inside the
	 * checkout form section. So we removed the default coupon form and added our own form.
	 *
	 * @since 3.5.0
	 */
	private function render_woocommerce_checkout_coupon_form() {
		$settings       = $this->get_settings_for_display();
		$button_classes = array( 'woocommerce-button', 'button', 'e-apply-coupon' );
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
		<div class="e-coupon-box">
			<p class="e-woocommerce-coupon-nudge e-mas-checkout-secondary-title"><?php esc_html_e( 'Have a coupon?', 'mas-addons-for-elementor' ); ?> <a href="#" class="e-show-coupon-form"><?php esc_html_e( 'Click here to enter your coupon code', 'mas-addons-for-elementor' ); ?></a></p>
			<div class="e-coupon-anchor" style="display:none">
				<label class="e-coupon-anchor-description"><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'mas-addons-for-elementor' ); ?></label>
				<div class="form-row">
					<div class="coupon-container-grid">
						<div class="col coupon-col-1 ">
							<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'mas-addons-for-elementor' ); ?>" id="coupon_code" value="" />
						</div>
						<div class="col coupon-col-2">
							<button <?php $this->print_render_attribute_string( 'button_coupon' ); ?>><?php esc_html_e( 'Apply', 'mas-addons-for-elementor' ); ?></button>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Should Render Login
	 *
	 * Decide if the login form should be rendered.
	 * The login form should be rendered if:
	 * 1) The WooCommerce setting is enabled
	 * 2) AND: a logged out user is viewing the page, OR the Editor is open
	 *
	 * @since 3.5.0
	 *
	 * @return boolean
	 */
	private function should_render_login() {
		return 'no' !== get_option( 'woocommerce_enable_checkout_login_reminder' ) && ( ! is_user_logged_in() || Plugin::elementor()->editor->is_edit_mode() );
	}

	/**
	 * Should Render Coupon
	 *
	 * Decide if the coupon form should be rendered.
	 * The coupon form should be rendered if:
	 * 1) The WooCommerce setting is enabled
	 * 2) And the Coupon Display toggle hasn't been set to 'no'
	 * 3) AND: a payment is needed, OR the Editor is open
	 *
	 * @since 3.5.0
	 *
	 * @return boolean
	 */
	private function should_render_coupon() {
		$settings               = $this->get_settings_for_display();
		$coupon_display_control = true;

		if ( '' === $settings['coupon_section_display'] ) {
			$coupon_display_control = false;
		}

		return ( WC()->cart->needs_payment() || Plugin::elementor()->editor->is_edit_mode() ) && wc_coupons_enabled() && $coupon_display_control;
	}

	/**
	 * WooCommerce Checkout Before Customer Details
	 *
	 * Callback function for the woocommerce_checkout_before_customer_details hook that outputs elements
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_checkout_before_customer_details() {
		?>
		<div class="e-checkout__container">
			<!--open container-->
			<div class="e-checkout__column e-checkout__column-start">
				<!--open column-1-->
		<?php
		if ( $this->should_render_login() ) {
			$this->render_woocommerce_checkout_login_form();
		}
	}

	/**
	 * Woocommerce Checkout After Customer Details
	 *
	 * Output containing elements. Callback function for the woocommerce_checkout_after_customer_details hook.
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_checkout_after_customer_details() {
		?>
					<!--close column-1-->
				</div>
		<?php
	}

	/**
	 * Woocommerce Checkout Before Order Review Heading 1
	 *
	 * Output containing elements. Callback function for the woocommerce_checkout_before_order_review_heading hook.
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_checkout_before_order_review_heading_1() {
		?>
				<div class="e-checkout__column e-checkout__column-end">
					<!--open column-2-->
						<div class="e-checkout__column-inner e-sticky-right-column">
							<!--open column-inner-->
		<?php
	}

	/**
	 * Woocommerce Checkout Before Order Review Heading 2
	 *
	 * Output containing elements. Callback function for the woocommerce_checkout_before_order_review_heading hook.
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_checkout_before_order_review_heading_2() {
		?>
							<div class="e-checkout__order_review">
								<!--open order_review-->
		<?php
	}

	/**
	 * Woocommerce Checkout Order Review
	 *
	 * Output containing elements. Callback function for the woocommerce_checkout_order_review hook.
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_checkout_order_review() {
		?>
									<!--close wc_order_review-->
								</div>
								<!--close order_review-->
							</div>
		<?php
		if ( $this->should_render_coupon() ) {
			$this->render_woocommerce_checkout_coupon_form();
		}
		?>
							<div class="e-checkout__order_review-2">
								<!--reopen wc_order_review-2-->
		<?php
	}

	/**
	 * Woocommerce Checkout After Order Review
	 *
	 * Output containing elements. Callback function for the woocommerce_checkout_after_order_review hook.
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 3.5.0
	 */
	public function woocommerce_checkout_after_order_review() {
		?>
										<!--close wc_order_review-2-->
						<!--close column-inner-->
					</div>
					<!--close column-2-->
				</div>
				<!--close container-->
			</div>
		<?php
	}

	/**
	 * Add Render Hooks
	 *
	 * Add actions & filters before displaying our widget.
	 *
	 * @since 3.5.0
	 */
	public function add_render_hooks() {
		add_filter( 'woocommerce_form_field_args', array( $this, 'modify_form_field' ), 70, 3 );
		add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', array( $this, 'woocommerce_terms_and_conditions_checkbox_text' ), 10, 1 );

		add_filter( 'gettext', array( $this, 'filter_gettext' ), 20, 3 );

		add_action( 'woocommerce_checkout_before_customer_details', array( $this, 'woocommerce_checkout_before_customer_details' ), 5 );
		add_action( 'woocommerce_checkout_after_customer_details', array( $this, 'woocommerce_checkout_after_customer_details' ), 95 );
		add_action( 'woocommerce_checkout_before_order_review_heading', array( $this, 'woocommerce_checkout_before_order_review_heading_1' ), 5 );
		add_action( 'woocommerce_checkout_before_order_review_heading', array( $this, 'woocommerce_checkout_before_order_review_heading_2' ), 95 );
		add_action( 'woocommerce_checkout_order_review', array( $this, 'woocommerce_checkout_order_review' ), 15 );
		add_action( 'woocommerce_checkout_after_order_review', array( $this, 'woocommerce_checkout_after_order_review' ), 95 );

		// remove the default login & coupon form because we'll be adding our own forms.
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form' );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form' );
	}

	/**
	 * Remove Render Hooks
	 *
	 * Remove actions & filters after displaying our widget.
	 *
	 * @since 3.5.0
	 */
	public function remove_render_hooks() {
		remove_filter( 'woocommerce_form_field_args', array( $this, 'modify_form_field' ), 70 );
		remove_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', array( $this, 'woocommerce_terms_and_conditions_checkbox_text' ), 10 );

		remove_filter( 'gettext', array( $this, 'filter_gettext' ), 20 );

		remove_action( 'woocommerce_checkout_before_customer_details', array( $this, 'woocommerce_checkout_before_customer_details' ), 5 );
		remove_action( 'woocommerce_checkout_after_customer_details', array( $this, 'woocommerce_checkout_after_customer_details' ), 95 );
		remove_action( 'woocommerce_checkout_before_order_review_heading', array( $this, 'woocommerce_checkout_before_order_review_heading_1' ), 5 );
		remove_action( 'woocommerce_checkout_before_order_review_heading', array( $this, 'woocommerce_checkout_before_order_review_heading_2' ), 95 );
		remove_action( 'woocommerce_checkout_order_review', array( $this, 'woocommerce_checkout_order_review' ), 15 );
		remove_action( 'woocommerce_checkout_after_order_review', array( $this, 'woocommerce_checkout_after_order_review' ), 95 );
	}

	/**
	 * Render
	 */
	protected function render() {
		$is_editor = Plugin::elementor()->editor->is_edit_mode();

		// Simulate a logged out user so that all WooCommerce sections will render in the Editor.
		if ( $is_editor ) {
			$store_current_user = wp_get_current_user()->ID;
			wp_set_current_user( 0 );
		}

		// Add actions & filters before displaying our Widget.
		$this->add_render_hooks();

		// Display our Widget.
		echo do_shortcode( '[woocommerce_checkout]' );

		// Remove actions & filters after displaying our Widget.
		$this->remove_render_hooks();

		// Return to existing logged-in user after widget is rendered.
		if ( $is_editor ) {
			wp_set_current_user( $store_current_user );
		}
	}

	/**
	 * Get Group name
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
