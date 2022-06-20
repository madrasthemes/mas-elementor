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
		return 'posts';
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
	 * Register the skins for the widget.
	 */
	protected function register_skins() {
		// $this->add_skin( new Skins\Skin_Classic( $this ) );
		// $this->add_skin( new Skins\Skin_Grid( $this ) );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->register_query_section_controls();
		$this->register_pagination_section_controls();
	}

	/**
	 * Set the query variable
	 */
	public function query_posts() {

		$query_args = array(
			// 'posts_per_page' => $this->get_current_skin()->get_instance_value( 'posts_per_page' ),
			'paged'          => $this->get_current_page(),
		);

		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, 'posts', $query_args, array() );
	}

	/**
	 * Register controls in the Query Section
	 */
	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			array(
				'name'    => $this->get_name(),
				'presets' => array( 'full' ),
				'exclude' => array(
					'posts_per_page', // use the one from Layout section.
				),
			)
		);
		
		$dir     = MAS_ELEMENTOR_PATH . 'templates/widgets/posts/';
		$templates = Utils::get_mas_post_templates($dir);

		$this->add_control(
			'select_template',
			[
				'label'   => esc_html__( 'Layout', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
				'default' => array_key_first($templates),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	public function render() {
		$this->query_posts();

		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		// echo $this->render_loop_header(); //phpcs:ignore

		// It's the global `wp_query` it self. and the loop was started from the theme.
		if ( $query->in_the_loop ) {
			$this->current_permalink = get_permalink();
			$this->render_post();
		} else {
			while ( $query->have_posts() ) {
				$query->the_post();

				$this->current_permalink = get_permalink();
				$this->render_post();
			}
		}

		wp_reset_postdata();

		// echo $this->render_loop_footer(); //phpcs:ignore
		// $this->render_mas_pagination();
	}

	/**
	 * Render Post.
	 */
	public function render_post() {
		$settings       = $this->get_settings_for_display();
		$template_hooks = str_replace( '-', '_','_' );
		$filter         = $template_hooks . '_template_args';
		$args           = apply_filters(
			$filter,
			array(
				'widget' => $this,
				'skin'   => $this,
			)
		);
		mas_elementor_get_template( 'widgets/posts/' . $settings['select_template'] , $args );
	}

}
