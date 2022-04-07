<?php
/**
 * The Posts Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use MASElementor\Modules\QueryControl\Controls\Group_Control_Query;
use MASElementor\Modules\Woocommerce\Classes\Products_Renderer;
use MASElementor\Modules\Woocommerce\Classes\Current_Query_Renderer;
use MASElementor\Modules\Woocommerce\Skins;
use MASElementor\Modules\QueryControl\Module;
use MASElementor\Modules\QueryControl\Module as Module_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Products
 */
class Products extends Products_Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'woocommerce-products';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Products', 'mas-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'product', 'archive' );
	}

	/**
	 * Get the Categories related to the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array(
			'woocommerce-elements',
		);
	}

	/**
	 * Get Query.
	 *
	 * @return array
	 */
	public function get_query() {
		return $this->query;
	}

	/**
	 * Get Current Page.
	 *
	 * @return array
	 */
	public function get_current_page() {
		if ( '' === $this->get_settings( 'pagination_type' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	/**
	 * Register Skins.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Classic( $this ) );
	}

	/**
	 * Register query controls in the Query Section
	 */
	protected function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_group_control(
			Group_Control_Query::get_type(),
			array(
				'name'           => Products_Renderer::QUERY_CONTROL_NAME,
				'post_type'      => 'product',
				'presets'        => array( 'include', 'exclude', 'order' ),
				'fields_options' => array(
					'post_type' => array(
						'default' => 'product',
						'options' => array(
							'current_query' => __( 'Current Query', 'mas-elementor' ),
							'product'       => __( 'Latest Products', 'mas-elementor' ),
							'sale'          => __( 'Sale', 'mas-elementor' ),
							'featured'      => __( 'Featured', 'mas-elementor' ),
							'by_id'         => _x( 'Manual Selection', 'Posts Query Control', 'mas-elementor' ),
						),
					),
					'orderby'   => array(
						'default' => 'date',
						'options' => array(
							'date'       => __( 'Date', 'mas-elementor' ),
							'title'      => __( 'Title', 'mas-elementor' ),
							'price'      => __( 'Price', 'mas-elementor' ),
							'popularity' => __( 'Popularity', 'mas-elementor' ),
							'rating'     => __( 'Rating', 'mas-elementor' ),
							'rand'       => __( 'Random', 'mas-elementor' ),
							'menu_order' => __( 'Menu Order', 'mas-elementor' ),
						),
					),
					'exclude'   => array(
						'options' => array(
							'current_post'     => __( 'Current Post', 'mas-elementor' ),
							'manual_selection' => __( 'Manual Selection', 'mas-elementor' ),
							'terms'            => __( 'Term', 'mas-elementor' ),
						),
					),
					'include'   => array(
						'options' => array(
							'terms' => __( 'Term', 'mas-elementor' ),
						),
					),
				),
				'exclude'        => array(
					'posts_per_page',
					'exclude_authors',
					'authors',
					'offset',
					'related_fallback',
					'related_ids',
					'query_id',
					'avoid_duplicates',
					'ignore_sticky_posts',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Query Posts
	 */
	public function query_posts() {

		$query_args = array(
			'paged' => $this->get_current_page(),
		);

		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, 'posts', $query_args, array() );
	}

	/**
	 * Register controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'mas-elementor' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'               => __( 'Columns', 'mas-elementor' ),
				'type'                => Controls_Manager::NUMBER,
				'prefix_class'        => 'mas-elementorducts-columns%s-',
				'min'                 => 1,
				'max'                 => 12,
				'default'             => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
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
				'label'       => __( 'Rows', 'mas-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
				'render_type' => 'template',
				'range'       => array(
					'px' => array(
						'max' => 20,
					),
				),
			)
		);

		$this->add_control(
			'paginate',
			array(
				'label'   => __( 'Pagination', 'mas-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'allow_order',
			array(
				'label'     => __( 'Allow Order', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'paginate' => 'yes',
				),
			)
		);

		$this->add_control(
			'wc_notice_frontpage',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Ordering is not available if this widget is placed in your front page. Visible on frontend only.', 'mas-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'paginate'    => 'yes',
					'allow_order' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_result_count',
			array(
				'label'     => __( 'Show Result Count', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'paginate' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->register_query_controls();

		parent::register_controls();
	}

	/**
	 * Get shortcode object
	 *
	 * @param array $settings settings of the widget.
	 */
	protected function get_shortcode_object( $settings ) {
		if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';
			return new Current_Query_Renderer( $settings, $type );
		}
		$type = 'products';
		return new Products_Renderer( $settings, $type );
	}

	/**
	 * Render
	 */
	protected function render() {
		if ( WC()->session ) {
			wc_print_notices();
		}

		// For Products_Renderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		$settings = $this->get_settings();

		$shortcode = $this->get_shortcode_object( $settings );

		$content = $shortcode->get_content();

		if ( $content ) {
			echo $content; //phpcs:ignore
		} elseif ( $this->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>'; //phpcs:ignore
		}
	}

	/**
	 * Render Plain Content
	 */
	public function render_plain_content() {}
}
