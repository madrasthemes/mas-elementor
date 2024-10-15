<?php
/**
 * Product Sold Count.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use MASElementor\Modules\Woocommerce\Tags\Traits\Tag_Product_Id;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Sold Count.
 */
class Product_Sold_Count extends Base_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-product-sold-count-tag';
	}

	/**
	 * Get Title.
	 */
	public function get_title() {
		return esc_html__( 'Product Sold Meta', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the Categories.
	 */
	public function get_categories() {
		return array(
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
		);
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_product_id_control();

		$this->add_control(
			'mas_product_meta',
			array(
				'label'   => esc_html__( 'Layout', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'sold'       => esc_html__( 'Products Sold', 'mas-addons-for-elementor' ),
					'available'  => esc_html__( 'Products Available', 'mas-addons-for-elementor' ),
					'percentage' => esc_html__( 'Sold Percentage', 'mas-addons-for-elementor' ),
				),
				'default' => 'sold',
			)
		);
	}

	/**
	 * Render.
	 */
	public function render() {
		$product_id           = empty( $this->get_settings( 'product_id' ) ) ? get_the_ID() : $this->get_settings( 'product_id' );
		$product              = wc_get_product( $product_id );
		$total_stock_quantity = get_post_meta( $product_id, '_total_stock_quantity', true );
		$option               = $this->get_settings( 'mas_product_meta' );
		$stock                = get_post_meta( $product_id, '_stock', true );
		if ( ! empty( $total_stock_quantity ) ) {
			$stock_quantity  = round( $total_stock_quantity );
			$stock_available = ( $stock ) ? round( $stock ) : 0;
			$stock_sold      = ( $stock_quantity > $stock_available ? $stock_quantity - $stock_available : 0 );
			$percentage      = ( $stock_sold > 0 ? round( $stock_sold / $stock_quantity * 100 ) : 0 );
		} else {
			$total_sales     = get_post_meta( $product_id, 'total_sales', true );
			$stock_available = ( $stock ) ? round( $stock ) : 0;
			$stock_sold      = ( $total_sales ) ? round( $total_sales ) : 0;
			$stock_quantity  = $stock_sold + $stock_available;
			$percentage      = ( ( $stock_available > 0 && $stock_quantity > 0 ) ? round( $stock_sold / $stock_quantity * 100 ) : 0 );
		}

		if ( ! $product || ! ( $stock_available > 0 ) ) {
			return;
		}

		switch ( $option ) {
			case 'sold':
				$output = $stock_sold;
				break;
			case 'available':
				$output = $stock_available;
				break;
			case 'percentage':
				$output = $percentage;
				break;
			default:
				$output = $stock_sold;
		}

		echo wp_kses_post( $output );
	}
}
