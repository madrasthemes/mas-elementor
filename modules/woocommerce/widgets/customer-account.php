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
class Customer_Account extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-customer-account';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Customer Account', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-my-account';
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
			'section_account',
			array(
				'label' => esc_html__( 'Account', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'icon_options',
			array(
				'label'   => esc_html__( 'Icon Options', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''          => esc_html__( 'Text and Icon', 'mas-addons-for-elementor' ),
					'text_only' => esc_html__( 'Text Only', 'mas-addons-for-elementor' ),
					'icon_only' => esc_html__( 'Icon Only', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'display_style',
			array(
				'label'     => esc_html__( 'Display Style', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''    => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'alt' => esc_html__( 'Alt', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'icon_options!' => 'text_only',
				),
			)
		);

		$this->add_control(
			'block_css',
			array(
				'label'   => esc_html__( 'Block CSS', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'wc-block-customer-account__account-icon',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_account',
			array(
				'label' => esc_html__( 'Account', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'account_color_typography',
				'selector' => '{{WRAPPER}} .wp-block-woocommerce-customer-account',
			)
		);

		$this->add_control(
			'account_color',
			array(
				'label'     => __( 'Account Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wp-block-woocommerce-customer-account' => 'color: {{VALUE}} !important',
				),
			)
		);

		$this->add_control(
			'account_bg_color',
			array(
				'label'     => __( 'Account BGColor', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wp-block-woocommerce-customer-account' => 'background-color: {{VALUE}} !important',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render
	 */
	protected function render() {
		echo wp_kses_post( $this->customer_account() );
	}

	/**
	 * Mini Cart.
	 */
	protected function customer_account() {
		$settings      = $this->get_settings_for_display();
		$block_content = '<!-- wp:woocommerce/customer-account /-->';
		$parsed_blocks = parse_blocks( $block_content );
		if ( isset( $parsed_blocks[0] ) ) {
			if ( ! empty( $settings['icon_options'] ) ) {
				$parsed_blocks[0]['attrs']['displayStyle'] = $settings['icon_options'];
			}

			if ( ! empty( $settings['display_style'] ) ) {
				$parsed_blocks[0]['attrs']['iconStyle'] = $settings['display_style'];
			}
			if ( ! empty( $settings['block_css'] ) ) {
				$parsed_blocks[0]['attrs']['iconClass'] = $settings['block_css'];
			}
		}
		$rendered_block = render_block( $parsed_blocks[0] );

		return $rendered_block;
	}

}
