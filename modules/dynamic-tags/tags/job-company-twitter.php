<?php
/**
 * Job Company Twitter.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-company-twitter.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Job_Company_Twitter class.
 */
class Job_Company_Twitter extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-job-company-twitter';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Job Company Twitter', 'mas-addons-for-elementor' );
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
		return array( Module::TEXT_CATEGORY );
	}
	/**
	 * Render the post  title.
	 */
	public function render() {
		$post = get_post( get_the_ID() );
		if ( ! $post || 'job_listing' !== $post->post_type ) {
			return;
		}
		$company_twitter = $post->_company_twitter;
		echo wp_kses_post( $company_twitter );
	}
}
