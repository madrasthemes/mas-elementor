<?php
/**
 * The Posts Widget.
 *
 * @package MASElementor/Modules/Posts/Widgets
 */

namespace MASElementor\Modules\Posts\Widgets;

use Elementor\Controls_Manager;
use MASElementor\Modules\QueryControl\Module as Module_Query;
use MASElementor\Modules\QueryControl\Controls\Group_Control_Related;
use MASElementor\Modules\Posts\Skins;
use MASElementor\Core\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Stack;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Posts
 */
class Posts extends Posts_Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-posts';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Posts', 'mas-elementor' );
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' );
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
	 * Called on import to override.
	 *
	 * @param array $element The element being imported.
	 */
	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}


	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->register_layout_section_controls();
		parent::register_controls();
		$this->register_pagination_section_controls();
	}

	/**
	 * Set the query variable
	 */
	public function query_posts() {
		$settings   = $this->get_settings_for_display();
		$rows       = ! empty( $settings['rows'] ) ? $settings['rows'] : 4;
		$columns    = ! empty( $settings['columns'] ) ? $settings['columns'] : 4;
		$query_args = array(
			'posts_per_page' => intval( $columns * $rows ),
			'paged'          => $this->get_current_page(),
		);

		if ( ! empty( $settings['swiper_posts_per_page'] ) && 'yes' === $settings['enable_carousel'] ) {
			$query_args['posts_per_page'] = intval( $settings['swiper_posts_per_page'] );
		}

		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, $this->get_name(), $query_args, array() );
	}

	/**
	 * Widget render.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$this->query_posts();

		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		$post_wrapper = 'mas-posts-container mas-posts mas-grid';
		$this->carousel_loop_header( $settings );

		// It's the global `wp_query` it self. and the loop was started from the theme.
		if ( $query->in_the_loop ) {

			$this->current_permalink = get_permalink();
			print( mas_render_template( $settings['select_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_reset_postdata();

		} else {
			$count = 1;
			if ( 'yes' !== $settings['enable_carousel'] ) {
				// mas-post-container open.
				?>
				<div class="<?php echo esc_attr( $post_wrapper ); ?>">
				<?php
			}
			while ( $query->have_posts() ) {

				$query->the_post();
				$this->carousel_slide_loop_start( $settings );
				$this->current_permalink = get_permalink();
				if ( ! empty( $settings['select_template'] ) ) {
					if ( ! empty( $settings['select_loop'] ) && in_array( (string) $count, $settings['select_loop'], true ) ) {
						print( mas_render_template( $settings['select_loop_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						print( mas_render_template( $settings['select_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				} else {
					mas_elementor_get_template( 'widgets/posts/post-classic.php', array( 'widget' => $this ) );
				}

				$this->carousel_slide_loop_end( $settings );

				$count ++;
			}
			if ( 'yes' !== $settings['enable_carousel'] ) {
				// mas-post-container close.
				?>
				</div>
				<?php
			}
					wp_reset_postdata();
		}

		$this->carousel_loop_footer( $settings );

		if ( 'yes' !== $settings['enable_carousel'] ) {
			$this->render_loop_footer();
		}

		$this->render_script( 'swiper-' . $this->get_id() );

	}
}
