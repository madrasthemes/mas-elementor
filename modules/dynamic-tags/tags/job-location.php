<?php
/**
 * Job Location Name.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-location.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Post title class.
 */
class Job_Location extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'job-location-name';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Job Location', 'mas-addons-for-elementor' );
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
		return array( Module::TEXT_CATEGORY, TagsModule::POST_META_CATEGORY );
	}
	/**
	 * Render the post  title.
	 */
	public function render() {
		echo wp_kses_post( get_the_job_location() );
	}
}
