<?php
/**
 * Category_Image.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use MASElementor\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Category Image
 */
class Category_Image extends Base_Data_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-category-image-tag';
	}

	/**
	 * Get Title.
	 */
	public function get_title() {
		return esc_html__( 'Category Image', 'mas-addons-for-elementor' );
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
		$category_id = 0;

		if ( is_product_category() ) {
			$category_id = get_queried_object_id();
		} elseif ( is_product() ) {
			$product = wc_get_product();
			if ( $product ) {
				$category_ids = $product->get_category_ids();
				if ( ! empty( $category_ids ) ) {
					$category_id = $category_ids[0];
				}
			}
		}

		if ( $category_id ) {
			$image_id = get_term_meta( $category_id, 'thumbnail_id', true );
		}

		if ( empty( $image_id ) ) {
			return array();
		}

		$src = wp_get_attachment_image_src( $image_id, 'full' );

		return array(
			'id'  => $image_id,
			'url' => $src[0],
		);
	}
}
