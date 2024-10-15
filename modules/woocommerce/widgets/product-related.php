<?php
/**
 * The Product Related  Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Product Related.
 */
class Product_Related extends Products_Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-product-related';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Product Related', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-related';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'related', 'similar', 'product' );
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
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_related_products_content',
			array(
				'label' => esc_html__( 'Related Products', 'mas-addons-for-elementor' ),
			)
		);

		$templates = function_exists( 'mas_template_options' ) ? mas_template_options() : array();
		$this->add_control(
			'select_template',
			array(
				'label'   => esc_html__( 'MAS Post Item', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Products Per Page', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'range'   => array(
					'px' => array(
						'max' => 20,
					),
				),
			)
		);

		$this->add_columns_responsive_control();

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__( 'Date', 'mas-addons-for-elementor' ),
					'title'      => esc_html__( 'Title', 'mas-addons-for-elementor' ),
					'price'      => esc_html__( 'Price', 'mas-addons-for-elementor' ),
					'popularity' => esc_html__( 'Popularity', 'mas-addons-for-elementor' ),
					'rating'     => esc_html__( 'Rating', 'mas-addons-for-elementor' ),
					'rand'       => esc_html__( 'Random', 'mas-addons-for-elementor' ),
					'menu_order' => esc_html__( 'Menu Order', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'mas-addons-for-elementor' ),
					'desc' => esc_html__( 'DESC', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'mas_products_class',
			array(
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'wc-products',
				'prefix_class' => 'mas-products-grid mas-elementor-',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		global $product;

		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$args = array(
			'posts_per_page' => 4,
			'columns'        => 4,
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
		);

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

		mas_elementor_get_template(
			'widgets/single-products/related.php',
			array(
				'related_products' => $args['related_products'],
				'widget'           => $this,
			)
		);

	}

	/**
	 * Render Plain Content
	 */
	public function render_plain_content() {}

	/**
	 * Get the group name of the widget.
	 *
	 * @return string
	 */
	public function get_group_name() {
		return 'woocommerce';
	}


}
