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
use Elementor\Widget_Accordion;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use MASElementor\Modules\QueryControl\Module as Query_Module;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Categories
 */
class Product_Categories_Dropdown extends Widget_Accordion {

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
		return 'mas-product-categories-dropdown';
	}

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_group_name() {
		return 'accordion';
	}

	/**
	 * Get inline css to the widget.
	 *
	 * @return array
	 */
	public function get_inline_css_depends() {
		return array(
			array(
				'name'               => 'accordion',
				'is_core_dependency' => true,
			),
		);
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Product Categories Dropdown', 'mas-addons-for-elementor' );
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
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mas-accordion-script' );
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce-elements', 'shop', 'store', 'categories', 'product', 'dropdown' );
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
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return array( 'widget-accordion' );
	}

	/**
	 * Display widgets.
	 *
	 * @return boolean
	 */
	public function show_in_panel(): bool {
		return true;
	}

	/**
	 * Register controls for this widget.
	 *
	 * @param string $wrapper wrapper selectors.
	 */
	protected function position_style_controls( $wrapper = '{{WRAPPER}} .elementor-tab-content' ) {

		$this->add_responsive_control(
			'content_position',
			array(
				'label'     => esc_html__( 'Position', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'relative',
				'options'   => array(
					'relative' => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'absolute' => esc_html__( 'Absolute', 'mas-addons-for-elementor' ),
					'fixed'    => esc_html__( 'Fixed', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-tab-content' => 'position: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'content_position_width',
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
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-tab-content' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'content_position' => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'_z_index',
			array(
				'label'     => esc_html__( 'Z-Index', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array(
					$wrapper => 'z-index: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Register controls for this widget.
	 */
	protected function add_subcategories_controls() {
		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => esc_html__( 'Content', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_control(
				'content_sub_background_color',
				array(
					'label'     => esc_html__( 'Background', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .elementor-tab-content' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'           => 'accordion__content_border',
					'selector'       => '{{WRAPPER}} .elementor-accordion .elementor-tab-content',
					'fields_options' => array(
						'border' => array(
							'default' => 'none',
						),
					),
				)
			);

			$this->add_responsive_control(
				'content_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .elementor-accordion .elementor-tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
			);

			$this->add_responsive_control(
				'content_sub_padding',
				array(
					'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->position_style_controls();

			$this->add_responsive_control(
				'content_sub_z_index',
				array(
					'label'     => esc_html__( 'Z-Index', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 0,
					'default'   => 1,
					'selectors' => array(
						'{{WRAPPER}} .elementor-tab-content' => 'z-index: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sub_cats_style',
			array(
				'label' => esc_html__( 'Sub Categories', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'sub_cats_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .sub-categories a' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
			)
		);

		$this->add_control(
			'sub_cat_tab_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .sub-categories a:hover' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_cats_typography',
				'selector' => '{{WRAPPER}} .sub-categories a',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			array(
				'name'     => 'sub_cat_text_stroke',
				'selector' => '{{WRAPPER}} .sub-categories a',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'sub_cat_shadow',
				'selector' => '{{WRAPPER}} .sub-categories a',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register controls for this widget.
	 */
	protected function add_images_controls() {

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
					'{{WRAPPER}} .cat-image img' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .cat-image img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

	}

	/**
	 * Register controls for this widget.
	 */
	protected function updating_controls() {
		$this->remove_control( 'tabs' );
		$this->remove_control( 'border_width' );
		$this->remove_control( 'title_background' );
		$this->remove_control( 'icon_align' );
		$this->remove_control( 'faq_schema' );
		$this->remove_control( 'title_html_tag' );
		$this->remove_control( 'icon_space' );

		$this->remove_control( 'section_toggle_style_content' );

		$this->add_subcategories_controls();

		$this->start_injection(
			array(
				'of' => 'tab_active_color',
				'at' => 'before',
			)
		);

		$this->add_control(
			'tab_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion-title:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_injection();

		$this->start_injection(
			array(
				'of' => 'border_color',
				'at' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'accordion_border',
				'selector'       => '{{WRAPPER}} .elementor-accordion-item',
				'exclude'        => array( 'color' ),
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
				),
			)
		);

		$this->end_injection();

		$this->update_control(
			'tab_active_color',
			array(
				'selectors' => array(
					'{{WRAPPER}} .cat-img-icon:has(.elementor-active) .elementor-accordion-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-active .elementor-accordion-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->start_injection(
			array(
				'of' => 'title_padding',
				'at' => 'before',
			)
		);

		$this->add_responsive_control(
			'title_padding_new',
			array(
				'label'      => esc_html__( 'Title Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_injection();

		$this->update_control(
			'title_padding',
			array(
				'label' => esc_html__( 'Icon Padding', 'mas-addons-for-elementor' ),
			)
		);
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();
		$this->updating_controls();

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
			'select_image_icon',
			array(
				'label'   => esc_html__( 'Select Image / Icon', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon'  => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
					'image' => esc_html__( 'Image', 'mas-addons-for-elementor' ),
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

		$this->add_control(
			'show_icons_no_subs',
			array(
				'label'       => esc_html__( 'Show Icons for No Subs', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'label_on'    => 'Hide',
				'label_off'   => 'Show',
				'description' => esc_html__( 'Show Dropdown Icons for categories with no sub-categories', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_content_no_subs',
			array(
				'label'       => esc_html__( 'No Sub Categories Dropdown', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'label_on'    => 'Hide',
				'label_off'   => 'Show',
				'description' => esc_html__( 'Show Dropdown Content for categories with no sub-categories', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'show_icons_no_subs' => 'yes',
				),
			)
		);

		$this->add_control(
			'no_sub_categories_text',
			array(
				'label'     => esc_html__( 'No Subs Text', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'No Sub Categories',
				'condition' => array(
					'show_content_no_subs' => 'yes',
					'show_icons_no_subs'   => 'yes',
				),
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
				'description'  => esc_html__( 'Terms are items in a taxonomy. The available taxonomies are: Categories, Enter parent categories only', 'mas-addons-for-elementor' ),
				'type'         => Query_Module::QUERY_CONTROL_ID,
				'options'      => array(),
				'label_block'  => true,
				'multiple'     => true,
				'autocomplete' => array(
					'object'  => Query_Module::QUERY_OBJECT_CPT_TAX,
					'display' => 'detailed',
				),
				'group_prefix' => 'select_terms',
				'tabs_wrapper' => $tabs_wrapper,
				'inner_tab'    => $include_wrapper,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_categories_image_style',
			array(
				'label' => esc_html__( 'Categories Image', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_images_controls();

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
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
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
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .cat-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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

		$this->add_responsive_control(
			'size',
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
					'size' => 30,
					'unit' => '%',
				),
				'tablet_default' => array(
					'size' => 40,
					'unit' => '%',
				),
				'mobile_default' => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'      => array(
					'{{WRAPPER}} .elementor-accordion-item' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'          => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px', 'em', 'rem', 'vw', 'custom' ),
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
					'size' => 100,
					'unit' => '%',
				),
				'tablet_default' => array(
					'size' => 100,
					'unit' => '%',
				),
				'mobile_default' => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'      => array(
					'{{WRAPPER}} .elementor-accordion-item' => 'height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .elementor-accordion-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->flex_controls( '', '{{WRAPPER}} .elementor-accordion' );

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_image_wrapper',
			array(
				'label' => esc_html__( 'Image & Categories', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->flex_controls( 'img_cat', '{{WRAPPER}} .cat-image' );

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_icon',
			array(
				'label' => esc_html__( 'Category & Icon', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->flex_controls( 'cat_icon', '{{WRAPPER}} .cat-img-icon' );

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_subcat',
			array(
				'label' => esc_html__( 'Sub Categories', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->flex_controls( 'cat_sub', '{{WRAPPER}} .sub-categories' );

		$this->end_controls_section();

		$this->register_category_icon_style_controls();
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
				'heading_field_icon',
				array(
					'label' => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
					'type'  => Controls_Manager::HEADING,
				)
			);

			$this->add_responsive_control(
				'field_icon_font_size',
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
						'{{WRAPPER}} .cat-image i' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'field_icon_margin',
				array(
					'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .cat-image i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'field_icon_color',
				array(
					'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .cat-image i' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'field_icon_hover_color',
				array(
					'label'     => esc_html__( 'Icon Hover Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .cat-image i:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .cat-image a:hover .cat-image i' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'field_icon_active_color',
				array(
					'label'     => esc_html__( 'Icon Active Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .cat-img-icon:has(.elementor-active) .cat-image i' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Render Plain Content.
	 */
	public function render_plain_content() {
	}

	/**
	 * Render accordion widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {

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
		$migrated       = isset( $settings['__fa4_migrated']['selected_icon'] );

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {

			$settings['icon']        = 'fa fa-plus';
			$settings['icon_active'] = 'fa fa-minus';
		}

		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		$has_icon = ( ! $is_new || ! empty( $settings['selected_icon']['value'] ) );
		$id_int   = substr( $this->get_id_int(), 0, 3 );
		?>
		<div class="elementor-accordion">
			<?php
				$tab_count = 0;
			foreach ( $all_categories as $index => $cat ) :

				if ( 0 === $cat->parent ) {

					$child_categories = get_term_children( $cat->term_id, $taxonomy );

					if ( $tab_count === $settings['number'] ) {
						break;
					}

					$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

					$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

					$this->add_render_attribute(
						$tab_title_setting_key,
						array(
							'id'            => 'elementor-tab-title-' . $id_int . $tab_count,
							'class'         => array( 'elementor-tab-title' ),
							'data-tab'      => $tab_count,
							'role'          => 'button',
							'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
							'aria-expanded' => 'false',
						)
					);

					$this->add_render_attribute(
						$tab_content_setting_key,
						array(
							'id'              => 'elementor-tab-content-' . $id_int . $tab_count,
							'class'           => array( 'elementor-tab-content', 'elementor-clearfix' ),
							'data-tab'        => $tab_count,
							'role'            => 'region',
							'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
						)
					);

					$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
					?>
						<div class="elementor-accordion-item mas-elementor-accordion-item">
							<div class="cat-img-icon">
								<div class="cat-image">
									<a href="<?php echo esc_url( get_term_link( $cat->slug, $taxonomy ) ); ?>" class="elementor-accordion-title" tabindex="0">
														<?php
														echo esc_html( $cat->name );
														?>
									</a>
									<?php
									$category_name      = $cat->name;
									$category_thumbnail = get_term_meta( $cat->term_id, 'thumbnail_id', true );
									if ( $category_thumbnail ) {
										$image = wp_get_attachment_image( $category_thumbnail );
									} else {
										$image = wc_placeholder_img();
									}

									if ( 'image' === $settings['select_image_icon'] && $image ) {
										echo wp_kses_post( '<a href="' . get_term_link( $cat->slug, $taxonomy ) . '">' . $image . '</a>' );
									}
									if ( 'icon' === $settings['select_image_icon'] && ! empty( $settings['icon_term_name'] ) ) {
										$icon_class = get_term_meta( $cat->term_id, $settings['icon_term_name'], true );
										$icon_html  = '<i class="' . esc_attr( $icon_class ) . '"></i>';
										echo wp_kses_post( '<a href="' . get_term_link( $cat->slug, $taxonomy ) . '">' . $icon_html . '</a>' );
									}
									if ( empty( $child_categories ) ) {
										$show_no_subs_icon = 'yes' === $settings['show_icons_no_subs'];
									} else {
										$show_no_subs_icon = true;
									}
									?>
								</div>
								<?php if ( $show_no_subs_icon ) : ?>
								<div <?php $this->print_render_attribute_string( $tab_title_setting_key ); ?>>
									<?php if ( $has_icon ) : ?>
										<span class="elementor-accordion-icon" aria-hidden="true">
										<?php
										if ( $is_new || $migrated ) {
											?>
											<span class="elementor-accordion-icon-closed"><?php Icons_Manager::render_icon( $settings['selected_icon'] ); ?></span>
											<span class="elementor-accordion-icon-opened"><?php Icons_Manager::render_icon( $settings['selected_active_icon'] ); ?></span>
										<?php } else { ?>
											<i class="elementor-accordion-icon-closed <?php echo esc_attr( $settings['icon'] ); ?>"></i>
											<i class="elementor-accordion-icon-opened <?php echo esc_attr( $settings['icon_active'] ); ?>"></i>
										<?php } ?>
										</span>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div>
							<?php
							$category_id = $cat->term_id;
							$args2       = array(
								'taxonomy'   => $taxonomy,
								'child_of'   => 0,
								'parent'     => $category_id,
								'orderby'    => $orderby,
								'hide_empty' => $empty,
							);
							$sub_cats    = get_categories( $args2 );

							if ( $sub_cats ) {
								?>
							<div <?php $this->print_render_attribute_string( $tab_content_setting_key ); ?>>
								<div class="sub-categories">
								<?php
								foreach ( $sub_cats as $sub_category ) {
									?>
										<div class="sub-category"><?php echo wp_kses_post( '<a href="' . get_term_link( $sub_category->slug, $taxonomy ) . '">' . $sub_category->name . '</a>' ); ?></div>
										<?php
								}
								?>
								</div>
							</div>
								<?php
							} else {
								if ( 'yes' === $settings['show_content_no_subs'] && ! empty( $settings['no_sub_categories_text'] ) ) {
									?>
								<div <?php $this->print_render_attribute_string( $tab_content_setting_key ); ?>>
									<div class="sub-categories">
											<div class="sub-category">
												<?php echo wp_kses_post( '<a href="' . get_term_link( $cat->slug, $taxonomy ) . '">' . $settings['no_sub_categories_text'] . '</a>' ); ?></div>
																				</div>
								</div>
									<?php
								}
							}
							?>
						</div>
						<?php
						$tab_count ++;
				}
				endforeach;
			?>
		</div>
		<?php
	}
}
