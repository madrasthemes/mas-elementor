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
		return esc_html__( 'Job Title', 'mas-addons-for-elementor' );
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
	 * Register icon widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function register_controls() {
		$this->add_control(
			'job_title_icon',
			array(
				'label'                  => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'icon',
				'default'                => array(
					'value' => 'fas fa-star',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'svg' ),
			)
		);

		$this->add_control(
			'job_title_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 300,
					),
				),
			)
		);

		$this->add_control(
			'job_title_icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::COLOR,
			)
		);

		$this->add_control(
			'job_title_icon__position',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => esc_html__( 'Icon Position', 'mas-addons-for-elementor' ),
				'default'            => 'after',
				'options'            => array(
					'before' => esc_html__( 'Before', 'mas-addons-for-elementor' ),
					'after'  => esc_html__( 'After', 'mas-addons-for-elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'job_title_icon_spacing',
			array(
				'label' => esc_html__( 'Icon Spacing', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 300,
					),
				),
			)
		);
	}

	/**
	 * Render the post  title.
	 */
	public function render_icon() {
		$settings = $this->get_settings_for_display();

		$fallback_defaults      = array(
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		);
		$icon_attr              = array();
		$icon_attr['font_size'] = 'font-size:' . $settings['job_title_icon_size']['size'] . $settings['job_title_icon_size']['unit'];
		$icon_attr['color']     = 'color:' . $settings['job_title_icon_color'];
		$icon_attr['spacing']   = 'before' === $settings['job_title_icon__position'] ? 'margin-right:' . $settings['job_title_icon_spacing']['size'] . $settings['job_title_icon_spacing']['unit'] : 'margin-left:' . $settings['job_title_icon_spacing']['size'] . $settings['job_title_icon_spacing']['unit'];
		$icon_attr_str          = implode( ';', $icon_attr );
		$migration_allowed      = Icons_Manager::is_migration_allowed();
		// add old default.
		if ( ! isset( $settings['icon'] ) && ! $migration_allowed ) {
			$settings['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
		}
		$migrated = isset( $settings['__fa4_migrated']['job_title_icon'] );
		$is_new   = ! isset( $settings['icon'] ) && $migration_allowed;
		$featured = is_position_featured();

		if ( $featured ) {
			if ( ! empty( $settings['icon'] ) || ( ! empty( $settings['job_title_icon']['value'] ) && $is_new ) ) :
				if ( $is_new || $migrated ) {
					Icons_Manager::render_icon(
						$settings['job_title_icon'],
						array(
							'aria-hidden' => 'true',
							'style'       => $icon_attr_str,
						)
					);
				} else {
					?>
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true" style="<?php $icon_attr_str; ?>"></i>
					<?php
				}
			endif;
		}
	}

	/**
	 * Render the post  title.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		if ( 'before' === $settings['job_title_icon__position'] ) {
			$this->render_icon();
		}
		echo wp_kses_post( wpjm_get_the_job_title() );
		if ( 'after' === $settings['job_title_icon__position'] ) {
			$this->render_icon();
		}
	}

}
