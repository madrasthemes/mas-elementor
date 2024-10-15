<?php
/**
 * The Product Attributes Widget.
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
use MASElementor\Modules\QueryControl\Module as Query_Module;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Attributes
 */
class Product_Attributes extends Base_Widget {

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
		return 'mas-product-attributes';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Product Attributes', 'mas-addons-for-elementor' );
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
		return array( 'woocommerce-elements', 'shop', 'store', 'brand', 'product', 'color', 'attribute' );
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
	 * Register more attribute controls for this widget.
	 */
	protected function register_attributes_title_controls() {
		$this->start_controls_section(
			'section_attributes_title',
			array(
				'label'     => esc_html__( 'Attributes Title', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'attributes_title!' => '',
				),
			)
		);

		$this->add_control(
			'attributes_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .attributes-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'attributes_title_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .attributes-title',
			)
		);

		$this->add_responsive_control(
			'attributes_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .attributes-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'attributes_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .attributes-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'attributes_title_width',
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
					'{{WRAPPER}} .attributes-title' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'attributes_title_height',
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
					'{{WRAPPER}} .attributes-title' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'attributes_title_tag',
			array(
				'label'   => esc_html__( 'HTML Tag', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'    => 'H1',
					'h2'    => 'H2',
					'h3'    => 'H3',
					'h4'    => 'H4',
					'h5'    => 'H5',
					'h6'    => 'H6',
					'div'   => 'div',
					'span'  => 'span',
					'small' => 'small',
					'p'     => 'p',
				),
				'default' => 'div',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register more attribute controls for this widget.
	 */
	protected function register_more_attributes_controls() {
		$this->start_controls_section(
			'section_more_attributes',
			array(
				'label'     => esc_html__( 'More Attributes Text', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'more_attributes!' => '',
				),
			)
		);

		$more_attributes_selector = array(
			'normal'  => '{{WRAPPER}} .all-attributes-link a',
			'hover'   => '{{WRAPPER}} .all-attributes-link a:hover',
			'spacing' => '{{WRAPPER}} .all-attributes-link',
		);

		$this->text_typography_and_spacing_controls( 'more_attributes_text', $more_attributes_selector );

		$this->add_responsive_control(
			'more_attributes_align_items',
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
					'{{WRAPPER}} .all-attributes-link' => 'display:flex; align-items: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
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

		$product_attributes = wc_get_attribute_taxonomies();

		$taxonomies = array( '' => 'None' );
		foreach ( $product_attributes as $product_attribute ) {
			$taxonomy_key                = 'pa_' . $product_attribute->attribute_name;
			$taxonomies[ $taxonomy_key ] = $product_attribute->attribute_label;
		}

		$this->add_control(
			'select_product_attribute',
			array(
				'label'   => esc_html__( 'Select Product Attribute', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $taxonomies,
			)
		);

		$this->add_control(
			'enable_term_description',
			array(
				'label'     => esc_html__( 'Show Description', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => 'Hide',
				'label_off' => 'Show',

			)
		);

		$this->add_control(
			'enable_thumbnail_image',
			array(
				'label'     => esc_html__( 'Show Thumbnail Image', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => 'Hide',
				'label_off' => 'Show',

			)
		);

		$this->add_control(
			'thumbnail_name',
			array(
				'label'     => esc_html__( 'Term thumbnail name', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'thumbnail_id',
				'condition' => array(
					'enable_thumbnail_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_thumbnail_placeholder',
			array(
				'label'       => esc_html__( 'Show Thumbnail Placeholder', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'label_on'    => 'Hide',
				'label_off'   => 'Show',
				'description' => esc_html__( 'Displays Placeholder for terms with no images', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'enable_thumbnail_image' => 'yes',
				),

			)
		);

		$this->add_control(
			'enable_cover_image',
			array(
				'label'     => esc_html__( 'Show Cover Image', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => 'Hide',
				'label_off' => 'Show',

			)
		);

		$this->add_control(
			'cover_image_name',
			array(
				'label'       => esc_html__( 'Cover image name', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'product_attribute_cover_image',
				'description' => esc_html__( 'Can add thumbnail by using ACF in product attribute', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'enable_cover_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_cover_placeholder',
			array(
				'label'       => esc_html__( 'Show Cover Placeholder', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'label_on'    => 'Hide',
				'label_off'   => 'Show',
				'description' => esc_html__( 'Displays Placeholder for terms with no images', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'enable_cover_image' => 'yes',
				),

			)
		);

		$this->register_enable_carousel_control( $this );

		$this->add_control(
			'attributes_title',
			array(
				'label'       => esc_html__( 'More Attribute Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Link for Shop page', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'enable_carousel!' => 'yes',
				),
			)
		);

		$this->add_control(
			'more_attributes',
			array(
				'label'       => esc_html__( 'More Attribute Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Link for Shop page', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'enable_carousel!' => 'yes',
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
			'number',
			array(
				'label'   => esc_html__( 'Attribute Count', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
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
				'description'  => esc_html__( 'Terms are items in a taxonomy. The available taxonomies are: Product Attributes', 'mas-addons-for-elementor' ),
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
			'section_wrapper_style',
			array(
				'label' => esc_html__( 'Terms Wrapper', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->flex_controls( '', '{{WRAPPER}} .mas-product-attributes' );

		$this->add_responsive_control(
			'atts_wrapper_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-product-attributes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'atts_wrapper_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-product-attributes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_style',
			array(
				'label' => esc_html__( 'All Terms', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'atts_size',
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
					'size' => 25,
					'unit' => '%',
				),
				'tablet_default' => array(
					'size' => 50,
					'unit' => '%',
				),
				'mobile_default' => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'      => array(
					'{{WRAPPER}} .attribute-card-list' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'atts_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .attribute-card-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'atts_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .attribute-card-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_item_style',
			array(
				'label' => esc_html__( 'Card Items', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->card_item_controls();

		$this->end_controls_section();

		$this->attributes_style_controls();

		$this->register_attributes_title_controls();

		$this->register_more_attributes_controls();
	}

	/**
	 * Register Card Item controls for this widget.
	 */
	protected function card_item_controls() {
		$this->add_control(
			'card_item_heading',
			array(
				'label' => esc_html__( 'Card Item', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'card_item_display',
			array(
				'label'       => esc_html__( 'Display', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => 'block',
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
					'{{WRAPPER}} .card-item' => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_item_height',
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
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .card-item' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'card_item_background',
				'selector' => '{{WRAPPER}} .card-item',
			)
		);

		$this->add_responsive_control(
			'card_item_border_last_child',
			array(
				'label'      => esc_html__( 'Last Child Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .card-item:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'card_item_border',
				'selector'  => '{{WRAPPER}} .card-item',
				'separator' => 'after',
			)
		);

		$this->add_responsive_control(
			'card_item_wrap_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .card-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'card_item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .card-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'card_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .card-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->position_controls( 'card_item', ' .card-item' );
	}

	/**
	 * Flex Controls.
	 *
	 * @param string $name name of the control.
	 * @param string $wrapper wrapper for control.
	 */
	public function flex_controls( $name = '', $wrapper = '{{WRAPPER}} .mas-product-attributes' ) {
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
			$name . 'attributes_wrap_direction',
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
			$name . 'attributes_wrap_justify_content',
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
			$name . 'attributes_wrap_align_items',
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
			$name . 'attributes_wrap_gap',
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
			$name . 'attributes_wrapper_wrap',
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
			$name . 'attributes_wrapper_align_content',
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
					'attributes_wrapper_wrap' => 'wrap',
				),
			)
		);
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

	/**
	 * Register Attributes Style controls for this widget.
	 */
	protected function attributes_style_controls() {
		$this->start_controls_section(
			'section_thumbnail_image_style',
			array(
				'label' => esc_html__( 'Thumbnail Image', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->attributes_image_controls( 'thumbnail_image', '{{WRAPPER}} .mas-thumbnail-image' );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cover_image_style',
			array(
				'label' => esc_html__( 'Cover Image', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->attributes_image_controls( 'cover_image', '{{WRAPPER}} .mas-cover-image' );

		$this->position_controls( 'cover_image', ' .mas-cover-image' );

		$this->add_responsive_control(
			'cover_image_translate',
			array(
				'label'       => esc_html__( 'Transform', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Top and Left input only works top represent Y and Left represent X', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'   => array(
					'{{WRAPPER}} .mas-cover-image' => 'transform: translate({{TOP}}{{UNIT}}, {{LEFT}}{{UNIT}})',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'term_description_style',
			array(
				'label' => esc_html__( 'Description', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$desc_selector = array(
			'normal' => '{{WRAPPER}} .mas-term-description',
			'hover'  => '{{WRAPPER}} .card-link:hover .mas-term-description, {{WRAPPER}} .card-normal .mas-term-description:hover',
		);
		$this->text_typography_and_spacing_controls( 'description', $desc_selector );

		$this->end_controls_section();

	}

	/**
	 * Text Typography and Spacing Controls.
	 *
	 * @param string $name name of the control.
	 * @param array  $wrapper wrapper for control.
	 */
	protected function text_typography_and_spacing_controls( $name, $wrapper ) {

		if ( ! isset( $wrapper['spacing'] ) ) {
			$wrapper['spacing'] = $wrapper['normal'];
		}

		$this->start_controls_tabs( $name . '_tab' );

			$this->start_controls_tab(
				$name . '_normal',
				array(
					'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				)
			);

				$this->add_control(
					$name . '_color',
					array(
						'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							$wrapper['normal'] => 'color: {{VALUE}}',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'     => $name . '_typography',
						'global'   => array(
							'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
						),
						'selector' => $wrapper['normal'],
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				$name . '_hover',
				array(
					'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				)
			);

				$this->add_control(
					$name . '_hover_color',
					array(
						'label'     => esc_html__( 'Hover Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_PRIMARY,
						),
						'selectors' => array(
							$wrapper['hover'] => 'color: {{VALUE}}',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'     => $name . '_typography_hover',
						'global'   => array(
							'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
						),
						'selector' => $wrapper['hover'],
					)
				);

			$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			$name . '_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					$wrapper['spacing'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					$wrapper['spacing'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);
	}

	/**
	 * Attributes Image Controls.
	 *
	 * @param string $name name of the control.
	 * @param string $wrapper wrapper for control.
	 */
	protected function attributes_image_controls( $name, $wrapper ) {
		$this->add_responsive_control(
			$name . '_width',
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
					'unit' => 'px',
				),
				'selectors'  => array(
					$wrapper => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_height',
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
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'  => array(
					$wrapper => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					$wrapper => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					$wrapper => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			$name . 'object-fit',
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
					$wrapper => 'object-fit: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Render.
	 */
	public function render() {
		$settings      = $this->get_settings_for_display();
		$attr_taxonomy = $settings['select_product_attribute'];
		if ( empty( $attr_taxonomy ) ) {
			return;
		}
		$json = wp_json_encode( $this->get_swiper_carousel_options( $this, $settings ) );
		$this->add_render_attribute( 'mas-attributes', 'class', 'mas-attributes-options' );
		$this->add_render_attribute( 'mas-attributes-wrapper', 'class', 'mas-product-attributes' );
		if ( 'yes' === $settings['enable_carousel'] ) {
			$this->add_render_attribute( 'mas-attributes', 'class', 'swiper' );
			$this->add_render_attribute( 'mas-attributes', 'data-swiper-options', $json );
			$this->add_render_attribute( 'mas-attributes-wrapper', 'class', 'swiper-wrapper' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'mas-attributes' ); ?>>
			<div <?php $this->print_render_attribute_string( 'mas-attributes-wrapper' ); ?>>
				<?php $this->render_attributes( $settings ); ?>
			</div>
			<?php echo wp_kses_post( $this->carousel_loop_footer( $this, $settings ) ); ?>
		</div>
		<?php

	}

	/**
	 * Render attributes.
	 *
	 * @param array $settings Settings of this widget.
	 */
	public function render_attributes( $settings ) {
		?>
		<?php if ( ! empty( $settings['attributes_title'] ) ) : ?>
			<<?php Utils::print_validated_html_tag( $settings['attributes_title_tag'] ); ?> class="attributes-title"><?php echo esc_html( $settings['attributes_title'] ); ?></<?php Utils::print_validated_html_tag( $settings['attributes_title_tag'] ); ?>>
			<?php
		endif;

		$this->attribute_loop( $settings );

		if ( ! empty( $settings['more_attributes'] ) ) :
			?>
		<div class="all-attributes-link">
			<?php $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) ); ?>
			<a href="<?php echo esc_url( $shop_page_url ); ?>" class="ms-auto"><?php echo esc_html( $settings['more_attributes'] ); ?></a>
		</div>
		<?php endif; ?>
		<?php

	}

	/**
	 * Attribute Loop .
	 *
	 * @param array $settings Settings of this widget.
	 */
	protected function attribute_loop( $settings ) {
		$attr_taxonomy = $settings['select_product_attribute'];
		if ( empty( $attr_taxonomy ) ) {
			return;
		}
		$orderby            = $settings['orderby'];
		$order              = $settings['order'];
		$empty              = 'yes' === $settings['hide_empty'] ? true : false;
		$attribute          = get_taxonomy( $attr_taxonomy );
		$attributes_options = array(
			'taxonomy'   => $attr_taxonomy,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $empty,
			'number'     => $settings['number'],
		);
		if ( ! empty( $settings['select_terms'] ) ) {
			$attributes_options['include'] = $settings['select_terms'];
		}

		$thumbnail_name   = $settings['thumbnail_name'];
		$cover_image_name = $settings['cover_image_name'];
		$terms            = get_terms( $attributes_options );
		foreach ( $terms as $index => $term ) :
			$this->add_render_attribute( 'attribute-card-list' . $index, 'class', 'attribute-card-list' );
			if ( 'yes' === $settings['enable_carousel'] ) {
				$this->add_render_attribute( 'attribute-card-list' . $index, 'class', 'swiper-slide' );
			}
			?>
			<div <?php $this->print_render_attribute_string( 'attribute-card-list' . $index ); ?>>
				<?php if ( $attribute->public ) : ?>
					<a href="<?php echo esc_url( apply_filters( 'mas_brand_link', get_term_link( $term ), $term ) ); ?>" class="card-item card-link">
				<?php else : ?>
					<div class="card-item card-normal">
				<?php endif; ?>
				<?php
					$thumbnail_id     = defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.6', '<' ) ? get_woocommerce_term_meta( $term->term_id, $thumbnail_name, true ) : get_term_meta( $term->term_id, $thumbnail_name, true );
					$image_attributes = '';

				if ( $thumbnail_id ) {

					$image_attributes = wp_get_attachment_image_src( $thumbnail_id, 'full' );

					if ( $image_attributes ) {
						$image_src    = $image_attributes[0];
						$image_width  = $image_attributes[1];
						$image_height = $image_attributes[2];
					}
				}

				if ( empty( $image_attributes ) ) {
					if ( 'yes' === $settings['enable_thumbnail_placeholder'] ) {
						$image_src = wc_placeholder_img_src();
					} else {
						$image_src = '';
					}
				}

					$image_src = str_replace( ' ', '%20', $image_src );

					$this->add_render_attribute( 'thumbnail-img' . $index, 'class', 'mas-thumbnail-image' );
					$this->add_render_attribute( 'thumbnail-img' . $index, 'src', $image_src );

				if ( ! empty( $thumbnail_id ) ) {
					// Get alt text from attachment meta.
					$thumbnail_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
				}
				if ( ! empty( $thumbnail_alt ) ) {
					$this->add_render_attribute( 'thumbnail-img' . $index, 'alt', $thumbnail_alt );
				}

					$cover_image_url       = mas_elementor_get_field( $cover_image_name, $term );
					$cover_image_url_valid = wp_http_validate_url( $cover_image_url );
				if ( $cover_image_url_valid ) {
					$cover_image_id = attachment_url_to_postid( $cover_image_url );

					$cover_image_alt = get_post_meta( $cover_image_id, '_wp_attachment_image_alt', true );
				} else {

					if ( 'yes' === $settings['enable_cover_placeholder'] ) {
						$cover_image_url = wc_placeholder_img_src();
					} else {
						$cover_image_url = '';
					}
				}

					$this->add_render_attribute( 'cover-img' . $index, 'class', 'mas-cover-image' );
					$this->add_render_attribute( 'cover-img' . $index, 'src', $cover_image_url );

				if ( ! empty( $cover_image_alt ) ) {
					$this->add_render_attribute( 'cover-img' . $index, 'alt', $cover_image_alt );
				}
				if ( 'yes' === $settings['enable_thumbnail_image'] && ! empty( $image_src ) ) :
					?>
						<img <?php $this->print_render_attribute_string( 'thumbnail-img' . $index ); ?>>
					<?php endif; ?>
					<?php if ( $term->description && 'yes' === $settings['enable_term_description'] ) : ?>
						<div class="mas-term-description"><?php echo esc_html( $term->description ); ?></div>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['enable_thumbnail_image'] && ! empty( $cover_image_url ) ) : ?>
						<img <?php $this->print_render_attribute_string( 'cover-img' . $index ); ?>>
					<?php endif; ?>
				<?php if ( $attribute->public ) : ?>
					</a>
				<?php else : ?>
					</div>
				<?php endif; ?>
			</div>
			<?php
		endforeach;
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
