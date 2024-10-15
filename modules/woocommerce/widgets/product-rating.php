<?php
/**
 * The Product Rating Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Product_Rating
 */
class Product_Rating extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-product-rating';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Product Rating', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-rating';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'rating', 'review', 'comments', 'stars', 'product' );
	}

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'mas-product-rating' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_product_rating_style',
			array(
				'label' => esc_html__( 'Style', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'mas-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => esc_html__( 'Star Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .star-rating' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'empty_star_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .star-rating::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-review-link' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-review-link',
			)
		);

		$this->add_control(
			'star_position',
			array(
				'label'     => esc_html__( 'Star Position', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''         => 'Relative',
					'absolute' => 'Absolute',
				),
				'selectors' => array(
					'{{WRAPPER}} .star-rating > span' => 'position:{{VALUE}}',
				),
			)
		);

		$left  = esc_html__( 'Left', 'mas-addons-for-elementor' );
		$right = esc_html__( 'Right', 'mas-addons-for-elementor' );

		$start = is_rtl() ? $right : $left;
		$end   = ! is_rtl() ? $right : $left;

		$wrapper = '{{WRAPPER}} .star-rating:before';

		$this->add_control(
			'icon_offset_orientation_h',
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
					'star_position' => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'icon_offset_x',
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
				'size_units' => array( 'px', '%', 'vw', 'vh', 'custom' ),
				'selectors'  => array(
					'body:not(.rtl) ' . $wrapper => 'left: {{SIZE}}{{UNIT}} !important',
					'body.rtl ' . $wrapper       => 'right: {{SIZE}}{{UNIT}} !important',
				),
				'condition'  => array(
					'icon_offset_orientation_h!' => 'end',
					'star_position' => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'icon_offset_x_end',
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
				'size_units' => array( 'px', '%', 'vw', 'vh', 'custom' ),
				'selectors'  => array(
					'body:not(.rtl) ' . $wrapper => 'right: {{SIZE}}{{UNIT}} !important',
					'body.rtl ' . $wrapper       => 'left: {{SIZE}}{{UNIT}} !important',
				),
				'condition'  => array(
					'icon_offset_orientation_h' => 'end',
					'star_position' => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'icon_offset_y',
			array(
				'label'      => esc_html__( 'Top', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => -1000,
						'max' => 1000,
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
					'size' => 0,
				),
				'selectors'  => array(
					$wrapper => 'top: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'star_position' => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'icon_offset_y_end',
			array(
				'label'      => esc_html__( 'Bottom', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => -1000,
						'max' => 1000,
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
					'size' => 0,
				),
				'selectors'  => array(
					$wrapper => 'bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'star_position' => 'absolute',
				),
			)
		);

		$this->add_responsive_control(
			'star_size',
			array(
				'label'      => esc_html__( 'Star Size', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'unit' => 'em',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'.woocommerce {{WRAPPER}} .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'unit' => 'em',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'.woocommerce:not(.rtl) {{WRAPPER}} .star-rating' => 'margin-right: {{SIZE}}{{UNIT}}',
					'.woocommerce.rtl {{WRAPPER}} .star-rating' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'prefix_class' => 'mas-elementor-product-rating--align-',
			)
		);

		$this->add_responsive_control(
			'icons_margin',
			array(
				'label'      => esc_html__( 'Icons Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'hide_review',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Review', 'mas-addons-for-elementor' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'return_value' => 'hide',
				'prefix_class' => 'mas-product-review-',
				'description'  => esc_html__( 'Hides the review link', 'mas-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';

		$this->start_controls_section(
			'product_rating_flex_options',
			array(
				'label' => esc_html__( 'Flex Options', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'enable_flex_options',
				array(
					'label'       => esc_html__( 'Enable Flex Options', 'mas-addons-for-elementor' ),
					'type'        => Controls_Manager::SWITCHER,
					'default'     => 'no',
					'description' => esc_html__( 'Rating flex options', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_responsive_control(
				'rating_enable_flex',
				array(
					'label'     => esc_html__( 'Enable Flex', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'block' => array(
							'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-ban',
						),
						'flex'  => array(
							'title' => esc_html__( 'Flex', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-wrap',
						),
					),
					'default'   => 'flex',
					'selectors' => array(
						'{{WRAPPER}} .star-rating > span' => 'display: {{VALUE}};',
					),
					'condition' => array(
						'enable_flex_options' => 'yes',
					),

				)
			);

			$this->add_responsive_control(
				'rating_flex_wrap',
				array(
					'label'       => esc_html__( 'Wrap', 'mas-addons-for-elementor' ),
					'type'        => Controls_Manager::CHOOSE,
					'options'     => array(
						'nowrap' => array(
							'title' => esc_html__( 'No Wrap', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-nowrap',
						),
						'wrap'   => array(
							'title' => esc_html__( 'Wrap', 'mas-addons-for-elementor' ),
							'icon'  => 'eicon-flex eicon-wrap',
						),
					),
					'description' => esc_html_x(
						'Items within the container can stay in a single line (No wrap), or break into multiple lines (Wrap).',
						'Flex Container Control',
						'mas-addons-for-elementor'
					),
					'default'     => 'wrap',
					'selectors'   => array(
						'{{WRAPPER}} .star-rating > span' => 'flex-wrap: {{VALUE}};',
					),
					'condition'   => array(
						'enable_flex_options' => 'yes',
					),
				)
			);

		$this->add_responsive_control(
			'rating_flex_direction',
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .star-rating > span' => 'flex-direction:{{VALUE}};',
				),
				'condition' => array_merge(
					array(
						'enable_flex' => 'flex',
					),
					array(
						'enable_flex_options' => 'yes',
					),
				),
				'default'   => 'column',
			)
		);

		$this->add_responsive_control(
			'rating_justify_content',
			array(
				'label'       => esc_html__( 'Justify Content', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'default'     => '',
				'options'     => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-start-h',
					),
					'center'        => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-center-h',
					),
					'flex-end'      => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
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
					'{{WRAPPER}} .star-rating > span' => 'justify-content: {{VALUE}};',
				),
				'default'     => 'center',
				'condition'   => array(
					'enable_flex_options' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rating_align_items',
			array(
				'label'     => esc_html__( 'Align Items', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .star-rating > span' => 'align-items: {{VALUE}};',
				),
				'default'   => 'stretch',
				'condition' => array(
					'enable_flex_options' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rating_gap',
			array(
				'label'      => esc_html__( 'Gap', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'vw' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .star-rating > span' => 'gap: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'enable_flex_options' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		if ( ! post_type_supports( 'product', 'comments' ) ) {
			return;
		}

		global $product;
		$product = $this->get_product();

		if ( ! $product ) {
			return;
		}

		wc_get_template( 'single-product/rating.php' );
	}

	/**
	 * Render Plain Content.
	 */
	public function render_plain_content() {}

	/**
	 * Get group name.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
