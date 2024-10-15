<?php
/**
 * Comments URL.
 *
 * @package MASElementor\Modules\DynamicTags\tags\comments-url.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Data_Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Comments URL class.
 */
class Comments_URL extends \Elementor\Core\DynamicTags\Data_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'comments-url';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Comments URL', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the group.
	 */
	public function get_group() {
		return Module::COMMENTS_GROUP;
	}
	/**
	 * Get the categories.
	 */
	public function get_categories() {
		return array( Module::URL_CATEGORY );
	}
	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		return get_comments_link();
	}
}
