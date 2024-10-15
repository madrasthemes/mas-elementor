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
use MASElementor\Modules\CarouselAttributes\Traits\Button_Widget_Trait;
use MASElementor\Modules\CarouselAttributes\Traits\Pagination_Trait;
use Elementor\Plugin;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Products
 */
class Products extends Products_Base {

	use Button_Widget_Trait;
	use Pagination_Trait;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-products';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Products', 'mas-addons-for-elementor' );
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
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elements' );
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mas-shop-view-switcher' );
	}

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'mas-shop-view-switcher' );
	}

	/**
	 * Called on import to override.
	 *
	 * @param array $element The element being imported.
	 */
	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'product';
		}

		return $element;
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
				'label' => __( 'Query', 'mas-addons-for-elementor' ),
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
							'current_query'    => __( 'Current Query', 'mas-addons-for-elementor' ),
							'product'          => __( 'Latest Products', 'mas-addons-for-elementor' ),
							'sale'             => __( 'Sale', 'mas-addons-for-elementor' ),
							'featured'         => __( 'Featured', 'mas-addons-for-elementor' ),
							'by_id'            => _x( 'Manual Selection', 'Posts Query Control', 'mas-addons-for-elementor' ),
							'related_products' => esc_html__( 'Related Products', 'mas-addons-for-elementor' ),
							'upsells'          => esc_html__( 'Upsells', 'mas-addons-for-elementor' ),
							'cross_sells'      => esc_html__( 'Cross-Sells', 'mas-addons-for-elementor' ),
							'recently_viewed'  => esc_html__( 'Recently Viewed', 'mas-addons-for-elementor' ),
						),
					),
					'orderby'   => array(
						'default' => 'date',
						'options' => array(
							'date'       => __( 'Date', 'mas-addons-for-elementor' ),
							'title'      => __( 'Title', 'mas-addons-for-elementor' ),
							'price'      => __( 'Price', 'mas-addons-for-elementor' ),
							'popularity' => __( 'Popularity', 'mas-addons-for-elementor' ),
							'rating'     => __( 'Rating', 'mas-addons-for-elementor' ),
							'rand'       => __( 'Random', 'mas-addons-for-elementor' ),
							'menu_order' => __( 'Menu Order', 'mas-addons-for-elementor' ),
						),
					),
					'exclude'   => array(
						'options' => array(
							'current_post'     => __( 'Current Post', 'mas-addons-for-elementor' ),
							'manual_selection' => __( 'Manual Selection', 'mas-addons-for-elementor' ),
							'terms'            => __( 'Term', 'mas-addons-for-elementor' ),
						),
					),
					'include'   => array(
						'options' => array(
							'terms' => __( 'Term', 'mas-addons-for-elementor' ),
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
				'label' => __( 'Content', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'template_options',
			array(
				'label'       => __( 'Select Template By', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'id',
				'options'     => array(
					'id' => esc_html__( 'ID', 'mas-addons-for-elementor' ),
					'slug'  => esc_html__( 'Slug', 'mas-addons-for-elementor' ),
				),
			)
		);

		$templates = function_exists( 'mas_template_options' ) ? mas_template_options() : array();
		$this->add_control(
			'select_template',
			array(
				'label'     => esc_html__( 'MAS Post Item', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $templates,
				'condition' => array(
					'_skin' => 'mas-products-skin',
					'template_options' => 'id',
				),
			)
		);

		$slug_options = function_exists( 'mas_template_slug_options' ) ? mas_template_slug_options() : array();
		$this->add_control(
			'slug_select_template',
			array(
				'label'     => esc_html__( 'MAS Post Item', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $slug_options,
				'condition' => array(
					'_skin' => 'mas-products-skin',
					'template_options' => 'slug',
				),
			)
		);

		$this->add_control(
			'mas_products_class',
			array(
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'wc-products',
				'prefix_class' => 'mas-products-grid mas-elementor-',
			)
		);

		$this->add_control(
			'enable_responsive_column',
			array(
				'label'     => __( 'Repeat style column', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'enable_carousel!' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'responsive_columns',
			array(
				'label'                => esc_html__( 'Columns', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SELECT,
				'options'              => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
				),
				'selectors'            => array(
					'{{WRAPPER}} .mas-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr))',
				),
				'condition'            => array(
					'enable_carousel!' => 'yes',
					'enable_responsive_column' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'               => __( 'Columns', 'mas-addons-for-elementor' ),
				'type'                => Controls_Manager::NUMBER,
				'prefix_class'        => 'mas-grid%s-',
				'min'                 => 1,
				'max'                 => 6,
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
				'condition'           => array(
					'enable_carousel!' => 'yes',
					'enable_responsive_column!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rows',
			array(
				'label'       => __( 'Rows', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
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
			'swiper_posts_per_page',
			array(
				'label'       => __( 'Posts Per Page', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 6,
				'render_type' => 'template',
				'condition'   => array(
					'enable_carousel' => 'yes',
				),
			)
		);

		$this->add_control(
			'paginate',
			array(
				'label'     => __( 'Pagination', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'enable_carousel!' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_shop_control_bar',
			array(
				'label'     => __( 'Enable shop control bar', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'enable_carousel!' => 'yes',
					'allow_order!'     => 'yes',
					'paginate'         => 'yes',
				),
			)
		);

		$this->add_control(
			'pag_mid_size',
			array(
				'label'     => __( 'Pagination Mid Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'condition' => array(
					'enable_carousel!' => 'yes',
					'paginate'         => 'yes',
				),
			)
		);

		$this->add_control(
			'pag_end_size',
			array(
				'label'     => __( 'Pagination End Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'condition' => array(
					'enable_carousel!' => 'yes',
					'paginate'         => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_prev_next',
			array(
				'label'     => __( 'Enable Previous Next', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'enable_carousel!' => 'yes',
					'paginate'         => 'yes',
				),
			)
		);

		$this->add_control(
			'allow_order',
			array(
				'label'     => __( 'Allow Order', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'paginate'                 => 'yes',
					'enable_carousel!'         => 'yes',
					'enable_shop_control_bar!' => 'yes',
				),
			)
		);

		$this->add_control(
			'wc_notice_frontpage',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Ordering is not available if this widget is placed in your front page. Visible on frontend only.', 'mas-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'paginate'         => 'yes',
					'allow_order'      => 'yes',
					'enable_carousel!' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_result_count',
			array(
				'label'     => __( 'Show Result Count', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'paginate'         => 'yes',
					'enable_carousel!' => 'yes',
				),
			)
		);

		$this->add_control(
			'result_count_position',
			array(
				'label'       => __( 'Result Count Position', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Display result count before or after product', 'mas-addons-for-elementor' ),
				'default'     => 'before',
				'options'     => array(
					'before' => esc_html__( 'Before', 'mas-addons-for-elementor' ),
					'after'  => esc_html__( 'After', 'mas-addons-for-elementor' ),
				),
				'condition'   => array(
					'paginate'          => 'yes',
					'enable_carousel!'  => 'yes',
					'show_result_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_carousel',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Carousel', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'condition' => array(
					'_skin' => 'mas-products-skin',
				),
			)
		);

		$this->end_controls_section();

		$this->register_query_controls();
		$this->register_carousel_attributes_controls();
		$this->register_carousel_attributes_style_controls();

		$this->start_controls_section(
			'result_count_style_section',
			array(
				'label'     => esc_html__( 'Result Count', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginate'          => 'yes',
					'enable_carousel!'  => 'yes',
					'show_result_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'result_count_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-result-count' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'result_count_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .woocommerce-result-count',
			)
		);

		$this->add_responsive_control(
			'result_count_style_margin_option',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-result-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'result_count_style_padding_option',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-result-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'shop_control_bar_bottom_flex_controls',
			array(
				'label'     => esc_html__( 'Pagination and Result Count', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'paginate'              => 'yes',
					'enable_carousel!'      => 'yes',
					'show_result_count'     => 'yes',
					'result_count_position' => 'after',
				),
			)
		);

		$this->flex_controls( 'shop_control_bar_bottom', '{{WRAPPER}} .mas-shop-control-bar-bottom' );

		$this->end_controls_section();

		parent::register_controls();
	}

	/**
	 * Add carousel controls to the column element.
	 */
	public function register_carousel_attributes_style_controls() {

		$this->start_controls_section(
			'section_mas_product_style_controls',
			array(
				'label' => __( 'Swiper Style', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'mas_product_carousel_padding_option',
			array(
				'label'      => esc_html__( 'Swiper Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mas_product_slide_padding_option',
			array(
				'label'      => esc_html__( 'Swiper Slide Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mas_product_carousel_swiper_width',
			array(
				'label'      => esc_html__( 'Swiper Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper' => 'width: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mas_section_advanced',
			array(
				'label' => esc_html__( 'Advanced', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'nothing_found_message',
			array(
				'label'   => esc_html__( 'Nothing Found Message', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'No Products Found', 'mas-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_nothing_found_style',
			array(
				'tab'       => Controls_Manager::TAB_STYLE,
				'label'     => esc_html__( 'Nothing Found Message', 'mas-addons-for-elementor' ),
				'condition' => array(
					'nothing_found_message!' => '',
				),
			)
		);

		$this->add_control(
			'nothing_found_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-products-nothing-found' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nothing_found_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .mas-elementor-products-nothing-found',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'nothing_found_border',
				'selector'       => '{{WRAPPER}} .mas-elementor-products-nothing-found',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#d4d9dd',
					),
				),
			)
		);

		$this->add_control(
			'nothing_found_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-products-nothing-found' => 'border-radius: {{SIZE}}{{UNIT}}',
				),

			)
		);

		$this->add_control(
			'nothing_found_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'default'   => '##f9fafa',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-products-nothing-found' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'mas_product_nothing_found_margin_option',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-products-nothing-found' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mas_product_nothing_found_padding_option',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-products-nothing-found' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Add carousel controls to the column element.
	 */
	public function register_carousel_attributes_controls() {

		$this->start_injection(
			array(
				'at' => 'before',
				'of' => 'section_query',
			)
		);
		$this->start_controls_section(
			'section_carousel_attributes',
			array(
				'label'     => __( 'Carousel', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array( 'enable_carousel' => 'yes' ),
			)
		);

		$this->add_control(
			'carousel_effect',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => esc_html__( 'Effect', 'mas-addons-for-elementor' ),
				'default'            => 'slide',
				'options'            => array(
					''      => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'slide' => esc_html__( 'Slide', 'mas-addons-for-elementor' ),
					'fade'  => esc_html__( 'Fade', 'mas-addons-for-elementor' ),
				),
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'enable_grid',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Grid', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'condition' => array(
					'enable_carousel' => 'yes',
					'carousel_effect' => 'slide',
				),
			)
		);

		$carousel_rows = array(
			'type'               => Controls_Manager::NUMBER,
			'label'              => esc_html__( 'Rows', 'mas-addons-for-elementor' ),
			'min'                => 1,
			'max'                => 10,
			'default'            => 1,
			'condition'          => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
				'enable_grid'     => 'yes',
			),
			'frontend_available' => true,
		);

		// TODO: Once Core 3.4.0 is out, get the active devices using Breakpoints/Manager::get_active_devices_list().
		$active_breakpoint_instances = Plugin::$instance->breakpoints->get_active_breakpoints();
		// Devices need to be ordered from largest to smallest.
		$active_devices = array_reverse( array_keys( $active_breakpoint_instances ) );

		$slides_per_view = array(
			'type'               => Controls_Manager::NUMBER,
			'label'              => esc_html__( 'Slides Per View', 'mas-addons-for-elementor' ),
			'min'                => 1,
			'max'                => 10,
			'default'            => 1,
			'condition'          => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
			),
			'frontend_available' => true,
		);

		$slides_to_scroll = array(
			'type'               => Controls_Manager::NUMBER,
			'label'              => esc_html__( 'Slides To Scroll', 'mas-addons-for-elementor' ),
			'min'                => 1,
			'max'                => 10,
			'default'            => 1,
			'condition'          => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
			),
			'default'            => 1,
			'frontend_available' => true,
		);

		$space_between = array(
			'type'        => Controls_Manager::NUMBER,
			'label'       => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
			'description' => esc_html__( 'Set Space between each Slides', 'mas-addons-for-elementor' ),
			'min'         => 0,
			'max'         => 100,
			'default'     => 8,
			'condition'   => array(
				'carousel_effect'      => 'slide',
				'enable_carousel'      => 'yes',
				'enable_space_between' => 'yes',
			),
		);

		foreach ( $active_devices as $active_device ) {
			$space_between[ $active_device . '_default' ]    = 8;
			$slides_per_view[ $active_device . '_default' ]  = 1;
			$slides_to_scroll[ $active_device . '_default' ] = 1;
			$carousel_rows[ $active_device . '_default' ]    = 1;
		}

		$this->add_responsive_control(
			'carousel_rows',
			$carousel_rows
		);

		$this->add_responsive_control(
			'slides_per_view',
			$slides_per_view
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			$slides_to_scroll
		);

		$this->add_control(
			'enable_space_between',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Space Between', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition' => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'space_between',
			$space_between
		);

		$this->add_control(
			'center_slides',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Center Slides', 'mas-addons-for-elementor' ),
				'default'            => 'no',
				'label_off'          => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'           => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'          => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
					'enable_grid!'    => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'mas_products_swiper_height',
			array(
				'label'                => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'auto',
				'options'              => array(
					'default' => 'Default',
					'auto'    => 'Auto',
				),
				'selectors_dictionary' => array(
					'default' => '100%',
					'auto'    => 'auto',
				),
				'selectors'            => array(
					'{{WRAPPER}} .swiper-slide' => 'height: {{VALUE}};',
				),
				'condition'            => array(
					'enable_carousel' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Arrows', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition' => array(
					'enable_carousel'     => 'yes',
					'show_custom_arrows!' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_inactive_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Hide Inactive Arrow', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'    => array(
					'enable_carousel'     => 'yes',
					'show_arrows'         => 'yes',
					'show_custom_arrows!' => 'yes',
				),
				'return_value' => 'hide',
				'prefix_class' => 'mas-swiper-inactive-arrows-',
			)
		);

		$this->add_responsive_control(
			'hide_responsive_arrows',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Arrow Responsive', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'    => array(
					'enable_carousel'     => 'yes',
					'show_arrows'         => 'yes',
					'show_custom_arrows!' => 'yes',
				),
				'return_value' => 'hide',
				'prefix_class' => 'mas-swiper-carousel--arrows%s-',
			)
		);

		$this->add_control(
			'show_custom_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Custom Arrows', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_arrows!'    => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_prev_id',
			array(
				'label'     => esc_html__( 'Previous Arrow ID', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_next_id',
			array(
				'label'     => __( 'Next Arrow ID', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'step'               => 100,
				'render_type'        => 'none',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => esc_html__( 'Autoplay', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'separator'          => 'before',
				'render_type'        => 'none',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => esc_html__( 'Autoplay Speed', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay'        => 'yes',
					'enable_carousel' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'              => esc_html__( 'Infinite Loop', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => esc_html__( 'Pause on Hover', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'autoplay'        => 'yes',
					'enable_carousel' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'show_pagination',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Enable Pagination', 'mas-addons-for-elementor' ),
				'default'            => 'yes',
				'label_off'          => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'           => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'hide_responsive_pagination',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Pagination Responsive', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'    => array(
					'enable_carousel' => 'yes',
					'show_pagination' => 'yes',
				),
				'return_value' => 'hide',
				'prefix_class' => 'mas-swiper-carousel--pagination%s-',
			)
		);

		$this->add_control(
			'pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bullets',
				'options'   => array(
					'bullets'     => esc_html__( 'Dots', 'mas-addons-for-elementor' ),
					'fraction'    => esc_html__( 'Fraction', 'mas-addons-for-elementor' ),
					'progressbar' => esc_html__( 'Progress', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_pagination' => 'yes',
				),
			)
		);

		$this->end_controls_section();
		$this->end_injection();

		$args = array(
			'concat'        => '',
			'button_concat' => '',
		);

		$this->register_pagination_style_controls( $this, $args );

		$this->start_controls_section(
			'masposts_swiper_button',
			array(
				'label'     => esc_html__( 'Button', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_arrows'     => 'yes',
				),
			)
		);

			$this->register_button_content_controls( $this, $args );

		$this->end_controls_section();

		$this->register_button_style_controls( $this, $args );

		$this->register_swiper_arrow_spacing_controls( $this, $args );

		$this->start_controls_section(
			'section_shop_control_bar__style',
			array(
				'label'     => __( 'Shop Control Bar', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginate'                => 'yes',
					'enable_carousel!'        => 'yes',
					'enable_shop_control_bar' => 'yes',
				),
			)
		);

		$this->register_shop_control_bar_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_shop_view_switcher_style',
			array(
				'label'     => __( 'Shop view switcher', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginate'                => 'yes',
					'enable_carousel!'        => 'yes',
					'enable_shop_control_bar' => 'yes',
				),
			)
		);

		$this->register_shop_view_switcher_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_shop_control_bar_advanced_pagination_style',
			array(
				'label'     => __( 'Shop Control Advanced Pagination', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginate'                => 'yes',
					'enable_carousel!'        => 'yes',
					'enable_shop_control_bar' => 'yes',
				),
			)
		);

		$this->register_shop_control_bar_advanced_pagination_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_shop_control_bar_ordering_style',
			array(
				'label'     => __( 'Shop Control Ordering', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginate'                => 'yes',
					'enable_carousel!'        => 'yes',
					'enable_shop_control_bar' => 'yes',
				),
			)
		);

		$this->register_shop_control_bar_ordering_style_controls();

		$this->end_controls_section();

	}

	/**
	 * Add shop control bar controls.
	 */
	public function register_shop_control_bar_style_controls() {
		$wrapper = '{{WRAPPER}} .mas-shop-control-bar';
		$this->flex_controls( 'shop_control_bar_', $wrapper );

		$this->add_control(
			'shop_control_bar_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$wrapper => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'shop_control_bar_border',
				'selector' => $wrapper,
			),
		);

		$this->add_control(
			'shop_control_bar_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					$wrapper => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar__padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'default'    => array(
					'top'      => 2.800,
					'right'    => 20.006,
					'bottom'   => 2.800,
					'left'     => 20.006,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					$wrapper => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar__margin',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					$wrapper => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	/**
	 * Add shop view switcher controls.
	 */
	public function register_shop_view_switcher_style_controls() {
		$wrapper = '{{WRAPPER}} .shop-view-switcher';
		$this->flex_controls( 'shop_view_switcher_', $wrapper );

		$this->add_control(
			'shop_view_switcher_icons_color',
			array(
				'label'     => __( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$wrapper . ' a i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'shop_view_switcher_icons_active_color',
			array(
				'label'     => __( 'Icon Active Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$wrapper . ' a.active i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'shop_view_switcher_icons_hover_color',
			array(
				'label'     => __( 'Icon Hover Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$wrapper . ' a:hover i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'shop_view_switcher_icons_size',
			array(
				'label'      => __( 'Icons Size', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'size' => 18,
				),
				'selectors'  => array(
					$wrapper . ' a i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'shop_view_switcher_icons_line_height',
			array(
				'label'      => __( 'Icons Line Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'size' => 40.329,
				),
				'selectors'  => array(
					$wrapper . ' a' => 'line-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'shop_view_switcher__padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					$wrapper => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_view_switcher__margin',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					$wrapper => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Add shop control bar controls.
	 */
	public function register_shop_control_bar_advanced_pagination_style_controls() {
		$wrapper = '{{WRAPPER}} .mas-elementor-advanced-pagination';
		$this->flex_controls( 'shop_control_bar_advanced_pagination_', $wrapper );

		$this->add_control(
			'shop_control_bar_advanced_pagination__padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					$wrapper => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination__margin',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					$wrapper => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination_icons_heading',
			array(
				'label'     => __( 'Page Icons', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'shop_control_bar_advanced_pagination_icons_typo',
				'selector' => $wrapper . ' .prev.page-numbers, ' . $wrapper . ' .next.page-numbers',
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination_icons_color',
			array(
				'label'     => __( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$wrapper . ' .prev.page-numbers' => 'color: {{VALUE}}',
					$wrapper . ' .next.page-numbers' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination_icons_hover_color',
			array(
				'label'     => __( 'Icon Hover Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$wrapper . ' .prev.page-numbers:hover' => 'color: {{VALUE}}',
					$wrapper . ' .next.page-numbers:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination_input_heading',
			array(
				'label'     => __( 'Input', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'shop_control_bar_advanced_pagination_input_typo',
				'selector' => $wrapper . ' .form-adv-pagination input',
			)
		);

		$this->add_responsive_control(
			'shop_control_bar_advanced_pagination_input_width',
			array(
				'label'      => __( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'size' => 18,
				),
				'selectors'  => array(
					$wrapper . ' .form-adv-pagination input' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination_input_color',
			array(
				'label'     => __( 'Input Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$wrapper . ' .form-adv-pagination input' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'shop_control_bar_advanced_pagination_input_background',
				'label'    => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'selector' => $wrapper . ' .form-adv-pagination input',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'shop_control_bar_advanced_pagination_input_border',
				'selector' => $wrapper . ' .form-adv-pagination input',
			),
		);

		$this->add_responsive_control(
			'shop_control_bar_advanced_pagination_input_text_align',
			array(
				'label'     => esc_html__( 'Text Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					$wrapper . ' .form-adv-pagination input' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination_input_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					$wrapper . ' .form-adv-pagination input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination_input_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					$wrapper . ' .form-adv-pagination input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination_input_total_heading',
			array(
				'label'     => __( 'Total Text', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'shop_control_bar_advanced_pagination_input_total_color',
			array(
				'label'     => __( 'Total Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$wrapper => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'shop_control_bar_advanced_pagination_input_total_typo',
				'selector' => $wrapper,
			)
		);
	}

	/**
	 * Add shop view switcher controls.
	 */
	public function register_shop_control_bar_ordering_style_controls() {

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'shop_control_bar_ordering_typo',
				'selector' => '{{WRAPPER}} .mas-shop-control-bar .woocommerce-ordering select',
			)
		);

		$this->add_control(
			'shop_control_bar_ordering_color',
			array(
				'label'     => __( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-shop-control-bar .woocommerce-ordering select' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'shop_control_bar_ordering_background',
				'label'    => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .mas-shop-control-bar .woocommerce-ordering select',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'shop_control_bar_ordering_border',
				'selector' => '{{WRAPPER}} .mas-shop-control-bar .woocommerce-ordering select',
			),
		);

		$this->add_control(
			'shop_control_bar_ordering_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-shop-control-bar .woocommerce-ordering select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_ordering_wrapper_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Wrapper Padding', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-shop-control-bar .woocommerce-ordering' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_ordering_wrapper_margin',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Wrapper Margin', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-shop-control-bar .woocommerce-ordering' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'shop_control_bar_ordering_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-shop-control-bar .woocommerce-ordering select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Render Plain Content
	 */
	public function render_plain_content() {}
}
