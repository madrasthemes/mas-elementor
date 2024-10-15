<?php
/**
 * Product_Rating.
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
 * Product Rating.
 */
class Product_Rating extends Base_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-product-rating-tag';
	}

	/**
	 * Get Title.
	 */
	public function get_title() {
		return esc_html__( 'Product Rating', 'mas-addons-for-elementor' );
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
		$this->add_control(
			'field',
			array(
				'label'   => esc_html__( 'Format', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'average_rating' => esc_html__( 'Average Rating', 'mas-addons-for-elementor' ),
					'rating_count'   => esc_html__( 'Rating Count', 'mas-addons-for-elementor' ),
					'review_count'   => esc_html__( 'Review Count', 'mas-addons-for-elementor' ),
				),
				'default' => 'average_rating',
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
			return '';
		}

		$field = $this->get_settings( 'field' );
		$value = '';
		switch ( $field ) {
			case 'average_rating':
				$value = $product->get_average_rating();
				break;
			case 'rating_count':
				$value = $product->get_rating_count();
				break;
			case 'review_count':
				$value = $product->get_review_count();
				break;
		}

		echo wp_kses_post( $value );
	}
}
