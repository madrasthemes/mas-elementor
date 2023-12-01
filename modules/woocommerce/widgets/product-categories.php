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
		return esc_html__( 'Product Categories & Sub', 'mas-elementor' );
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
				'label' => esc_html__( 'Layout', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => esc_html__( 'Categories Count', 'mas-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
			)
		);

		$this->add_control(
			'sub_cat_count',
			array(
				'label'   => esc_html__( 'SubCategories Count', 'mas-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
			)
		);

		$this->add_control(
			'select_image_icon',
			array(
				'label'   => esc_html__( 'Select Image / Icon', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => array(
					'none'  => esc_html__( 'None', 'mas-elementor' ),
					'image' => esc_html__( 'Image', 'mas-elementor' ),
					'icon'  => esc_html__( 'Icon', 'mas-elementor' ),
				),
			)
		);

		$this->add_control(
			'enable_placeholder_image',
			array(
				'label'     => esc_html__( 'Show Image Placeholder', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Icon Term Name', 'mas-elementor' ),
				'description' => esc_html__( 'By default the term meta we have used is "icon", you can create your own term meta for product category', 'mas-elementor' ),
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
				'label'     => esc_html__( 'See More', 'mas-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'See More',
				'condition' => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter',
			array(
				'label' => esc_html__( 'Query', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_child_cat',
			array(
				'label'     => esc_html__( 'Show Child Categories', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => 'Hide',
				'label_off' => 'Show',
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'     => esc_html__( 'Hide Empty', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'label_on'  => 'Hide',
				'label_off' => 'Show',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'name'        => esc_html__( 'Name', 'mas-elementor' ),
					'slug'        => esc_html__( 'Slug', 'mas-elementor' ),
					'description' => esc_html__( 'Description', 'mas-elementor' ),
					'count'       => esc_html__( 'Count', 'mas-elementor' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'mas-elementor' ),
					'desc' => esc_html__( 'DESC', 'mas-elementor' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_categories_image_style',
			array(
				'label'     => esc_html__( 'Categories Image', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Image', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->register_transform_controls();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} a > img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} a > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
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
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-cat-wrap img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_subcat_styles',
			array(
				'label' => esc_html__( 'Cat & Subs', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => esc_html__( 'Category', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Category Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .categories > a'         => 'color: {{VALUE}}',
					'{{WRAPPER}} .categories > .cat-name' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Category Hover Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .categories > a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .categories > .cat-name:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .cat-wrapper:hover .categories .cat-name' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .categories > a, {{WRAPPER}} .categories > .cat-name',
			)
		);

		$this->add_responsive_control(
			'cat_title_wrapper_width',
			array(
				'label'      => esc_html__( 'Category Text Wrapper Width', 'mas-elementor' ),
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
			)
		);

		$this->add_responsive_control(
			'cat_title_width',
			array(
				'label'      => esc_html__( 'Category Text Width', 'mas-elementor' ),
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
				'label'      => esc_html__( 'Category Text Height', 'mas-elementor' ),
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
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'category_title_overflow',
			array(
				'label'     => esc_html__( 'Overflow', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hidden',
				'options'   => array(
					''       => esc_html__( 'Default', 'mas-elementor' ),
					'hidden' => esc_html__( 'Hidden', 'mas-elementor' ),
					'auto'   => esc_html__( 'Auto', 'mas-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .cat-name' => 'overflow: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'category_title_align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Sub Category', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Show / Hide SubCategories', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'block' => array(
						'title' => esc_html__( 'Block', 'mas-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'none'  => array(
						'title' => esc_html__( 'None', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),

				),
				'selectors'   => array(
					'{{WRAPPER}} .sub-categories' => 'display: {{VALUE}};',
				),
				'condition'   => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->add_control(
			'sub_cat_color',
			array(
				'label'     => esc_html__( 'Sub Category Color', 'mas-elementor' ),
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

		$this->add_control(
			'sub_cat_hover_color',
			array(
				'label'     => esc_html__( 'Sub Category Hover Color', 'mas-elementor' ),
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_categories_see_all_style',
			array(
				'label'     => esc_html__( 'See all', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'sub_cat_count!' => 0,
					'see_more!'      => '',
				),
			)
		);

		$this->add_control(
			'heading_see_all_style',
			array(
				'label'     => esc_html__( 'See All', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'see_all_color',
			array(
				'label'     => esc_html__( 'See All Color', 'mas-elementor' ),
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
				'label'     => esc_html__( 'See All Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Show / Hide', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'block' => array(
						'title' => esc_html__( 'Block', 'mas-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'none'  => array(
						'title' => esc_html__( 'None', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),

				),
				'selectors'   => array(
					'{{WRAPPER}} .see-all-wrapper' => 'display: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_style',
			array(
				'label' => esc_html__( 'All Categories', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'enable_card_link',
			array(
				'label'     => esc_html__( 'Enable Card Link', 'mas-elementor' ),
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
			'categories_overflow',
			array(
				'label'     => esc_html__( 'Wrapper Overflow', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''       => esc_html__( 'Default', 'mas-elementor' ),
					'hidden' => esc_html__( 'Hidden', 'mas-elementor' ),
					'auto'   => esc_html__( 'Auto', 'mas-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-categories-wrapper' => 'overflow: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'cat_flex_size',
			array(
				'label'                => esc_html__( 'Size', 'mas-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => '',
				'options'              => array(
					'none'   => array(
						'title' => esc_html__( 'None', 'mas-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'grow'   => array(
						'title' => esc_html__( 'Grow', 'mas-elementor' ),
						'icon'  => 'eicon-grow',
					),
					'shrink' => array(
						'title' => esc_html__( 'Shrink', 'mas-elementor' ),
						'icon'  => 'eicon-shrink',
					),
					'custom' => array(
						'title' => esc_html__( 'Custom', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Flex Grow', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Flex Shrink', 'mas-elementor' ),
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
				'label'          => esc_html__( 'Width', 'mas-elementor' ),
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
			'categories_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'categories_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->flex_controls( '', '{{WRAPPER}} .mas-categories-wrapper' );

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_image_wrapper',
			array(
				'label' => esc_html__( 'Image / Icon & Categories', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'img_icon_wrap_min_height',
			array(
				'label'      => esc_html__( 'Min Height', 'mas-elementor' ),
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
			'categories_img_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
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

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_subcat',
			array(
				'label'     => esc_html__( 'Category & Sub Categories', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'sub_cat_count!' => 0,
				),
			)
		);

		$this->flex_controls( 'cat_sub', '{{WRAPPER}} .categories' );

		$this->end_controls_section();

		$this->register_background_controls();

		$this->register_category_icon_style_controls();

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
				'label'     => esc_html__( 'Categories Icon', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'select_image_icon' => 'icon',
				),
			)
		);

			$this->add_control(
				'heading_icon',
				array(
					'label' => esc_html__( 'Icon', 'mas-elementor' ),
					'type'  => Controls_Manager::HEADING,
				)
			);

			$this->add_responsive_control(
				'icon_font_size',
				array(
					'label'          => esc_html__( 'Font Size', 'mas-elementor' ),
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
					'label'      => esc_html__( 'Margin', 'mas-elementor' ),
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
					'label'     => esc_html__( 'Icon Color', 'mas-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mas-category-icon i' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'icon_hover_color',
				array(
					'label'     => esc_html__( 'Icon Hover Color', 'mas-elementor' ),
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
					'label'     => esc_html__( 'Icon Wrapper', 'mas-elementor' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'icon_wrap_width',
				array(
					'label'          => esc_html__( 'Width', 'mas-elementor' ),
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
					'label'          => esc_html__( 'Height', 'mas-elementor' ),
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
					'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
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
					'label'      => esc_html__( 'Margin', 'mas-elementor' ),
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
					'label'     => esc_html__( 'Icon Wrapper BG Color', 'mas-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mas-category-icon' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'icon_wrap_bg_hover_color',
				array(
					'label'     => esc_html__( 'Icon Wrapper BG hover Color', 'mas-elementor' ),
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
					'label'       => esc_html__( 'Justify Content', 'mas-elementor' ),
					'type'        => Controls_Manager::CHOOSE,
					'label_block' => true,
					'default'     => 'center',
					'options'     => array(
						'flex-start'    => array(
							'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-start-h',
						),
						'center'        => array(
							'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-center-h',
						),
						'flex-end'      => array(
							'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-end-h',
						),
						'space-between' => array(
							'title' => esc_html__( 'Space Between', 'mas-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-space-between-h',
						),
						'space-around'  => array(
							'title' => esc_html__( 'Space Around', 'mas-elementor' ),
							'icon'  => 'eicon-flex eicon-justify-space-around-h',
						),
						'space-evenly'  => array(
							'title' => esc_html__( 'Space Evenly', 'mas-elementor' ),
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
					'label'     => esc_html__( 'Align Items', 'mas-elementor' ),
					'type'      => Controls_Manager::CHOOSE,
					'default'   => 'flex-end',
					'options'   => array(
						'flex-start' => array(
							'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-elementor' ),
							'icon'  => 'eicon-flex eicon-align-start-v',
						),
						'center'     => array(
							'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-elementor' ),
							'icon'  => 'eicon-flex eicon-align-center-v',
						),
						'flex-end'   => array(
							'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-elementor' ),
							'icon'  => 'eicon-flex eicon-align-end-v',
						),
						'stretch'    => array(
							'title' => esc_html__( 'Stretch', 'mas-elementor' ),
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
				'label' => esc_html__( 'Background', 'mas-elementor' ),
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
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
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
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
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
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Transition Duration', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Image Rotate', 'mas-elementor' ),
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
				'label'      => esc_html__( 'Image Wrapper Width', 'mas-elementor' ),
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
				'label'          => esc_html__( 'Width', 'mas-elementor' ),
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
				'label'          => esc_html__( 'Height', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Items', 'mas-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrap_direction',
			array(
				'label'     => esc_html__( 'Direction', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'row'            => array(
						'title' => esc_html__( 'Row - horizontal', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-' . $end,
					),
					'column'         => array(
						'title' => esc_html__( 'Column - vertical', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-down',
					),
					'row-reverse'    => array(
						'title' => esc_html__( 'Row - reversed', 'mas-elementor' ),
						'icon'  => 'eicon-arrow-' . $start,
					),
					'column-reverse' => array(
						'title' => esc_html__( 'Column - reversed', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Justify Content', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'flex-start'    => array(
						'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-start-h',
					),
					'center'        => array(
						'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-center-h',
					),
					'flex-end'      => array(
						'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-end-h',
					),
					'space-between' => array(
						'title' => esc_html__( 'Space Between', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-between-h',
					),
					'space-around'  => array(
						'title' => esc_html__( 'Space Around', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-around-h',
					),
					'space-evenly'  => array(
						'title' => esc_html__( 'Space Evenly', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Align Items', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html_x( 'Start', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html_x( 'Center', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html_x( 'End', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-elementor' ),
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
				'label'                  => esc_html__( 'Gaps', 'mas-elementor' ),
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
				'label'       => esc_html_x( 'Wrap', 'Flex Container Control', 'mas-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'nowrap' => array(
						'title' => esc_html_x( 'No Wrap', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-nowrap',
					),
					'wrap'   => array(
						'title' => esc_html_x( 'Wrap', 'Flex Container Control', 'mas-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
				),
				'description' => esc_html__( 'Items within the container can stay in a single line (No wrap), or break into multiple lines (Wrap).', 'mas-elementor' ),
				'default'     => '',
				'selectors'   => array(
					$wrapper => 'flex-wrap: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'categories_wrapper_align_content',
			array(
				'label'     => esc_html__( 'Align Content', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''              => esc_html__( 'Default', 'mas-elementor' ),
					'center'        => esc_html__( 'Center', 'mas-elementor' ),
					'flex-start'    => esc_html__( 'Start', 'mas-elementor' ),
					'flex-end'      => esc_html__( 'End', 'mas-elementor' ),
					'space-between' => esc_html__( 'Space Between', 'mas-elementor' ),
					'space-around'  => esc_html__( 'Space Around', 'mas-elementor' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'mas-elementor' ),
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

		$args           = array(
			'taxonomy'   => $taxonomy,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $empty,
		);
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
					$category_name      = $cat->name;
					$category_thumbnail = get_term_meta( $cat->term_id, 'thumbnail_id', true );
					$image              = '';
					if ( ! empty( wp_get_attachment_image( $category_thumbnail ) ) ) {
						$image = wp_get_attachment_image( $category_thumbnail );
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
						<?php
						if ( 'a' === $categories_tag ) {
							$this->add_render_attribute( 'categories' . $index, 'href', get_term_link( $cat->slug, $taxonomy ) );
						}
						$this->add_render_attribute( 'categories' . $index, 'class', 'cat-name' );
						?>
						<<?php echo esc_html( $categories_tag ); ?> <?php $this->print_render_attribute_string( 'categories' . $index ); ?>><?php echo esc_html( $cat->name ); ?></<?php echo esc_html( $categories_tag ); ?>>
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
								<div class="sub-category"><?php echo wp_kses_post( '<a href="' . get_term_link( $sub_category->slug, 'product_cat' ) . '">' . $sub_category->name . '</a>' ); ?></div>
								<?php
								$sub_tab_count++;
							}
							?>
						</div>
							<?php
						}
						?>
					</div>
				</<?php echo esc_html( $img_cat_wrap_tag ); ?>>
				<?php

				if ( ! empty( $settings['see_more'] && ( ! empty( $sub_cats ) && ( count( $sub_cats ) > $settings['sub_cat_count'] ) ) ) ) {
					?>
				<div class="see-all-wrapper">
					<?php
						echo wp_kses_post( '<a class="see-all" href="' . get_term_link( $cat->slug, 'product_cat' ) . '">' . $settings['see_more'] . '</a>' );
					?>
				</div>
					<?php
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
				'label'     => esc_html__( 'Button', 'mas-elementor' ),
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
}
