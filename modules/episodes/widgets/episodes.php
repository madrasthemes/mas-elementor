<?php
/**
 * The Mas Nav Tab Widget.
 *
 * @package MASElementor/Modules/Episodes/Widgets
 */

namespace MASElementor\Modules\Episodes\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use MASElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use MASElementor\Core\Controls_Manager as MAS_Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Controls_Stack;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Episodes Elementor Widget.
 */
class Episodes extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'episodes';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Episodes', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-related';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'episodes', 'mas' );
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elements' );
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the element requires.
	 *
	 * @since 1.9.0
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'episodes-stylesheet' );
	}

	/**
	 * Register Controls in Layout Section.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$templates = function_exists( 'mas_template_options' ) ? mas_template_options() : array();
		$this->add_control(
			'select_template',
			array(
				'label'   => esc_html__( 'Mas Templates', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Renders the Nav Tabs widget.
	 */
	protected function render() {

		$episodes = masvideos_get_episode( get_the_ID() );
		if ( empty( $episodes ) ) {
			return;
		}
		$tv_show_id = $episodes->get_tv_show_id();
		$tv_show    = masvideos_get_tv_show( $tv_show_id );

		$seasons = $tv_show->get_seasons();

		$settings = $this->get_settings();
		$test_id  = get_the_ID();
		?><div class="episodes-wrapper">
		<?php
		foreach ( $seasons as $key => $season ) {
			$original_post = $GLOBALS['post'];
			foreach ( $season['episodes'] as $key => $episode_id ) {
				if ( $test_id === $episode_id ) {
					continue;
				}

				$GLOBALS['post'] = get_post( $episode_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				setup_postdata( $GLOBALS['post'] );

				setup_postdata( masvideos_setup_episode_data( $episode_id ) ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
				print( mas_render_template( $settings['select_template'], false ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			}
			$GLOBALS['post'] = $original_post;// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			wp_reset_postdata();
		}
		?>
		</div>
		<?php
	}
}
