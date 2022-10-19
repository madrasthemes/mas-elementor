<?php
namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Related extends Products_Base {

	public function get_name() {
		return 'mas-woocommerce-product-related';
	}

	public function get_title() {
		return esc_html__( 'Product Related', 'mas-elementor' );
	}

	public function get_icon() {
		return 'eicon-product-related';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'related', 'similar', 'product' ];
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elements' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_related_products_content',
			[
				'label' => esc_html__( 'Related Products', 'mas-elementor' ),
			]
		);

		$templates = function_exists( 'mas_template_options' ) ? mas_template_options() : array();
		$this->add_control(
			'select_template',
			array(
				'label'   => esc_html__( 'Mas Templates', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
			)
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Products Per Page', 'mas-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'range' => [
					'px' => [
						'max' => 20,
					],
				],
			]
		);

		$this->add_columns_responsive_control();

		$this->add_control(
			'orderby',
			[
				'label' => esc_html__( 'Order By', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'mas-elementor' ),
					'title' => esc_html__( 'Title', 'mas-elementor' ),
					'price' => esc_html__( 'Price', 'mas-elementor' ),
					'popularity' => esc_html__( 'Popularity', 'mas-elementor' ),
					'rating' => esc_html__( 'Rating', 'mas-elementor' ),
					'rand' => esc_html__( 'Random', 'mas-elementor' ),
					'menu_order' => esc_html__( 'Menu Order', 'mas-elementor' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => esc_html__( 'Order', 'mas-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => esc_html__( 'ASC', 'mas-elementor' ),
					'desc' => esc_html__( 'DESC', 'mas-elementor' ),
				],
			]
		);

		$this->end_controls_section();

		
	}

	protected function render() {
		global $product;

		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$args = [
			'posts_per_page' => 4,
			'columns' => 4,
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
		];

		if ( ! empty( $settings['posts_per_page'] ) ) {
			$args['posts_per_page'] = $settings['posts_per_page'];
		}

		if ( ! empty( $settings['columns'] ) ) {
			$args['columns'] = $settings['columns'];
		}

		// Get visible related products then sort them at random.
		$args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );

		// Handle orderby.
		$args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );

		

		mas_elementor_get_template( 'widgets/single-products/related.php', array( 'related_products' => $args['related_products'], 'widget' => $this ) );

		

	}

	public function render_plain_content() {}

	public function get_group_name() {
		return 'woocommerce';
	}

		
}
