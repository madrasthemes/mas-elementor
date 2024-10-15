<?php
/**
 * Product_Image.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use MASElementor\Modules\Woocommerce\Module;
use MASElementor\Modules\Woocommerce\Tags\Traits\Tag_Product_Id;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Image.
 */
class Product_Image extends Base_Data_Tag {
	use Tag_Product_Id;

	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-product-image-tag';
	}

	/**
	 * Get Title.
	 */
	public function get_title() {
		return esc_html__( 'Product Image', 'mas-addons-for-elementor' );
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_product_id_control();
	}

	/**
	 * Get Group.
	 */
	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}

	/**
	 * Get Categories.
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY );
	}

	/**
	 * Get Value.
	 *
	 * @param array $options options.
	 */
	public function get_value( array $options = array() ) {
		$product = wc_get_product( $this->get_settings( 'product_id' ) );

		if ( ! $product ) {
			return array();
		}

		$image_id = $product->get_image_id();

		if ( ! $image_id ) {
			return array();
		}

		$src = wp_get_attachment_image_src( $image_id, 'full' );

		return array(
			'id'  => $image_id,
			'url' => $src[0],
		);
	}
}
