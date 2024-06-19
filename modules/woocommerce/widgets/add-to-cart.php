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

		$this->start_controls_section(
			'section_style_wrapper',
			array(
				'label' => esc_html__( 'Button Wrapper', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_button_cart_wrapper_style_controls();

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
				'label'            => esc_html__( 'Variable Product Icon', 'mas-elementor' ),
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
				'label'            => esc_html__( 'Grouped Product Icon', 'mas-elementor' ),
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
				'label'            => esc_html__( 'External Product Icon', 'mas-elementor' ),
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
				'label'            => esc_html__( 'Out of Stock / Read More Icon', 'mas-elementor' ),
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
	protected function register_button_cart_wrapper_style_controls() {

		$start   = is_rtl() ? 'right' : 'left';
		$end     = is_rtl() ? 'left' : 'right';
		$wrapper = '{{WRAPPER}} .mas-add-to-cart .elementor-button-wrapper';

		$this->add_responsive_control(
			'enable_flex',
			array(
				'label'     => esc_html__( 'Enable Flex', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'block' => array(
						'title' => esc_html__( 'Block', 'mas-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'flex'  => array(
						'title' => esc_html__( 'Flex', 'mas-elementor' ),
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
			'flex_wrap',
			array(
				'label'       => esc_html__( 'Wrap', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'nowrap' => array(
						'title' => esc_html__( 'No Wrap', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-nowrap',
					),
					'wrap'   => array(
						'title' => esc_html__( 'Wrap', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
				),
				'description' => esc_html_x(
					'Items within the container can stay in a single line (No wrap), or break into multiple lines (Wrap).',
					'Flex Container Control',
					'mas-elementor'
				),
				'default'     => 'wrap',
				'selectors'   => array(
					$wrapper => 'flex-wrap: {{VALUE}};',
				),
				// 'responsive' => true,

			)
		);

		$this->add_responsive_control(
			'flex_direction',
			array(
				'label'     => esc_html__( 'Direction', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'row'            => array(
						'title' => esc_html__( 'Row - horizontal', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-' . $end,
					),
					'column'         => array(
						'title' => esc_html__( 'Column - vertical', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-down',
					),
					'row-reverse'    => array(
						'title' => esc_html__( 'Row - reversed', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-' . $start,
					),
					'column-reverse' => array(
						'title' => esc_html__( 'Column - reversed', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Justify Content', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Start', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-start-h',
					),
					'center'        => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-center-h',
					),
					'flex-end'      => array(
						'title' => esc_html__( 'End', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-end-h',
					),
					'space-between' => array(
						'title' => esc_html__( 'Space Between', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-between-h',
					),
					'space-around'  => array(
						'title' => esc_html__( 'Space Around', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-around-h',
					),
					'space-evenly'  => array(
						'title' => esc_html__( 'Space Evenly', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Align Items', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'selectors' => array(
					$wrapper => 'align-items: {{VALUE}};',
				),
				'default'   => 'stretch',
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Hide Cart Button', 'mas-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'    => esc_html__( 'Show', 'mas-elementor' ),
				'description' => esc_html__( 'Hide add to cart button when clicked', 'mas-elementor' ),
				'default'     => 'yes',
			)
		);

		$this->add_responsive_control(
			'hide_button_text',
			array(
				'label'       => esc_html__( 'Button Title Display Settings', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'inline'       => array(
						'title' => esc_html__( 'Inline', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'block'        => array(
						'title' => esc_html__( 'Block', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'inline-block' => array(
						'title' => esc_html__( 'Inline Block', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'none'         => array(
						'title' => esc_html__( 'None', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'default'     => 'inline-block',
				'selectors'   => array(
					'{{WRAPPER}} .elementor-button-text' => 'display: {{VALUE}};',
				),
				'description' => esc_html__( 'For Button text display styles', 'mas-elementor' ),
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
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
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
					'{{WRAPPER}} .added_to_cart:hover, {{WRAPPER}} .added_to_cart:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .added_to_cart:hover svg, {{WRAPPER}} .added_to_cart:focus svg' => 'fill: {{VALUE}};',
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
				'selector'       => '{{WRAPPER}} .elementor-button:hover,{{WRAPPER}} .added_to_cart:hover, {{WRAPPER}} .elementor-button:focus,{{WRAPPER}} .added_to_cart:focus, {{WRAPPER}} .cart button:hover, {{WRAPPER}} .cart button:focus',
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
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .added_to_cart:hover, {{WRAPPER}} .elementor-button:focus, {{WRAPPER}} .added_to_cart:focus' => 'border-color: {{VALUE}};',
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
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .cart button, {{WRAPPER}} .added_to_cart',
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
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
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
	 * Render Ajax Button.
	 *
	 * @param \WC_Product $product product.
	 */
	private function render_ajax_button( $product ) {
		$settings       = $this->get_settings_for_display();
		$product_values = array();

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

		} elseif ( current_user_can( 'manage_options' ) ) {
			$settings['text'] = esc_html__( 'Please set a valid product', 'mas-elementor' );
			$this->set_settings( $settings );
		}

		$this->render_button( $product_values );
	}

		/**
		 * Render button widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @param array                       $product_values product values.
		 * @param \Elementor\Widget_Base|null $instance instance.
		 */
	protected function render_button( $product_values = array(), Widget_Base $instance = null ) {
		if ( empty( $instance ) ) {
			$instance = $this;
		}

		$settings = $instance->get_settings_for_display();

		$instance->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

		$instance->add_render_attribute( 'button', 'class', 'elementor-button' );

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
		?>
		<div <?php $instance->print_render_attribute_string( 'wrapper' ); ?>>
			<a <?php $instance->print_render_attribute_string( 'button' ); ?>>
				<?php $this->render_text( $instance, $product_values ); ?>
			</a>
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

		$icon_align_class = ! empty( $settings['icon_align'] ) ? 'elementor-align-icon-' . $settings['icon_align'] : '';

		$instance->add_render_attribute(
			array(
				'content-wrapper' => array(
					'class' => 'elementor-button-content-wrapper',
				),
				'icon-align'      => array(
					'class' => array(
						'elementor-button-icon',
						$icon_align_class,
					),
				),
				'text'            => array(
					'class' => 'elementor-button-text',
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
