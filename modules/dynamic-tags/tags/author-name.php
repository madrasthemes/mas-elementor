<?php
/**
 * Author Meta.
 *
 * @package MASElementor\Modules\DynamicTags\tags\author-name.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Module;
use Elementor\Core\DynamicTags\Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Author Name class.
 */
class Author_Name extends Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'author-name';
	}

	/**
	 * Get tag title.
	 */
	public function get_title() {
		return esc_html__( 'Author Name', 'mas-addons-for-elementor' );
	}

	/**
	 * Get tag group.
	 */
	public function get_group() {
		return Module::AUTHOR_GROUP;
	}

	/**
	 * Get categories.
	 */
	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}

	/**
	 * Render.
	 */
	public function render() {
		echo wp_kses_post( get_the_author() );
	}
}
