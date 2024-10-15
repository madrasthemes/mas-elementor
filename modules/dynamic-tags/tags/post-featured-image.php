<?php
/**
 * Post time.
 *
 * @package MASElementor\Modules\DynamicTags\tags\post-time.php
 */

namespace  MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use  MASElementor\Modules\DynamicTags\Tags\Base\Data_Tag;
use  MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Post Featured Image Tag Class.
 */
class Post_Featured_Image extends \Elementor\Core\DynamicTags\Data_Tag {
	/**
	 * Get post-featured-mage tag name.
	 */
	public function get_name() {
		return 'post-featured-image';
	}
	/**
	 * Get post-featured-mage tag group.
	 */
	public function get_group() {
		return Module::POST_GROUP;
	}
	/**
	 * Get post-featured-mage categories name.
	 */
	public function get_categories() {
		return array( Module::IMAGE_CATEGORY );
	}
	/**
	 * Get post-title tag name.
	 */
	public function get_title() {
		return esc_html__( 'Featured Image', 'mas-addons-for-elementor' );
	}
	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		$thumbnail_id  = get_post_thumbnail_id();
		$thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, 'full' );

		if ( $thumbnail_id && ! empty( $thumbnail_url ) ) {
			$image_data = array(
				'id'  => $thumbnail_id,
				'url' => $thumbnail_url[0],
			);
		} else {
			$image_data = $this->get_settings( 'mas-fallback' );
		}

		return $image_data;
	}
	/**
	 * Register control.
	 */
	protected function register_controls() {
		$this->add_control(
			'mas-fallback',
			array(
				'label' => esc_html__( 'Fallback', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
	}
}
