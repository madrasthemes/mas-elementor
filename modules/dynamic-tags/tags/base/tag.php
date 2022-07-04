<?php
/**
 * Tag.
 *
 * @package MASElementor\Modules\DynamicTags\tags\base\tag.php
 */

namespace ElementorPro\Modules\DynamicTags\Tags\Base;

use Elementor\Core\DynamicTags\Tag as Base_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Abstract class for tag
 */
abstract class Tag extends Base_Tag {

	use Tag_Trait;
}
