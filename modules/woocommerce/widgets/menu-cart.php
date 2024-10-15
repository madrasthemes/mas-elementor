<?php
/**
 * The Menu Cart Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use MASElementor\Modules\Woocommerce\Module;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Menu Cart
 */
class Menu_Cart extends Base_Widget {
	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-menu-cart';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Menu Cart', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-cart';
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
		return array( 'mas-cart-stylesheet' );
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'theme-elements', 'woocommerce-elements' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_menu_icon_content',
			array(
				'label' => esc_html__( 'Menu Icon', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'        => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'cart-light'    => esc_html__( 'Cart', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Light', 'mas-addons-for-elementor' ),
					'cart-medium'   => esc_html__( 'Cart', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Medium', 'mas-addons-for-elementor' ),
					'cart-solid'    => esc_html__( 'Cart', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'basket-light'  => esc_html__( 'Basket', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Light', 'mas-addons-for-elementor' ),
					'basket-medium' => esc_html__( 'Basket', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Medium', 'mas-addons-for-elementor' ),
					'basket-solid'  => esc_html__( 'Basket', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'bag-light'     => esc_html__( 'Bag', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Light', 'mas-addons-for-elementor' ),
					'bag-medium'    => esc_html__( 'Bag', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Medium', 'mas-addons-for-elementor' ),
					'bag-solid'     => esc_html__( 'Bag', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'custom'        => esc_html__( 'Custom', 'mas-addons-for-elementor' ),
				),
				'default'      => 'cart-medium',
				'prefix_class' => 'toggle-icon--', // Prefix class not used anymore, but kept for BC reasons.
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'menu_icon_svg',
			array(
				'label'            => esc_html__( 'Custom Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_active',
				'default'          => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
				'skin_settings'    => array(
					'inline' => array(
						'none' => array(
							'label' => 'None',
						),
					),
				),
				'recommended'      => array(
					'fa-solid' => array(
						'shopping-bag',
						'shopping-basket',
						'shopping-cart',
						'cart-arrow-down',
						'cart-plus',
					),
				),
				'skin'             => 'inline',
				'label_block'      => false,
				'condition'        => array(
					'icon' => 'custom',
				),
			)
		);

		$this->add_control(
			'items_indicator',
			array(
				'label'        => esc_html__( 'Items Indicator', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'none'   => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'bubble' => esc_html__( 'Bubble', 'mas-addons-for-elementor' ),
					'plain'  => esc_html__( 'Plain', 'mas-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-menu-cart--items-indicator-',
				'default'      => 'bubble',
			)
		);

		$this->add_control(
			'hide_empty_indicator',
			array(
				'label'        => esc_html__( 'Hide Empty', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mas-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'return_value' => 'hide',
				'prefix_class' => 'elementor-menu-cart--empty-indicator-',
				'condition'    => array(
					'items_indicator!' => 'none',
				),
			)
		);

		$this->add_control(
			'show_subtotal',
			array(
				'label'        => esc_html__( 'Subtotal', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'elementor-menu-cart--show-subtotal-',
			)
		);

		$this->add_responsive_control(
			'alignment',
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
					'{{WRAPPER}}' => '--main-alignment: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart',
			array(
				'label' => esc_html__( 'Cart', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'cart_type',
			array(
				'label'              => esc_html__( 'Cart Type', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'side-cart' => esc_html__( 'Side Cart', 'mas-addons-for-elementor' ),
					'mini-cart' => esc_html__( 'Mini Cart', 'mas-addons-for-elementor' ),
				),
				'default'            => 'side-cart',
				'prefix_class'       => 'elementor-menu-cart--cart-type-',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'open_cart',
			array(
				'label'              => esc_html__( 'Open Cart', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'click'     => esc_html__( 'On Click', 'mas-addons-for-elementor' ),
					'mouseover' => esc_html__( 'On Hover', 'mas-addons-for-elementor' ),
				),
				'default'            => 'click',
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_responsive_control(
			'side_cart_alignment',
			array(
				'label'                => esc_html__( 'Cart Position', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start' => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'end'   => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition'            => array(
					'cart_type' => 'side-cart',
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'start' => '--side-cart-alignment-transform: translateX(-100%); --side-cart-alignment-right: auto; --side-cart-alignment-left: 0;',
					'end'   => '--side-cart-alignment-transform: translateX(100%); --side-cart-alignment-left: auto; --side-cart-alignment-right: 0;',
				),
			)
		);

		$this->add_responsive_control(
			'mini_cart_alignment',
			array(
				'label'                => esc_html__( 'Cart Position', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'  => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition'            => array(
					'cart_type' => 'mini-cart',
				),
				'selectors'            => array(
					'{{WRAPPER}}.elementor-menu-cart--cart-type-mini-cart .elementor-menu-cart__container' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'start'  => 'left: 0; right: auto; transform: none;',
					'center' => 'left: 50%; right: auto; transform: translateX(-50%);',
					'end'    => 'right: 0; left: auto; transform: none;',
				),
			)
		);

		$this->add_responsive_control(
			'mini_cart_spacing',
			array(
				'label'      => esc_html__( 'Distance', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => -300,
						'max' => 300,
					),
					'%'  => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'condition'  => array(
					'cart_type' => 'mini-cart',
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--mini-cart-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_close_cart_button',
			array(
				'label'     => esc_html__( 'Close Cart', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'close_cart_button_show',
			array(
				'label'                => esc_html__( 'Close Icon', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SWITCHER,
				'label_on'             => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off'            => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'return_value'         => 'yes',
				'default'              => 'yes',
				'selectors'            => array(
					'{{WRAPPER}} .elementor-menu-cart__close-button, {{WRAPPER}} .elementor-menu-cart__close-button-custom' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'' => 'display: none;',
				),
			)
		);

		$this->add_control(
			'close_cart_icon_svg',
			array(
				'label'            => esc_html__( 'Custom Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_active',
				'skin_settings'    => array(
					'inline' => array(
						'none' => array(
							'label' => 'Default',
							'icon'  => 'fas fa-times',
						),
						'icon' => array(
							'icon' => 'eicon-star',
						),
					),
				),
				'recommended'      => array(
					'fa-regular' => array(
						'times-circle',
					),
					'fa-solid'   => array(
						'times',
						'times-circle',
					),
				),
				'skin'             => 'inline',
				'label_block'      => false,
				'condition'        => array(
					'close_cart_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'close_cart_button_alignment',
			array(
				'label'                => esc_html__( 'Icon Position', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start' => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'end'   => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition'            => array(
					'close_cart_button_show!' => '',
				),
				'selectors_dictionary' => array(
					'start' => 'margin-right: auto',
					'end'   => 'margin-left: auto',
				),
				'selectors'            => array(
					'{{WRAPPER}} .elementor-menu-cart__close-button, {{WRAPPER}} .elementor-menu-cart__close-button-custom' => '{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'heading_remove_item_button',
			array(
				'label'     => esc_html__( 'Remove Item', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_remove_icon',
			array(
				'label'        => esc_html__( 'Remove Item Icon', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'elementor-menu-cart--show-remove-button-',
			)
		);

		$this->add_control(
			'remove_item_button_position',
			array(
				'label'        => esc_html__( 'Icon Position', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'      => '',
				'prefix_class' => 'remove-item-position--',
				'condition'    => array(
					'show_remove_icon!' => '',
				),
			)
		);

		$this->add_control(
			'heading_price_quantity',
			array(
				'label'     => esc_html__( 'Price and Quantity', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'price_quantity_position',
			array(
				'label'                => esc_html__( 'Position', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'top'    => '--price-quantity-position--grid-template-rows: auto 75%; --price-quantity-position--align-self: start;',
					'bottom' => '',
				),
			)
		);

		$this->add_control(
			'show_divider',
			array(
				'label'                => esc_html__( 'Cart Dividers', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SWITCHER,
				'separator'            => 'before',
				'label_on'             => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off'            => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'return_value'         => 'yes',
				'default'              => 'yes',
				'selectors'            => array(
					'{{WRAPPER}}' => '--divider-style: {{VALUE}}; --subtotal-divider-style: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					''    => 'none',
					'yes' => 'solid',
				),
			)
		);

		$this->add_control(
			'heading_buttons',
			array(
				'label'     => esc_html__( 'Buttons', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'view_cart_button_show',
			array(
				'label'                => esc_html__( 'View Cart', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SWITCHER,
				'label_on'             => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off'            => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'return_value'         => 'yes',
				'default'              => 'yes',
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'' => '--view-cart-button-display: none; --cart-footer-layout: 1fr;',
				),
			)
		);

		$this->add_control(
			'view_cart_button_alignment',
			array(
				'label'                => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'     => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justify', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'condition'            => array(
					'view_cart_button_show!' => '',
					'checkout_button_show'   => '',
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'start'   => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: left; --cart-footer-buttons-alignment-button-width: auto;',
					'center'  => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: center; --cart-footer-buttons-alignment-button-width: auto;',
					'end'     => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: right; --cart-footer-buttons-alignment-button-width: auto;',
					'justify' => '--cart-footer-layout: 1fr;',
				),
			)
		);

		$this->add_control(
			'checkout_button_show',
			array(
				'label'                => esc_html__( 'Checkout', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SWITCHER,
				'label_on'             => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off'            => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'return_value'         => 'yes',
				'default'              => 'yes',
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'' => '--checkout-button-display: none; --cart-footer-layout: 1fr;',
				),
			)
		);

		$this->add_control(
			'checkout_button_alignment',
			array(
				'label'                => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'     => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justify', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'condition'            => array(
					'checkout_button_show!' => '',
					'view_cart_button_show' => '',
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'start'   => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: left; --cart-footer-buttons-alignment-button-width: auto;',
					'center'  => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: center; --cart-footer-buttons-alignment-button-width: auto;',
					'end'     => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: right; --cart-footer-buttons-alignment-button-width: auto;',
					'justify' => '--cart-footer-layout: 1fr;',
				),
			)
		);

		$this->add_control(
			'checkout_button_display',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HIDDEN,
				'condition' => array(
					'checkout_button_show'  => '',
					'view_cart_button_show' => '',
				),
				'default'   => '--cart-footer-buttons-alignment-display: none;',
				'selectors' => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'buttons_position',
			array(
				'label'                => esc_html__( 'Vertical Position', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => '',
				'condition'            => array(
					'cart_type' => 'side-cart',
				),
				'conditions'           => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'view_cart_button_show',
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'checkout_button_show',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'bottom' => '--cart-buttons-position-margin: auto;',
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
			'heading_additional_options',
			array(
				'label' => esc_html__( 'Cart', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'automatically_open_cart',
			array(
				'label'              => esc_html__( 'Automatically Open Cart', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'description'        => esc_html__( 'Open the cart every time an item is added.', 'mas-addons-for-elementor' ),
				'label_on'           => esc_html__( 'Yes', 'mas-addons-for-elementor' ),
				'label_off'          => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'return_value'       => 'yes',
				'default'            => 'no',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'automatically_update_cart',
			array(
				'label'                => esc_html__( 'Automatically Update Cart', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SWITCHER,
				'label_on'             => esc_html__( 'Yes', 'mas-addons-for-elementor' ),
				'label_off'            => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'return_value'         => 'yes',
				'default'              => 'yes',
				'description'          => esc_html__( 'Updates to the cart (e.g., a removed item) via Ajax. The cart will update without refreshing the whole page.', 'mas-addons-for-elementor' ),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'yes' => '--elementor-remove-from-cart-button: none; --remove-from-cart-button: block;',
					''    => '--elementor-remove-from-cart-button: block; --remove-from-cart-button: none;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label' => esc_html__( 'Menu Icon', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'toggle_button_colors' );

		$this->start_controls_tab( 'toggle_button_normal_colors', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'toggle_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--toggle-button-text-color: {{VALUE}};',
				),
				'condition' => array(
					'show_subtotal!' => '',
				),
			)
		);

		$this->add_control(
			'toggle_button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--toggle-button-icon-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--toggle-button-background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toggle_button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--toggle-button-border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'toggle_button_normal_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'toggle_button_hover_colors', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'toggle_button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--toggle-button-hover-text-color: {{VALUE}};',
				),
				'condition' => array(
					'show_subtotal!' => '',
				),
			)
		);

		$this->add_control(
			'toggle_button_hover_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--toggle-button-icon-hover-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--toggle-button-hover-background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toggle_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--toggle-button-hover-border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'toggle_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_button_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'max' => 20,
					),
					'em' => array(
						'max' => 2,
					),
				),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}}' => '--toggle-button-border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--toggle-button-border-radius: {{SIZE}}{{UNIT}}',
				),

			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'toggle_button_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button',
				'separator' => 'before',
				'condition' => array(
					'show_subtotal!' => '',
				),
			)
		);

		$this->add_control(
			'heading_icon_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'toggle_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--toggle-icon-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-text' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-text' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_subtotal!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--toggle-icon-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'items_indicator_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Items Indicator', 'mas-addons-for-elementor' ),
				'separator' => 'before',
				'condition' => array(
					'items_indicator!' => 'none',
				),
			)
		);
		$this->add_control(
			'items_indicator_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--items-indicator-text-color: {{VALUE}};',
				),
				'condition' => array(
					'items_indicator!' => 'none',
				),
			)
		);

		$this->add_control(
			'items_indicator_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--items-indicator-background-color: {{VALUE}};',
				),
				'condition' => array(
					'items_indicator' => 'bubble',
				),
			)
		);

		$this->add_responsive_control(
			'items_indicator_distance',
			array(
				'label'      => esc_html__( 'Distance', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-icon .elementor-button-icon-qty[data-counter]' => 'right: -{{SIZE}}{{UNIT}}; top: -{{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-icon .elementor-button-icon-qty[data-counter]' => 'right: {{SIZE}}{{UNIT}}; top: -{{SIZE}}{{UNIT}}; left: auto;',
				),
				'condition'  => array(
					'items_indicator' => 'bubble',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_style',
			array(
				'label' => esc_html__( 'Cart', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--cart-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_type',
			array(
				'label'     => esc_html__( 'Border Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'   => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--cart-border-style: {{VALUE}};',
				),
				'default'   => 'none',
			)
		);

		$this->add_responsive_control(
			'border_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-menu-cart__main' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--cart-border-color: {{VALUE}};',
				),
				'condition' => array(
					'border_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--cart-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cart_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-menu-cart__main',
			)
		);

		$this->add_responsive_control(
			'cart_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--cart-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_close',
			array(
				'label'     => esc_html__( 'Close Cart', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'close_cart_button_show!' => '',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'close_cart_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--cart-close-icon-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'close_cart_button_show!' => '',
				),
			)
		);

		$this->start_controls_tabs( 'cart_icon_style' );

		$this->start_controls_tab(
			'icon_normal',
			array(
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => array(
					'close_cart_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'close_cart_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--cart-close-button-color: {{VALUE}};',
				),
				'condition' => array(
					'close_cart_button_show!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => array(
					'close_cart_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'close_cart_icon_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--cart-close-button-hover-color: {{VALUE}};',
				),
				'condition' => array(
					'close_cart_button_show!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_remove_item_button_style',
			array(
				'label'     => esc_html__( 'Remove Item', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_remove_icon!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'remove_item_button_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--remove-item-button-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_remove_icon!' => '',
				),
			)
		);

		$this->start_controls_tabs(
			'cart_remove_item_button_style',
			array(
				'condition' => array(
					'show_remove_icon!' => '',
				),
			)
		);

		$this->start_controls_tab(
			'remove_item_button_normal',
			array(
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => array(
					'show_remove_icon!' => '',
				),
			)
		);

		$this->add_control(
			'remove_item_button_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--remove-item-button-color: {{VALUE}}',
				),
				'condition' => array(
					'show_remove_icon!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'remove_item_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => array(
					'show_remove_icon!' => '',
				),
			)
		);

		$this->add_control(
			'remove_item_button_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--remove-item-button-hover-color: {{VALUE}};',
				),
				'condition' => array(
					'show_remove_icon!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_subtotal_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Subtotal', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'subtotal_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--menu-cart-subtotal-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtotal_typography',
				'selector' => '{{WRAPPER}} .elementor-menu-cart__subtotal',
			)
		);

		$this->add_responsive_control(
			'subtotal_alignment',
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
					'{{WRAPPER}}' => '--menu-cart-subtotal-text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'subtotal_divider_style',
			array(
				'label'                => esc_html__( 'Divider Style', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SELECT,
				'options'              => array(
					''       => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
					'groove' => esc_html__( 'Groove', 'mas-addons-for-elementor' ),
				),
				'selectors'            => array(
					'{{WRAPPER}} .widget_shopping_cart_content' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					''       => '--subtotal-divider-left-width: 0; --subtotal-divider-right-width: 0;',
					'solid'  => '--subtotal-divider-style: solid;',
					'double' => '--subtotal-divider-style: double;',
					'dotted' => '--subtotal-divider-style: dotted;',
					'dashed' => '--subtotal-divider-style: dashed;',
					'groove' => '--subtotal-divider-style: groove;',
				),
			)
		);

		$this->add_responsive_control(
			'subtotal_divider_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .widget_shopping_cart_content' => '--subtotal-divider-top-width: {{TOP}}{{UNIT}}; --subtotal-divider-right-width: {{RIGHT}}{{UNIT}}; --subtotal-divider-bottom-width: {{BOTTOM}}{{UNIT}}; --subtotal-divider-left-width: {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'subtotal_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .widget_shopping_cart_content' => '--subtotal-divider-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_tabs_style',
			array(
				'label' => esc_html__( 'Products', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_product_title_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Product Title', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_title_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .elementor-menu-cart__product-name a',
			)
		);

		$this->start_controls_tabs( 'product_title_colors' );

		$this->start_controls_tab( 'product_title_normal_colors', array( 'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'product_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-menu-cart__product-name a' => 'color: {{VALUE}};',

				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'product_title_hover_colors', array( 'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ) ) );

		$this->add_control(
			'product_title_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-menu-cart__product-name a:hover' => 'color: {{VALUE}};',

				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_product_variations_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Variations', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'product_variations_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--product-variations-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_variations_typography',
				'selector' => '{{WRAPPER}} .elementor-menu-cart__product .variation',
			)
		);

		$this->add_control(
			'heading_product_price_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Product Price', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'product_price_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--product-price-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_price_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .elementor-menu-cart__product-price',
			)
		);

		$this->add_control(
			'heading_quantity_title_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Quantity', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'product_quantity_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-menu-cart__product-price .product-quantity' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_quantity_typography',
				'selector' => '{{WRAPPER}} .elementor-menu-cart__product-price .product-quantity',
			)
		);

		$this->add_control(
			'heading_product_divider_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Divider', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => esc_html__( 'Style', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}}' => '--divider-style: {{VALUE}}; --subtotal-divider-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--divider-color: {{VALUE}}; --subtotal-divider-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'divider_width',
			array(
				'label'      => esc_html__( 'Weight', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--divider-width: {{SIZE}}{{UNIT}}; --subtotal-divider-top-width: {{SIZE}}{{UNIT}}; --subtotal-divider-right-width: {{SIZE}}{{UNIT}}; --subtotal-divider-bottom-width: {{SIZE}}{{UNIT}}; --subtotal-divider-left-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'divider_gap',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--product-divider-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_buttons',
			array(
				'label'      => esc_html__( 'Buttons', 'mas-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'view_cart_button_show',
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'checkout_button_show',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'buttons_layout',
			array(
				'label'                => esc_html__( 'Layout', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SELECT,
				'options'              => array(
					'inline'  => esc_html__( 'Inline', 'mas-addons-for-elementor' ),
					'stacked' => esc_html__( 'Stacked', 'mas-addons-for-elementor' ),
				),
				'default'              => 'inline',
				'devices'              => array( 'desktop', 'tablet', 'mobile' ),
				'condition'            => array(
					'view_cart_button_show!' => '',
					'checkout_button_show!'  => '',
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'inline'  => '--cart-footer-layout: 1fr 1fr; --products-max-height-sidecart: calc(100vh - 240px); --products-max-height-minicart: calc(100vh - 385px)',
					'stacked' => '--cart-footer-layout: 1fr; --products-max-height-sidecart: calc(100vh - 300px); --products-max-height-minicart: calc(100vh - 450px)',
				),
			)
		);

		$this->add_responsive_control(
			'space_between_buttons',
			array(
				'label'      => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--space-between-buttons: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'view_cart_button_show!' => '',
					'checkout_button_show!'  => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_buttons_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .elementor-menu-cart__footer-buttons .elementor-button',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--cart-footer-buttons-border-radius: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'heading_view_cart_button_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'View Cart', 'mas-addons-for-elementor' ),
				'condition' => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'view_cart_buttons_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .elementor-menu-cart__footer-buttons a.elementor-button--view-cart',
				'separator' => 'before',
				'condition' => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->start_controls_tabs(
			'view_cart_button_text_colors',
			array(
				'condition' => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->start_controls_tab(
			'heading_view_cart_button_normal_style',
			array(
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'view_cart_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--view-cart-button-text-color: {{VALUE}};',
				),
				'condition' => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'view_cart_button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--view-cart-button-background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'heading_view_cart_button_hover_style',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'view_cart_button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--view-cart-button-hover-text-color: {{VALUE}};',
				),
				'condition' => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'view_cart_button_hover_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--view-cart-button-hover-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_button_border_hover_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-menu-cart__footer-buttons .elementor-button--view-cart:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'view_cart_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'view_cart_border',
				'selector'  => '{{WRAPPER}} .elementor-button--view-cart',
				'separator' => 'before',
				'condition' => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'view_cart_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-menu-cart__footer-buttons a.elementor-button--view-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'view_cart_button_box_shadow',
				'selector'  => '{{WRAPPER}} .elementor-button--view-cart',
				'condition' => array(
					'view_cart_button_show!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'view_cart_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--view-cart-button-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'view_cart_button_show!' => '',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'heading_checkout_button_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Checkout', 'mas-addons-for-elementor' ),
				'condition' => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cart_checkout_button_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .elementor-menu-cart__footer-buttons a.elementor-button--checkout',
				'separator' => 'before',
				'condition' => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->start_controls_tabs(
			'cart_checkout_button_text_colors',
			array(
				'condition' => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->start_controls_tab(
			'heading_cart_checkout_button_normal_style',
			array(
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'checkout_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-text-color: {{VALUE}};',
				),
				'condition' => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'checkout_button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'heading_cart_checkout_button_hover_style',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'checkout_button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-hover-text-color: {{VALUE}};',
				),
				'condition' => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->add_control(
			'checkout_button_hover_background',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--checkout-button-hover-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'checkout_button_border_hover_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-menu-cart__footer-buttons .elementor-button--checkout:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'checkout_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'checkout_border',
				'selector'  => '{{WRAPPER}} .elementor-button--checkout',
				'separator' => 'before',
				'condition' => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'view_checkout_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-menu-cart__footer-buttons a.elementor-button--checkout' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'view_checkout_button_box_shadow',
				'selector'  => '{{WRAPPER}} .elementor-button--checkout',
				'condition' => array(
					'checkout_button_show!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'view_checkout_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--checkout-button-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'checkout_button_show!' => '',
				),
				'separator'  => 'after',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_messages',
			array(
				'label' => esc_html__( 'Messages', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cart_empty_message_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__empty-message',
			)
		);

		$this->add_control(
			'empty_message_color',
			array(
				'label'     => esc_html__( 'Empty Cart Message Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--empty-message-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'empty_message_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--empty-message-alignment: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Check if user did not explicitly disabled the use of our mini-cart template and set the option accordingly.
	 * The option value is later used by Module::woocommerce_locate_template().
	 */
	private function maybe_use_mini_cart_template() {
		$option_value = get_option( 'elementor_' . Module::OPTION_NAME_USE_MINI_CART, '' );
		if ( empty( $option_value ) || 'initial' === $option_value ) {
			update_option( 'elementor_' . Module::OPTION_NAME_USE_MINI_CART, 'yes' );
		}
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->maybe_use_mini_cart_template();
		Module::render_menu_cart( $settings );
	}

	/**
	 * Render Plain Content
	 */
	public function render_plain_content() {}

	/**
	 * Get group name.
	 *
	 * @return string
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
