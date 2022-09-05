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
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;


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
	 * Register icon widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function register_controls() {
		$this->add_control(
			'job_title_icon',
			array(
				'label'            => esc_html__( 'Icon', 'mas-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value' => 'fas fa-star',
				),
			)
		);
	}

	/**
	 * Render icon widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$fallback_defaults = array(
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		);
		$migration_allowed = Icons_Manager::is_migration_allowed();
		echo wp_kses_post( wpjm_get_the_job_title() );
		// add old default.
		if ( ! isset( $settings['icon'] ) && ! $migration_allowed ) {
			$settings['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
		}
		$migrated = isset( $settings['__fa4_migrated']['job_title_icon'] );
		$is_new   = ! isset( $settings['icon'] ) && $migration_allowed;
		$featured = is_position_featured();

		if ( $featured ) {
			if ( ! empty( $settings['icon'] ) || ( ! empty( $settings['job_title_icon']['value'] ) && $is_new ) ) :
				?>
				<span class="mas-job-title-icon">
					<?php
					if ( $is_new || $migrated ) {
						Icons_Manager::render_icon( $settings['job_title_icon'], array( 'aria-hidden' => 'true' ) );
					} else {
						?>
						<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
					<?php } ?>
				</span>
				<?php
			endif;
		}
	}
}
