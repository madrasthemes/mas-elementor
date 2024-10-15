<?php
/**
 * Cart_Button_Url.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use MASElementor\Modules\DynamicTags\Module as DyModule;
use MASElementor\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cart Button.
 */
class Cart_Button_Url extends Base_Data_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-cart-button-url';
	}
	/**
	 * Get the title for post url.
	 */
	public function get_title() {
		return esc_html__( 'Cart Button URL', 'mas-addons-for-elementor' );
	}
	/**
	 * Get group.
	 */
	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}
	/**
	 * Get categories.
	 */
	public function get_categories() {
		return array( DyModule::URL_CATEGORY );
	}
	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		return $product->add_to_cart_url();
	}
}
