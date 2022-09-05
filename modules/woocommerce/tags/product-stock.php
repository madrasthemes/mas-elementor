<?php
/**
 * Product_Stock.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use Elementor\Controls_Manager;
use MASElementor\Modules\Woocommerce\Tags\Traits\Tag_Product_Id;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Stock.
 */
class Product_Stock extends Base_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-product-stock-tag';
	}

	/**
	 * Get Title.
	 */
	public function get_title() {
		return esc_html__( 'Product Stock', 'mas-elementor' );
	}

	/**
	 * Render.
	 */
	public function render() {
		$product = wc_get_product( $this->get_settings( 'product_id' ) );
		if ( ! $product ) {
			return;
		}

		if ( 'yes' === $this->get_settings( 'show_text' ) ) {
			$value = wc_get_stock_html( $product );
		} else {
			$value = (int) $product->get_stock_quantity();
		}

		// PHPCS - `wc_get_stock_html` is safe, and `get_stock_quantity` protected with (int).
		echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'show_text',
			array(
				'label'     => esc_html__( 'Show Text', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
			)
		);

		$this->add_product_id_control();
	}
}
