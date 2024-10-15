<?php
/**
 * Post Image Attachements.
 *
 * @package MASElementor\Modules\DynamicTags\tags\post-gallery.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Data_Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Post Gallery class.
 */
class Post_Gallery extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Get post-gallery tag name.
	 */
	public function get_name() {
		return 'post-gallery';
	}

	/**
	 * Get post-gallery tag name.
	 */
	public function get_title() {
		return esc_html__( 'Post Image Attachments', 'mas-addons-for-elementor' );
	}

	/**
	 * Get post-gallery categories name.
	 */
	public function get_categories() {
		return array( Module::GALLERY_CATEGORY );
	}

	/**
	 * Get post-gallery tag group.
	 */
	public function get_group() {
		return Module::POST_GROUP;
	}

	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		$images = get_post_gallery( get_the_ID(), false );

		if ( ! $images ) {
			return;
		}

		$gallery_attachment_ids = explode( ',', $images['ids'] );
		$value                  = array();

		foreach ( $gallery_attachment_ids as $gallery_attachment_id ) {
			$value[] = array(
				'id' => $gallery_attachment_id,
			);
		}

		return $value;
	}
}
