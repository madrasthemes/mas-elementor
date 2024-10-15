<?php
/**
 * Product_Sale.
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
 * Product Sale.
 */
class Product_Sale extends Base_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-product-sale-tag';
	}

	/**
	 * Get Title.
	 */
	public function get_title() {
		return esc_html__( 'Product Sale', 'mas-addons-for-elementor' );
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Text', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Sale!', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_product_id_control();
	}

	/**
	 * Render.
	 */
	public function render() {
		$product = wc_get_product( $this->get_settings( 'product_id' ) );
		if ( ! $product ) {
			return;
		}

		$value = '';

		if ( $product->is_on_sale() ) {
			$value = $this->get_settings( 'text' );
		}

		echo wp_kses_post( $value );
	}
}
