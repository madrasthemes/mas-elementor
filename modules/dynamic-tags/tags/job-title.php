<?php
/**
 * Job Title Name.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-location.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Post title class.
 */
class Job_Title extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'job-title';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Job Title', 'mas-elementor' );
	}
	/**
	 * Get the group.
	 */
	public function get_group() {
		return Module::POST_GROUP;
	}
	/**
	 * Get the categories.
	 */
	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'job_title_icon',
			array(
				'label'            => esc_html__( 'Icon', 'mas-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
			)
		);
	}

	/**
	 * Render the post  title.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$migrated = isset( $settings['__fa4_migrated']['job_title_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		$featured = is_position_featured();
		if ( $featured ) {
			if ( $is_new || $migrated ) :
				Icons_Manager::render_icon( $settings['job_title_icon'], array( 'aria-hidden' => 'true' ) );
			else : ?>
				<i <?php $this->print_render_attribute_string( 'icon' ); ?>></i>
				<?php
			endif;
		}
		echo wp_kses_post( wpjm_get_the_job_title() );
	}
}
