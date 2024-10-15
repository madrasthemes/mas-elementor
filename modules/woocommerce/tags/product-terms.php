<?php
/**
 * Product_Terms.
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
 * Product Terms.
 */
class Product_Terms extends Base_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-woocommerce-product-terms-tag';
	}

	/**
	 * Get Title.
	 */
	public function get_title() {
		return esc_html__( 'Product Terms', 'mas-addons-for-elementor' );
	}

	/**
	 * Register Advanced Section.
	 */
	protected function register_advanced_section() {
		parent::register_advanced_section();

		$this->update_control(
			'before',
			array(
				'default' => esc_html__( 'Categories', 'mas-addons-for-elementor' ) . ': ',
			)
		);
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$taxonomy_filter_args = array(
			'show_in_nav_menus' => true,
			'object_type'       => array( 'product' ),
		);

		$taxonomies = get_taxonomies( $taxonomy_filter_args, 'objects' );

		$options = array();

		foreach ( $taxonomies as $taxonomy => $object ) {
			$options[ $taxonomy ] = $object->label;
		}

		$this->add_control(
			'taxonomy',
			array(
				'label'   => esc_html__( 'Taxonomy', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $options,
				'default' => 'product_cat',
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'   => esc_html__( 'Separator', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => ', ',
			)
		);

		$this->add_product_id_control();
	}

	/**
	 * Render.
	 */
	public function render() {
		$settings = $this->get_settings();

		$product = wc_get_product( $settings['product_id'] );
		if ( ! $product ) {
			return;
		}

		$value = get_the_term_list( $product->get_id(), $settings['taxonomy'], '', $settings['separator'] );

		echo wp_kses_post( $value );
	}
}
