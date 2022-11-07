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
	 * Register Controls in Layout Section.
	 */
	protected function register_layout_section_controls() {
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

		$this->add_control(
			'mas_posts_class',
			array(
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'mas-posts',
				'prefix_class' => 'mas-posts-grid mas-elementor-',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'               => __( 'Columns', 'mas-elementor' ),
				'type'                => Controls_Manager::NUMBER,
				'prefix_class'        => 'mas-grid%s-',
				'min'                 => 1,
				'max'                 => 10,
				'default'             => 4,
				'required'            => true,
				'render_type'         => 'template',
				'device_args'         => array(
					Controls_Stack::RESPONSIVE_TABLET => array(
						'required' => false,
					),
					Controls_Stack::RESPONSIVE_MOBILE => array(
						'required' => false,
					),
				),
				'min_affected_device' => array(
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET  => Controls_Stack::RESPONSIVE_TABLET,
				),
				'condition'           => array(
					'enable_carousel!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rows',
			array(
				'label'       => __( 'Rows', 'mas-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 4,
				'render_type' => 'template',
				'range'       => array(
					'px' => array(
						'max' => 20,
					),
				),
				'condition'   => array(
					'enable_carousel!' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_loop_selection',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Loop Selection', 'mas-elementor' ),
				'default'   => 'no',
				'separator' => 'before',
				'condition' => array(
					'select_template!' => '',
				),
			)
		);

		$loop = range( 1, 12 );
		$loop = array_combine( $loop, $loop );

		$this->add_control(
			'select_loop',
			array(
				'label'     => esc_html__( 'Select Loop', 'mas-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => array() + $loop,
				'condition' => array(
					'enable_loop_selection' => 'yes',
				),
			)
		);

		$this->add_control(
			'select_loop_template',
			array(
				'label'       => esc_html__( 'Mas Select Templates', 'mas-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $templates,
				'description' => esc_html__( 'Select Templates for the above selected posts series', 'mas-elementor' ),
				'condition'   => array(
					'enable_loop_selection' => 'yes',
				),
			)
		);

		$this->add_control(
			'swiper_posts_per_page',
			array(
				'label'       => __( 'Posts Per Page', 'mas-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 6,
				'render_type' => 'template',
				'condition'   => array(
					'enable_carousel' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_carousel',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Enable Carousel', 'mas-elementor' ),
				'default' => 'no',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Carousel Loop Header.
	 *
	 * @param array $settings Settings of this widget.
	 */
	public function carousel_loop_header( array $settings = array() ) {

		if ( 'yes' === $settings['enable_carousel'] ) {
			$json = wp_json_encode( $this->get_swiper_carousel_options( $settings ) );
			$this->add_render_attribute( 'post_swiper', 'class', 'swiper-' . $this->get_id() );
			$this->add_render_attribute( 'post_swiper', 'class', 'swiper' );
			$this->add_render_attribute( 'post_swiper', 'data-swiper-options', $json );
			?>
			<div <?php $this->print_render_attribute_string( 'post_swiper' ); ?>>
				<div class="swiper-wrapper">
			<?php
		}

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
				if ( 'yes' === $settings['enable_carousel'] ) {
					?>
					<div class="swiper-slide">
					<?php
				}

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

				if ( 'yes' === $settings['enable_carousel'] ) {
					?>
					</div>
					<?php
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
		}

		$this->carousel_loop_footer( $settings );

		if ( 'yes' !== $settings['enable_carousel'] ) {
			$this->render_loop_footer();
		}

		$this->render_script( 'swiper-' . $this->get_id() );

	}
}
