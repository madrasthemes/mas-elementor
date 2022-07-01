<?php
namespace  MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use  MASElementor\Modules\DynamicTags\Tags\Base\Data_Tag;
use  MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Featured_Image extends \Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'post-featured-image';
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [ Module::IMAGE_CATEGORY ];
	}

	public function get_title() {
		return esc_html__( 'Featured Image', 'mas-elementor' );
	}

	public function get_value( array $options = [] ) {
		$thumbnail_id = get_post_thumbnail_id();

		if ( $thumbnail_id ) {
			$image_data = [
				'id' => $thumbnail_id,
				'url' => wp_get_attachment_image_src( $thumbnail_id, 'full' )[0],
			];
		} else {
			$image_data = $this->get_settings( 'fallback' );
		}

		return $image_data;
	}

	protected function register_controls() {
		$this->add_control(
			'mas-fallback',
			[
				'label' => esc_html__( 'Fallback', 'mas-elementor' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
	}
}
