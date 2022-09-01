<?php
/**
 * Dynamic Tags.
 *
 * @package MASElementor\Modules\DynamicTags
 */

namespace MASElementor\Modules\DynamicTags;

use Elementor\Modules\DynamicTags\Module as TagsModule;
use MASElementor\Modules\DynamicTags\ACF;

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

		// ACF 5 and up.
		if ( class_exists( '\acf' ) && function_exists( 'acf_get_field_groups' ) ) {
			$this->add_component( 'acf', new ACF\Module() );
		}
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
			'Comments_Number',
			'Comments_URL',
			'Post_Excerpt',
			'Post_Featured_Image',
			'Post_Terms',
			'Post_Time',
			'Post_Title',
			'Post_URL',
			'Post_Date',
			'Author_Info',
			'Author_Meta',
			'Author_Name',
			'Author_Profile_Picture',
			'Author_URL',
			'Job_Title',
			'Job_Company',
			'Job_Location',
			'Job_Terms',
			'Job_Expiration',
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
