<?php
/**
 * Job Company Website.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-company-website.php
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
class Job_Company_Website extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-job-company-website';
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
		$company_website = $post->_company_website;
		echo wp_kses_post( $company_website );
	}
}
