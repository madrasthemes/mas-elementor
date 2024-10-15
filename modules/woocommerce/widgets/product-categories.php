<?php
/**
 * The Categories Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use Elementor\Group_Control_Box_Shadow;
use MASElementor\Modules\CarouselAttributes\Traits\Button_Widget_Trait;
use MASElementor\Modules\CarouselAttributes\Traits\Pagination_Trait;
use MASElementor\Modules\CarouselAttributes\Traits\Swiper_Options_Trait;
use ELementor\Plugin;
use Elementor\Icons_Manager;
use MASElementor\Modules\QueryControl\Module as Query_Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Categories
 */
class Product_Categories extends Base_Widget {

	use Button_Widget_Trait;
	use Pagination_Trait;
	use Swiper_Options_Trait;

	/**
	 * Has content template
	 *
	 * @var _has_template_content
	 */
	protected $_has_template_content = false; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-product-categories-sub';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Product Categories & Sub', 'mas-addons-for-elementor' );
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
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-categories';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce-elements', 'shop', 'store', 'categories', 'product' );
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array(
			'woocommerce-elements',
		);
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->register_carousel_attributes_controls();
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => esc_html__( 'Categories Count', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
			)
		);

		$this->add_control(
			'sub_cat_count',
			array(
				'label'   => esc_html__( 'SubCategories Count', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
			)
		);

		$this->add_control(
			'select_image_icon',
			array(
				'label'   => esc_html__( 'Select Image / Icon', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => array(
					'none'  => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'image' => esc_html__( 'Image', 'mas-addons-for-elementor' ),
					'icon'  => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'image_option',
			array(
				'label'     => esc_html__( 'Select Thumbnail / Cover', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => array(
					'thumbnail' => esc_html__( 'Thumbnail', 'mas-addons-for-elementor' ),
					'cover'     => esc_html__( 'Cover', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'select_image_icon' => 'image',
				),
			)
		);

		$this->add_control(
			'image_sizes',
			array(
				'label'     => esc_html__( 'Enter Image Sizes', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'thumbnail',
				'condition' => array(
					'select_image_icon' => 'image',
				),
			)
		);

		$this->add_control(
			'cover_image_field_name',
			array(
				'label'     => esc_html__( 'Cover Image Field Name', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'product_cat_cover_image',
				'condition' => array(
					'image_option' => 'cover',
				),
			)
		);

		$this->add_control(
			'enable_placeholder_image',
			array(
				'label'     => esc_html__( 'Show Image Placeholder', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => 'Hide',
				'label_off' => 'Show',
				'condition' => array(
					'select_image_icon' => 'image',
				),
			)
		);

		$this->add_control(
			'icon_term_name',
			array(
				'label'       => esc_html__( 'Icon Term Name', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'By default the term meta we have used is "icon", you can create your own term meta for product category', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'icon',
				'condition'   => array(
					'select_image_icon' => 'icon',
				),
			)
		);

		$this->register_enable_carousel_control( $this );

		$this->add_control(
			'see_more',
			array(
				'label'     => esc_html__( 'See More', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'See More',
				'condition' => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->add_control(
			'enable_see_more_all',
			array(
				'label'       => esc_html__( 'Enable See More', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Enable See More text for all categories', 'mas-addons-for-elementor' ),
				'default'     => 'no',
				'label_on'    => 'Hide',
				'label_off'   => 'Show',
				'condition'   => array(
					'see_more!' => '',
				),
			)
		);

		$this->add_control(
			'see_more_position',
			array(
				'label'       => esc_html__( 'See More Position', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'out',
				'description' => esc_html__( 'Positioning the see more text inside/outside sub-category', 'mas-addons-for-elementor' ),
				'options'     => array(
					'out' => esc_html__( 'Outside', 'mas-addons-for-elementor' ),
					'in'  => esc_html__( 'Inside', 'mas-addons-for-elementor' ),
				),
				'condition'   => array(
					'see_more!' => '',
				),
			)
		);

		$this->add_control(
			'enable_cat_link_button',
			array(
				'label'     => esc_html__( 'Show Category Link Button', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => 'Hide',
				'label_off' => 'Show',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter',
			array(
				'label' => esc_html__( 'Query', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_child_cat',
			array(
				'label'     => esc_html__( 'Show Child Categories', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => 'Hide',
				'label_off' => 'Show',
			)
		);

		$this->add_control(
			'show_cat_product_count',
			array(
				'label'       => esc_html__( 'Show Cat product count', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Show product count for Categories', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'label_on'    => 'Hide',
				'label_off'   => 'Show',
			)
		);

		$this->add_control(
			'show_subcat_product_count',
			array(
				'label'       => esc_html__( 'Show SubCat product count', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Show product count for Sub categories', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'label_on'    => 'Hide',
				'label_off'   => 'Show',
			)
		);

		$this->add_control(
			'product_count_singular_pattern',
			array(
				'label'       => esc_html__( 'Product Count Singular Pattern', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '%s product',
				'description' => '%s displays the count',
				'default'     => '',
			)
		);

		$this->add_control(
			'product_count_plural_pattern',
			array(
				'label'       => esc_html__( 'Product Count Singular Pattern', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '%s products',
				'description' => '%s displays the count',
				'default'     => '',
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'     => esc_html__( 'Hide Empty', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'label_on'  => 'Hide',
				'label_off' => 'Show',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'name'        => esc_html__( 'Name', 'mas-addons-for-elementor' ),
					'slug'        => esc_html__( 'Slug', 'mas-addons-for-elementor' ),
					'description' => esc_html__( 'Description', 'mas-addons-for-elementor' ),
					'count'       => esc_html__( 'Count', 'mas-addons-for-elementor' ),
					'menu_order'  => esc_html__( 'Menu Order', 'mas-addons-for-elementor' ),
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

		$tabs_wrapper    = 'select_terms_query_args';
		$include_wrapper = 'select_terms_query_include';

		$this->add_control(
			'select_terms',
			array(
				'label'        => esc_html__( 'Term', 'mas-addons-for-elementor' ),
				'description'  => esc_html__( 'Terms are items in a taxonomy. The available taxonomies are: Categories', 'mas-addons-for-elementor' ),
				'type'         => Query_Module::QUERY_CONTROL_ID,
				'options'      => array(),
				'label_block'  => true,
				'multiple'     => true,
				'autocomplete' => array(
					'object'  => Query_Module::QUERY_OBJECT_CPT_TAX,
					'display' => 'detailed',
				),
				'group_prefix' => 'select_terms',
				// 'condition'    => array(
				// 'include'    => 'terms',
				// 'post_type!' => array(
				// 'by_id',
				// 'current_query',
				// ),
				// ),
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab'    => $include_wrapper,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_categories_image_style',
			array(
				'label'     => esc_html__( 'Categories Image', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'select_image_icon' => 'image',
				),
			)
		);

		$this->add_images_controls();

		$this->add_control(
			'heading_image_style',
			array(
				'label'     => esc_html__( 'Image', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'cat_image_align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
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
					'{{WRAPPER}} .image-wrap' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->register_transform_controls();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} a > img, {{WRAPPER}} .image-wrap > img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} a > img, {{WRAPPER}} .image-wrap > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'image_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_image_wrap_style',
			array(
				'label'     => esc_html__( 'Image Wrap', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'image_wrap_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap .image-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_wrap_border',
				'selector' => '{{WRAPPER}} .image-wrap',
			)
		);

		$this->add_responsive_control(
			'image_wrap_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_subcat_styles',
			array(
				'label' => esc_html__( 'Cat & Subs', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => esc_html__( 'Category', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'cat_subs_height',
			array(
				'label'      => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .categories' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'cat_subs_border',
				'selector' => '{{WRAPPER}} .categories',
			)
		);

		$this->add_responsive_control(
			'cat_subs_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .categories' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->position_controls( 'cat_subs', ' .categories' );

		$this->add_responsive_control(
			'cat_d_block',
			array(
				'label'       => esc_html__( 'Categories display type', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => 'inline',
				'options'     => array(
					'block'  => array(
						'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'flex'   => array(
						'title' => esc_html__( 'Flex', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-exchange',
					),
					'inline' => array(
						'title' => esc_html__( 'Inline', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-nowrap',
					),

				),
				'selectors'   => array(
					'{{WRAPPER}} .categories a' => 'display: {{VALUE}};',
				),
				'separator'   => 'after',
			)
		);

		$this->start_controls_tabs( 'category_title_tab' );

			$this->start_controls_tab(
				'cat_title_normal',
				array(
					'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				)
			);

				$this->add_control(
					'cat_title_heading',
					array(
						'label' => esc_html__( 'Category', 'mas-addons-for-elementor' ),
						'type'  => Controls_Manager::HEADING,
					)
				);

				$this->add_control(
					'title_color',
					array(
						'label'     => esc_html__( 'Category Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							// '{{WRAPPER}} .categories a' => 'color: {{VALUE}}',
							'{{WRAPPER}} .categories .cat-name' => 'color: {{VALUE}}',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'     => 'title_typography',
						'global'   => array(
							'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
						),
						'selector' => '{{WRAPPER}} .categories .cat-name',
					)
				);

				// Product Count Styling.
				$this->add_control(
					'cat_prod_count_heading',
					array(
						'label'     => esc_html__( 'Product Count', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::HEADING,
						'condition' => array(
							'show_cat_product_count' => 'yes',
						),
					)
				);

				$this->add_control(
					'cat_prod_count_color',
					array(
						'label'     => esc_html__( 'Count Hover Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							'{{WRAPPER}} .categories .cat-prod-count' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'show_cat_product_count' => 'yes',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'      => 'cat_prod_count_typography',
						'global'    => array(
							'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
						),
						'selector'  => '{{WRAPPER}} .categories .cat-prod-count',
						'condition' => array(
							'show_cat_product_count' => 'yes',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'cat_title_hover',
				array(
					'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				)
			);
				$this->add_control(
					'cat_title_hover_heading',
					array(
						'label' => esc_html__( 'Category', 'mas-addons-for-elementor' ),
						'type'  => Controls_Manager::HEADING,
					)
				);

				$this->add_control(
					'title_hover_color',
					array(
						'label'     => esc_html__( 'Category Hover Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							// '{{WRAPPER}} .categories a:hover' => 'color: {{VALUE}}',
							'{{WRAPPER}} .categories .cat-name:hover' => 'color: {{VALUE}}',
							'{{WRAPPER}} a.img-cat-wrap:hover .cat-name' => 'color: {{VALUE}}',
							// '{{WRAPPER}} .cat-wrapper:hover .categories .cat-name' => 'color: {{VALUE}}',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'     => 'title_typography_hover',
						'global'   => array(
							'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
						),
						'selector' => '{{WRAPPER}} .categories .cat-name:hover, {{WRAPPER}} a.img-cat-wrap:hover .cat-name',
					)
				);

				// Product Count Styling.
				$this->add_control(
					'cat_prod_count_hover_heading',
					array(
						'label'     => esc_html__( 'Product Count', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::HEADING,
						'condition' => array(
							'show_cat_product_count' => 'yes',
						),
					)
				);

				$this->add_control(
					'cat_prod_count_hover_color',
					array(
						'label'     => esc_html__( 'Count Hover Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							'{{WRAPPER}} .categories .cat-prod-count:hover' => 'color: {{VALUE}}',
							'{{WRAPPER}} a.img-cat-wrap:hover .cat-prod-count' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'show_cat_product_count' => 'yes',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'      => 'cat_prod_count_typography_hover',
						'global'    => array(
							'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
						),
						'selector'  => '{{WRAPPER}} .categories .cat-prod-count:hover, {{WRAPPER}} a.img-cat-wrap:hover .cat-prod-count',
						'condition' => array(
							'show_cat_product_count' => 'yes',
						),
					)
				);

			$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'cat_title_wrapper_width',
			array(
				'label'      => esc_html__( 'Category Text Wrapper Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .categories' => 'width: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'cat_title_width',
			array(
				'label'      => esc_html__( 'Category Text Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .cat-name' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cat_title_height',
			array(
				'label'      => esc_html__( 'Category Text Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .cat-name' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cat_title_spacing',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'cat_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .categories' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'category_title_overflow',
			array(
				'label'     => esc_html__( 'Overflow', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hidden',
				'options'   => array(
					''       => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'hidden' => esc_html__( 'Hidden', 'mas-addons-for-elementor' ),
					'auto'   => esc_html__( 'Auto', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .cat-name' => 'overflow: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'category_title_align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
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
					'{{WRAPPER}} .cat-name' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_sub_cat_style',
			array(
				'label'     => esc_html__( 'Sub Category', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->add_responsive_control(
			'show_hide_sub_cat',
			array(
				'label'       => esc_html__( 'Show / Hide SubCategories', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'block' => array(
						'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'none'  => array(
						'title' => esc_html__( 'None', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),

				),
				'selectors'   => array(
					'{{WRAPPER}} .sub-categories' => 'display: {{VALUE}};',
				),
				'condition'   => array(
					'sub_cat_count!' => 0,
				),
				'separator'   => 'after',
			)
		);

		$this->add_responsive_control(
			'sub_cat_d_block',
			array(
				'label'       => esc_html__( 'SubCategories display type', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => 'inline',
				'options'     => array(
					'block'  => array(
						'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'flex'   => array(
						'title' => esc_html__( 'Flex', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-exchange',
					),
					'inline' => array(
						'title' => esc_html__( 'Inline', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-nowrap',
					),

				),
				'selectors'   => array(
					'{{WRAPPER}} .sub-category a' => 'display: {{VALUE}};',
				),
				'condition'   => array(
					'sub_cat_count!' => 0,
				),
				'separator'   => 'after',
			)
		);

		$this->start_controls_tabs( 'subcategory_title_tab' );

			$this->start_controls_tab(
				'subcat_title_normal',
				array(
					'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
					'condition' => array(
						'sub_cat_count!' => 0,
					),
				)
			);

				$this->add_control(
					'sub_cat_color',
					array(
						'label'     => esc_html__( 'Sub Category Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							'{{WRAPPER}} .sub-categories .sub-category a' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'sub_cat_count!' => 0,
						),
					)
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'      => 'sub_cat_typography',
						'global'    => array(
							'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
						),
						'selector'  => '{{WRAPPER}} .sub-categories .sub-category, {{WRAPPER}} .sub-categories .sub-category a',
						'condition' => array(
							'sub_cat_count!' => 0,
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'subcat_title_hover',
				array(
					'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
					'condition' => array(
						'sub_cat_count!' => 0,
					),
				)
			);
				$this->add_control(
					'sub_cat_hover_color',
					array(
						'label'     => esc_html__( 'Sub Category Hover Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							'{{WRAPPER}} .sub-categories .sub-category a:hover' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'sub_cat_count!' => 0,
						),
					)
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'      => 'sub_cat_hover_typography',
						'global'    => array(
							'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
						),
						'selector'  => '{{WRAPPER}} .sub-categories .sub-category:hover, {{WRAPPER}} .sub-categories .sub-category a:hover',
						'condition' => array(
							'sub_cat_count!' => 0,
						),
					)
				);

			$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_categories_see_all_style',
			array(
				'label'     => esc_html__( 'See all', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'sub_cat_count!'    => 0,
					'see_more!'         => '',
					'see_more_position' => 'out',
				),
			)
		);

		$this->add_control(
			'heading_see_all_style',
			array(
				'label'     => esc_html__( 'See All', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'see_all_color',
			array(
				'label'     => esc_html__( 'See All Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .see-all' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'see_all_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .see-all',
			)
		);

		$this->add_responsive_control(
			'see_all_align',
			array(
				'label'     => esc_html__( 'See All Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
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
					'{{WRAPPER}} .see-all-wrapper' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'see_all_hide',
			array(
				'label'       => esc_html__( 'Show / Hide', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'block' => array(
						'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'none'  => array(
						'title' => esc_html__( 'None', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),

				),
				'selectors'   => array(
					'{{WRAPPER}} .see-all-wrapper' => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'see_all_border',
				'selector' => '{{WRAPPER}} .see-all-wrapper',
			)
		);

		$this->add_responsive_control(
			'see_all_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .see-all-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'see_all_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .see-all-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_style',
			array(
				'label' => esc_html__( 'All Categories', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'enable_card_link',
			array(
				'label'     => esc_html__( 'Enable Card Link', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => 'Enabled',
				'label_off' => 'Enable',
				'condition' => array(
					'sub_cat_count' => 0,
				),
			)
		);

		$this->add_control(
			'enable_image_link',
			array(
				'label'     => esc_html__( 'Enable Image Link', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => 'Enabled',
				'label_off' => 'Enable',
			)
		);

		$this->add_control(
			'categories_overflow',
			array(
				'label'     => esc_html__( 'Wrapper Overflow', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''       => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'hidden' => esc_html__( 'Hidden', 'mas-addons-for-elementor' ),
					'auto'   => esc_html__( 'Auto', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-categories-wrapper' => 'overflow: {{VALUE}}',
				),
			)
		);

		$this->flex_controls( '', '{{WRAPPER}} .mas-categories-wrapper' );

		$this->end_controls_section();

		$this->start_controls_section(
			'category_wrapper',
			array(
				'label' => esc_html__( 'Category', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'cat_flex_size',
			array(
				'label'                => esc_html__( 'Size', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => '',
				'options'              => array(
					'none'   => array(
						'title' => esc_html__( 'None', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'grow'   => array(
						'title' => esc_html__( 'Grow', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-grow',
					),
					'shrink' => array(
						'title' => esc_html__( 'Shrink', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-shrink',
					),
					'custom' => array(
						'title' => esc_html__( 'Custom', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ellipsis-v',
					),
				),
				'selectors_dictionary' => array(
					'grow'   => 'flex-grow: 1; flex-shrink: 0;',
					'shrink' => 'flex-grow: 0; flex-shrink: 1;',
					'custom' => '',
					'none'   => 'flex-grow: 0; flex-shrink: 0;',
				),
				'selectors'            => array(
					'{{SELECTOR}} .mas-categories-wrapper .cat-wrapper' => '{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'cat_flex_grow',
			array(
				'label'       => esc_html__( 'Flex Grow', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => array(
					'{{SELECTOR}} .mas-categories-wrapper .cat-wrapper' => 'flex-grow: {{VALUE}};',
				),
				'default'     => 1,
				'placeholder' => 1,
				'condition'   => array(
					'cat_flex_size' => 'custom',
				),
			)
		);

		$this->add_responsive_control(
			'cat_flex_shrink',
			array(
				'label'       => esc_html__( 'Flex Shrink', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => array(
					'{{SELECTOR}} .mas-categories-wrapper .cat-wrapper' => 'flex-shrink: {{VALUE}};',
				),
				'default'     => 1,
				'placeholder' => 1,
				'condition'   => array(
					'cat_flex_size' => 'custom',
				),
			)
		);

		$this->add_responsive_control(
			'cat_size',
			array(
				'label'          => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'default'        => array(
					'size' => 500,
				),
				'tablet_default' => array(
					'size' => 500,
				),
				'mobile_default' => array(
					'size' => 500,
				),
				'selectors'      => array(
					'{{WRAPPER}} .cat-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cat_height',
			array(
				'label'      => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'categories_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'categories_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'separator'  => 'after',
			)
		);

		$this->add_responsive_control(
			'cat_wrap_border_last_child',
			array(
				'label'      => esc_html__( 'Last Child Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'cat_wrap_border',
				'selector'  => '{{WRAPPER}} .cat-wrapper',
				'separator' => 'after',
			)
		);

		$this->add_responsive_control(
			'cat_wrap_wrap_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->flex_controls( 'category_wrap_', '{{WRAPPER}} .cat-wrapper' );

		$this->update_control(
			'category_wrap_categories_wrap_direction',
			array(
				'default' => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_image_wrapper',
			array(
				'label' => esc_html__( 'Image / Icon & Categories', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'img_icon_wrap_height',
			array(
				'label'      => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'img_icon_wrap_min_height',
			array(
				'label'      => esc_html__( 'Min Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'categories_img_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'categories_img_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->flex_controls( 'img_cat', '{{WRAPPER}} .img-cat-wrap' );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'img_icon_background',
				'types'          => array( 'classic', 'gradient', 'video', 'slideshow' ),
				'fields_options' => array(
					'background' => array(
						'frontend_available' => true,
					),
					'image'      => array(
						'background_lazyload' => array(
							'active' => true,
							'keys'   => array( 'background_image', 'url' ),
						),
					),
				),
				'selector'       => '{{WRAPPER}} .img-cat-wrap',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'img_icon_border',
				'selector'  => '{{WRAPPER}} .img-cat-wrap',
				'separator' => 'after',
			)
		);

		$this->add_responsive_control(
			'img_icon_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_subcat',
			array(
				'label' => esc_html__( 'Category & Sub Categories', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->flex_controls( 'cat_sub', '{{WRAPPER}} .categories' );

		$this->end_controls_section();

		$this->register_background_controls();

		$this->register_category_icon_style_controls();

		$this->start_controls_section(
			'cat_product_count',
			array(
				'label'     => esc_html__( 'Category & Product Count', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'show_cat_product_count' => 'yes',
				),
			)
		);

		$this->flex_controls( 'cat_prod_count', '{{WRAPPER}} .categories .cat-name-wrapper' );

		$this->end_controls_section();

		$this->start_controls_section(
			'subcat_product_count',
			array(
				'label'     => esc_html__( 'Sub Category & Product Count', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'show_subcat_product_count' => 'yes',
				),
			)
		);

		$this->flex_controls( 'subcat_prod_count', '{{WRAPPER}} .categories .sub-category' );

		$this->end_controls_section();

		$this->category_link_button_content_controls();
		$this->category_link_button_style_controls();

	}

	/**
	 * Register the category icon and wrapper style controls.
	 *
	 * @return void
	 */
	protected function register_category_icon_style_controls() {
		$this->start_controls_section(
			'section_categories_icon_style',
			array(
				'label'     => esc_html__( 'Categories Icon', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'select_image_icon' => 'icon',
				),
			)
		);

			$this->add_control(
				'heading_icon',
				array(
					'label' => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
					'type'  => Controls_Manager::HEADING,
				)
			);

			$this->add_responsive_control(
				'icon_font_size',
				array(
					'label'          => esc_html__( 'Font Size', 'mas-addons-for-elementor' ),
					'type'           => Controls_Manager::SLIDER,
					'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
					'range'          => array(
						'%'  => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'px' => array(
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						),
					),
					'default'        => array(
						'size' => 40,
						'unit' => 'px',
					),
					'tablet_default' => array(
						'size' => 40,
						'unit' => 'px',
					),
					'mobile_default' => array(
						'size' => 40,
						'unit' => 'px',
					),
					'selectors'      => array(
						'{{WRAPPER}} .mas-category-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_margin',
				array(
					'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .mas-category-icon i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'icon_color',
				array(
					'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mas-category-icon i' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'icon_hover_color',
				array(
					'label'     => esc_html__( 'Icon Hover Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mas-category-icon i:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .mas-categories-wrapper a:hover .mas-category-icon i' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'heading_icon_wrapper',
				array(
					'label'     => esc_html__( 'Icon Wrapper', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'icon_wrap_width',
				array(
					'label'          => esc_html__( 'Width', 'mas-addons-for-elementor' ),
					'type'           => Controls_Manager::SLIDER,
					'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
					'range'          => array(
						'%'  => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'px' => array(
							'min'  => 0,
							'max'  => 5000,
							'step' => 1,
						),
					),
					'default'        => array(
						'size' => 122,
					),
					'tablet_default' => array(
						'size' => 122,
					),
					'mobile_default' => array(
						'size' => 122,
					),
					'selectors'      => array(
						'{{WRAPPER}} .mas-category-icon' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_wrap_height',
				array(
					'label'          => esc_html__( 'Height', 'mas-addons-for-elementor' ),
					'type'           => Controls_Manager::SLIDER,
					'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
					'range'          => array(
						'%'  => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'px' => array(
							'min'  => 0,
							'max'  => 5000,
							'step' => 1,
						),
					),
					'default'        => array(
						'size' => 74,
					),
					'tablet_default' => array(
						'size' => 74,
					),
					'mobile_default' => array(
						'size' => 74,
					),
					'selectors'      => array(
						'{{WRAPPER}} .mas-category-icon' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'icon_wrap_border',
					'selector' => '{{WRAPPER}} .mas-category-icon',
				)
			);

			$this->add_responsive_control(
				'icon_wrap_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .mas-category-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
			);

			$this->add_responsive_control(
				'icon_wrap_margin',
				array(
					'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .mas-category-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'icon_wrap_bg_color',
				array(
					'label'     => esc_html__( 'Icon Wrapper BG Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mas-category-icon' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'icon_wrap_bg_hover_color',
				array(
					'label'     => esc_html__( 'Icon Wrapper BG hover Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mas-category-icon:hover' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .mas-categories-wrapper a:hover .mas-category-icon' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'icon_wrap_justify_content',
				array(
					'label'       => esc_html__( 'Justify Content', 'mas-addons-for-elementor' ),
					'type'        => Controls_Manager::CHOOSE,
					'label_block' => true,
					'default'     => 'center',
					'options'     => array(
						'flex-start'    => array(
							'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-start-h',
						),
						'center'        => array(
							'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-center-h',
						),
						'flex-end'      => array(
							'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-end-h',
						),
						'space-between' => array(
							'title' => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-space-between-h',
						),
						'space-around'  => array(
							'title' => esc_html__( 'Space Around', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-space-around-h',
						),
						'space-evenly'  => array(
							'title' => esc_html__( 'Space Evenly', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-space-evenly-h',
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .mas-category-icon' => 'display:flex;justify-content: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_wrap_align_items',
				array(
					'label'     => esc_html__( 'Align Items', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::CHOOSE,
					'default'   => 'flex-end',
					'options'   => array(
						'flex-start' => array(
							'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-align-start-v',
						),
						'center'     => array(
							'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-align-center-v',
						),
						'flex-end'   => array(
							'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-align-end-v',
						),
						'stretch'    => array(
							'title' => esc_html__( 'Stretch', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-align-stretch-v',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .mas-category-icon' => 'display:flex;align-items: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register the Container's background controls.
	 *
	 * @return void
	 */
	protected function register_background_controls() {
		$this->start_controls_section(
			'section_background',
			array(
				'label' => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'category_border',
				'selector' => '{{WRAPPER}} .cat-wrapper',
			)
		);

		$this->add_responsive_control(
			'category_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_background' );

		/**
		 * Normal.
		 */
		$this->start_controls_tab(
			'tab_background_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cat_box_shadow',
				'selector' => '{{WRAPPER}} .cat-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'background',
				'types'          => array( 'classic', 'gradient', 'video', 'slideshow' ),
				'fields_options' => array(
					'background' => array(
						'frontend_available' => true,
					),
					'image'      => array(
						'background_lazyload' => array(
							'active' => true,
							'keys'   => array( 'background_image', 'url' ),
						),
					),
				),
				'selector'       => '{{WRAPPER}} .cat-wrapper',
			)
		);

		$this->end_controls_tab();

		/**
		 * Hover.
		 */
		$this->start_controls_tab(
			'tab_background_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cat_box_shadow_hover',
				'selector' => '{{WRAPPER}} .cat-wrapper:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background_hover',
				'selector' => '{{WRAPPER}} .cat-wrapper:hover',
			)
		);

		$this->add_control(
			'background_hover_transition',
			array(
				'label'       => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 0.3,
				),
				'render_type' => 'ui',
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} .cat-wrapper' => 'background-transition: {{SIZE}}s;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Transform controls for this widget.
	 */
	protected function register_transform_controls() {

		$default_unit_values_deg = array();
		$default_unit_values_ms  = array();

		// Set the default unit sizes for all active breakpoints.
		foreach ( Breakpoints_Manager::get_default_config() as $breakpoint_name => $breakpoint_config ) {
			$default_unit_values_deg[ $breakpoint_name ] = array(
				'default' => array(
					'unit' => 'deg',
				),
			);

			$default_unit_values_ms[ $breakpoint_name ] = array(
				'default' => array(
					'unit' => 'ms',
				),
			);
		}
		$this->add_responsive_control(
			'transform_rotate_image',
			array(
				'label'       => esc_html__( 'Image Rotate', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'device_args' => $default_unit_values_deg,
				'range'       => array(
					'px' => array(
						'min' => -360,
						'max' => 360,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'transform: rotate({{SIZE}}deg)',
				),
			)
		);
	}

	/**
	 * Register controls for this widget.
	 */
	protected function add_images_controls() {

		$this->add_responsive_control(
			'image_wrap_width',
			array(
				'label'      => esc_html__( 'Image Wrapper Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .image-wrap' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'          => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'default'        => array(
					'size' => 48,
				),
				'tablet_default' => array(
					'size' => 48,
				),
				'mobile_default' => array(
					'size' => 48,
				),
				'selectors'      => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'          => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'default'        => array(
					'size' => 48,
				),
				'tablet_default' => array(
					'size' => 48,
				),
				'mobile_default' => array(
					'size' => 48,
				),
				'selectors'      => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_object_fit',
			array(
				'label'     => esc_html__( 'Object Fit', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''        => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'fill'    => esc_html__( 'Fill', 'mas-addons-for-elementor' ),
					'cover'   => esc_html__( 'Cover', 'mas-addons-for-elementor' ),
					'contain' => esc_html__( 'Contain', 'mas-addons-for-elementor' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'object-fit: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Flex Controls.
	 *
	 * @param string $name name of the control.
	 * @param string $wrapper wrapper for control.
	 */
	public function flex_controls( $name = '', $wrapper = '{{WRAPPER}} .mas-categories-wrapper' ) {
		$start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';
		$this->add_control(
			$name . 'items',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Items', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrap_direction',
			array(
				'label'     => esc_html__( 'Direction', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'row'            => array(
						'title' => esc_html__( 'Row - horizontal', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-' . $end,
					),
					'column'         => array(
						'title' => esc_html__( 'Column - vertical', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-down',
					),
					'row-reverse'    => array(
						'title' => esc_html__( 'Row - reversed', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-' . $start,
					),
					'column-reverse' => array(
						'title' => esc_html__( 'Column - reversed', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-up',
					),
				),
				'default'   => 'row',
				'selectors' => array(
					$wrapper => 'display:flex; flex-direction:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrap_justify_content',
			array(
				'label'       => esc_html__( 'Justify Content', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'flex-start'    => array(
						'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-start-h',
					),
					'center'        => array(
						'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-center-h',
					),
					'flex-end'      => array(
						'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-end-h',
					),
					'space-between' => array(
						'title' => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-between-h',
					),
					'space-around'  => array(
						'title' => esc_html__( 'Space Around', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-around-h',
					),
					'space-evenly'  => array(
						'title' => esc_html__( 'Space Evenly', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-evenly-h',
					),
				),
				'selectors'   => array(
					$wrapper => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrap_align_items',
			array(
				'label'     => esc_html__( 'Align Items', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'selectors' => array(
					$wrapper => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrap_gap',
			array(
				'label'                  => esc_html__( 'Gaps', 'mas-addons-for-elementor' ),
				'type'                   => Controls_Manager::GAPS,
				'size_units'             => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'default'                => array(
					'unit' => 'px',
				),
				'separator'              => 'before',
				'selectors'              => array(
					$wrapper => 'gap: {{ROW}}{{UNIT}} {{COLUMN}}{{UNIT}}',
				),
				'conversion_map'         => array(
					'old_key' => 'size',
					'new_key' => 'column',
				),
				'upgrade_conversion_map' => array(
					'old_key'  => 'size',
					'new_keys' => array( 'column', 'row' ),
				),
				'validators'             => array(
					'Number' => array(
						'min' => 0,
					),
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrapper_wrap',
			array(
				'label'       => esc_html_x( 'Wrap', 'Flex Container Control', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'nowrap' => array(
						'title' => esc_html_x( 'No Wrap', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-nowrap',
					),
					'wrap'   => array(
						'title' => esc_html_x( 'Wrap', 'Flex Container Control', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
				),
				'description' => esc_html__( 'Items within the container can stay in a single line (No wrap), or break into multiple lines (Wrap).', 'mas-addons-for-elementor' ),
				'default'     => '',
				'selectors'   => array(
					$wrapper => 'flex-wrap: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrapper_align_content',
			array(
				'label'     => esc_html__( 'Align Content', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''              => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'center'        => esc_html__( 'Center', 'mas-addons-for-elementor' ),
					'flex-start'    => esc_html__( 'Start', 'mas-addons-for-elementor' ),
					'flex-end'      => esc_html__( 'End', 'mas-addons-for-elementor' ),
					'space-between' => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
					'space-around'  => esc_html__( 'Space Around', 'mas-addons-for-elementor' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					$wrapper => 'align-content: {{VALUE}};',
				),
				'condition' => array(
					'categories_wrapper_wrap' => 'wrap',
				),
			)
		);
	}

	/**
	 * Render.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$json     = wp_json_encode( $this->get_swiper_carousel_options( $this, $settings ) );
		$this->add_render_attribute( 'mas-categories', 'class', 'mas-categories' );
		$this->add_render_attribute( 'mas-categories-wrapper', 'class', 'mas-categories-wrapper' );
		if ( 'yes' === $settings['enable_carousel'] ) {
			$this->add_render_attribute( 'mas-categories', 'class', 'swiper' );
			$this->add_render_attribute( 'mas-categories', 'data-swiper-options', $json );
			$this->add_render_attribute( 'mas-categories-wrapper', 'class', 'swiper-wrapper' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'mas-categories' ); ?>>
			<div <?php $this->print_render_attribute_string( 'mas-categories-wrapper' ); ?>>
				<?php $this->render_categories(); ?>
			</div>
			<?php echo wp_kses_post( $this->carousel_loop_footer( $this, $settings ) ); ?>
		</div>
		<?php

	}

	/**
	 * Render.
	 */
	public function render_categories() {
		$settings = $this->get_settings_for_display();
		$taxonomy = 'product_cat';
		$orderby  = $settings['orderby'];
		$order    = $settings['order'];
		$empty    = 'yes' === $settings['hide_empty'] ? true : false;

		$args = array(
			'taxonomy'   => $taxonomy,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $empty,
		);
		if ( ! empty( $settings['select_terms'] ) ) {
			$args['include'] = $settings['select_terms'];
		}
		$all_categories = get_terms( $args );
		$tab_count      = 0;
		if ( 'yes' === $settings['enable_card_link'] ) {
			$img_cat_wrap_tag = 'a';
			$categories_tag   = 'div';
			$img_wrap_tag     = 'div';
			$icon_wrap_tag    = 'div';

		} else {
			$img_cat_wrap_tag = 'div';
			$categories_tag   = 'a';
			$img_wrap_tag     = 'a';
			$icon_wrap_tag    = 'a';
		}

		if ( 'yes' === $settings['enable_image_link'] ) {
			$img_wrap_tag = 'a';

		} else {
			$img_wrap_tag = 'div';
		}

		foreach ( $all_categories as $index => $cat ) {
			if ( 'yes' === $settings['show_child_cat'] ) {
				$condition = true;
			} else {
				$condition = 0 === $cat->parent;
			}

			if ( $condition ) {
				if ( $tab_count === $settings['number'] ) {
					break;
				}
				if ( 'a' === $img_cat_wrap_tag ) {
					$this->add_render_attribute( 'img-cat-wrap' . $index, 'href', get_term_link( $cat->slug, $taxonomy ) );
				}

				$this->add_render_attribute( 'img-cat-wrap' . $index, 'class', 'img-cat-wrap' );

				$this->add_render_attribute( 'cat-wrapper' . $index, 'class', 'cat-wrapper' );

				if ( 'yes' === $settings['enable_carousel'] ) {
					$this->add_render_attribute( 'cat-wrapper' . $index, 'class', 'swiper-slide' );
				}

				?>
			<div <?php $this->print_render_attribute_string( 'cat-wrapper' . $index ); ?>>
				<<?php echo esc_html( $img_cat_wrap_tag ); ?> <?php $this->print_render_attribute_string( 'img-cat-wrap' . $index ); ?>>
					<?php
					$image              = '';
					$category_thumbnail = '';
					if ( 'cover' === $settings['image_option'] ) {
						$cover_image_name      = $settings['cover_image_field_name'];
						$cover_image_url       = mas_elementor_get_field( $cover_image_name, $cat );
						$cover_image_url_valid = wp_http_validate_url( $cover_image_url );
						if ( $cover_image_url_valid ) {
							$category_thumbnail = attachment_url_to_postid( $cover_image_url );
						}
					} else {
						$category_name      = $cat->name;
						$category_thumbnail = get_term_meta( $cat->term_id, 'thumbnail_id', true );
					}

					if ( ! empty( wp_get_attachment_image( $category_thumbnail ) ) ) {
						$image = wp_get_attachment_image( $category_thumbnail, $settings['image_sizes'] );
					} elseif ( $settings['enable_placeholder_image'] ) {
						$image = wc_placeholder_img();
					}

					if ( 'a' === $img_wrap_tag ) {
						$this->add_render_attribute( 'cat-img' . $index, 'href', get_term_link( $cat->slug, $taxonomy ) );
						$this->add_render_attribute( 'cat-icon' . $index, 'href', get_term_link( $cat->slug, $taxonomy ) );
					}
					$icon = get_term_meta( $cat->term_id, $settings['icon_term_name'], true );
					$this->add_render_attribute( 'cat-img' . $index, 'class', 'image-wrap' );
					$this->add_render_attribute( 'cat-icon' . $index, 'class', 'mas-category-icon' );

					if ( 'icon' === $settings['select_image_icon'] ) {
						if ( ! empty( $icon ) ) {
							?>
							<<?php echo esc_html( $icon_wrap_tag ); ?> <?php $this->print_render_attribute_string( 'cat-icon' . $index ); ?>>
								<i class="<?php echo esc_attr( $icon ); ?>"></i>
							</<?php echo esc_html( $icon_wrap_tag ); ?>>
							<?php
						}
					} elseif ( 'image' === $settings['select_image_icon'] ) {
						if ( ! empty( $image ) ) {
							?>
						<<?php echo esc_html( $img_wrap_tag ); ?> <?php $this->print_render_attribute_string( 'cat-img' . $index ); ?>>
							<?php
							echo wp_kses_post( $image );
							?>
						</<?php echo esc_html( $img_wrap_tag ); ?>>
							<?php
						}
					}

					$category_id = $cat->term_id;
					?>
					<div class="categories">
						<?php $this->category_link_button( $settings, $cat, $taxonomy ); ?>
						<div class="cat-name-wrapper">
						<?php
						if ( 'a' === $categories_tag ) {
							$this->add_render_attribute( 'categories' . $index, 'href', get_term_link( $cat->slug, $taxonomy ) );
						}
						$this->add_render_attribute( 'categories' . $index, 'class', 'cat-name' );
						?>
						<<?php echo esc_html( $categories_tag ); ?> <?php $this->print_render_attribute_string( 'categories' . $index ); ?>><?php echo esc_html( $cat->name ); ?></<?php echo esc_html( $categories_tag ); ?>>
						<?php
						if ( 'yes' === $settings['show_cat_product_count'] ) {
							$cat_product_count = sprintf(
								_n(
									$settings['product_count_singular_pattern'], //phpcs:ignore
									$settings['product_count_plural_pattern'],  //phpcs:ignore
									$cat->count,
									'mas-addons-for-elementor'
								),
								$cat->count
							);
							?>
						<div class="cat-prod-count"><?php echo wp_kses_post( $cat_product_count ); ?></div>
							<?php
						}
						?>
						</div>
						<?php
						$args2    = array(
							'taxonomy'   => $taxonomy,
							'child_of'   => 0,
							'parent'     => $category_id,
							'orderby'    => $orderby,
							'hide_empty' => $empty,
						);
						$sub_cats = get_categories( $args2 );
						if ( $sub_cats && 0 < $settings['sub_cat_count'] ) {
							$sub_tab_count = 0;
							?>
						<div class="sub-categories">
							<?php
							foreach ( $sub_cats as $sub_category ) {
								if ( $sub_tab_count === $settings['sub_cat_count'] ) {
									break;
								}
								?>
								<div class="sub-category"><?php echo wp_kses_post( '<a href="' . get_term_link( $sub_category->slug, $taxonomy ) . '">' . $sub_category->name . '</a>' ); ?>
								<?php
								if ( 'yes' === $settings['show_subcat_product_count'] ) {
									$sub_cat_product_count = sprintf(
										_n(
											$settings['product_count_singular_pattern'],  //phpcs:ignore
											$settings['product_count_plural_pattern'],  //phpcs:ignore
											$sub_category->count,
											'mas-addons-for-elementor'
										),
										$sub_category->count
									);
									?>
								<div class="subcat-prod-count"><?php echo wp_kses_post( $sub_cat_product_count ); ?></div>
									<?php
								}
								?>
								</div>
								<?php
								$sub_tab_count++;
							}
							if ( 'in' === $settings['see_more_position'] && ! empty( $settings['see_more'] ) ) {
								if ( ( ! empty( $sub_cats ) && ( count( $sub_cats ) > $settings['sub_cat_count'] ) ) || 'yes' === $settings['enable_see_more_all'] ) {
									?>
									<div class="sub-category">
										<?php
											echo wp_kses_post( '<a class="see-all" href="' . get_term_link( $cat->slug, $taxonomy ) . '">' . $settings['see_more'] . '</a>' );
										?>
									</div>
										<?php
								}
							}
							?>
						</div>
							<?php
						}
						?>
					</div>
				</<?php echo esc_html( $img_cat_wrap_tag ); ?>>
				<?php

				if ( 'out' === $settings['see_more_position'] && ! empty( $settings['see_more'] ) ) {
					if ( ( ! empty( $sub_cats ) && ( count( $sub_cats ) > $settings['sub_cat_count'] ) ) || 'yes' === $settings['enable_see_more_all'] ) {
						?>
						<div class="see-all-wrapper">
							<?php
								echo wp_kses_post( '<a class="see-all" href="' . get_term_link( $cat->slug, $taxonomy ) . '">' . $settings['see_more'] . '</a>' );
							?>
						</div>
						<?php
					}
				}

				?>
			</div>
				<?php
				$tab_count++;
			}
		}

	}

	/**
	 * Render Plain Content.
	 */
	public function render_plain_content() {
	}

	/**
	 * Get group name.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}

	/**
	 * Add carousel arrow and pagination controls to the product categories element.
	 */
	public function register_carousel_arrow_pagination_controls() {

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

	}

	/**
	 * Add carousel controls to the product categories element.
	 */
	public function register_carousel_attributes_controls() {

		$this->start_injection(
			array(
				'at' => 'before',
				'of' => 'section_query',
			)
		);

		$this->register_swiper_controls( $this );

		$this->end_injection();

		$this->register_carousel_arrow_pagination_controls();
	}

	/**
	 * Carousel Loop Footer.
	 *
	 * @param array $widget widget.
	 * @param array $settings Settings of this widget.
	 * @return string
	 */
	public function carousel_loop_footer( $widget, array $settings = array() ) {
		ob_start();
		if ( 'yes' === $settings['enable_carousel'] ) {
			?>
			<?php
			$widget_id = $widget->get_id();
			if ( ! empty( $widget_id ) && 'yes' === $settings['show_pagination'] ) {
				$widget->add_render_attribute( 'swiper-pagination', 'id', 'pagination-' . $widget_id );
			}
			$widget->add_render_attribute( 'swiper-pagination', 'class', 'swiper-pagination' );
			$widget->add_render_attribute( 'swiper-pagination', 'style', 'position: ' . $settings['mas_swiper_pagination_position'] . ';' );
			if ( 'yes' === $settings['show_pagination'] ) :
				?>
			<div <?php $widget->print_render_attribute_string( 'swiper-pagination' ); ?>></div>
				<?php
			endif;
			if ( 'yes' === $settings['show_arrows'] ) :
				$prev_id = ! empty( $widget_id ) ? 'prev-' . $widget_id : '';
				$next_id = ! empty( $widget_id ) ? 'next-' . $widget_id : '';
				?>
				<!-- If we need navigation buttons -->
				<div class="d-flex mas-swiper-arrows">
					<?php
					$widget->render_button( $widget, $prev_id, $next_id );
					?>
				</div>
				<?php
			endif;
			?>
			<?php
		}
		return ob_get_clean();
	}

	/**
	 * Category Link Button Content Controls.
	 */
	protected function category_link_button_content_controls() {
		$this->start_controls_section(
			'section_category_link_button_content',
			array(
				'label'     => esc_html__( 'Category Link Button', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'enable_cat_link_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_link_button_icon',
			array(
				'label'            => esc_html__( 'Category Link Button Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'default'          => array(
					'value'   => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				),
				'label_block'      => false,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Category Link Button Style Controls.
	 */
	protected function category_link_button_style_controls() {
		$this->start_controls_section(
			'section_category_link_button_style',
			array(
				'label'     => esc_html__( 'Category Link Button', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_cat_link_button' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'category_link_button_tab' );

			$this->start_controls_tab(
				'category_link_button_normal',
				array(
					'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				)
			);

				$this->add_control(
					'category_link_button_icon_color',
					array(
						'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							'{{WRAPPER}} .category-link-button i' => 'color: {{VALUE}}',
							'{{WRAPPER}} .category-link-button svg' => 'fill: {{VALUE}}',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					array(
						'name'     => 'category_link_button_bg',
						'selector' => '{{WRAPPER}} .category-link-button',
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					array(
						'name'     => 'category_link_button_box_shadow',
						'selector' => '{{WRAPPER}} .category-link-button',
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'category_link_button_hover',
				array(
					'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				)
			);

				$this->add_control(
					'category_link_button_icon_color_hover',
					array(
						'label'     => esc_html__( 'Icon Hover Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							'{{WRAPPER}} .category-link-button:hover i' => 'color: {{VALUE}}',
							'{{WRAPPER}} .category-link-button:hover svg' => 'fill: {{VALUE}}',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					array(
						'name'     => 'category_link_button_bg_hover',
						'selector' => '{{WRAPPER}} .category-link-button:hover',
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					array(
						'name'     => 'category_link_button_box_shadow_hover',
						'selector' => '{{WRAPPER}} .category-link-button:hover',
					)
				);

			$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'category_link_button_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .category-link-button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .category-link-button svg' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .category-link-button svg' => 'height: {{SIZE}}{{UNIT}} !important;',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'category_link_button_border',
				'selector' => '{{WRAPPER}} .category-link-button',
			)
		);

		$this->add_responsive_control(
			'category_link_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .category-link-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'category_link_button_spacing',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .category-link-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'category_link_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .category-link-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'category_link_button_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .category-link-button' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'category_link_button_height',
			array(
				'label'      => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .category-link-button' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->flex_controls( 'category_link_button', '{{WRAPPER}} .category-link-button' );

		$this->position_controls( 'category_link_button', ' .category-link-button' );

		$this->end_controls_section();

	}

	/**
	 * Category Link Button.
	 *
	 * @param array  $settings Settings of this widget.
	 * @param array  $cat cat term.
	 * @param string $taxonomy taxonomy .
	 */
	protected function category_link_button( $settings, $cat, $taxonomy ) {
		if ( 'yes' !== $settings['enable_cat_link_button'] ) {
			return;
		}
		$migrated = isset( $settings['__fa4_migrated']['category_link_button_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		?>
		<a href="<?php echo esc_url( get_term_link( $cat->slug, $taxonomy ) ); ?>" class="category-link-button">
			<!-- <i class="fas fa-chevron-right"></i> -->
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['category_link_button_icon']['value'] ) ) : ?>
			
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['category_link_button_icon'], array( 'aria-hidden' => 'true' ) );
				else :
					?>
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			
			<?php endif; ?>
		</a>
		<?php
	}

	/**
	 * Position Controls.
	 *
	 * @param string $name name of the control.
	 * @param string $wrapper wrapper for control.
	 */
	protected function position_controls( $name, $wrapper ) {
		$this->add_control(
			$name . '_position_description',
			array(
				'raw'             => '<strong>' . esc_html__( 'Please note!', 'mas-addons-for-elementor' ) . '</strong> ' . esc_html__( 'Custom positioning is not considered best practice for responsive web design and should not be used too frequently.', 'mas-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'render_type'     => 'ui',
				'condition'       => array(
					$name . '_position!' => 'relative',
				),
			)
		);

		$this->add_control(
			$name . '_position',
			array(
				'label'              => esc_html__( 'Position', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'relative',
				'options'            => array(
					'relative' => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'absolute' => esc_html__( 'Absolute', 'mas-addons-for-elementor' ),
					'fixed'    => esc_html__( 'Fixed', 'mas-addons-for-elementor' ),
				),
				'prefix_class'       => 'mas-elementor-',
				'frontend_available' => true,
				'separator'          => 'before',
				'selectors'          => array(
					'{{WRAPPER}}' . $wrapper => 'position: {{VALUE}}',
				),
			)
		);

		$start = is_rtl() ? esc_html__( 'Right', 'mas-addons-for-elementor' ) : esc_html__( 'Left', 'mas-addons-for-elementor' );
		$end   = ! is_rtl() ? esc_html__( 'Right', 'mas-addons-for-elementor' ) : esc_html__( 'Left', 'mas-addons-for-elementor' );

		$this->add_control(
			$name . '_offset_orientation_h',
			array(
				'label'       => esc_html__( 'Horizontal Orientation', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => array(
					'start' => array(
						'title' => $start,
						'icon'  => 'eicon-h-align-left',
					),
					'end'   => array(
						'title' => $end,
						'icon'  => 'eicon-h-align-right',
					),
				),
				'classes'     => 'elementor-control-start-end',
				'render_type' => 'ui',
				'condition'   => array(
					$name . '_position!' => 'relative',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_offset_x',
			array(
				'label'      => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => '0',
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}}' . $wrapper => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}}' . $wrapper => 'right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$name . '_offset_orientation_h!' => 'end',
					$name . '_position!'             => 'relative',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_offset_x_end',
			array(
				'label'      => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 0.1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => '0',
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}}' . $wrapper => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}}' . $wrapper => 'left: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$name . '_offset_orientation_h' => 'end',
					$name . '_position!'            => 'relative',
				),
			)
		);

		$this->add_control(
			$name . '_offset_orientation_v',
			array(
				'label'       => esc_html__( 'Vertical Orientation', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => array(
					'start' => array(
						'title' => esc_html__( 'Top', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'end'   => array(
						'title' => esc_html__( 'Bottom', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'render_type' => 'ui',
				'condition'   => array(
					$name . '_position!' => 'relative',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_offset_y',
			array(
				'label'      => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ),
				'default'    => array(
					'size' => '0',
				),
				'selectors'  => array(
					'{{WRAPPER}}' . $wrapper => 'top: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$name . '_offset_orientation_v!' => 'end',
					$name . '_position!'             => 'relative',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_offset_y_end',
			array(
				'label'      => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ),
				'default'    => array(
					'size' => '0',
				),
				'selectors'  => array(
					'{{WRAPPER}}' . $wrapper => 'bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$name . '_offset_orientation_v' => 'end',
					$name . '_position!'            => 'relative',
				),
			)
		);
	}
}
