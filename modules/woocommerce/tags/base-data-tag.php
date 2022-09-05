<?php
/**
 * Base_Data_Tag.
 *
 * @package MASElementor\Modules\Woocommerce\Tags
 */

namespace MASElementor\Modules\Woocommerce\Tags;

use MASElementor\Modules\Woocommerce\Module;
use Elementor\Core\DynamicTags\Data_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Base Data Tag.
 */
abstract class Base_Data_Tag extends Data_Tag {

	/**
	 * Get the group name.
	 */
	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}
}
