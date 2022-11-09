<?php
/**
 * The Mas Nav Tab Widget.
 *
 * @package MASElementor/Modules/MasEpisodes/Widgets
 */

namespace MASElementor\Modules\MasEpisodes\Widgets;

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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Episodes Elementor Widget.
 */
class Mas_Episodes extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-episodes';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Episodes', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'episode', 'mas' );
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
		$settings = $this->get_settings();
		$tv_shows = masvideos_get_tv_show( get_the_ID() );
		?>
		<div class="mas-episodes-tabs mas-episodes-tabs-wrapper">
			<ul class="me-tabs nav nav-tabs" role="tablist">
				<?php
				$seasons = $tv_shows->get_seasons();
				foreach ( $seasons as $key => $season ) {
					$active = '';
					$count  = $key + 1;

					?>
					<li class="mas-nav-link nav-item" id="me-tab-title-<?php echo esc_attr( $count ); ?>" role="tab" aria-controls="me-tab-<?php echo esc_attr( $count ); ?>">
						<a class ="nav-link" href="#me-tab-<?php echo esc_attr( $count ); ?>" data-toggle="tab"><?php echo esc_html( $season['name'] ); ?></a>
					</li>
					<?php
				}
				?>
			</ul>
			<div class="mas-episodes-content-wrapper tab-content">
			<?php
			foreach ( $seasons as $index => $season ) {
				$count    = $index + 1;
				$active   = '';
				$selected = 'false';

				if ( 1 === $count ) {
					$active   = ' active';
					$selected = 'true';
					$this->add_render_attribute( 'list_link_item' . $count, 'class' );
				}
				$this->add_render_attribute(
					'list_link_item' . $count,
					array(
						'class'           => 'tab-pane' . $active,
						'id'              => 'me-tab-' . $count,
						'role'            => 'tabpanel',
						'aria-labelledby' => 'tab-title-' . $count,
						'aria-selected'   => $selected,
					)
				);
				?>
				<div <?php $this->print_render_attribute_string( 'list_link_item' . $count ); ?>>
				<?php
				foreach ( $season['episodes'] as $key => $episode_id ) {
					$episode = masvideos_get_episode( $episode_id );

					$post_object = get_post( $episode->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
					print( mas_render_template( $settings['select_template'], false ) );//phpcs:ignore

				}
						wp_reset_postdata();
				?>
				</div>
				<?php
			}
			?>
			</div>		</div>
		<?php
	}
}
