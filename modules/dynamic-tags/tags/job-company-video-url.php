<?php
/**
 * Job Application Email URL.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-company-video-url.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Job_Company_Video_Url class.
 */
class Job_Company_Video_Url extends \Elementor\Core\DynamicTags\Data_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-job-company-video-url';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Job Company Video', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the group.
	 */
	public function get_group() {
		return Module::JOB_GROUP;
	}
	/**
	 * Get the categories.
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
		$post = get_post( get_the_ID() );
		if ( ! $post || 'job_listing' !== $post->post_type ) {
			return;
		}
		$url = $post->_company_video;
		return wp_kses_post( $url );
	}
}
