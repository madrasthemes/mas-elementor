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
		return 'woocommerce-product-data-tabs';
	}
	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Product Data Tabs', 'mas-elementor' );
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
	 * Register controls.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_product_tabs_style',
			array(
				'label' => esc_html__( 'Tabs', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'mas-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab(
			'normal_tabs_style',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tab_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tabs_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'active_tabs_style',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'active_tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'active_tab_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel, .woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'background-color: {{VALUE}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'border-bottom-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'active_tabs_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'border-color: {{VALUE}} {{VALUE}} {{active_tab_bg_color.VALUE}} {{VALUE}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li:not(.active)' => 'border-bottom-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'separator_tabs_style',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_typography',
				'label'    => esc_html__( 'Typography', 'mas-elementor' ),
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a',
			)
		);

		$this->add_control(
			'tab_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_panel_style',
			array(
				'label' => esc_html__( 'Panel', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-Tabs-panel' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'label'    => esc_html__( 'Typography', 'mas-elementor' ),
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel',
			)
		);

		$this->add_control(
			'heading_panel_heading_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Heading', 'mas-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-Tabs-panel h2' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_heading_typography',
				'label'    => esc_html__( 'Typography', 'mas-elementor' ),
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel h2',
			)
		);

		$this->add_control(
			'separator_panel_style',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'panel_border_width',
			array(
				'label'     => esc_html__( 'Border Width', 'mas-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; margin-top: -{{TOP}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'panel_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs' => 'margin-left: {{TOP}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'panel_box_shadow',
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel',
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

		wc_get_template( 'single-product/tabs/tabs.php' );

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
