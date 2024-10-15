<?php
/**
 * The Product Data Tabs  Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class Product Data Tabs.
 */
class Product_Data_Tabs extends Base_Widget {
	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-product-data-tabs';
	}
	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Product Data Tabs', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-tabs';
	}
	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'data', 'product', 'tabs' );
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
	 * Register controls.
	 */
	protected function register_controls() {

		// Section for TAB Wrapper Controls in STYLE Tab.
		$this->start_controls_section(
			'tabs_wrapper_section',
			array(
				'label' => esc_html__( 'Tabs Wrapper', 'mas-addons-for-elementor' ),
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

		$this->start_controls_tabs( 'mas_product_data_tab' );

		// Spacing Tab.
		$this->start_controls_tab(
			'mas_product_data_tab_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		// Border Tab.
		$this->start_controls_tab(
			'mas_product_data_tab_border',
			array(
				'label' => esc_html__( 'Border', 'mas-addons-for-elementor' ),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_product_data_tab_border',
				'selector' => '{{WRAPPER}} .woocommerce-tabs',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_product_data_tab_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-tabs',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for UL Wrapper Controls in STYLE Tab.
		$this->start_controls_section(
			'UL_wrapper_section',
			array(
				'label' => esc_html__( 'UL Wrapper', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'mas_product_data_tab_ul' );

		// Spacing Tab.
		$this->start_controls_tab(
			'mas_product_data_tab_ul_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_ul_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_ul_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		// Border Tab.
		$this->start_controls_tab(
			'mas_product_data_tab_ul_border',
			array(
				'label' => esc_html__( 'Border', 'mas-addons-for-elementor' ),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_product_data_tab_ul_border',
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_ul_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_product_data_tab_ul_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for LI Wrapper Controls in STYLE Tab.
		$this->start_controls_section(
			'li_wrapper_section',
			array(
				'label' => esc_html__( 'LI Wrapper', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'mas_product_data_tab_li' );

		// Spacing Tab.
		$this->start_controls_tab(
			'mas_product_data_tab_li_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_li_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_li_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_product_data_tab_li_border',
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_li_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_product_data_tab_li_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li',
			)
		);

		// Background Controls.
		$this->add_control(
			'mas_product_data_tab_li_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		// Spacing Tab.
		$this->start_controls_tab(
			'mas_product_data_tab_li_active',
			array(
				'label' => esc_html__( 'Active', 'mas-addons-for-elementor' ),
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_li_active_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_li_active_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_product_data_tab_li_active_border',
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_li_active_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_product_data_tab_li_active_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active',
			)
		);

		// Background Controls.
		$this->add_control(
			'mas_product_data_tab_li_active_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section for Anchor Controls in STYLE Tab.
		$this->start_controls_section(
			'mas_product_data_tab_anchor',
			array(
				'label' => esc_html__( 'Anchor Element', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Color Controls.
		$this->add_control(
			'anchor_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'color: {{VALUE}}',
				),
			)
		);

		// Typography Controls.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'anchor_typography',
				'label'    => esc_html__( 'Typography', 'mas-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a',
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_anchor_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_anchor_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'mas_product_data_tab_anchor_border',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'border-color: {{VALUE}}',
				),
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_anchor_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_product_data_tab_anchor_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a',
			)
		);

		$this->end_controls_section();

		// Section for Content Wrapper Controls in STYLE Tab.
		$this->start_controls_section(
			'mas_product_data_tab_content_wrapper',
			array(
				'label' => esc_html__( 'Content Wrapper', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_content_wrapper_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_content_wrapper_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Section for Content Heading Controls in STYLE Tab.
		$this->start_controls_section(
			'mas_product_data_tab_title',
			array(
				'label' => esc_html__( 'Content Title', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Color Controls.
		$this->add_control(
			'mas_product_data_tab_heading_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel h2' => 'color: {{VALUE}};display: block;',
				),
			)
		);

		// Typography Controls.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mas_product_data_tab_heading_typography',
				'label'    => esc_html__( 'Title Typography', 'mas-addons-for-elementor' ),
				'selector' => ' {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel h2',
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Section for Content Description Controls in STYLE Tab.
		$this->start_controls_section(
			'mas_product_data_tab_description',
			array(
				'label' => esc_html__( 'Content Description', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'mas_product_data_tab_description_color',
			array(
				'label'     => esc_html__( 'Description Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'color: {{VALUE}}',
				),
			)
		);

		// Typography Controls.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mas_product_data_tab_description_typography',
				'label'    => esc_html__( 'Description Typography', 'mas-addons-for-elementor' ),
				'selector' => ' {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel',
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_product_data_tab_content_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render Widget
	 */
	protected function render() {
		global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		setup_postdata( $product->get_id() );

		// wc_get_template( 'single-product/tabs/tabs.php' ) .
		mas_elementor_get_template( 'woocommerce/single-product/tabs/tabs.php' );
		// On render widget from Editor - trigger the init manually.
		if ( wp_doing_ajax() ) {
			?>
			<script>
				jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
			</script>
			<?php
		}
	}
	/**
	 * Render Plain Content
	 */
	public function render_plain_content() {}
	/**
	 * Get the group name of the widget.
	 *
	 * @return string
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
