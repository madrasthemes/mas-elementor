<?php
/**
 * Countdown Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\AddToWishlist;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module
 */
class Module extends Module_Base {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	/**
	 * Get Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'yith_wcwl_add_to_wishlist';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/yith_wcwl_add_to_wishlist/product_section/after_section_end', array( $this, 'add_content_style_controls' ), 15 );
	}

	/**
	 * Add style controls to the element.
	 *
	 * @param Element $element element object.
	 */
	public function add_content_style_controls( $element ) {

		$element->start_controls_section(
			'wishlist_style',
			array(
				'label' => esc_html__( 'Style', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$element->add_responsive_control(
			'wishlist_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 14,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .yith-wcwl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$element->add_responsive_control(
			'wishlist_icon_width',
			array(
				'label'     => esc_html__( 'Icon Width', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .yith-wcwl-icon' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$element->add_responsive_control(
			'wishlist_icon_height',
			array(
				'label'     => esc_html__( 'Icon Height', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .yith-wcwl-icon' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$element->add_responsive_control(
			'wishlist_icon_text_gap',
			array(
				'label'     => esc_html__( 'Icon Text Gap', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .yith-wcwl-add-to-wishlist-button' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$element->add_control(
			'wishlist_icon_text_hide',
			array(
				'label'       => esc_html__( 'Hide Wishlist Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => 'Hide the wishlist text',
				'label_off'   => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'    => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'return_value' => 'none',
				'selectors'   => array(
					'{{WRAPPER}} .yith-wcwl-add-to-wishlist-button__label' => 'display: {{VALUE}} !important;',
				),
			)
		);

		$element->add_control(
			'wishlist_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .yith-wcwl-icon' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$element->add_responsive_control(
			'opacity_cart',
			array(
				'label'     => esc_html__( 'Icon Opacity', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'opacity: {{SIZE}};',
				),
			)
		);

		$element->add_responsive_control(
			'opacity_cart_hover',
			array(
				'label'     => esc_html__( 'Card Hover Opacity', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}:hover' => 'opacity: {{SIZE}} !important;',
					'.mas-product .elementor > *:hover .elementor-widget-yith_wcwl_add_to_wishlist' => 'opacity: {{SIZE}} !important;',
				),
			)
		);

		$element->add_control(
			'wishlist_icon_wishlisted_color',
			array(
				'label'     => esc_html__( 'Icon Wishlisted Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .feedback .yith-wcwl-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$element->add_responsive_control(
			'wishlist_icon_icon_margin',
			array(
				'label'      => esc_html__( 'Icon Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .yith-wcwl-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$element->add_responsive_control(
			'wishlist_icon_wishlisted_icon_margin',
			array(
				'label'      => esc_html__( 'Wishlisted Icon Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .feedback .yith-wcwl-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$element->end_controls_section();
	}

}
