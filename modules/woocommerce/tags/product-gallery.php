<?php
/**
 * Product_Gallery.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use MASElementor\Modules\Woocommerce\Module;
use Elementor\Controls_Manager;

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
		return esc_html__( 'Product Gallery', 'mas-addons-for-elementor' );
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

		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ) {
				$value[] = array(
					'id' => $attachment_id,
				);
			}
		} else {
			$fall_back = $this->get_settings( 'mas-fallback' );
			if ( ! empty( $fall_back['id'] ) ) {
				$value[] = array(
					'id' => $fall_back['id'],
				);
			}
		}

		return $value;
	}

	/**
	 * Register control.
	 */
	protected function register_controls() {
		$this->add_control(
			'mas-fallback',
			array(
				'label' => esc_html__( 'Fallback', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
	}
}
