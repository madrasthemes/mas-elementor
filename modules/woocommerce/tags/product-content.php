<?php
/**
 * Product_Id.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use MASElementor\Modules\Woocommerce\Tags\Traits\Tag_Product_Id;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Id.
 */
class Product_Content extends Base_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-product-content-tag';
	}

	/**
	 * Get Id.
	 */
	public function get_title() {
		return esc_html__( 'Product Content', 'mas-addons-for-elementor' );
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
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

		echo wp_kses_post( $product->get_description() );
	}
}
