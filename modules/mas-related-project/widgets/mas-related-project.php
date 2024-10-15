<?php
/**
 * The Related Project Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\MasRelatedProject\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use MASElementor\Base\Base_Widget;
use Elementor\Icons_Manager;
use MASElementor\Plugin;
use Elementor\Controls_Stack;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Related Project.
 */
class Mas_Related_Project extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-related-project';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Related Project', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-related';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'shop', 'store', 'related', 'similar', 'product' );
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
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_related_projects_content',
			array(
				'label' => esc_html__( 'Related Projects', 'mas-addons-for-elementor' ),
			)
		);

		$templates = function_exists( 'mas_template_options' ) ? mas_template_options() : array();
		$this->add_control(
			'select_template',
			array(
				'label'   => esc_html__( 'MAS Post Item', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
			)
		);

		$this->add_control(
			'mas_projects_class',
			array(
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'mas-projects',
				'prefix_class' => 'mas-projects-grid mas-elementor-',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'               => __( 'Columns', 'mas-addons-for-elementor' ),
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
			)
		);

		$this->add_control(
			'rows',
			array(
				'label'       => __( 'Rows', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'render_type' => 'template',
				'range'       => array(
					'px' => array(
						'max' => 20,
					),
				),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Post Per Page', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'range'   => array(
					'px' => array(
						'max' => 20,
					),
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__( 'Date', 'mas-addons-for-elementor' ),
					'title'      => esc_html__( 'Title', 'mas-addons-for-elementor' ),
					'price'      => esc_html__( 'Price', 'mas-addons-for-elementor' ),
					'popularity' => esc_html__( 'Popularity', 'mas-addons-for-elementor' ),
					'rating'     => esc_html__( 'Rating', 'mas-addons-for-elementor' ),
					'rand'       => esc_html__( 'Random', 'mas-addons-for-elementor' ),
					'menu_order' => esc_html__( 'Menu Order', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'mas-addons-for-elementor' ),
					'desc' => esc_html__( 'DESC', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		global $post;

		$settings = $this->get_settings_for_display();

		$portfolio_types = get_the_terms( get_the_ID(), 'jetpack-portfolio-type' );

		$portfolio_tags = get_the_terms( get_the_ID(), 'jetpack-portfolio-tag' );

		$project_wrapper = 'mas-project-container mas-projects mas-grid';

		if ( $portfolio_types || $portfolio_tags ) {
			$type_ids = array();
			if ( $portfolio_types ) {
				foreach ( $portfolio_types as $portfolio_type ) {
					$type_ids[] = $portfolio_type->term_id;

				}
			}

			$tag_ids = array();
			if ( $portfolio_tags ) {
				foreach ( $portfolio_tags as $portfolio_tag ) {
					$tag_ids[] = $portfolio_tag->term_id;

				}
			}

			$args = array(
				'tax_query'      => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					'relation' => 'OR',
					array(
						'taxonomy' => 'jetpack-portfolio-type',
						'field'    => 'id',
						'terms'    => $type_ids,
					),
					array(
						'taxonomy' => 'jetpack-portfolio-tag',
						'field'    => 'id',
						'terms'    => $tag_ids,
					),
				),
				'post__not_in'   => array( get_the_ID() ),
				'post_type'      => 'jetpack-portfolio',
				'posts_per_page' => 4,
				'columns'        => 1,
				'rows'           => 1,
				'orderby'        => $settings['orderby'],
				'order'          => $settings['order'],
			);

			if ( ! empty( $settings['posts_per_page'] ) ) {
				$args['posts_per_page'] = $settings['posts_per_page'];
			}

			if ( ! empty( $settings['rows'] ) ) {
				$args['rows'] = $settings['rows'];
			}

			if ( ! empty( $settings['columns'] ) ) {
				$args['columns'] = $settings['columns'];
			}

			$related_works = new \WP_Query( $args );

			if ( $related_works->have_posts() ) {
				?>
				<div class="<?php echo esc_attr( $project_wrapper ); ?>">
				<?php
				while ( $related_works->have_posts() ) {
					$related_works->the_post();
					// display each post.
					print( mas_render_template( $settings['select_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
				</div>
				<?php
			}
		}
	}
}
