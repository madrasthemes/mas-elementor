<?php
/**
 * Dynamic Tags.
 *
 * @package MASElementor\Modules\DynamicTags
 */

namespace MASElementor\Modules\DynamicTags;

use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * The Dynamic Tag module class
 */
class Module extends TagsModule {

	const AUTHOR_GROUP = 'author';

	const POST_GROUP = 'post';

	const COMMENTS_GROUP = 'comments';

	const SITE_GROUP = 'site';

	const ARCHIVE_GROUP = 'archive';

	const MEDIA_GROUP = 'media';

	const ACTION_GROUP = 'action';

	public function __construct() { //PHPCS:ignore.
		parent::__construct();
	}
	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'tags';
	}
	/**
	 * Get tag class name.
	 */
	public function get_tag_classes_names() {
		return array(
			'Post_Excerpt',
			'Post_Featured_Image',
			'Post_Terms',
			'Post_Time',
			'Post_Title',
			'Post_URL',
		);
	}
	/**
	 * Get groups.
	 */
	public function get_groups() {
		return array(
			self::POST_GROUP     => array(
				'title' => esc_html__( 'Post', 'mas-elementor' ),
			),
			self::ARCHIVE_GROUP  => array(
				'title' => esc_html__( 'Archive', 'mas-elementor' ),
			),
			self::SITE_GROUP     => array(
				'title' => esc_html__( 'Site', 'mas-elementor' ),
			),
			self::MEDIA_GROUP    => array(
				'title' => esc_html__( 'Media', 'mas-elementor' ),
			),
			self::ACTION_GROUP   => array(
				'title' => esc_html__( 'Actions', 'mas-elementor' ),
			),
			self::AUTHOR_GROUP   => array(
				'title' => esc_html__( 'Author', 'mas-elementor' ),
			),
			self::COMMENTS_GROUP => array(
				'title' => esc_html__( 'Comments', 'mas-elementor' ),
			),
		);
	}
}
