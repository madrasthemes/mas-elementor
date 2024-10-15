<?php
/**
 * Post Title.
 *
 * @package MASElementor\Modules\DynamicTags\tags\post-title.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Post title class.
 */
class Post_Title extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'post-title';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Post Title', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the group.
	 */
	public function get_group() {
		return Module::POST_GROUP;
	}
	/**
	 * Get the categories.
	 */
	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}
	/**
	 * Render the post  title.
	 */
	public function render() {
		echo wp_kses_post( get_the_title() );
	}
}
