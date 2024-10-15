<?php
/**
 * Job Application Email URL.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-company-website-url.php
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
class Job_Company_Website_Url extends \Elementor\Core\DynamicTags\Data_Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-job-company-website-url';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Job Company Website', 'mas-addons-for-elementor' );
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
		$url = $post->_company_website;
		return wp_kses_post( $url );
	}
}
