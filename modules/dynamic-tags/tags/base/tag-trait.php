<?php
/**
 * Tag trait.
 *
 * @package MASElementor\Modules\DynamicTags\tags\base\tag-trait.php
 */

namespace ElementorPro\Modules\DynamicTags\Tags\Base;

use ElementorPro\License\API as License_API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

trait Tag_Trait {
	/**
	 * Function for editable.
	 */
	public function is_editable() {
		return License_API::is_license_active();
	}
}
