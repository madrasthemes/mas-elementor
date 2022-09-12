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
		return esc_html__( 'Product Rating', 'mas-elementor' );
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'field',
			array(
				'label'   => esc_html__( 'Format', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'average_rating' => esc_html__( 'Average Rating', 'mas-elementor' ),
					'rating_count'   => esc_html__( 'Rating Count', 'mas-elementor' ),
					'review_count'   => esc_html__( 'Review Count', 'mas-elementor' ),
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

		// PHPCS - Safe WC data.
		echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}