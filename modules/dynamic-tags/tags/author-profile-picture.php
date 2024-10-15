<?php
/**
 * Author Profile Picture.
 *
 * @package MASElementor\Modules\DynamicTags\tags\author-profile-picture.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Data_Tag;
use MASElementor\Core\Utils;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Author Profile Picture class.
 */
class Author_Profile_Picture extends Data_Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'author-profile-picture';
	}

	/**
	 * Get tag title.
	 */
	public function get_title() {
		return esc_html__( 'Author Profile Picture', 'mas-addons-for-elementor' );
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
		return array( Module::IMAGE_CATEGORY );
	}

	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		Utils::set_global_authordata();

		return array(
			'id'  => '',
			'url' => get_avatar_url( (int) get_the_author_meta( 'ID' ) ),
		);
	}
}
