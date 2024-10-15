<?php
/**
 * The Purchase summary Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use MASElementor\Plugin;
use MASElementor\Modules\Woocommerce\Module;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Purchase_Summary
 */
class Purchase_Summary extends Base_Widget {

	/**
	 * Order id.
	 *
	 * @var order_id
	 */
	private $order_id = null;

	/**
	 * Order key.
	 *
	 * @var order_key
	 */
	private $order_key = null;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-purchase-summary';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Purchase Summary', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-purchase-summary';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'summary', 'thank you', 'confirmation', 'purchase' );
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
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'mas-purchase-summary' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'confirmation_message',
			array(
				'label' => esc_html__( 'Confirmation Message', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'confirmation_message_active',
			array(
				'label'     => esc_html__( 'Confirmation Message', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
				'selectors' => array(
					'{{WRAPPER}}' => '--confirmation-message-display: block;',
				),
			)
		);

		$this->add_control(
			'confirmation_message_text',
			array(
				'label'       => esc_html__( 'Message', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Thank You. Your order has been received.', 'mas-addons-for-elementor' ),
				'label_block' => true,
				'condition'   => array(
					'confirmation_message_active!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'confirmation_message_alignment',
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
				'condition' => array(
					'confirmation_message_active!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--confirmation-message-alignment: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'payment_details',
			array(
				'label' => esc_html__( 'Payment Details', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_details_number',
			array(
				'label'   => esc_html__( 'Number', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Order Number:', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_details_date',
			array(
				'label'   => esc_html__( 'Date:', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Order Date:', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_details_email',
			array(
				'label'   => esc_html__( 'Email', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Order Email:', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_details_total',
			array(
				'label'   => esc_html__( 'Total', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Order Total:', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_details_payment',
			array(
				'label'   => esc_html__( 'Payment', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Payment Method:', 'mas-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'bank_details',
			array(
				'label' => esc_html__( 'Bank Details', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'bank_details_text',
			array(
				'label'   => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Our Bank Details', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'bank_details_alignment',
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
					'{{WRAPPER}}' => '--bank-details-alignment: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'downloads',
			array(
				'label' => esc_html__( 'Downloads', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'downloads_text',
			array(
				'label'   => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Downloads', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'downloads_alignment',
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
					'{{WRAPPER}}' => '--downloads-alignment: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'order_summary',
			array(
				'label' => esc_html__( 'Purchase Summary', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_summary_text',
			array(
				'label'   => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Order Details', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}}' => '--order-summary-alignment: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'billing_details',
			array(
				'label' => esc_html__( 'Billing Details', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'billing_details_text',
			array(
				'label'   => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Billing Details', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}}' => '--billing-details-alignment: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'shipping_details',
			array(
				'label' => esc_html__( 'Shipping Address', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'shipping_details_text',
			array(
				'label'   => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Shipping Details', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'shipping_details_alignment',
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
					'{{WRAPPER}}' => '--shipping-details-alignment: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'preview_order',
			array(
				'label' => esc_html__( 'Preview Settings', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'preview_order_type',
			array(
				'label'   => esc_html__( 'Preview order with', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''             => 'Latest Order',
					'custom-order' => 'Order ID',
				),
			)
		);

		$this->add_control(
			'preview_order_custom',
			array(
				'label'       => esc_html__( 'Order ID', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => array(
					'preview_order_type' => 'custom-order',
				),
				'render_type' => 'template',
				'description' => esc_html__( 'Note: To find an order ID, go to the WP dashboard: WooCommerce > Orders', 'mas-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sections_tabs_style',
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
				'name'     => 'sections_box_shadow',
				'selector' => '{{WRAPPER}} .shop_table, {{WRAPPER}} address',
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
			)
		);

		$this->add_responsive_control(
			'sections_border_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .shop_table, {{WRAPPER}} address' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'sections_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--sections-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'typography_title',
			array(
				'label' => esc_html__( 'Typography', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'confirmation_message_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Confirmation Message', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'confirmation_message_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--confirmation-message-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'confirmation_message_typography',
				'selector' => '{{WRAPPER}} .woocommerce-thankyou-order-received',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'confirmation_message_text_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-thankyou-order-received',
			)
		);

		$this->add_control(
			'titles_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Titles', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'titles_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--titles-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'titles_typography',
				'selector' => '{{WRAPPER}} h2',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'titles_text_shadow',
				'selector' => '{{WRAPPER}} h2',
			)
		);

		$this->add_responsive_control(
			'titles_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--titles-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'general_text_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'General Text', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'general_text_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--general-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'general_text_typography',
				'selector' => '{{WRAPPER}} address, {{WRAPPER}} .product-purchase-note, {{WRAPPER}} .woocommerce-thankyou-order-details + p',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'payment_details_title',
			array(
				'label' => esc_html__( 'Payment Details', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'payment_details_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 75,
					),
				),
				'default'    => array( 'px' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--payment-details-space-between: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'payment_details_titles_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Titles', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_details_titles_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--payment-details-titles-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'payment_details_titles_typography',
				'selector' => '{{WRAPPER}} .woocommerce-order-overview.order_details li',
				'exclude'  => array(
					'text_decoration',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'payment_details_titles_text_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-order-overview.order_details li',
			)
		);

		$this->add_responsive_control(
			'payment_details_titles_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--payment-details-titles-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'payment_details_items_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Items', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'payment_details_items_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--payment-details-items-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'payment_details_items_typography',
				'selector' => '{{WRAPPER}} .woocommerce-order-overview.order_details li strong',
				'exclude'  => array(
					'text_decoration',
				),
			)
		);

		$this->add_control(
			'payment_details_dividers_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Dividers', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'payment_details_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--payment-details-border-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'payment_details_border_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--payment-details-border-width: {{SIZE}}{{UNIT}};',
				),

				'condition'  => array(
					'payment_details_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'payment_details_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--payment-details-border-color: {{VALUE}};',
				),
				'condition' => array(
					'payment_details_border_type!' => 'none',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'bank_details_title',
			array(
				'label' => esc_html__( 'Bank Details', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'bank_details_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 75,
					),
				),
				'default'    => array( 'px' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--bank-details-space-between: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'bank_details_account_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Account Title', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'account_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--account-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'account_title_typography',
				'selector' => '{{WRAPPER}} .wc-bacs-bank-details-account-name',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'account_title_text_shadow',
				'selector' => '{{WRAPPER}} .wc-bacs-bank-details-account-name',
			)
		);

		$this->add_responsive_control(
			'account_title_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--account-title-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bank_details_titles_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Titles', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'bank_details_titles_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--bank-details-titles-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bank_details_titles_typography',
				'selector' => '{{WRAPPER}} .woocommerce-bacs-bank-details .wc-bacs-bank-details li',
				'exclude'  => array(
					'text_decoration',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'bank_details_titles_text_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-bacs-bank-details .wc-bacs-bank-details li',
			)
		);

		$this->add_responsive_control(
			'bank_details_titles_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--bank-details-titles-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bank_details_items_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Items', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'bank_details_items_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--bank-details-items-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bank_details_items_typography',
				'selector' => '{{WRAPPER}} .woocommerce-bacs-bank-details .wc-bacs-bank-details li strong',
				'exclude'  => array(
					'text_decoration',
				),
			)
		);

		$this->add_control(
			'bank_details_dividers_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Dividers', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'bank_details_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--bank-details-border-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'bank_details_border_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--bank-details-border-width: {{SIZE}}{{UNIT}};',
				),

				'condition'  => array(
					'bank_details_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'bank_details_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--bank-details-border-color: {{VALUE}};',
				),
				'condition' => array(
					'bank_details_border_type!' => 'none',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'order_details_title',
			array(
				'label' => esc_html__( 'Order Details', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'order_details_rows_gap',
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
					'{{WRAPPER}}' => '--order-details-rows-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'order_details_titles_totals',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Titles &amp; Totals', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_details_titles_totals_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-details-titles-totals-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_details_titles_totals_typography',
				'selector' => '{{WRAPPER}} .shop_table thead tr th, {{WRAPPER}} .shop_table tfoot th, {{WRAPPER}} .shop_table tfoot tr td, {{WRAPPER}} .shop_table tfoot tr td span, {{WRAPPER}} .woocommerce-table--order-downloads tr td:before',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'order_details_titles_totals_text_shadow',
				'selector' => '{{WRAPPER}} .shop_table thead tr th, {{WRAPPER}} .shop_table tfoot th, {{WRAPPER}} .shop_table tfoot tr td, {{WRAPPER}} .shop_table tfoot tr td span, {{WRAPPER}} .woocommerce-table--order-downloads tr td:before',
			)
		);

		$this->add_control(
			'order_details_items_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Items', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_details_items_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-details-items-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_details_items_typography',
				'selector' => '{{WRAPPER}} .product-quantity, {{WRAPPER}} .woocommerce-table--order-details td a, {{WRAPPER}} td.product-total, {{WRAPPER}} td.download-product, {{WRAPPER}} td.download-remaining, {{WRAPPER}} td.download-expires, {{WRAPPER}} td.download-file',
			)
		);

		$this->add_control(
			'order_details_variations_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Variations', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'order_details_variations_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-details-variations-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_details_variations_typography',
				'selector' => '{{WRAPPER}} .product-name .wc-item-meta .wc-item-meta-label, {{WRAPPER}} .wc-item-meta li p',
			)
		);

		$this->add_control(
			'order_details_product_links_title',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Link', 'mas-addons-for-elementor' ),
			)
		);

		$this->start_controls_tabs( 'order_details_product_links_colors' );

		$this->start_controls_tab( 'order_details_product_links_normal_colors', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'order_details_product_links_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-details-product-links-normal-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'order_details_product_links_hover_colors', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'order_details_product_links_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--order-details-product-links-hover-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'order_details_dividers_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Dividers', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'order_details_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--tables-divider-border-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_details_border_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--tables-divider-border-width: {{SIZE}}{{UNIT}};',
				),

				'condition'  => array(
					'order_details_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'order_details_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--tables-divider-border-color: {{VALUE}};',
				),
				'condition' => array(
					'order_details_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'order_details_button_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Buttons', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_details_button_typography',
				'selector' => '{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'order_details_button_text_shadow',
				'selector' => '{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button',
			)
		);

		$this->start_controls_tabs( 'order_details_button_styles' );

		$this->start_controls_tab( 'order_details_button_styles_normal', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'order_details_button_normal_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--button-normal-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'order_details_button_normal_background',
				'selector' => '{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'order_details_button_normal_box_shadow',
				'selector' => '{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'order_details_button_styles_hover', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'order_details_button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--button-hover-text-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'order_details_button_hover_background',
				'selector' => '{{WRAPPER}} .shop_table .button:hover, {{WRAPPER}} .order-again .button:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'order_details_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .shop_table .button:hover, {{WRAPPER}} .order-again .button:hover',
			)
		);

		$this->add_control(
			'order_details_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .shop_table .button:hover, {{WRAPPER}} .order-again .button:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'order_details_button_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'order_details_button_hover_transition_duration',
			array(
				'label'     => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ) . ' (ms)',
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--button-hover-transition-duration: {{SIZE}}ms',
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
			'order_details_button_hover_animation',
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
			'order_details_button_border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--buttons-border-type: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'order_details_button_border_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .shop_table .button, {{WRAPPER}} .order-again .button, {{WRAPPER}} .woocommerce-pagination .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'order_details_button_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'order_details_button_border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' => '--buttons-border-color: {{VALUE}};',
				),
				'condition' => array(
					'order_details_button_border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'order_details_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--button-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_details_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--button-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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
			'Order number:'    => isset( $instance['payment_details_number'] ) ? $instance['payment_details_number'] : '',
			'Date:'            => isset( $instance['payment_details_date'] ) ? $instance['payment_details_date'] : '',
			'Email:'           => isset( $instance['payment_details_email'] ) ? $instance['payment_details_email'] : '',
			'Total:'           => isset( $instance['payment_details_total'] ) ? $instance['payment_details_total'] : '',
			'Payment method:'  => isset( $instance['payment_details_payment'] ) ? $instance['payment_details_payment'] : '',
			'Our bank details' => isset( $instance['bank_details_text'] ) ? $instance['bank_details_text'] : '',
			'Order details'    => isset( $instance['order_summary_text'] ) ? $instance['order_summary_text'] : '',
			'Billing address'  => isset( $instance['billing_details_text'] ) ? $instance['billing_details_text'] : '',
			'Shipping address' => isset( $instance['shipping_details_text'] ) ? $instance['shipping_details_text'] : '',
			'Downloads'        => isset( $instance['downloads_text'] ) ? $instance['downloads_text'] : '',
		);
	}

	/**
	 * Modify Order Received Text.
	 *
	 * @since 3.5.0
	 *
	 * @param string $text text.
	 * @return string
	 */
	public function modify_order_received_text( $text ) {
		$instance = $this->get_settings_for_display();

		if ( isset( $instance['confirmation_message_text'] ) ) {
			$text = $instance['confirmation_message_text'];
		}

		return $text;
	}

	/**
	 * Get modified order id.
	 */
	public function get_modified_order_id() {
		return $this->order_id;
	}

	/**
	 * Get modified order key.
	 */
	public function get_modified_order_key() {
		return $this->order_key;
	}

	/**
	 * Render.
	 */
	protected function render() {
		$is_editor  = Plugin::elementor()->editor->is_edit_mode();
		$is_preview = Module::is_preview();

		if ( $is_editor || $is_preview ) {
			$this->set_preview_order();

			add_filter( 'woocommerce_thankyou_order_id', array( $this, 'get_modified_order_id' ) );
			add_filter( 'woocommerce_thankyou_order_key', array( $this, 'get_modified_order_key' ) );

			/**
			 * The action `template_redirect` is not run during the re-loading of the Widget and as a result the
			 * `wc_template_redirect` function is not run which is responsible for loading the following, so we
			 * must load them ourselves.
			 */
			WC()->payment_gateways();
			WC()->shipping();
		}

		/*
		 * Add actions & filters before displaying our Widget.
		 */
		add_filter( 'gettext', array( $this, 'filter_gettext' ), 20, 3 );
		add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'modify_order_received_text' ) );

		/**
		 * Display our Widget.
		 */
		global $wp;
		if ( isset( $wp->query_vars['order-received'] ) && wc_get_order( intval( $wp->query_vars['order-received'] ) ) ) {
			echo do_shortcode( '[woocommerce_checkout]' );
		} elseif ( $is_editor || $is_preview ) {
			$this->no_order_notice();
		}

		/*
		 * Remove actions & filters after displaying our Widget.
		 */
		remove_filter( 'gettext', array( $this, 'filter_gettext' ), 20 );
		remove_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'modify_order_received_text' ) );

		if ( $is_editor || $is_preview ) {
			remove_filter( 'woocommerce_thankyou_order_id', array( $this, 'get_modified_order_id' ) );
			remove_filter( 'woocommerce_thankyou_order_key', array( $this, 'get_modified_order_key' ) );
		}
	}

	/**
	 * No order notice.
	 */
	public function no_order_notice() {
		?>
		<div class="woocommerce-error" role="alert">
			<?php echo esc_html__( 'You need at least one WooCommerce order to preview the order here.', 'mas-addons-for-elementor' ); ?>
		</div>
		<?php
	}

	/**
	 * Set preview order.
	 */
	public function set_preview_order() {
		$instance = $this->get_settings_for_display();
		$order    = false;

		if ( 'custom-order' === $instance['preview_order_type'] ) {
			$order = wc_get_order( $instance['preview_order_custom'] );
		}

		if ( ! $order ) {
			$latest_order = wc_get_orders(
				array(
					'limit'   => 1,
					'orderby' => 'date',
					'order'   => 'DESC',
					'return'  => 'ids',
				)
			);

			if ( isset( $latest_order[0] ) ) {
				$order = wc_get_order( $latest_order[0] );
			}
		}

		if ( $order ) {
			global $wp;
			$wp->set_query_var( 'order-received', $order->get_id() );

			$this->order_id  = $order->get_id();
			$this->order_key = $order->get_order_key();
		}
	}

	/**
	 * Get group name.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
