<?php
/**
 * The Product Title Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

 namespace MASElementor\Modules\DynamicHeading\Widgets;

use Elementor\Widget_Heading;
use MASElementor\Base\Base_Widget_Trait;
use MASElementor\Plugin;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Product_Title
 */
class Dynamic_Heading extends Widget_Heading {

	use Base_Widget_Trait;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-dynamic-heading';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Dynamic Heading', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-title';
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
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'title', 'heading' );
	}

	/**
	 * Get inline css to the widget.
	 *
	 * @return array
	 */
	public function get_inline_css_depends() {
		return array(
			array(
				'name'               => 'heading',
				'is_core_dependency' => true,
			),
		);
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();
		$this->icon_controls();
		$this->wrapper_controls();
	}

	/**
	 * Wrapper Controls.
	 */
	protected function wrapper_controls() {
		$this->start_controls_section(
			'section_wrapper',
			array(
				'label' => esc_html__( 'Wrapper', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'enable_flex_wrap',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Flex Wrap', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Wrap', 'mas-addons-for-elementor' ),
				'return_value' => 'wrap',
				'prefix_class' => 'mas-dynamic-heading-wrapper%s-',
			)
		);

		$this->add_responsive_control(
			'wrap_align',
			array(
				'label'     => esc_html__( 'Wrap Alignment', 'mas-addons-for-elementor' ),
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-dynamic-heading-wrapper' => 'display:flex; justify-content: {{VALUE}} !important;',
				),
				'condition' => array(
					'enable_flex_wrap!' => 'no',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add Icon Controls.
	 */
	protected function icon_controls() {
		$this->start_controls_section(
			'section_icon',
			array(
				'label' => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'            => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'icon_view',
			array(
				'label'        => esc_html__( 'View', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'default' => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'stacked' => esc_html__( 'Stacked', 'mas-addons-for-elementor' ),
					'framed'  => esc_html__( 'Framed', 'mas-addons-for-elementor' ),
				),
				'default'      => 'default',
				'prefix_class' => 'elementor-view-',
			)
		);

		$this->add_control(
			'shape',
			array(
				'label'        => esc_html__( 'Shape', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'circle' => esc_html__( 'Circle', 'mas-addons-for-elementor' ),
					'square' => esc_html__( 'Square', 'mas-addons-for-elementor' ),
				),
				'default'      => 'circle',
				'condition'    => array(
					'view!' => 'default',
				),
				'prefix_class' => 'elementor-shape-',
			)
		);

		$this->add_control(
			'icon_link',
			array(
				'label'       => esc_html__( 'Link', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'https://your-link.com', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'icon_align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			array(
				'label' => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'primary_color',
			array(
				'label'     => esc_html__( 'Primary Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon svg' => 'fill: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
			)
		);

		$this->add_control(
			'secondary_color',
			array(
				'label'     => esc_html__( 'Secondary Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'view!' => 'default',
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'hover_primary_color',
			array(
				'label'     => esc_html__( 'Primary Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_secondary_color',
			array(
				'label'     => esc_html__( 'Secondary Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'view!' => 'default',
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label' => esc_html__( 'Hover Animation', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-icon svg' => 'height: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'fit_to_size',
			array(
				'label'       => esc_html__( 'Fit to Size', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => 'Avoid gaps around icons when width and height aren\'t equal',
				'label_off'   => esc_html__( 'Off', 'mas-addons-for-elementor' ),
				'label_on'    => esc_html__( 'On', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'selected_icon[library]' => 'svg',
				),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-wrapper svg' => 'width: 100%;',
				),
			)
		);

		$this->add_control(
			'icon_padding',
			array(
				'label'     => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'range'     => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
				'condition' => array(
					'view!' => 'default',
				),
			)
		);

		$this->add_responsive_control(
			'rotate',
			array(
				'label'          => esc_html__( 'Rotate', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'deg', 'grad', 'rad', 'turn', 'custom' ),
				'default'        => array(
					'unit' => 'deg',
				),
				'tablet_default' => array(
					'unit' => 'deg',
				),
				'mobile_default' => array(
					'unit' => 'deg',
				),
				'selectors'      => array(
					'{{WRAPPER}} .elementor-icon i, {{WRAPPER}} .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->add_control(
			'border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'icon_view' => 'framed',
				),
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'view!' => 'default',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get HTML wrapper class.
	 */
	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' elementor-page-title elementor-widget-' . parent::get_name();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( '' === $settings['title'] ) {
			return;
		}
		?><div class="mas-dynamic-heading-wrapper" style="display:flex;">
		<?php
		$this->icon_render();
		parent::render();
		?>
		</div>
		<?php
	}

	/**
	 * Render Icon.
	 */
	protected function icon_render() {
		$settings = $this->get_settings_for_display();

		if ( '' === $settings['title'] ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-icon-wrapper' );

		$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-icon' );

		if ( ! empty( $settings['hover_animation'] ) ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		$icon_tag = 'div';

		if ( ! empty( $settings['icon_link']['url'] ) ) {
			$this->add_link_attributes( 'icon-wrapper', $settings['icon_link'] );

			$icon_tag = 'a';
		}

		if ( empty( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default.
			$settings['icon'] = 'fa fa-star';
		}

		if ( ! empty( $settings['icon'] ) ) {
			$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			$this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
		}

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<<?php Utils::print_unescaped_internal_string( $icon_tag . ' ' . $this->get_render_attribute_string( 'icon-wrapper' ) ); ?>>
			<?php
			if ( $is_new || $migrated ) :
				Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
			else :
				?>
				<i <?php $this->print_render_attribute_string( 'icon' ); ?>></i>
			<?php endif; ?>
			</<?php Utils::print_unescaped_internal_string( $icon_tag ); ?>>
		</div>
		<?php
	}

}
