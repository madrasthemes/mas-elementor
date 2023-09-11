<?php
/**
 * Megamenu.
 *
 * @package MASElementor/Modules/MegaMenu/Widgets
 */

namespace MASElementor\Modules\MegaMenu\Widgets;

use MASElementor\Base\Base_Widget_Trait;
use MASElementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Modules\NestedElements\Base\Widget_Nested_Base;
use Elementor\Modules\NestedElements\Controls\Control_Nested_Repeater;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use MASElementor\Modules\MegaMenu\Traits\Url_Helper_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Megamenu.
 */
class Mega_Menu extends Widget_Nested_Base {
	use Base_Widget_Trait;
	use Url_Helper_Trait;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mega-menu';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Menu', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-mega-menu';
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'pro-elements', 'theme-elements' );
	}

	/**
	 * Get the Keywords for the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'Mega Menu', 'Nested Elements' );
	}

	/**
	 * Get content horizontal controls.
	 *
	 * @return array
	 */
	private function get_content_horizontal_controls(): array {
		$horizontal_controls = array(
			'left'   => array(
				'title' => esc_html__( 'Left', 'mas-elementor' ),
				'icon'  => 'eicon-h-align-left',
			),
			'center' => array(
				'title' => esc_html__( 'Center', 'mas-elementor' ),
				'icon'  => 'eicon-h-align-center',
			),
			'right'  => array(
				'title' => esc_html__( 'Right', 'mas-elementor' ),
				'icon'  => 'eicon-h-align-right',
			),
		);

		return is_rtl() ? array_reverse( $horizontal_controls ) : $horizontal_controls;
	}

	/**
	 * Get default children elements.
	 */
	protected function get_default_children_elements() {
		return array(
			array(
				'elType'   => 'container',
				'settings' => array(
					'_title' => __( 'Item #1', 'mas-elementor' ),
				),
			),
			array(
				'elType'   => 'container',
				'settings' => array(
					'_title' => __( 'Item #2', 'mas-elementor' ),
				),
			),
			array(
				'elType'   => 'container',
				'settings' => array(
					'_title' => __( 'Item #3', 'mas-elementor' ),
				),
			),
		);
	}

	/**
	 * Get default repeater title setting key.
	 */
	protected function get_default_repeater_title_setting_key() {
		return 'item_title';
	}

	/**
	 * Get default children title.
	 */
	protected function get_default_children_title() {
		return esc_html__( 'Item #%d', 'mas-elementor' ); //phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
	}

	/**
	 * Get default children placeholder selector.
	 */
	protected function get_default_children_placeholder_selector() {
		return '.e-n-menu-items-content';
	}

	/**
	 * Get html wrapper class.
	 */
	protected function get_html_wrapper_class() {
		return 'elementor-widget-n-menu';
	}

	/**
	 * Define a selector class for a widget control.
	 *
	 * @param string $control_item The name of the element which we need to select.
	 * @param string $state The state of the selector, e.g. `:hover` or `:focus`.
	 *
	 * @return string The css selector for our element.
	 * @since 3.12.0
	 */
	protected function get_control_selector_class( $control_item, $state = '' ) {
		if ( 'menu_toggle_icon' === $control_item ) {
			return "{{WRAPPER}} > .elementor-widget-container > .e-n-menu > .e-n-menu-toggle{$state} > .e-n-menu-toggle-icon";
		} elseif ( 'active_content_container' === $control_item ) {
			return ":where( {{WRAPPER}} > .elementor-widget-container > .e-n-menu > .e-n-menu-items-content ) > .e-con{$state}";
		}
	}

	/**
	 * Get Typography Selector
	 *
	 * Returns a selector class for the typography widget control.
	 *
	 * @param string $menu_mobile_and_desktop_css_selector The css selector for the menu.
	 *
	 * @return string The css selector for the typography control.
	 */
	protected function get_typography_selector( $menu_mobile_and_desktop_css_selector ): string {
		$typography_selector  = "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title";
		$typography_selector .= ", {$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title > .e-n-menu-item-title-text";
		$typography_selector .= ", {$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title > .e-n-menu-item-title-text > a.e-n-menu-item-title-link";

		return $typography_selector;
	}

	/**
	 * Register controls.
	 */
	protected function register_controls() {
		$menu_mobile_and_desktop_css_selector = ':is( {{WRAPPER}} > .elementor-widget-container > .e-n-menu > .e-n-menu-items-heading, {{WRAPPER}} > .elementor-widget-container > .e-n-menu > .e-n-menu-items-content )';
		$start                                = is_rtl() ? 'right' : 'left';
		$end                                  = is_rtl() ? 'left' : 'right';
		$tooltip_start                        = is_rtl() ? esc_html__( 'Right', 'mas-elementor' ) : esc_html__( 'Left', 'mas-elementor' );
		$tooltip_end                          = is_rtl() ? esc_html__( 'Left', 'mas-elementor' ) : esc_html__( 'Right', 'mas-elementor' );
		$start_logical                        = is_rtl() ? 'end' : 'start';
		$end_logical                          = is_rtl() ? 'start' : 'end';

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'mas-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title',
			array(
				'label'       => esc_html__( 'Title', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Item Title', 'mas-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'item_link',
			array(
				'label'              => esc_html__( 'Link', 'mas-elementor' ),
				'type'               => Controls_Manager::URL,
				'placeholder'        => esc_html__( 'Paste URL or type', 'mas-elementor' ),
				'dynamic'            => array(
					'active' => true,
				),
				'frontend_available' => true,
			)
		);

		$repeater->add_control(
			'item_dropdown_content',
			array(
				'label'              => esc_html__( 'Dropdown Content', 'mas-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => esc_html__( 'OFF', 'mas-elementor' ),
				'label_on'           => esc_html__( 'ON', 'mas-elementor' ),
				'default'            => 'no',
				'description'        => esc_html__( 'Click on the menu item to edit its dropdown content.', 'mas-elementor' ),
				'frontend_available' => true,
			)
		);

		$repeater->add_control(
			'item_icon',
			array(
				'label'            => esc_html__( 'Icon', 'mas-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
			)
		);

		$repeater->add_control(
			'item_icon_active',
			array(
				'label'            => esc_html__( 'Active Icon', 'mas-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'condition'        => array(
					'item_icon[value]!' => '',
				),
			)
		);

		$repeater->add_control(
			'element_id',
			array(
				'label'          => esc_html__( 'CSS ID', 'mas-elementor' ),
				'type'           => Controls_Manager::TEXT,
				'default'        => '',
				'dynamic'        => array(
					'active' => true,
				),
				'title'          => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'mas-elementor' ),
				'style_transfer' => false,
			)
		);

		$this->add_control(
			'menu_items',
			array(
				'label'              => esc_html__( 'Menu Items', 'mas-elementor' ),
				'type'               => Control_Nested_Repeater::CONTROL_TYPE,
				'fields'             => $repeater->get_controls(),
				'default'            => array(
					array(
						'item_title' => esc_html__( 'Item #1', 'mas-elementor' ),
					),
					array(
						'item_title' => esc_html__( 'Item #2', 'mas-elementor' ),
					),
					array(
						'item_title' => esc_html__( 'Item #3', 'mas-elementor' ),
					),
				),
				'title_field'        => '{{{ item_title }}}',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'content_width',
			array(
				'label'                => esc_html__( 'Content Width', 'mas-elementor' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'full_width',
				'separator'            => 'before',
				'options'              => array(
					'full_width'     => esc_html__( 'Full Width', 'mas-elementor' ),
					'fit_to_content' => esc_html__( 'Fit To Content', 'mas-elementor' ),
				),
				'prefix_class'         => 'e-',
				'frontend_available'   => true,
				'selectors'            => array(
					'{{WRAPPER}}' => '--n-menu-dropdown-content-max-width: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'full_width'     => 'initial',
					'fit_to_content' => 'fit-content',
				),
			)
		);

		$this->add_control(
			'content_horizontal_position',
			array(
				'label'              => esc_html__( 'Content Horizontal Position', 'mas-elementor' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => $this->get_content_horizontal_controls(),
				'default'            => 'center',
				'condition'          => array(
					'content_width' => 'fit_to_content',
				),
				'frontend_available' => true,
				'render_type'        => 'ui',
			)
		);

		$this->add_control(
			'item_layout',
			array(
				'label'              => esc_html__( 'Item Layout', 'mas-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'horizontal',
				'options'            => array(
					'horizontal' => esc_html__( 'Horizontal', 'mas-elementor' ),
					'dropdown'   => esc_html__( 'Dropdown', 'mas-elementor' ),
				),
				'prefix_class'       => 'e-n-menu-layout-',
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'item_position_horizontal',
			array(
				'label'                => esc_html__( 'Item Position', 'mas-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'   => array(
						'title' => esc_html__( 'Start', 'mas-elementor' ),
						'icon'  => "eicon-align-$start_logical-h",
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-align-center-h',
					),
					'end'     => array(
						'title' => esc_html__( 'End', 'mas-elementor' ),
						'icon'  => "eicon-align-$end_logical-h",
					),
					'stretch' => array(
						'title' => esc_html__( 'Stretch', 'mas-elementor' ),
						'icon'  => 'eicon-align-stretch-h',
					),
				),
				'selectors_dictionary' => array(
					'start'   => '--n-menu-items-heading-justify-content: initial; --n-menu-item-title-flex-grow: initial; --n-menu-item-title-justify-content: initial; --n-menu-item-title-justify-content-mobile: initial;',
					'center'  => '--n-menu-items-heading-justify-content: center; --n-menu-item-title-flex-grow: initial; --n-menu-item-title-justify-content: initial; --n-menu-item-title-justify-content-mobile: center;',
					'end'     => '--n-menu-items-heading-justify-content: flex-end; --n-menu-item-title-flex-grow: initial; --n-menu-item-title-justify-content: initial; --n-menu-item-title-justify-content-mobile: flex-end;',
					'stretch' => '--n-menu-items-heading-justify-content: space-between; --n-menu-item-title-flex-grow: 1; --n-menu-item-title-justify-content: center; --n-menu-item-title-justify-content-mobile: center;',
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'condition'            => array(
					'item_layout' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'item_position_dropdown',
			array(
				'label'                => esc_html__( 'Item Position', 'mas-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'  => array(
						'title' => esc_html__( 'Start', 'mas-elementor' ),
						'icon'  => "eicon-align-$start_logical-h",
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-align-center-h',
					),
					'end'    => array(
						'title' => esc_html__( 'End', 'mas-elementor' ),
						'icon'  => "eicon-align-$end_logical-h",
					),
				),
				'selectors_dictionary' => array(
					'start'  => '--n-menu-item-title-justify-content: initial;  --n-menu-item-title-justify-content-mobile: initial;',
					'center' => '--n-menu-item-title-justify-content: center; --n-menu-item-title-justify-content-mobile: center;',
					'end'    => '--n-menu-item-title-justify-content: flex-end; --n-menu-item-title-justify-content-mobile: flex-end;',
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'condition'            => array(
					'item_layout' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'submenu_indicator_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Dropdown Indicator', 'mas-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'menu_item_icon',
			array(
				'label'       => esc_html__( 'Icon', 'mas-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-caret-down',
					'library' => 'fa-solid',
				),
				'recommended' => array(
					'fa-solid'   => array(
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					),
					'fa-regular' => array(
						'caret-square-down',
					),
				),
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->add_control(
			'menu_item_icon_active',
			array(
				'label'            => esc_html__( 'Active Icon', 'mas-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_active',
				'default'          => array(
					'value'   => 'fas fa-caret-up',
					'library' => 'fa-solid',
				),
				'recommended'      => array(
					'fa-solid'   => array(
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					),
					'fa-regular' => array(
						'caret-square-up',
					),
				),
				'skin'             => 'inline',
				'label_block'      => false,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dropdown_effect',
			array(
				'label' => esc_html__( 'Dropdown Effect', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'open_on',
			array(
				'label'              => esc_html__( 'Open On', 'mas-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'hover',
				'options'            => array(
					'hover' => esc_html__( 'Hover', 'mas-elementor' ),
					'click' => esc_html__( 'Click', 'mas-elementor' ),
				),
				'condition'          => array(
					'item_layout' => 'horizontal',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'open_on_hover_description',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The hover effect is inactive while editing. Preview your page to see it in action.', 'mas-elementor' ),
				'content_classes' => 'elementor-control-field-description',
				'condition'       => array(
					'item_layout' => 'horizontal',
					'open_on'     => 'hover',
				),
			)
		);

		$this->add_control(
			'open_animation',
			array(
				'label'              => esc_html__( 'Animation', 'mas-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'options'            => array(
					'none'   => esc_html__( 'None', 'mas-elementor' ),
					'fadeIn' => esc_html__( 'Fade in', 'mas-elementor' ), // Key must match the class from animate.css.
				),
				'assets'             => array(
					'styles' => array(
						array(
							'name'       => 'e-animations',
							'conditions' => array(
								'terms' => array(
									array(
										'name'     => 'open_animation',
										'operator' => '!==',
										'value'    => '',
									),
								),
							),
						),
					),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'open_animation_duration',
			array(
				'label'      => esc_html__( 'Animation Duration', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 's', 'ms', 'custom' ),
				'default'    => array(
					'unit' => 'ms',
					'size' => 500,
				),
				'range'      => array(
					'ms' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-open-animation-duration: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'open_animation!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'menu_toggle_section',
			array(
				'label' => esc_html__( 'Menu Toggle', 'mas-elementor' ),
			)
		);

		$this->add_responsive_control(
			'toggle_align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'mas-elementor' ),
						'icon'  => "eicon-align-$start_logical-h",
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'mas-elementor' ),
						'icon'  => "eicon-align-$end_logical-h",
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-toggle-align: {{VALUE}}',
				),
			)
		);

		$this->start_controls_tabs( 'menu_toggle_tabs_section' );

		$this->start_controls_tab(
			'menu_toggle_normal_options',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'menu_toggle_icon_normal',
			array(
				'label'            => esc_html__( 'Icon', 'mas-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => array(
					'inline' => array(
						'none' => array(
							'label' => esc_html__( 'Default', 'mas-elementor' ),
							'icon'  => 'eicon-menu-bar',
						),
						'icon' => array(
							'icon' => 'eicon-star',
						),
					),
				),
				'recommended'      => array(
					'fa-solid'   => array(
						'plus-square',
						'plus',
						'plus-circle',
						'bars',
					),
					'fa-regular' => array(
						'plus-square',
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'menu_toggle_hover_options',
			array(
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'menu_toggle_icon_hover_animation',
			array(
				'label'              => esc_html__( 'Hover Animation', 'mas-elementor' ),
				'type'               => Controls_Manager::HOVER_ANIMATION,
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'menu_toggle_animation_duration',
			array(
				'label'       => esc_html__( 'Animation Duration', 'mas-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 's', 'ms', 'custom' ),
				'render_type' => 'ui',
				'default'     => array(
					'unit' => 'ms',
					'size' => 500,
				),
				'range'       => array(
					'ms' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}}' => '--n-menu-toggle-icon-wrapper-animation-duration: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'menu_toggle_active_options',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'menu_toggle_icon_active',
			array(
				'label'            => esc_html__( 'Icon', 'mas-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => array(
					'inline' => array(
						'none' => array(
							'label' => esc_html__( 'Default', 'mas-elementor' ),
							'icon'  => 'eicon-close',
						),
						'icon' => array(
							'icon' => 'eicon-star',
						),
					),
				),
				'recommended'      => array(
					'fa-solid'   => array(
						'window-close',
						'times-circle',
						'times',
						'minus-square',
						'minus-circle',
						'minus',
					),
					'fa-regular' => array(
						'window-close',
						'times-circle',
						'minus-square',
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_responsive_mega_menu',
			array(
				'label' => esc_html__( 'Responsive Settings', 'mas-elementor' ),
			)
		);

		$dropdown_options     = array();
		$excluded_breakpoints = array(
			'laptop',
			'tablet_extra',
			'widescreen',
		);

		foreach ( Plugin::elementor()->breakpoints->get_active_breakpoints() as $breakpoint_key => $breakpoint_instance ) {
			// Exclude the larger breakpoints from the dropdown selector.
			if ( in_array( $breakpoint_key, $excluded_breakpoints, true ) ) {
				continue;
			}

			$dropdown_options[ $breakpoint_key ] = sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value. */
				esc_html__( '%1$s (%2$s %3$dpx)', 'mas-elementor' ),
				$breakpoint_instance->get_label(),
				'>',
				$breakpoint_instance->get_value()
			);
		}

		$this->add_control(
			'breakpoint_selector',
			array(
				'label'              => esc_html__( 'Breakpoint', 'mas-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'description'        => esc_html__( 'Note: Item layout will switch to dropdown on any screen smaller than the selected breakpoint.', 'mas-elementor' ),
				'options'            => $dropdown_options,
				'default'            => 'tablet',
				'prefix_class'       => 'e-n-menu-',
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_menu_items_style',
			array(
				'label' => esc_html__( 'Menu Items', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'menu_item_title_space_between',
			array(
				'label'      => esc_html__( 'Space between Items', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-item-title-space-between: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'menu_item_title_distance_from_content',
			array(
				'label'              => esc_html__( 'Distance from content', 'mas-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'            => array(
					'size' => 0,
				),
				'selectors'          => array(
					'{{WRAPPER}}' => '--n-menu-item-title-distance-from-content: {{SIZE}}{{UNIT}}',
				),
				'frontend_available' => true,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'menu_item_title_typography',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => $this->get_typography_selector( $menu_mobile_and_desktop_css_selector ),
				'fields_options' => array(
					'font_size'   => array(
						'selectors' => array(
							'{{WRAPPER}}' => '--n-menu-item-title-font-size: {{SIZE}}{{UNIT}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-line-height: {{SIZE}}{{UNIT}}',
							'{{SELECTOR}}' => '--n-menu-item-title-line-height: {{SIZE}}',
						),
					),
				),
			)
		);

		$this->start_controls_tabs( 'menu_item_title_style' );

		$this->start_controls_tab(
			'menu_item_title_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'menu_item_title_text_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-item-title-color-normal: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'           => 'menu_item_title_text_shadow_normal',
				'selector'       => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title:not( .e-current ):not( :hover )",
				'fields_options' => array(
					'text_shadow_type' => array(
						'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'menu_item_title_background_color',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title:not( .e-current ):not( :hover )",
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Background Color', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'menu_item_title_box_border',
				'selector'       => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title:not( .e-current ):not( :hover )",
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Border Color', 'mas-elementor' ),
					),
					'width' => array(
						'label' => esc_html__( 'Border Width', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'menu_item_title_box_shadow',
				'selector' => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title:not( .e-current ):not( :hover )",
			)
		);

		$divider_condition = array(
			'menu_divider' => 'yes',
			'item_layout'  => 'horizontal',
		);

		$this->add_control(
			'menu_divider',
			array(
				'label'     => esc_html__( 'Divider', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'mas-elementor' ),
				'label_on'  => esc_html__( 'On', 'mas-elementor' ),
				'condition' => array(
					'item_layout' => 'horizontal',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-divider-content: "";',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'menu_divider_style',
			array(
				'label'     => esc_html__( 'Style', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'mas-elementor' ),
					'double' => esc_html__( 'Double', 'mas-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-elementor' ),
				),
				'default'   => 'solid',
				'condition' => $divider_condition,
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-divider-style: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_divider_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'condition'  => $divider_condition,
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-divider-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'menu_divider_height',
			array(
				'label'      => esc_html__( 'Height', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'condition'  => $divider_condition,
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-divider-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'menu_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'condition' => $divider_condition,
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-divider-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab(); // End Normal Tab.

		$this->start_controls_tab(
			'menu_item_title_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'menu_item_title_text_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' => '--n-menu-item-title-color-hover: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'           => 'menu_item_title_text_shadow_hover',
				'selector'       => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title:hover:not( .e-current )",
				'fields_options' => array(
					'text_shadow_type' => array(
						'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'menu_item_title_background_color_hover',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title:hover:not( .e-current )",
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Background Color', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'menu_item_title_box_border_hover',
				'selector'       => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title:hover:not( .e-current )",
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Border Color', 'mas-elementor' ),
					),
					'width' => array(
						'label' => esc_html__( 'Border Width', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'menu_item_title_box_shadow_hover',
				'selector' => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title:hover:not( .e-current )",
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label' => esc_html__( 'Hover Animation', 'mas-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->add_control(
			'menu_item_title_transition_duration',
			array(
				'label'      => esc_html__( 'Transition Duration', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 's', 'ms', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-item-title-transition: {{SIZE}}{{UNIT}}',
				),
				'range'      => array(
					'ms' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
				'default'    => array(
					'unit' => 'ms',
					'size' => 300,
				),
			)
		);
		$this->end_controls_tab(); // End Hover Tab.

		$this->start_controls_tab(
			'menu_item_title_active',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'menu_item_title_text_color_active',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' => '--n-menu-item-title-color-active: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'           => 'menu_item_title_text_shadow_active',
				'selector'       => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title.e-current",
				'fields_options' => array(
					'text_shadow_type' => array(
						'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'menu_item_title_background_color_active',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title.e-current",
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Background Color', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'menu_item_title_box_border_active',
				'selector'       => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title.e-current",
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Border Color', 'mas-elementor' ),
					),
					'width' => array(
						'label' => esc_html__( 'Border Width', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'menu_item_title_box_shadow_active',
				'selector' => "{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title.e-current",
			)
		);

		$this->end_controls_tab(); // End Active Tab.

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'menu_item_title_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					"{$menu_mobile_and_desktop_css_selector} > .e-n-menu-item-title" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'menu_item_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-item-title-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Icon Style.
		$this->start_controls_section(
			'icon_section_style',
			array(
				'label' => esc_html__( 'Icon', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_position',
			array(
				'label'                => esc_html__( 'Position', 'mas-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'mas-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'end'    => array(
						'title' => $tooltip_end,
						'icon'  => 'eicon-h-align-' . $end,
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'mas-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'start'  => array(
						'title' => $tooltip_start,
						'icon'  => 'eicon-h-align-' . $start,
					),
				),
				'selectors_dictionary' => array(
					'top'    => '--n-menu-title-direction: column; --n-menu-icon-order: initial; --n-menu-item-icon-align-items: flex-end; --n-menu-item-title-justify-content: center; --n-menu-title-align-items-toggle: initial;',
					'end'    => '--n-menu-title-direction: row; --n-menu-icon-order: 1; --n-menu-item-icon-align-items: initial; --n-menu-item-title-justify-content: initial; --n-menu-title-align-items-toggle: center;',
					'bottom' => '--n-menu-title-direction: column; --n-menu-icon-order: 1; --n-menu-item-icon-align-items: flex-start; --n-menu-item-title-justify-content: center; --n-menu-title-align-items-toggle: initial;',
					'start'  => '--n-menu-title-direction: row; --n-menu-icon-order: initial; --n-menu-item-icon-align-items: initial; --n-menu-item-title-justify-content: initial; --n-menu-title-align-items-toggle: center;',
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 100,
					),
					'em'  => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-icon-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 400,
					),
					'vw' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array( 'px', 'vw' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-icon-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'icon_style_states' );

		$this->start_controls_tab(
			'icon_section_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-icon-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_section_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-icon-color-hover: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_section_active',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'icon_color_active',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-icon-color-active: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_menu_toggle_style',
			array(
				'label' => esc_html__( 'Menu Toggle', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style_menu_toggle_icon_heading',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Toggle Icon', 'mas-elementor' ),
			)
		);

		$this->add_responsive_control(
			'style_menu_toggle_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'   => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'em'  => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-toggle-icon-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'style_menu_toggle_tabs' );

		$this->start_controls_tab(
			'style_menu_toggle_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'menu_toggle_icon_color_normal',
			array(
				'label'       => esc_html__( 'Color', 'mas-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}}' => '--n-menu-toggle-icon-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'menu_toggle_icon_background_color_normal',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->get_control_selector_class( 'menu_toggle_icon', ':not( .e-active ):not( :hover )' ),
				'fields_options' => array(
					'color' => array(
						'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
						'selectors' => array(
							'{{SELECTOR}}' => 'background: {{VALUE}}',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'menu_toggle_icon_border_normal',
				'selector'       => $this->get_control_selector_class( 'menu_toggle_icon', ':not( .e-active ):not( :hover )' ),
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Border Color', 'mas-elementor' ),
					),
					'width' => array(
						'label' => esc_html__( 'Border Width', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'menu_toggle_icon_box_shadow_normal',
				'selector' => $this->get_control_selector_class( 'menu_toggle_icon', ':not( .e-active ):not( :hover )' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_menu_toggle_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'menu_toggle_icon_color_hover',
			array(
				'label'       => esc_html__( 'Color', 'mas-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}}' => '--n-menu-toggle-icon-color-hover: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'menu_toggle_icon_background_color_hover',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->get_control_selector_class( 'menu_toggle_icon', ':hover:is( .e-active, :not( .e-active ) )' ),
				'fields_options' => array(
					'color' => array(
						'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
						'selectors' => array(
							'{{SELECTOR}}' => 'background: {{VALUE}}',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'menu_toggle_icon_border_hover',
				'selector'       => $this->get_control_selector_class( 'menu_toggle_icon', ':hover:is( .e-active, :not( .e-active ) )' ),
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Border Color', 'mas-elementor' ),
					),
					'width' => array(
						'label' => esc_html__( 'Border Width', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'menu_toggle_icon_box_shadow_hover',
				'selector' => $this->get_control_selector_class( 'menu_toggle_icon', ':hover:is( .e-active, :not( .e-active ) )' ),
			)
		);

		$this->add_control(
			'menu_toggle_icon_animation_duration',
			array(
				'label'      => esc_html__( 'Animation Duration', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 's', 'ms', 'custom' ),
				'default'    => array(
					'unit' => 'ms',
					'size' => 500,
				),
				'range'      => array(
					'ms' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-toggle-icon-hover-duration: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_menu_toggle_active',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'menu_toggle_icon_color_active',
			array(
				'label'       => esc_html__( 'Color', 'mas-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}}' => '--n-menu-toggle-icon-color-active: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'menu_toggle_icon_background_color_active',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->get_control_selector_class( 'menu_toggle_icon', '.e-active' ),
				'fields_options' => array(
					'color' => array(
						'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
						'selectors' => array(
							'{{SELECTOR}}' => 'background: {{VALUE}}',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'menu_toggle_icon_border_active',
				'selector'       => $this->get_control_selector_class( 'menu_toggle_icon', '.e-active' ),
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Border Color', 'mas-elementor' ),
					),
					'width' => array(
						'label' => esc_html__( 'Border Width', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'menu_toggle_icon_box_shadow_active',
				'selector' => $this->get_control_selector_class( 'menu_toggle_icon', '.e-active' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'menu_toggle_icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-toggle-icon-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'menu_toggle_icon__padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-toggle-icon-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'menu_toggle_icon_distance_from_dropdown',
			array(
				'label'       => esc_html__( 'Distance from dropdown', 'mas-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'     => array(
					'size' => 0,
				),
				'placeholder' => array(
					'size' => 0,
				),
				'size_units'  => array( 'px' ),
				'selectors'   => array(
					'{{WRAPPER}}' => '--n-menu-toggle-icon-distance-from-dropdown: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => esc_html__( 'Content', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'content_background_color',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->get_control_selector_class( 'active_content_container' ),
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Background Color', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'content_border',
				'selector'       => $this->get_control_selector_class( 'active_content_container' ),
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Border Color', 'mas-elementor' ),
					),
					'width' => array(
						'label' => esc_html__( 'Border Width', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_responsive_control(
			'content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					$this->get_control_selector_class( 'active_content_container' ) => '--border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'content_shadow',
				'selector' => $this->get_control_selector_class( 'active_content_container' ),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					$this->get_control_selector_class( 'active_content_container' ) => '--padding-top: {{TOP}}{{UNIT}}; --padding-right: {{RIGHT}}{{UNIT}}; --padding-bottom: {{BOTTOM}}{{UNIT}}; --padding-left: {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dropdown_menu_style',
			array(
				'label' => esc_html__( 'Dropdown Menu', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'dropdown_menu_items_title',
			array(
				'label' => esc_html__( 'Menu Items', 'mas-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'dropdown_menu_items_description',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Styles apply to items when the menu switches to dropdown layout', 'mas-elementor' ),
				'content_classes' => 'elementor-control-field-description',
			)
		);

		$this->start_controls_tabs( 'menu_dropdown_states_section' );

		$this->start_controls_tab(
			'normal_menu_dropdown_states',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'dropdown_menu_item_text_color_normal',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-item-title-normal-color-dropdown: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'dropdown_menu_item_background_color_normal',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} > .elementor-widget-container > .e-n-menu > .e-n-menu-items-content > .e-n-menu-item-title.e-collapse:not( .e-current )',
				'fields_options' => array(
					'color' => array(
						'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
						'selectors' => array(
							'{{SELECTOR}}' => 'background: {{VALUE}}',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'dropdown_menu_item_box_shadow_normal',
				'selector' => '{{WRAPPER}} > .elementor-widget-container > .e-n-menu > .e-n-menu-items-content > .e-n-menu-item-title.e-collapse:not( .e-current )',

			)
		);

		$this->end_controls_tab(); // Normal tab end.

		$this->start_controls_tab(
			'active_menu_dropdown_states',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'dropdown_menu_item_text_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--n-menu-item-title-active-color-dropdown: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'dropdown_menu_item_background_color_active',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} > .elementor-widget-container > .e-n-menu > .e-n-menu-items-content > .e-n-menu-item-title.e-collapse.e-current',
				'fields_options' => array(
					'color' => array(
						'label' => esc_html__( 'Background Color', 'mas-elementor' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'dropdown_menu_item_box_shadow_active',
				'selector' => '{{WRAPPER}} > .elementor-widget-container > .e-n-menu > .e-n-menu-items-content > .e-n-menu-item-title.e-collapse.e-current',

			)
		);

		$this->end_controls_tab(); // Active tab end.

		$this->end_controls_tabs();

		$this->add_control(
			'menu_dropdown_box_title',
			array(
				'label'     => esc_html__( 'Dropdown Box', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'menu_dropdown_box_description',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Style the dropdown box that contains menu items.', 'mas-elementor' ),
				'content_classes' => 'elementor-control-field-description',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'menu_dropdown_box_border',
				'fields_options' => array(
					'border' => array(
						'selectors' => array(
							'{{WRAPPER}}' => '--n-menu-dropdown-content-box-border-style: {{VALUE}}',
						),
					),
					'color'  => array(
						'label'     => esc_html__( 'Border Color', 'mas-elementor' ),
						'selectors' => array(
							'{{WRAPPER}}' => '--n-menu-dropdown-content-box-border-color: {{VALUE}}',
						),
					),
					'width'  => array(
						'label'     => esc_html__( 'Border Width', 'mas-elementor' ),
						'selectors' => array(
							'{{WRAPPER}}' => '--n-menu-dropdown-content-box-border-width-top: {{TOP}}{{UNIT}}; --n-menu-dropdown-content-box-border-width-right: {{RIGHT}}{{UNIT}}; --n-menu-dropdown-content-box-border-width-bottom: {{BOTTOM}}{{UNIT}}; --n-menu-dropdown-content-box-border-width-left: {{LEFT}}{{UNIT}}',
						),
					),
				),
			)
		);

		$this->add_control(
			'menu_dropdown_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--n-menu-dropdown-content-box-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'menu_dropdown_box_shadow',
				'fields_options' => array(
					'box_shadow' => array(
						'selectors' => array(
							'{{WRAPPER}}' => '--n-menu-dropdown-content-box-shadow-horizontal: {{HORIZONTAL}}px; --n-menu-dropdown-content-box-shadow-vertical: {{VERTICAL}}px; --n-menu-dropdown-content-box-shadow-blur: {{BLUR}}px; --n-menu-dropdown-content-box-shadow-spread: {{SPREAD}}px; --n-menu-dropdown-content-box-shadow-color: {{COLOR}}; --n-menu-dropdown-content-box-shadow-position: {{box_shadow_position.VALUE}};',
						),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings                       = $this->get_settings_for_display();
		$desktop_menu_titles            = '';
		$mobile_menu_titles_and_content = '';

		foreach ( $settings['menu_items'] as $index => $item ) {
			echo 'js';
			$desktop_menu_titles            .= $this->create_desktop_menu_titles( $index, $item );
			$mobile_menu_titles_and_content .= $this->create_mobile_menu_titles_and_content( $index, $item );
		}

		?>
		<nav class="e-n-menu">
			<?php $this->render_menu_toggle( $settings ); ?>
			<div class="e-n-menu-items-heading">
				<?php echo $desktop_menu_titles; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="e-n-menu-items-content" aria-orientation="vertical">
				<?php echo $mobile_menu_titles_and_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</nav>
		<?php
	}

	/**
	 * Render menu toggle.
	 *
	 * @param array $settings settings.
	 */
	protected function render_menu_toggle( $settings ) {
		$menu_toggle_hover_animation = ! empty( $settings['menu_toggle_icon_hover_animation'] )
			? ' elementor-animation-' . $settings['menu_toggle_icon_hover_animation']
			: '';

		$menu_toggle_class = 'e-n-menu-toggle' . $menu_toggle_hover_animation;

		$this->add_render_attribute(
			'menu-toggle',
			array(
				'class'      => $menu_toggle_class,
				'role'       => 'button',
				'aria-label' => esc_html__( 'Menu Toggle', 'mas-elementor' ),
			)
		);
		?>
		<div <?php $this->print_render_attribute_string( 'menu-toggle' ); ?>>
			<?php
			$open_class  = 'e-n-menu-toggle-icon e-open';
			$close_class = 'e-n-menu-toggle-icon e-close';

			$normal_icon = ! empty( $settings['menu_toggle_icon_normal']['value'] )
				? $settings['menu_toggle_icon_normal']
				: array(
					'library' => 'eicons',
					'value'   => 'eicon-menu-bar',
				);

			?>
			<span class="<?php echo esc_attr( $open_class ); ?>">
				<?php Icons_Manager::render_icon( $normal_icon ); ?>
			</span>
			<?php

			$active_icon = ! empty( $settings['menu_toggle_icon_active']['value'] )
				? $settings['menu_toggle_icon_active']
				: array(
					'library' => 'eicons',
					'value'   => 'eicon-close',
				);

			?>
			<span class="<?php echo esc_attr( $close_class ); ?>">
				<?php Icons_Manager::render_icon( $active_icon ); ?>
			</span>

			<span class="elementor-screen-only"><?php echo esc_html__( 'Menu', 'mas-elementor' ); ?></span>
		</div>
		<?php
	}

	/**
	 * Render menu toggle template.
	 */
	protected function render_menu_toggle_template() {
		?>
			<#
			const menuToggleKey = 'e-n-menu-toggle-' + elementUid,
				iconHoverAnimation = !! settings.menu_toggle_icon_hover_animation
					? 'elementor-animation-' + settings.menu_toggle_icon_hover_animation
					: '',
				openClass = 'e-n-menu-toggle-icon e-open',
				closeClass = 'e-n-menu-toggle-icon e-close',
				iconNormal = !! settings.menu_toggle_icon_normal.value ? settings.menu_toggle_icon_normal : '',
				iconActive = !! settings.menu_toggle_icon_active.value ? settings.menu_toggle_icon_active : '';

				view.addRenderAttribute( menuToggleKey, {
					'class': [ 'e-n-menu-toggle', 'elementor-clickable', iconHoverAnimation ],
					'role': 'button',
					'aria-label': '<?php echo esc_html__( 'Menu Toggle', 'mas-elementor' ); ?>',
				} );
			#>
			<div {{{ view.getRenderAttributeString( menuToggleKey ) }}}>
				<span class="{{{ openClass }}}">
					<# if ( !! iconNormal ) { #>
						{{{ elementor.helpers.renderIcon( view, iconNormal, {}, 'i', 'object' ).value }}}
					<# } else { #>
						<?php
						Icons_Manager::render_icon(
							array(
								'library' => 'eicons',
								'value'   => 'eicon-menu-bar',
							)
						);
						?>
					<# } #>
				</span>
				<span class="{{{ closeClass }}}">
					<# if ( !! iconActive ) { #>
						{{{ elementor.helpers.renderIcon( view, iconActive, {}, 'i', 'object' ).value }}}
					<# } else { #>
						<?php
						Icons_Manager::render_icon(
							array(
								'library' => 'eicons',
								'value'   => 'eicon-close',
							)
						);
						?>
					<# } #>
				</span>
			</div>
		<?php
	}

	/**
	 * Merge menu title classes.
	 *
	 * @param int   $index index.
	 * @param array $item item.
	 * @param array $classes classes.
	 *
	 * @return array
	 */
	protected function merge_menu_title_classes( $index, $item, $classes ) {
		$current_class       = $this->get_current_menu_item_class( $item['item_link']['url'] );
		$items_open_on_click = 'click' === $this->get_settings_for_display( 'open_on' );

		if ( ! empty( $current_class ) ) {
			$classes[] = $current_class;
		}

		if ( $items_open_on_click && $this->item_has_dropdown_with_content( $index, $this->get_children(), $item['item_dropdown_content'] ) ) {
			$classes[] = 'e-click';
		}

		return $classes;
	}

	/**
	 * Create desktop menu titles.
	 *
	 * @param int   $index index.
	 * @param array $item item.
	 *
	 * @return string
	 */
	protected function create_desktop_menu_titles( $index, $item ) {
		$args = array(
			'isMobile'    => false,
			'setting_key' => 'item_title',
			'class'       => $this->merge_menu_title_classes( $index, $item, array( 'e-n-menu-item-title', 'e-normal' ) ),
		);
		return $this->render_menu_titles_html( $index, $item, $args );
	}

	/**
	 * Create mobile menu titles and content.
	 *
	 * @param int   $index index.
	 * @param array $item item.
	 *
	 * @return string
	 */
	protected function create_mobile_menu_titles_and_content( $index, $item ) {
		$args = array(
			'isMobile'    => true,
			'setting_key' => 'item_title_mobile',
			'class'       => $this->merge_menu_title_classes( $index, $item, array( 'e-n-menu-item-title', 'e-collapse' ) ),
		);
		return $this->render_menu_titles_html( $index, $item, $args );
	}

	/**
	 * Render menu titles html.
	 *
	 * @param int   $index index.
	 * @param array $item item.
	 * @param array $args args.
	 *
	 * @return string
	 */
	protected function render_menu_titles_html( $index, $item, $args ) {
		$settings              = $this->get_settings_for_display();
		$icon_html             = Icons_Manager::try_get_icon_html( $settings['menu_item_icon'], array( 'aria-hidden' => 'true' ) );
		$icon_active_html      = Icons_Manager::try_get_icon_html( $settings['menu_item_icon_active'], array( 'aria-hidden' => 'true' ) );
		$display_index         = $index + 1;
		$id_int                = substr( $this->get_id_int(), 0, 3 );
		$item_dropdown_content = $settings['menu_items'][ $index ]['item_dropdown_content'];
		$menu_item_id          = empty( $item['element_id'] ) ? 'e-n-menu-item-title-' . $id_int . $display_index : $item['element_id'];
		$key                   = $this->get_repeater_setting_key( $args['setting_key'], 'menu_items', $display_index );
		$menu_item             = $settings['menu_items'][ $index ];
		$menu_item_icon        = Icons_Manager::try_get_icon_html( $menu_item['item_icon'], array( 'aria-hidden' => 'true' ) );
		$menu_item_active_icon = $this->is_active_icon_exist( $menu_item )
			? Icons_Manager::try_get_icon_html( $item['item_icon_active'], array( 'aria-hidden' => 'true' ) )
			: $menu_item_icon;

		if ( ! empty( $settings['hover_animation'] ) ) {
			$args['class'][] = 'elementor-animation-' . $settings['hover_animation'];
		}

		$this->add_attributes_to_item( $key, $args['class'], $menu_item_id, $display_index, $id_int );

		ob_start();
		?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( $key ) ); ?> >
				<?php echo $this->get_title_container_opening_tag( $item, $item['item_link']['url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $menu_item_icon ) { ?>
						<span class="e-n-menu-icon">
							<span class="icon-active"><?php echo $menu_item_active_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="icon-inactive" ><?php echo $menu_item_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</span>
					<?php } ?>
					<span class="e-n-menu-item-title-text" >
						<?php
						echo $item['item_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</span>

				<?php if ( 'yes' === $item_dropdown_content ) { ?>
					<span class="e-n-menu-item-icon">
						<span class="e-n-menu-item-icon-opened" ><?php echo $icon_active_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="e-n-menu-item-icon-closed"><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</span>
				<?php } ?>
				<?php echo $this->get_title_container_closing_tag( $item['item_link']['url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			</div>
		<?php
		if ( $args['isMobile'] ) {
			$this->print_child( $index, $item_dropdown_content );
		}

		return ob_get_clean();
	}

	/**
	 * Add attributes to item.
	 *
	 * @param string $key key.
	 * @param array  $classes classes.
	 * @param int    $menu_item_id menu item id.
	 * @param int    $display_index display index.
	 * @param int    $id_int id int.
	 *
	 * @return void
	 */
	public function add_attributes_to_item( $key, $classes, $menu_item_id, $display_index, $id_int ) {
		$this->add_render_attribute(
			$key,
			array(
				'id'            => $menu_item_id,
				'class'         => $classes,
				'aria-selected' => 1 === $display_index ? 'true' : 'false',
				'data-tab'      => $display_index,
				'role'          => 'tab',
				'tabindex'      => 1 === $display_index ? '0' : '-1',
				'aria-controls' => 'e-n-tab-content-' . $id_int . $display_index,
				'aria-expanded' => 'false',
			)
		);
	}

	/**
	 * Get current menu item class.
	 *
	 * @param string $menu_link_url menu link url.
	 *
	 * @return string
	 */
	protected function get_current_menu_item_class( $menu_link_url ) {
		$menu_link_url = trim( $menu_link_url );

		if ( str_contains( $menu_link_url, '#' ) ) {
			return 'e-anchor';
		}

		$permalink_url = $this->get_permalink_for_current_page();

		if ( empty( $menu_link_url ) || empty( $permalink_url ) ) {
			return '';
		}

		$permalink_array     = $this->parse_url( $permalink_url );
		$menu_item_url_array = $this->parse_url( $menu_link_url );
		$has_equal_urls      = $permalink_array === $menu_item_url_array;

		return $has_equal_urls ? 'e-current' : '';
	}

	/**
	 * Print the content area.
	 *
	 * @param int    $index index.
	 * @param string $item_dropdown_content item dropdown content.
	 */
	public function print_child( $index, $item_dropdown_content = 'no' ) {
		$children = $this->get_children();
		$tab_id   = $index + 1;

		// Add data-tab attribute to the content area.
		$add_attribute_to_container = function ( $should_render, $container ) use ( $tab_id ) {
			$container->add_render_attribute( '_wrapper', 'data-content', $tab_id );

			return $should_render;
		};

		if ( $this->item_has_dropdown_with_content( $index, $children, $item_dropdown_content ) ) {
			add_filter( 'elementor/frontend/container/should_render', $add_attribute_to_container, 10, 3 );

			$children[ $index ]->print_element();

			remove_filter( 'elementor/frontend/container/should_render', $add_attribute_to_container );
		}
	}

	/**
	 * Item has dropdown content.
	 *
	 * @param int    $index index.
	 * @param array  $children children.
	 * @param string $item_dropdown_content item dropdown content.
	 *
	 * @return bool
	 */
	protected function item_has_dropdown_with_content( $index, $children, $item_dropdown_content = 'no' ) {
		$data     = ! empty( $children[ $index ] ) ? $children[ $index ]->get_data() : array();
		$elements = empty( $data['elements'] ) ? array() : $data['elements'];

		return ! empty( $children[ $index ] ) && ! empty( $elements ) && 'yes' === $item_dropdown_content;
	}

	/**
	 * Get title container opening tag.
	 *
	 * @param array  $item item.
	 * @param string $url url.
	 */
	private function get_title_container_opening_tag( $item, $url ) {
		$link_id = 'e-n-menu-item-title-container-' . $item['_id'];
		$this->remove_render_attribute( $link_id );
		$this->add_render_attribute(
			$link_id,
			'class',
			array(
				'e-n-menu-item-title-container',
				'e-link',
			)
		);
		$this->add_link_attributes( $link_id, $item['item_link'] );
		$current_class = $this->get_current_menu_item_class( $item['item_link']['url'] );
		$opening_tag   = '<div class="e-n-menu-item-title-container">';

		if ( ! empty( $current_class ) ) {
			$this->add_render_attribute( $link_id, 'aria-current', 'page' );
		}

		$tag_content = $this->get_render_attribute_string( $link_id );

		if ( $url ) {
			$opening_tag = '<a ' . $tag_content . '>';
		}

		return $opening_tag;
	}

	/**
	 * Get title container closing tag.
	 *
	 * @param string $url url.
	 */
	private function get_title_container_closing_tag( $url ) {
		$closing_tag = '</div>';

		if ( $url ) {
			$closing_tag = '</a>';
		}

		return $closing_tag;
	}

	/**
	 * Is active icon exist.
	 *
	 * @param array $item item.
	 * @return bool
	 */
	private function is_active_icon_exist( $item ) {
		return array_key_exists( 'item_icon_active', $item ) && ! empty( $item['item_icon_active'] ) && ! empty( $item['item_icon_active']['value'] );
	}

	/**
	 * Content Template.
	 */
	protected function content_template() {
	}
}
