<?php
/**
 * The Product Add To Cart Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use MASElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Product Add To Cart.
 */
class Product_Add_To_Cart extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-product-add-to-cart';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Add To Cart', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-add-to-cart';
	}

	/**
	 * Return the keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' );
	}

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'mas-product-add-to-cart' );
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

		add_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'before_add_to_cart_quantity' ), 95 );
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'before_add_to_cart_quantity' ), 5 );
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'after_add_to_cart_button' ), 5 );
		?>

		<div class="elementor-add-to-cart elementor-product-<?php echo esc_attr( $product->get_type() ); ?>">
			<?php
			if ( $this->is_loop_item() ) {
				$this->render_loop_add_to_cart();
			} else {
				woocommerce_template_single_add_to_cart();
			}
			?>
		</div>

		<?php
		remove_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'before_add_to_cart_quantity' ), 95 );
		remove_action( 'woocommerce_before_add_to_cart_button', array( $this, 'before_add_to_cart_quantity' ), 5 );
		remove_action( 'woocommerce_after_add_to_cart_button', array( $this, 'after_add_to_cart_button' ), 5 );
	}

	/**
	 * Render loop add to cart.
	 */
	private function render_loop_add_to_cart() {
		$quantity_args = $this->get_loop_quantity_args();
		$button_args   = array( 'quantity' => $quantity_args['min_value'] );
		?>
		<div class="e-loop-add-to-cart-form-container">
			<form class="cart e-loop-add-to-cart-form">
				<?php
				$this->before_add_to_cart_quantity();

				$this->render_loop_quantity_input( $quantity_args );
				woocommerce_template_loop_add_to_cart( $button_args );

				$this->after_add_to_cart_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render loop quantity input.
	 *
	 * @param array $quantity_args quantity arguments.
	 */
	private function render_loop_quantity_input( $quantity_args ) {
		global $product;

		if (
			'simple' === $product->get_type()
			&& 'yes' === $this->get_settings_for_display( 'show_quantity' )
		) {
			woocommerce_quantity_input( $quantity_args );
		}
	}

	/**
	 * Get loop quantity args.
	 */
	private function get_loop_quantity_args() {
		global $product;

		$quantity_args = array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => $product->get_min_purchase_quantity(),
			'classes'     => array( 'input-text', 'qty', 'text' ),
		);

		if ( 'no' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
			$quantity_args['min_value']   = $product->get_min_purchase_quantity();
			$quantity_args['input_value'] = $product->get_min_purchase_quantity();
			$quantity_args['classes'][]   = 'disabled';
		}

		return $quantity_args;
	}

	/**
	 * Is Loop Item.
	 */
	private function is_loop_item() {
		return 'loop-item' === Plugin::elementor()->documents->get_current()->get_type();
	}

	/**
	 * Is Loop Item template edit.
	 */
	private function is_loop_item_template_edit() {
		return ( Plugin::elementor()->editor->is_edit_mode() && $this->is_loop_item() );
	}

	/**
	 * Should add container.
	 */
	public function should_add_container() {
		global $product;

		if ( ! in_array( $this->get_settings_for_display( 'layout' ), array( 'auto', 'stacked' ), true ) ) {
			return false;
		}

		switch ( current_action() ) {
			case 'woocommerce_before_add_to_cart_quantity':
				return in_array( $product->get_type(), array( 'simple', 'variable' ), true );
			case 'woocommerce_before_add_to_cart_button':
				return in_array( $product->get_type(), array( 'grouped', 'external' ), true );
			case 'woocommerce_after_add_to_cart_button':
			default:
				return true;
		}
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
		if ( ! $this->should_add_container() ) {
			return;
		}
		?>
		<div class="e-atc-qty-button-holder">
		<?php
	}

	/**
	 * After Add to Cart Button
	 *
	 * @since 3.6.0
	 */
	public function after_add_to_cart_button() {
		if ( ! $this->should_add_container() ) {
			return;
		}
		?>
		</div>
		<?php
	}

	/**
	 * Register button content update controls for this widget.
	 *
	 * @param string $prefix prefix.
	 * @param string $wrapper wrapper.
	 */
	protected function register_flex_style_controls( $prefix = '', $wrapper = '' ) {

		$start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';

		$this->add_responsive_control(
			$prefix . 'enable_flex',
			array(
				'label'     => esc_html__( 'Enable Flex', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'block'     => array(
						'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'flex'      => array(
						'title' => esc_html__( 'Flex', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
					'table-row' => array(
						'title' => esc_html__( 'Table Row', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-nowrap',
					),
				),
				'default'   => 'flex',
				'selectors' => array(
					$wrapper => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . 'flex_wrap',
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
			)
		);

		$this->add_responsive_control(
			$prefix . 'flex_direction',
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
					$prefix . 'enable_flex' => 'flex',
				),
				'default'   => 'column',
			)
		);

		$this->add_responsive_control(
			$prefix . 'justify_content',
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
			)
		);

		$this->add_responsive_control(
			$prefix . 'align_items',
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
			)
		);

		$this->add_responsive_control(
			$prefix . 'gap',
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
	 * Register Controls.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'        => esc_html__( 'Layout', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					''        => esc_html__( 'Inline', 'mas-addons-for-elementor' ),
					'stacked' => esc_html__( 'Stacked', 'mas-addons-for-elementor' ),
					'auto'    => esc_html__( 'Auto', 'mas-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-add-to-cart--layout-',
				'render_type'  => 'template',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_atc_button_style',
			array(
				'label' => esc_html__( 'Button', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'add_to_cart_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'em' => array(
						'min'  => 0,
						'max'  => 3.5,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .single_add_to_cart_button' => 'width: {{SIZE}}{{UNIT}};',
				),
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
			'alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'prefix_class' => 'elementor-add-to-cart%s--align-',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .cart button, {{WRAPPER}} .cart .button',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .cart button, {{WRAPPER}} .cart .button',
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cart button, {{WRAPPER}} .cart .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cart button, {{WRAPPER}} .cart .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		$this->start_controls_tab(
			'button_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart button, {{WRAPPER}} .cart .button' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart button, {{WRAPPER}} .cart .button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart button, {{WRAPPER}} .cart .button' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart button:hover, {{WRAPPER}} .cart .button:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart button:hover, {{WRAPPER}} .cart .button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart button:hover, {{WRAPPER}} .cart .button:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_transition',
			array(
				'label'     => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0.2,
				),
				'range'     => array(
					'px' => array(
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .cart button, {{WRAPPER}} .cart .button' => 'transition: all {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_view_cart_style',
			array(
				'label'     => esc_html__( 'View Cart', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'view_cart_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .added_to_cart' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .added_to_cart',
			)
		);

		$this->add_responsive_control(
			'view_cart_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
					'em' => array(
						'min'  => 0,
						'max'  => 3.5,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--view-cart-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_atc_quantity_style',
			array(
				'label' => esc_html__( 'Quantity', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_quantity',
			array(
				'label'        => esc_html__( 'Quantity', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'e-add-to-cart--show-quantity-',
				'render_type'  => 'template',
			)
		);

		$this->add_responsive_control(
			'spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--button-spacing: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_quantity!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'quantity_typography',
				'selector'  => '{{WRAPPER}} .quantity .qty',
				'condition' => array(
					'show_quantity!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'quantity_border',
				'selector'  => '{{WRAPPER}} .quantity .qty',
				'exclude'   => array( 'color' ),
				'condition' => array(
					'show_quantity!' => '',
				),
			)
		);

		$this->add_control(
			'quantity_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .quantity .qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_quantity!' => '',
				),
			)
		);

		$this->add_control(
			'quantity_container_padding',
			array(
				'label'      => esc_html__( 'Quantity Container Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .qty-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_quantity!' => '',
				),
			)
		);

		$this->add_control(
			'quantity_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .quantity .qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_quantity!' => '',
				),
			)
		);

		$this->add_control(
			'quantity_button_padding',
			array(
				'label'      => esc_html__( 'Quantity Button Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cart .qty-minus, {{WRAPPER}} .cart .qty-plus, {{WRAPPER}} .cart .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_quantity!' => '',
				),
			)
		);

		$this->start_controls_tabs(
			'quantity_style_tabs',
			array(
				'condition' => array(
					'show_quantity!' => '',
				),
			)
		);

		$this->start_controls_tab(
			'quantity_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'quantity_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'quantity_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'quantity_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'quantity_style_focus',
			array(
				'label' => esc_html__( 'Focus', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'quantity_text_color_focus',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'quantity_bg_color_focus',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty:focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'quantity_border_color_focus',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'quantity_transition',
			array(
				'label'     => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0.2,
				),
				'range'     => array(
					'px' => array(
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty' => 'transition: all {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_atc_variations_style',
			array(
				'label' => esc_html__( 'Variations', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'variations_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'default'    => array(
					'unit' => '%',
				),
				'selectors'  => array(
					'.woocommerce {{WRAPPER}} form.cart .variations' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'variations_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'.woocommerce {{WRAPPER}} form.cart .variations' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'variations_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'.woocommerce {{WRAPPER}} form.cart table.variations tr th, .woocommerce {{WRAPPER}} form.cart table.variations tr td' => 'padding-top: calc( {{SIZE}}{{UNIT}}/2 ); padding-bottom: calc( {{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->add_control(
			'heading_variations_label_style',
			array(
				'label'     => esc_html__( 'Label', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variations_label_color_focus',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} form.cart table.variations label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variations_label_typography',
				'selector' => '.woocommerce {{WRAPPER}} form.cart table.variations label',
			)
		);

		$this->add_control(
			'heading_variations_select_style',
			array(
				'label'     => esc_html__( 'Select field', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variations_select_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} form.cart table.variations td.value select' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'variations_select_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce {{WRAPPER}} form.cart table.variations td.value:before' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'variations_select_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce {{WRAPPER}} form.cart table.variations td.value:before' => 'border: 1px solid {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variations_select_typography',
				'selector' => '.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce div.product.elementor{{WRAPPER}} form.cart table.variations td.value:before',
			)
		);

		$this->add_control(
			'variations_select_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce {{WRAPPER}} form.cart table.variations td.value:before' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'variable_product_clear_filter',
			array(
				'label'     => esc_html__( 'Clear filters Alignment', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}} .variations td:has(a.reset_variations)' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_wrapper',
			array(
				'label' => esc_html__( 'Quantity and Button Wrapper', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$wrapper = '{{WRAPPER}} .woocommerce-variation-add-to-cart, {{WRAPPER}} .e-atc-qty-button-holder, {{WRAPPER}} .cart:has(>.quantity) ';

		$this->register_button_cart_wrapper_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_grouped_style',
			array(
				'label' => esc_html__( 'Grouped', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_grouped_product_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_grouped_items_options',
			array(
				'label' => esc_html__( 'Grouped Product Items', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'grouped_product_items_margin',
			array(
				'label'      => esc_html__( 'Product Items Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-grouped-product-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_grouped_item_flex',
			array(
				'label' => esc_html__( 'Grouped Product Item Flex', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_grouped_product_flex_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_grouped_item_typo',
			array(
				'label' => esc_html__( 'Grouped Product Typograhy', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_grouped_products_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_out_of_stock_style',
			array(
				'label' => esc_html__( 'Out Of Stock', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_out_of_stock_product_style_controls();

		$this->end_controls_section();
	}

	/**
	 * Register button content update controls for this widget.
	 */
	protected function register_grouped_products_style_controls() {

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Label Typography', 'mas-addons-for-elementor' ),
				'name'     => 'grouped_product_label',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__label label a',
			)
		);

		$this->add_responsive_control(
			'grouped_product_label_alignment',
			array(
				'label'     => esc_html__( 'Label Alignment', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__label' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Price Typography', 'mas-addons-for-elementor' ),
				'name'     => 'grouped_product_price',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__price',
			)
		);

		$this->add_responsive_control(
			'grouped_product_price_alignment',
			array(
				'label'     => esc_html__( 'Price Alignment', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__price' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs(
			'grouped_product_tabs',
			array()
		);

			$this->start_controls_tab(
				'grouped_product_tab_normal',
				array(
					'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'grouped_product_label_color',
				array(
					'label'     => esc_html__( 'Label Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .woocommerce-grouped-product-list-item__label label a' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'grouped_product_price_color',
				array(
					'label'     => esc_html__( 'Price Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .woocommerce-grouped-product-list-item__price' => 'color: {{VALUE}}',
					),
				)
			);
			$this->add_control(
				'grouped_product_price_del_color',
				array(
					'label'     => esc_html__( 'Onsale Price Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .woocommerce-grouped-product-list-item__price del' => 'color: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'grouped_product_tab_hover',
				array(
					'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'grouped_product_label_hover_color',
				array(
					'label'     => esc_html__( 'Label Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .woocommerce-grouped-product-list-item__label label a:hover' => 'color: {{VALUE}}',
					),
				)
			);
			$this->end_controls_tab();

		$this->end_controls_tabs();
	}
	/**
	 * Register button content update controls for this widget.
	 */
	protected function register_button_cart_wrapper_style_controls() {
		$wrapper = '{{WRAPPER}} .woocommerce-variation-add-to-cart, {{WRAPPER}} .e-atc-qty-button-holder, {{WRAPPER}} .cart:has(>.quantity) ';

		$this->register_flex_style_controls( '', $wrapper );
	}

	/**
	 * Grouped product style.
	 */
	public function register_grouped_product_style_controls() {

		$this->add_control(
			'heading_grouped_product_quantity',
			array(
				'label' => esc_html__( 'Product Quantity', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'grouped_product_quantity_padding',
			array(
				'label'      => esc_html__( 'Product Quantity Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'grouped_product_quantity_border',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__quantity',
			)
		);

		$this->add_control(
			'grouped_product_quantity_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_grouped_product_label',
			array(
				'label'     => esc_html__( 'Product Label', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'grouped_product_label_padding',
			array(
				'label'      => esc_html__( 'Product Label Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'grouped_product_label_border',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__label',
			)
		);

		$this->add_control(
			'grouped_product_label_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_grouped_product_price',
			array(
				'label'     => esc_html__( 'Product Price', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'grouped_product_price_padding',
			array(
				'label'      => esc_html__( 'Product Price Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'grouped_product_price_border',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__price',
			)
		);

		$this->add_control(
			'grouped_product_price_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	/**
	 * Out of stock product style.
	 */
	public function register_out_of_stock_product_style_controls() {

		$this->add_responsive_control(
			'display_out_of_stock',
			array(
				'label'     => esc_html__( 'Display', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'block'  => array(
						'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'inline' => array(
						'title' => esc_html__( 'Inline', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
				),
				'default'   => 'inline',
				'selectors' => array(
					'{{WRAPPER}} .out-of-stock' => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'out_of_stock_product_text_align',
			array(
				'label'     => esc_html__( 'Text Alignment', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}} .out-of-stock,{{WRAPPER}} .elementor-add-to-cart' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'out_of_stock_product_quantity_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .out-of-stock' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'out_of_stock_product_border',
				'selector' => '{{WRAPPER}} .out-of-stock',
			)
		);

		$this->add_control(
			'out_of_stock_product_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .out-of-stock' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'out_of_stock_typography',
				'selector' => '{{WRAPPER}} .out-of-stock',
			)
		);

		$this->start_controls_tabs(
			'out_of_stock_product_tabs',
			array()
		);

			$this->start_controls_tab(
				'out_of_stock_product_tab_normal',
				array(
					'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'out_of_stock_product_color',
				array(
					'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .out-of-stock' => 'color: {{VALUE}}',
					),
				)
			);
			$this->add_control(
				'out_of_stock_product_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .out-of-stock' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'out_of_stock_product_tab_hover',
				array(
					'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'out_of_stock_hover_product_color',
				array(
					'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .out-of-stock:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'out_of_stock_product_hover_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .out-of-stock:hover' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	/**
	 * Grouped product style.
	 */
	public function register_grouped_product_flex_style_controls() {
		$wrapper = '{{WRAPPER}} .woocommerce-grouped-product-list .woocommerce-grouped-product-list-item';

		$this->register_flex_style_controls( 'grouped_item_', $wrapper );
	}

	/**
	 * Is Loop Item template edit.
	 */
	public function render_plain_content() {}

	/**
	 * Get group name.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
