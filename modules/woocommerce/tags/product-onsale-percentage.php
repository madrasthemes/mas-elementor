<?php
/**
 * Product_Onsale_Percentage.
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
class Product_Onsale_Percentage extends Base_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-product-onsale-percentage-tag';
	}

	/**
	 * Get Title.
	 */
	public function get_title() {
		return esc_html__( 'OnSale Percentage', 'mas-addons-for-elementor' );
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'output',
			array(
				'label'   => esc_html__( 'Output Format', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'amount'     => esc_html__( 'Amount', 'mas-addons-for-elementor' ),
					'percentage' => esc_html__( 'Percentage', 'mas-addons-for-elementor' ),
				),
				'default' => 'percentage',
			)
		);

		$this->add_control(
			'text_prefix',
			array(
				'label'   => esc_html__( 'Prefix', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '-', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'text_suffix',
			array(
				'label'   => esc_html__( 'Suffix', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '%', 'mas-addons-for-elementor' ),
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

		if ( $product->is_type( 'variable' ) ) {
			$var_regular_price    = array();
			$var_sale_price       = array();
			$var_diff_price       = array();
			$available_variations = $product->get_available_variations();
			foreach ( $available_variations as $key => $available_variation ) {
				$variation_id     = $available_variation['variation_id']; // Getting the variable id of just the 1st product. You can loop $available_variations to get info about each variation.
				$variable_product = new WC_Product_Variation( $variation_id );

				$variable_product_regular_price = $variable_product->get_regular_price();
				$variable_product_sale_price    = $variable_product->get_sale_price();

				if ( ! empty( $variable_product_regular_price ) ) {
					$var_regular_price[] = $variable_product_regular_price;
				} else {
					$var_regular_price[] = 0;
				}
				if ( ! empty( $variable_product_sale_price ) ) {
					$var_sale_price[] = $variable_product_sale_price;
				} else {
					$var_sale_price[] = 0;
				}
			}

			foreach ( $var_regular_price as $key => $reg_price ) {
				if ( isset( $var_sale_price[ $key ] ) && 0 !== $var_sale_price[ $key ] ) {
					$var_diff_price[] = $reg_price - $var_sale_price[ $key ];
				} else {
					$var_diff_price[] = 0;
				}
			}

			$best_key = array_search( max( $var_diff_price ), $var_diff_price, true );

			$regular_price = $var_regular_price[ $best_key ];
			$sale_price    = $var_sale_price[ $best_key ];
		} else {
			$regular_price = $product->get_regular_price();
			$sale_price    = $product->get_sale_price();
		}

		$regular_price = wc_get_price_to_display(
			$product,
			array(
				'qty'   => 1,
				'price' => $regular_price,
			)
		);
		$sale_price    = wc_get_price_to_display(
			$product,
			array(
				'qty'   => 1,
				'price' => $sale_price,
			)
		);

		if ( empty( $sale_price ) ) {
			return;
		}

		if ( 'amount' === $this->get_settings( 'output' ) ) {

			$savings = wc_price( $regular_price - $sale_price );

		} elseif ( 'percentage' === $this->get_settings( 'output' ) ) {

			$savings = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
		}

		if ( ! empty( $savings ) ) {
			$savings = $this->get_settings( 'text_prefix' ) . $savings . $this->get_settings( 'text_suffix' );
			echo wp_kses_post( $savings );
		}

	}
}
