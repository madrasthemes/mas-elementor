<?php
/**
 * The Posts Widget.
 *
 * @package MASElementor/Modules/Posts/Widgets
 */

namespace MASElementor\Modules\Posts\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Posts
 */
class Movie_Linked_Videos extends Posts_Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-movie-linked-videos';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Movie Linked Videos', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type', 'movie', 'linked' );
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
	 * Set the query variable
	 */
	public function query_posts() {
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->register_layout_section_controls();
		parent::register_controls();
	}

	/**
	 * Register Controls in Layout Section.
	 */
	protected function register_query_section_controls() {
	}

	/**
	 * Widget render.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		$movie = masvideos_get_movie( get_the_ID() );

		if ( empty( $movie ) ) {
			return;
		}

		$rows    = ! empty( $settings['rows'] ) ? $settings['rows'] : 4;
		$columns = ! empty( $settings['columns'] ) ? $settings['columns'] : 4;
		$args    = array(
			'posts_per_page' => intval( $columns * $rows ),
		);

		if ( ! empty( $settings['swiper_posts_per_page'] ) && 'yes' === $settings['enable_carousel'] ) {
			$args['posts_per_page'] = intval( $settings['swiper_posts_per_page'] );
		}
		$linked_videos = $movie->get_related_video_ids();
		if ( ! $linked_videos ) {
			?><div class="elementor-nothing-found mas-elementor-posts-nothing-found"><?php echo esc_html( $settings['nothing_found_message'] ); ?> </div>
			<?php
			return;
		}

		$post_wrapper = 'mas-posts-container mas-posts mas-grid';
		$this->carousel_loop_header( $settings );

		// It's the global `wp_query` it self. and the loop was started from the theme.
			$count = 1;
		if ( 'yes' !== $settings['enable_carousel'] ) {
			// mas-post-container open.
			?>
				<div class="<?php echo esc_attr( $post_wrapper ); ?>">
				<?php
		}
		foreach ( $linked_videos as $linked_video ) {
			$post_object = get_post( $linked_video );

			setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
			$this->carousel_slide_loop_start( $settings );

			$this->current_permalink = get_permalink();
			if ( ! empty( $settings['select_template'] ) ) {
				if ( ! empty( $settings['select_loop'] ) && in_array( $count, $settings['select_loop'] ) ) { //phpcs:ignore
					print( mas_render_template( $settings['select_loop_template'], false ) );//phpcs:ignore

				} else {
					print( mas_render_template( $settings['select_template'], false ) );//phpcs:ignore

				}
			} else {
				mas_elementor_get_template( 'widgets/posts/post-classic.php', array( 'widget' => $this ) );
			}

			$this->carousel_slide_loop_end( $settings );

			if ( $args['posts_per_page'] === $count ) {
				break;
			}

			$count ++;
		}
		if ( 'yes' !== $settings['enable_carousel'] ) {
			// mas-post-container close.
			?>
				</div>
			<?php
		}
			wp_reset_postdata();

		$this->carousel_loop_footer( $settings );

		$this->render_script( 'swiper-' . $this->get_id() );

	}
}
