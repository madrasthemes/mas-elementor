<?php
/**
 * Tag_Product_Id.
 *
 * @package MASElementor\Modules\Woocommerce\Tags\Traits
 */

namespace MASElementor\Modules\Woocommerce\Tags\Traits;

use MASElementor\Modules\QueryControl\Module as QueryControlModule;

/**
 * Trait Product Id.
 */
trait Tag_Product_Id {
	/**
	 * Add product id control.
	 */
	public function add_product_id_control() {
		$this->add_control(
			'product_id',
			array(
				'label'        => esc_html__( 'Product', 'mas-addons-for-elementor' ),
				'type'         => QueryControlModule::QUERY_CONTROL_ID,
				'options'      => array(),
				'label_block'  => true,
				'autocomplete' => array(
					'object' => QueryControlModule::QUERY_OBJECT_POST,
					'query'  => array(
						'post_type' => array( 'product' ),
					),
				),
				// Since we're using the `wc_get_product` method to retrieve products, when no product selected manually
				// by the dynamic tag - the default should be `false` so the method will use the product id given in the
				// http request instead.
				'default'      => false,
			)
		);
	}
}
