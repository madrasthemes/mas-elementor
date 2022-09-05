<?php
/**
 * Base_Tag.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use MASElementor\Modules\Woocommerce\Module;
use MASElementor\Modules\Woocommerce\Tags\Traits\Tag_Product_Id;
use Elementor\Core\DynamicTags\Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Base Tag.
 */
abstract class Base_Tag extends Tag {
	use Tag_Product_Id;

	/**
	 * Get the post-term group name.
	 */
	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}

	/**
	 * Get the Categories.
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
	}
}
