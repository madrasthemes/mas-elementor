<?php
/**
 * The Base Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Base_Widget
 */
abstract class Base_Widget extends \MASElementor\Base\Base_Widget {

	/**
	 * Get Categories.
	 */
	public function get_categories() {
		return array( 'woocommerce-elements-single' );
	}
}
