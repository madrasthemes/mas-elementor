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
use Elementor\Widget_Base;
use Elementor\Icons_Manager;


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
		return esc_html__( 'Custom Add To Cart', 'mas-addons-for-elementor' );
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
	 * Return the script dependencies of the module.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mas-add-to-cart' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_product',
			array(
				'label' => esc_html__( 'Product', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'   => esc_html__( 'Product ID', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'show_quantity',
			array(
				'label'       => esc_html__( 'Show Quantity', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'    => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Please note that switching on this option will disable some of the design controls.', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'quantity',
			array(
				'label'     => esc_html__( 'Quantity', 'mas-addons-for-elementor' ),
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
				'label' => esc_html__( 'Button', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_button_cart_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_wrapper',
			array(
				'label' => esc_html__( 'Button Wrapper', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_button_cart_wrapper_style_controls( '', '{{WRAPPER}} .mas-add-to-cart .elementor-button-wrapper', array() );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content_wrapper',
			array(
				'label' => esc_html__( 'Button Content Wrapper', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'enable_button_content_flex',
			array(
				'label'   => esc_html__( 'Content Wrapper Flex Options', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->register_button_cart_wrapper_style_controls(
			'content_',
			'{{WRAPPER}} .mas-add-to-cart .elementor-button-content-wrapper',
			array(
				'enable_button_content_flex' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_button_icon_style_controls();

		$this->end_controls_section();

		$this->start_injection(
			array(
				'of' => 'section_style_cart',
				'at' => 'before',
			)
		);

		$this->register_quantity_controls();

		$this->end_injection();

		$this->start_injection(
			array(
				'of' => 'selected_icon',
				'at' => 'after',
			)
		);

		$this->add_control(
			'variable_icon',
			array(
				'label'            => esc_html__( 'Variable Product Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'default'          => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'grouped_icon',
			array(
				'label'            => esc_html__( 'Grouped Product Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'default'          => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'external_icon',
			array(
				'label'            => esc_html__( 'External Product Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'default'          => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'others_icon',
			array(
				'label'            => esc_html__( 'Out of Stock / Read More Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'default'          => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
			)
		);

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
				'default'     => esc_html__( 'Add to Cart', 'mas-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Add to Cart', 'mas-addons-for-elementor' ),
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

		$this->start_controls_section(
			'section_style_cart_count',
			array(
				'label'     => esc_html__( 'Cart Count Text', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_hover_transition' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cart_count_typography',
				'selector' => '{{WRAPPER}} .cart-count',
			)
		);

		$this->add_control(
			'cart_count_color',
			array(
				'label'     => __( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart-count' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'enable_stretched_link',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Stretched Link', 'mas-addons-for-elementor' ),
				'default' => 'no',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register controls for this widget.
	 */
	public function register_quantity_controls() {
		$this->start_controls_section(
			'section_quantity_style',
			array(
				'label'     => __( 'Quantity', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_quantity' => 'yes',
				),
			)
		);

		$this->add_control(
			'quantity_max_width',
			array(
				'label'      => esc_html__( 'Max Width', 'mas-addons-for-elementor' ),
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
				'label'     => esc_html__( 'Bottom Spacing', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}} .mas-add-to-cart .quantity .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'quantity_align',
			array(
				'label'     => __( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
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
				'label' => __( 'Normal', 'mas-addons-for-elementor' ),
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
				'label'     => __( 'Background Color', 'mas-addons-for-elementor' ),
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
				'label' => __( 'Hover', 'mas-addons-for-elementor' ),
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
				'label'     => __( 'Background Color', 'mas-addons-for-elementor' ),
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
	 * Register button icon style controls for this widget.
	 */
	protected function register_button_icon_style_controls() {
		$wrapper        = '{{WRAPPER}} .mas-add-to-cart .elementor-button-icon';
		$hover_wrapper  = '{{WRAPPER}} .mas-add-to-cart a:hover .elementor-button-icon, .mas-card-hover .mas-product:hover {{WRAPPER}} .mas-add-to-cart a .elementor-button-icon';
		$active_wrapper = '{{WRAPPER}} .mas-add-to-cart .added_to_cart .elementor-button-icon';

		$this->add_control(
			'show_view_cart_icon',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Show View Cart Icon', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'return_value' => 'show',
				'prefix_class' => 'mas-view-cart--icon-',
				'description'  => esc_html__( 'Displays cart icon after clicking near view cart text', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'hide_view_cart_text',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide View Cart Text', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'return_value' => 'hide',
				'prefix_class' => 'mas-view-cart--text-',
				'description'  => esc_html__( 'Hide view cart Text', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'enable_size_color_options',
			array(
				'label'   => esc_html__( 'Size, Color Options', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_responsive_control(
			'cart_icon_svg_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-add-to-cart .elementor-button-icon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'enable_size_color_options' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'hide_cart_icon',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Cart Icon', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'    => array(
					'enable_size_color_options' => 'yes',
				),
				'return_value' => 'hide',
				'prefix_class' => 'mas-cart--icon%s-',
			)
		);

		$this->add_control(
			'icon_width',
			array(
				'label'      => esc_html__( 'Icon Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => '52',
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
					$wrapper => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'enable_size_color_options' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_height',
			array(
				'label'      => esc_html__( 'Icon Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
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
					$wrapper => 'height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'enable_size_color_options' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_font_size',
			array(
				'label'      => esc_html__( 'Font Size', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => '18',
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
					$wrapper => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'enable_size_color_options' => 'yes',
				),
			)
		);

		$this->start_controls_tabs(
			'cart_tabs_icon_style',
			array(
				'condition' => array(
					'enable_size_color_options' => 'yes',
				),
			)
		);

			$this->start_controls_tab(
				'cart_tab_icon_normal',
				array(
					'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
					'condition' => array(
						'enable_size_color_options' => 'yes',
					),
				)
			);

				$this->add_control(
					'icon_background_color',
					array(
						'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							$wrapper => 'background-color: {{VALUE}};',
						),
						'condition' => array(
							'enable_size_color_options' => 'yes',
						),
					)
				);

				$this->add_control(
					'icon_normal_color',
					array(
						'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							$wrapper . ' svg *' => 'color: {{VALUE}};fill: {{VALUE}};',
							$wrapper . ' svg' => 'color: {{VALUE}};fill: {{VALUE}};',
						),
						'condition' => array(
							'enable_size_color_options' => 'yes',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'      => 'cart_icon_border',
						'selector'  => $wrapper,
						'condition' => array(
							'enable_size_color_options' => 'yes',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'cart_tab_icon_hover',
				array(
					'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
					'condition' => array(
						'enable_size_color_options' => 'yes',
					),
				)
			);

			$this->add_control(
				'icon_hover_background_color',
				array(
					'label'     => esc_html__( 'Hover Background Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						$hover_wrapper => 'background-color: {{VALUE}};',
					),
					'condition' => array(
						'enable_size_color_options' => 'yes',
					),
				)
			);

			$this->add_control(
				'icon_hover_color',
				array(
					'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						$hover_wrapper . ' svg *' => 'color: {{VALUE}};fill: {{VALUE}};',
						$hover_wrapper . ' svg' => 'color: {{VALUE}};fill: {{VALUE}};',
						'{{WRAPPER}} .mas-add-to-cart a:hover .elementor-button-icon  svg *' => 'color: {{VALUE}};fill: {{VALUE}};',
						'{{WRAPPER}} .mas-add-to-cart a:hover .elementor-button-icon  svg' => 'color: {{VALUE}};fill: {{VALUE}};',
					),
					'condition' => array(
						'enable_size_color_options' => 'yes',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'      => 'cart_icon_border_hover',
					'selector'  => $hover_wrapper,
					'condition' => array(
						'enable_size_color_options' => 'yes',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'cart_tab_icon_active',
				array(
					'label'     => esc_html__( 'Active', 'mas-addons-for-elementor' ),
					'condition' => array(
						'enable_size_color_options' => 'yes',
					),
				)
			);

				$this->add_control(
					'icon_background_color_active',
					array(
						'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							$active_wrapper => 'background-color: {{VALUE}};',
						),
						'condition' => array(
							'enable_size_color_options' => 'yes',
						),
					)
				);

				$this->add_control(
					'icon_active_color',
					array(
						'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							$active_wrapper . ' svg *' => 'color: {{VALUE}};fill: {{VALUE}};',
							$active_wrapper . ' svg' => 'color: {{VALUE}};fill: {{VALUE}};',
						),
						'condition' => array(
							'enable_size_color_options' => 'yes',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'      => 'cart_icon_border_active',
						'selector'  => $active_wrapper,
						'condition' => array(
							'enable_size_color_options' => 'yes',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'cart_icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '8',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '8',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					$wrapper => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'condition'  => array(
					'enable_size_color_options' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_flex_options',
			array(
				'label'     => esc_html__( 'Enable Flex Options', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'icon_enable_flex',
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
				'condition' => array(
					'enable_flex_options' => 'yes',
				),

			)
		);

		$this->add_responsive_control(
			'icon_justify_content',
			array(
				'label'       => esc_html__( 'Justify Content', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
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
				'condition'   => array(
					'enable_flex_options' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'icon_align_items',
			array(
				'label'     => esc_html__( 'Align Items', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
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
				'selectors' => array(
					$wrapper => 'align-items: {{VALUE}};',
				),
				'default'   => 'center',
				'condition' => array(
					'enable_flex_options' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_icon_position',
			array(
				'label'     => esc_html__( 'Enable Icon Position', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'     => esc_html__( 'Position', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'relative',
				'options'   => array(
					'relative' => esc_html__( 'Relative', 'mas-addons-for-elementor' ),
					'absolute' => esc_html__( 'Absolute', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'enable_icon_position' => 'yes',
				),
				'selectors' => array(
					$wrapper => 'position: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_icon_wrapper_position',
			array(
				'label'     => esc_html__( 'Wrapper Position', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HIDDEN,
				'selectors' => array(
					'{{WRAPPER}} .mas-add-to-cart' => 'position: relative;',
				),
				'condition' => array(
					'enable_icon_position' => 'yes',
					'button_icon_position' => 'absolute',
				),
			)
		);

		$left  = esc_html__( 'Left', 'mas-addons-for-elementor' );
		$right = esc_html__( 'Right', 'mas-addons-for-elementor' );

		$start = is_rtl() ? $right : $left;
		$end   = ! is_rtl() ? $right : $left;

		$this->add_control(
			'icon_offset_orientation_h',
			array(
				'label'       => esc_html__( 'Horizontal Orientation', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => array(
					'start' => array(
						'title' => $start,
						'icon'  => 'eicon-h-align-left',
					),
					'end'   => array(
						'title' => $end,
						'icon'  => 'eicon-h-align-right',
					),
				),
				'classes'     => 'elementor-control-start-end',
				'render_type' => 'ui',
				'condition'   => array(
					'enable_icon_position' => 'yes',
					'button_icon_position' => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'icon_offset_x',
			array(
				'label'      => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => '0',
				),
				'size_units' => array( 'px', '%', 'vw', 'vh', 'custom' ),
				'selectors'  => array(
					'body:not(.rtl) ' . $wrapper => 'left: {{SIZE}}{{UNIT}} !important',
					'body.rtl ' . $wrapper       => 'right: {{SIZE}}{{UNIT}} !important',
				),
				'condition'  => array(
					'icon_offset_orientation_h!' => 'end',
					'enable_icon_position'       => 'yes',
					'button_icon_position'       => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'icon_offset_x_end',
			array(
				'label'      => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 0.1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => '0',
				),
				'size_units' => array( 'px', '%', 'vw', 'vh', 'custom' ),
				'selectors'  => array(
					'body:not(.rtl) ' . $wrapper => 'right: {{SIZE}}{{UNIT}} !important',
					'body.rtl ' . $wrapper       => 'left: {{SIZE}}{{UNIT}} !important',
				),
				'condition'  => array(
					'icon_offset_orientation_h' => 'end',
					'enable_icon_position'      => 'yes',
					'button_icon_position'      => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'icon_offset_y',
			array(
				'label'      => esc_html__( 'Top', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => -1000,
						'max' => 1000,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ),
				'default'    => array(
					'size' => 0,
				),
				'selectors'  => array(
					$wrapper => 'top: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'enable_icon_position' => 'yes',
					'button_icon_position' => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'icon_offset_y_end',
			array(
				'label'      => esc_html__( 'Bottom', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => -1000,
						'max' => 1000,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ),
				'default'    => array(
					'size' => 0,
				),
				'selectors'  => array(
					$wrapper => 'bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'enable_icon_position' => 'yes',
					'button_icon_position' => 'absolute',
				),
			)
		);
	}

	/**
	 * Register button content update controls for this widget.
	 *
	 * @param string $name Name of the control.
	 * @param string $wrapper Selectors.
	 * @param array  $condition Condition.
	 */
	protected function register_button_cart_wrapper_style_controls( $name, $wrapper, $condition ) {

		$start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';

		$this->add_responsive_control(
			$name . 'enable_flex',
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
				'condition' => $condition,
			)
		);

		$this->add_responsive_control(
			$name . 'flex_wrap',
			array(
				'label'       => esc_html__( 'Wrap', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'nowrap' => array(
						'title' => esc_html__( 'No Wrap', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-nowrap',
					),
					'wrap'   => array(
						'title' => esc_html__( 'Wrap', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
				),
				'description' => esc_html_x(
					'Items within the container can stay in a single line (No wrap), or break into multiple lines (Wrap).',
					'Flex Container Control',
					'mas-addons-for-elementor'
				),
				'default'     => 'wrap',
				'selectors'   => array(
					$wrapper => 'flex-wrap: {{VALUE}};',
				),
				'condition'   => $condition,
			)
		);

		$this->add_responsive_control(
			$name . 'flex_direction',
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
				'condition' => array_merge(
					array(
						'enable_flex' => 'flex',
					),
					$condition
				),
				'default'   => 'column',
			)
		);

		$this->add_responsive_control(
			$name . 'justify_content',
			array(
				'label'       => esc_html__( 'Justify Content', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
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
				'condition'   => $condition,
			)
		);

		$this->add_responsive_control(
			$name . 'align_items',
			array(
				'label'     => esc_html__( 'Align Items', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
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
				'selectors' => array(
					$wrapper => 'align-items: {{VALUE}};',
				),
				'default'   => 'stretch',
				'condition' => $condition,
			)
		);

		$this->add_responsive_control(
			$name . 'gap',
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
				'condition'  => $condition,
			)
		);

	}

	/**
	 * Register button content update controls for this widget.
	 */
	protected function register_button_cart_update_controls() {

		$this->start_injection(
			array(
				'at' => 'after',
				'of' => 'section_button',
			)
		);

		$this->add_control(
			'hide_cart_button',
			array(
				'label'       => esc_html__( 'Hide Cart Button', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'    => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Hide add to cart button when clicked', 'mas-addons-for-elementor' ),
				'default'     => 'yes',
			)
		);

		$this->add_control(
			'enable_hover_transition',
			array(
				'label'       => esc_html__( 'Enable Transition Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Show Cart Count Transition Text', 'mas-addons-for-elementor' ),
				'default'     => 'no',
			)
		);

		$this->add_control(
			'enable_cart_count',
			array(
				'label'        => esc_html__( 'Enable Cart Count', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Show Cart Count in button', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'prefix_class' => 'mas-count-in-btn-',
			)
		);

		$this->add_control(
			'cart_count_prefix',
			array(
				'label'       => esc_html__( 'Prefix', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Show Cart Count Prefix', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'enable_hover_transition',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'enable_cart_count',
							'operator' => '===',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'cart_count_suffix',
			array(
				'label'       => esc_html__( 'Suffix', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ' in cart',
				'description' => esc_html__( 'Show Cart Count Suffix', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'enable_hover_transition',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'enable_cart_count',
							'operator' => '===',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'hide_button_text',
			array(
				'label'       => esc_html__( 'Button Title Display Settings', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'inline'       => array(
						'title' => esc_html__( 'Inline', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'block'        => array(
						'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'inline-block' => array(
						'title' => esc_html__( 'Inline Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'none'         => array(
						'title' => esc_html__( 'None', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'default'     => 'inline-block',
				'selectors'   => array(
					'{{WRAPPER}} .elementor-button-text' => 'display: {{VALUE}};',
				),
				'description' => esc_html__( 'For Button text display styles', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'view_cart_text_align',
			array(
				'label'       => esc_html__( 'View Cart Text Align', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .added_to_cart' => 'text-align: {{VALUE}};',
				),
				'description' => esc_html__( 'For view cart button text alignment', 'mas-addons-for-elementor' ),
			)
		);

		$this->end_injection();

		$this->update_control(
			'button_type',
			array(
				'condition' => array(
					'show_quantity!' => 'yes',
				),
			),
		);
		$this->remove_control( 'button_type' );
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
					'{{WRAPPER}} .elementor-button .elementor-align-icon-row-reverse' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-row' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .cart .elementor-button-content-wrapper .elementor-button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .added_to_cart .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .added_to_cart .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
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
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button, {{WRAPPER}} .added_to_cart',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'cart_text_shadow',
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button, {{WRAPPER}} .added_to_cart',
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
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .added_to_cart'    => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .cart button'      => 'fill: {{VALUE}}; color: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'cart_background',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button, {{WRAPPER}} .added_to_cart',
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

		$this->add_control(
			'icon_normal_brightness',
			array(
				'label'     => esc_html__( 'Icon Brightness', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button svg, {{WRAPPER}} .cart button svg, .mas-card-hover .mas-product {{WRAPPER}} .elementor-button svg, .mas-card-hover .mas-product {{WRAPPER}} .cart button svg' => 'filter: brightness({{SIZE}});',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_tab_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover, .mas-card-hover .mas-product:hover {{WRAPPER}} .elementor-button, {{WRAPPER}} .elementor-button:focus, .mas-card-hover .mas-product:focus {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',

					'{{WRAPPER}} .elementor-button:hover svg,  .mas-card-hover .mas-product:hover {{WRAPPER}} .elementor-button svg, {{WRAPPER}} .elementor-button:focus svg, .mas-card-hover .mas-product:focus {{WRAPPER}} .elementor-button svg' => 'fill: {{VALUE}};',

					'{{WRAPPER}} .cart button:hover, .mas-card-hover .mas-product:hover {{WRAPPER}} .cart button , {{WRAPPER}} .cart button:focus, .mas-card-hover .mas-product:focus {{WRAPPER}} .cart button' => 'color: {{VALUE}};',

					'{{WRAPPER}} .cart button:hover svg, .mas-card-hover .mas-product:hover {{WRAPPER}} .cart button svg , {{WRAPPER}} .cart button:focus svg, .mas-card-hover .mas-product:focus {{WRAPPER}} .cart button svg' => 'fill: {{VALUE}};',

					'{{WRAPPER}} .added_to_cart:hover, .mas-card-hover .mas-product:hover {{WRAPPER}} .added_to_cart ,  {{WRAPPER}} .added_to_cart:focus, .mas-card-hover .mas-product:focus {{WRAPPER}} .added_to_cart' => 'color: {{VALUE}};',

					'{{WRAPPER}} .added_to_cart:hover svg, .mas-card-hover .mas-product:hover {{WRAPPER}} .added_to_cart svg, {{WRAPPER}} .added_to_cart:focus svg, .mas-card-hover .mas-product:focus {{WRAPPER}} .added_to_cart: svg' => 'fill: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'cart_button_background_hover',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .elementor-button:hover,{{WRAPPER}} .added_to_cart:hover, {{WRAPPER}} .elementor-button:focus,{{WRAPPER}} .added_to_cart:focus, {{WRAPPER}} .cart button:hover, {{WRAPPER}} .cart button:focus, .mas-card-hover .mas-product:hover {{WRAPPER}} .elementor-button, .mas-card-hover .mas-product:hover {{WRAPPER}} .added_to_cart, .mas-card-hover .mas-product:focus {{WRAPPER}} .elementor-button,.mas-card-hover .mas-product:focus {{WRAPPER}} .added_to_cart, .mas-card-hover .mas-product:hover {{WRAPPER}} .cart button, .mas-card-hover .mas-product:focus {{WRAPPER}} .cart button',
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
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .added_to_cart:hover, {{WRAPPER}} .elementor-button:focus, {{WRAPPER}} .added_to_cart:focus, .mas-card-hover .mas-product:hover {{WRAPPER}} .elementor-button, .mas-card-hover .mas-product:hover {{WRAPPER}} .added_to_cart, .mas-card-hover .mas-product:focus {{WRAPPER}} .elementor-button, .mas-card-hover .mas-product:focus {{WRAPPER}} .added_to_cart' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .cart button:hover, {{WRAPPER}} .cart button:focus, .mas-card-hover .mas-product:hover {{WRAPPER}} .cart button, .mas-card-hover .mas-product:focus {{WRAPPER}} .cart button' => 'border-color: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'icon_brightness',
			array(
				'label'     => esc_html__( 'Icon Brightness', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .cart button:hover svg, .mas-card-hover .mas-product:hover {{WRAPPER}} .elementor-button svg, .mas-card-hover .mas-product:hover {{WRAPPER}} .cart button svg' => 'filter: brightness({{SIZE}});',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'icon_transition_duration',
			array(
				'label'      => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 's', 'ms', 'custom' ),
				'default'    => array(
					'unit' => 's',
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button:hover, .mas-card-hover .mas-product:hover {{WRAPPER}} .elementor-button' => 'transition-duration: {{SIZE}}{{UNIT}};',
				),
				'condition'  => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_hover_animation',
			array(
				'label'     => esc_html__( 'Hover Animation', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => $args['section_condition'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_tab_button_active',
			array(
				'label'     => esc_html__( 'Active', 'mas-addons-for-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_active_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-add-to-cart .added_to_cart' => 'color: {{VALUE}};',

					'{{WRAPPER}} .mas-add-to-cart .added_to_cart svg' => 'fill: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'cart_button_background_active',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .mas-add-to-cart .added_to_cart',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
				'condition'      => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_button_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-add-to-cart .added_to_cart' => 'border-color: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'active_icon_brightness',
			array(
				'label'     => esc_html__( 'Icon Brightness', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .mas-add-to-cart .added_to_cart svg' => 'filter: brightness({{SIZE}}) !important;',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'cart_border',
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button, {{WRAPPER}} .added_to_cart',
				'separator' => 'before',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'cart_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .cart button'      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .added_to_cart'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'cart_button_box_shadow',
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button, {{WRAPPER}} .added_to_cart',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_responsive_control(
			'cart_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .cart button'      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .added_to_cart'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 * Get the number of items in the cart for a given product id.
	 *
	 * @param number $product_id The product id.
	 * @return number The number of items in the cart.
	 */
	private function get_cart_item_quantities_by_product_id( $product_id ) {
		if ( ! isset( WC()->cart ) ) {
			return 0;
		}

		$cart = WC()->cart->get_cart_item_quantities();
		return isset( $cart[ $product_id ] ) ? $cart[ $product_id ] : 0;
	}

	/**
	 * Render Ajax Button.
	 *
	 * @param \WC_Product $product product.
	 */
	private function render_ajax_button( $product ) {
		$settings       = $this->get_settings_for_display();
		$product_values = array();

		$cart_count  = '';
		$cart_number = '';

		if ( $product ) {
			if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				$product_type = $product->get_type();
			} else {
				$product_type = $product->product_type;
			}
			$product_values['product_type']         = $product_type;
			$product_values['in_stock_purchasable'] = $product->is_purchasable() && $product->is_in_stock();
			$class                                  = implode(
				' ',
				array_filter(
					array(
						'product_type_' . $product_type,
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
					)
				)
			);
			if ( 'yes' === $settings['hide_cart_button'] ) {
				$class .= ' hide-mas-cart';
			}

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

			$cart_count  = $this->get_cart_item_quantities_by_product_id( $product->get_id() );
			$cart_number = $cart_count;

			if ( empty( $cart_count ) ) {
				$cart_count = $product->add_to_cart_text();
			} else {
				$cart_count = $settings['cart_count_prefix'] . $cart_count . $settings['cart_count_suffix'];
			}
		} elseif ( current_user_can( 'manage_options' ) ) {
			$settings['text'] = esc_html__( 'Please set a valid product', 'mas-addons-for-elementor' );
			$this->set_settings( $settings );
		}

		$this->render_button( $product_values, $this, $cart_count, $cart_number );
	}

		/**
		 * Render button widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @param array                       $product_values product values.
		 * @param \Elementor\Widget_Base|null $instance instance.
		 * @param string                      $cart_count cart count.
		 * @param string                      $cart_number cart number.
		 */
	protected function render_button( $product_values = array(), Widget_Base $instance = null, $cart_count = '', $cart_number = '' ) {
		if ( empty( $instance ) ) {
			$instance = $this;
		}

		$settings = $instance->get_settings_for_display();

		$instance->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

		$instance->add_render_attribute( 'button', 'class', 'elementor-button' );

		if ( 'yes' === $settings['enable_stretched_link'] ) {
			$instance->add_render_attribute( 'button', 'class', 'stretched-link' );
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$instance->add_link_attributes( 'button', $settings['link'] );
			$instance->add_render_attribute( 'button', 'class', 'elementor-button-link' );
		} else {
			$instance->add_render_attribute( 'button', 'role', 'button' );
		}

		if ( ! empty( $settings['button_css_id'] ) ) {
			$instance->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$instance->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
		}

		if ( ! empty( $settings['hover_animation'] ) ) {
			$instance->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		$instance->add_render_attribute( 'cart_count', 'class', 'cart-count' );
		$instance->add_render_attribute( 'cart_number', 'class', 'cart-count-btn' );

		if ( 'yes' === $settings['enable_cart_count'] ) {
			if ( ! empty( trim( $settings['cart_count_prefix'] ) ) ) {
				$instance->add_render_attribute( 'cart_number', 'data-prefix', trim( $settings['cart_count_prefix'] ) );
			}
			if ( ! empty( trim( $settings['cart_count_suffix'] ) ) ) {
				$instance->add_render_attribute( 'cart_number', 'data-suffix', trim( $settings['cart_count_suffix'] ) );
			}
		}

		if ( 'yes' === $settings['enable_stretched_link'] ) {
			$instance->add_render_attribute( 'cart_count', 'class', 'mas-position-static' );
		}
		?>
		<div <?php $instance->print_render_attribute_string( 'wrapper' ); ?>>
			<a <?php $instance->print_render_attribute_string( 'button' ); ?>>
				<?php $this->render_text( $instance, $product_values ); ?>
			</a>
			<?php if ( 'yes' === $settings['enable_hover_transition'] ) : ?>
			<span <?php $instance->print_render_attribute_string( 'cart_count' ); ?>><?php echo esc_html( $cart_count ); ?></span>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['enable_cart_count'] ) : ?>
			<span <?php $instance->print_render_attribute_string( 'cart_number' ); ?>><?php echo esc_html( $cart_number ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

		/**
		 * Render button text.
		 *
		 * Render button widget text.
		 *
		 * @param \Elementor\Widget_Base|null $instance instance.
		 * @param array                       $product_values product values.
		 */
	protected function render_text( Widget_Base $instance = null, $product_values = array() ) {
		// The default instance should be `$this` (a Button widget), unless the Trait is being used from outside of a widget (e.g. `Skin_Base`) which should pass an `$instance`.
		if ( empty( $instance ) ) {
			$instance = $this;
		}
		$product_type         = isset( $product_values['product_type'] ) ? $product_values['product_type'] : 'simple';
		$in_stock_purchasable = isset( $product_values['in_stock_purchasable'] ) ? $product_values['in_stock_purchasable'] : false;
		switch ( $product_type ) {
			case 'grouped':
				$icon_type = 'grouped_icon';
				break;
			case 'variable':
				$icon_type = 'variable_icon';
				break;
			case 'external':
				$icon_type = 'external_icon';
				break;
			default:
				$icon_type = 'selected_icon';
		}

		$icon_type = ! $in_stock_purchasable && ( 'simple' === $product_type ) ? 'others_icon' : $icon_type;
		$settings  = $instance->get_settings_for_display();

		$migrated = isset( $settings['__fa4_migrated'][ $icon_type ] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			// old default
			$settings['icon_align'] = $instance->get_settings( 'icon_align' );
		}

		$icon_align = 'row';

		if ( isset( $settings['icon_align'] ) ) {
			$icon_align = 'row' === $settings['icon_align'] ? 'row' : 'row-reverse';
		}

		$instance->add_render_attribute(
			array(
				'content-wrapper' => array(
					'class' => 'elementor-button-content-wrapper',
				),
				'icon-align'      => array(
					'class' => array(
						'elementor-button-icon',
						'elementor-align-icon-' . $icon_align,
					),
				),
				'text'            => array(
					'class' => 'elementor-button-text add-cart-btn',
				),
			)
		);

		// TODO: replace the protected with public
		// $instance->add_inline_editing_attributes( 'text', 'none' );.
		?>
		<span <?php $instance->print_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings[ $icon_type ]['value'] ) ) : ?>
			<span <?php $instance->print_render_attribute_string( 'icon-align' ); ?>>
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings[ $icon_type ], array( 'aria-hidden' => 'true' ) );
				else :
					?>
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php $instance->print_render_attribute_string( 'text' ); ?>><?php $this->print_unescaped_setting( 'text' ); ?></span>
		</span>
		<?php
	}

	/**
	 * Render Form Button.
	 *
	 * @param \WC_Product $product product.
	 */
	private function render_form_button( $product ) {
		if ( ! $product && current_user_can( 'manage_options' ) ) {
			echo esc_html__( 'Please set a valid product', 'mas-addons-for-elementor' );

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
