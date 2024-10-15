<?php
/**
 * Post time.
 *
 * @package MASElementor\Modules\DynamicTags\tags\post-url.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Data_Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Post url tag class
 */
class Post_URL extends \Elementor\Core\DynamicTags\Data_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'post-url';
	}
	/**
	 * Get the title for post url.
	 */
	public function get_title() {
		return esc_html__( 'Post URL', 'mas-addons-for-elementor' );
	}
	/**
	 * Get group.
	 */
	public function get_group() {
		return Module::POST_GROUP;
	}
	/**
	 * Get categories.
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
		return get_permalink();
	}
}
