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
class Mini_Cart extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-mini-cart';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Mini Cart', 'mas-elementor' );
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
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_minicart',
			array(
				'label' => esc_html__( 'Mini Cart', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'open_drawer',
			array(
				'label'   => esc_html__( 'Open drawer when adding products', 'mas-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'   => esc_html__( 'Show Price', 'mas-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default', 'mas-elementor' ),
					'bag'     => esc_html__( 'Bag', 'mas-elementor' ),
					'bag-alt' => esc_html__( 'Bag Alt', 'mas-elementor' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_minicart',
			array(
				'label' => esc_html__( 'Mini Cart', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'icon_typography',
				'selector' => '{{WRAPPER}} svg',
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} svg' => 'color: {{VALUE}} !important',
				),
				'separator' => 'after',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_color_typography',
				'selector' => '{{WRAPPER}} .wc-block-mini-cart__badge',
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => __( 'Product Count Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .wc-block-mini-cart__badge' => 'color: {{VALUE}} !important',
				),
			)
		);

		$this->add_control(
			'count_bg_color',
			array(
				'label'     => __( 'Product Count BGColor', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .wc-block-mini-cart__badge' => 'background-color: {{VALUE}} !important',
				),
				'separator' => 'after',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_count_typography',
				'selector' => '{{WRAPPER}} .wc-block-mini-cart__badge',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Product Count BGColor', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .wc-block-mini-cart__amount' => 'color: {{VALUE}} !important',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render
	 */
	protected function render() {
		echo wp_kses_post( $this->mini_cart() );
	}

	/**
	 * Mini Cart.
	 */
	protected function mini_cart() {
		$settings      = $this->get_settings_for_display();
		$block_content = '<!-- wp:woocommerce/mini-cart /-->';
		$parsed_blocks = parse_blocks( $block_content );
		if ( isset( $parsed_blocks[0] ) ) {
			if ( 'yes' === $settings['open_drawer'] ) {
				$parsed_blocks[0]['attrs']['addToCartBehaviour'] = 'open_drawer';
			}

			if ( 'yes' === $settings['show_price'] ) {
				$parsed_blocks[0]['attrs']['hasHiddenPrice'] = false;
			}

			if ( 'yes' === $settings['show_price'] ) {
				$parsed_blocks[0]['attrs']['hasHiddenPrice'] = false;
			}

			if ( ! empty( $settings['icon'] ) ) {
				$parsed_blocks[0]['attrs']['miniCartIcon'] = $settings['icon'];
			}
		}
		$rendered_block = render_block( $parsed_blocks[0] );

		return $rendered_block;
	}

}
