<?php
/**
 * Product_Gallery.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use MASElementor\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Gallery.
 */
class Product_Gallery extends Base_Data_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-product-gallery-tag';
	}

	/**
	 * Get Title.
	 */
	public function get_title() {
		return esc_html__( 'Product Gallery', 'mas-elementor' );
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
		return array( \Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY );
	}

	/**
	 * Get Value.
	 *
	 * @param array $options options.
	 */
	public function get_value( array $options = array() ) {
		$product = wc_get_product();
		if ( ! $product ) {
			return array();
		}
		$value = array();

		$attachment_ids = $product->get_gallery_image_ids();

		foreach ( $attachment_ids as $attachment_id ) {
			$value[] = array(
				'id' => $attachment_id,
			);
		}

		return $value;
	}
}
