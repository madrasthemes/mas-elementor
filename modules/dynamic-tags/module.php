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

	const JOB_GROUP = 'job';

	const COMMENTS_GROUP = 'comments';

	const SITE_GROUP = 'site';

	const ARCHIVE_GROUP = 'archive';

	const MEDIA_GROUP = 'media';

	const ACTION_GROUP = 'action';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

		add_action( 'elementor/frontend/before_enqueue_styles', array( $this, 'register_frontend_styles' ) );
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
		$tags = array(
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
			'Post_Gallery',
		);
		$jobs = array();
		if ( class_exists( 'WP_Job_Manager' ) ) {
			$jobs = array(
				'Job_Title',
				'Job_Company',
				'Job_Location',
				'Job_Terms',
				'Job_Expiration',
				'Job_Application_Email',
				'Job_Application_Email_Url',
				'Job_Company_Website',
				'Job_Company_Website_Url',
				'Job_Company_Video_Url',
				'Job_Company_Twitter_Url',
				'Job_Company_Twitter',
				'Job_Company_Tagline',
				'Job_Remote_Position',
				'Job_Position_Filled',
			);
			$tags = array_merge( $tags, $jobs );
		}
		$movie = array();
		if ( class_exists( 'MasVideos' ) ) {
			$movie = array(
				'Movie_Rating',
				'Mas_Videos_Text_Fields',
				'Mas_Videos_Url_Fields',
				'Mas_Videos_Media_Fields',
				'Mas_Videos_Episodes_Text_Fields',
				'Mas_Videos_Episodes_Url_Fields',
				'Mas_Videos_Tv_Shows_Episode_Url_Fields',
			);
			$tags  = array_merge( $tags, $movie );

		}

		return $tags;
	}
	/**
	 * Get groups.
	 */
	public function get_groups() {
		return array(
			self::POST_GROUP     => array(
				'title' => esc_html__( 'Post', 'mas-addons-for-elementor' ),
			),
			self::JOB_GROUP     => array(
				'title' => esc_html__( 'Job', 'mas-addons-for-elementor' ),
			),
			self::ARCHIVE_GROUP  => array(
				'title' => esc_html__( 'Archive', 'mas-addons-for-elementor' ),
			),
			self::SITE_GROUP     => array(
				'title' => esc_html__( 'Site', 'mas-addons-for-elementor' ),
			),
			self::MEDIA_GROUP    => array(
				'title' => esc_html__( 'Media', 'mas-addons-for-elementor' ),
			),
			self::ACTION_GROUP   => array(
				'title' => esc_html__( 'Actions', 'mas-addons-for-elementor' ),
			),
			self::AUTHOR_GROUP   => array(
				'title' => esc_html__( 'Author', 'mas-addons-for-elementor' ),
			),
			self::COMMENTS_GROUP => array(
				'title' => esc_html__( 'Comments', 'mas-addons-for-elementor' ),
			),
		);
	}

	/**
	 * Add Actions.
	 */
	protected function add_actions() {
	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_enqueue_style(
			'mas-dynamic-tag-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'dynamic-tags/assets/css/dynamic-tag.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}
}
