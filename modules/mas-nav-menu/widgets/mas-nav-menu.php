<?php
/**
 * Nav Menu Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\MasNavMenu\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use MASElementor\Base\Base_Widget;
use MASElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Nav-Menu Widget
 */
class Mas_Nav_Menu extends Base_Widget {

	/**
	 * Nav Menu Item Index.
	 *
	 * @var array $nav_menu_index index.
	 */
	protected $nav_menu_index = 1;

	/**
	 * Get widget name.
	 *
	 * Retrieve Nav Menu widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mas-nav-menu';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Nav Menu widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MAS Nav Menu', 'mas-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Nav Menu widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
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
	 * Get widget keywords.
	 *
	 * Retrieve Nav Menu widget keywords.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return array( 'menu', 'nav', 'button', 'mas' );
	}

	/**
	 * Get widget depends.
	 *
	 * Retrieve Nav Menu widget script depends.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget depends.
	 */
	public function get_script_depends() {
		return array( 'navigation-script', 'mas-nav-init' );
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the element requires.
	 *
	 * @since 1.9.0
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'nav-menu-stylesheet', 'mas-el-stylesheet' );
	}

	/**
	 * Get widget Nav menu index.
	 *
	 * Retrieve Nav Menu widget script Nav menu index.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget Nav menu index.
	 */
	public function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	/**
	 * Get widget available menus.
	 *
	 * Retrieve Nav Menu widget script available menus.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget available menus.
	 */
	public function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = array();

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	public function register_controls() {

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Nav Menu', 'mas-addons-for-elementor' ),
			)
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				array(
					'label'        => __( 'Menu', 'mas-addons-for-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'separator'    => 'after',
					'description'  => sprintf( /* translators: %1$s: Link to Menu Link. */ __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'mas-addons-for-elementor' ), admin_url( 'nav-menus.php' ) ),
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . __( 'There are no menus in your site.', 'mas-addons-for-elementor' ) . '</strong><br>' . sprintf( /* translators: %1$s: Link to Menu Link. */__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'mas-addons-for-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->add_control(
			'nav_action',
			array(
				'label'   => esc_html__( 'Nav Action', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'hover' => 'Hover',
					'click' => 'Click',
				),
				'default' => 'hover',
			)
		);

		$this->add_control(
			'walker',
			array(
				'label'   => esc_html__( 'Walker', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default'   => 'Default',
					'bootstrap' => 'Bootstrap Nav Walker',
				),
				'default' => 'default',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'              => esc_html__( 'Layout', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'horizontal',
				'options'            => array(
					'horizontal' => esc_html__( 'Horizontal', 'mas-addons-for-elementor' ),
					'vertical'   => esc_html__( 'Vertical', 'mas-addons-for-elementor' ),
					'dropdown'   => esc_html__( 'Dropdown', 'mas-addons-for-elementor' ),
				),
				'frontend_available' => true,
				'prefix_class'       => 'mas-nav-layout-',
				'condition'          => array(
					'walker!' => 'default',
				),
			)
		);

		$this->add_responsive_control(
			'mas_nav_content_align',
			array(
				'label'     => esc_html__( 'Menu Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mas-align' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'layout' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'mas_nav_space_between',
			array(
				'label'          => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'          => array(
					'px' => array(
						'max' => 150,
					),
				),
				'default'        => array(
					'size' => 0,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => 0,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .mas-elementor-nav-menu>li' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mas-elementor-nav-menu>li:last-child' => 'padding-right: 0px;',
				),
				'condition'      => array(
					'layout' => 'horizontal',
				),
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_main-menu',
			array(
				'label' => esc_html__( 'Main Menu', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'main_menu_padding',
			array(
				'label'      => esc_html__( 'Menu Item Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .header-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'menu_typography',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'       => '{{WRAPPER}} .mas-elementor-nav-menu .menu-item .nav-link',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '400',
					),
					'font_family' => array(
						'default' => 'Varela Round',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 24.5,
						),
					),
				),
			)
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'color_menu_item',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item .nav-link' => 'color: {{VALUE}}; fill: {{VALUE}};',
					'{{WRAPPER}} .mas-elementor-nav-menu > .menu-item > a' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'color_menu_item_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item .nav-link:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
					'{{WRAPPER}} .mas-elementor-nav-menu > .menu-item:hover > a' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
				'default'   => '#000',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active',
			array(
				'label' => esc_html__( 'Active', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'color_menu_item_active',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item a:active' => 'color: {{VALUE}}',
					'{{WRAPPER}} .mas-elementor-nav-menu > menu-item a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'mas_nav_submenu',
			array(
				'label'     => __( 'Submenu', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'enable_submenu_color',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Enable Submenu color', 'mas-addons-for-elementor' ),
				'default' => 'no',
			)
		);

		$this->start_controls_tabs(
			'tabs_sub_menu_item_style',
			array(
				'condition' => array(
					'walker!'              => 'default',
					'enable_submenu_color' => 'yes',
				),
			)
		);

		$this->start_controls_tab(
			'tab_sub_menu_item_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'sub_menu_text_color',
			array(
				'label'     => esc_html__( 'Submenu Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item > .dropdown-menu > .menu-item > a' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sub_menu_item_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'sub_menu_text_color_hover',
			array(
				'label'     => esc_html__( 'Submenu Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item > .dropdown-menu > .menu-item:hover > a' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'submenu_typography',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'       => '{{WRAPPER}} .sub-menu .menu-item a, {{WRAPPER}} .dropdown-menu .menu-item a, {{WRAPPER}} .mas-elementor-nav-menu .dropdown-menu .menu-item .nav-link',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '400',
					),
					'font_family' => array(
						'default' => 'Varela Round',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 24.5,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'lm_background',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .sub-menu, {{WRAPPER}} .dropdown-menu',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#ffffff',
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'lm_border',
				'selector'       => '{{WRAPPER}} .sub-menu, {{WRAPPER}} .dropdown-menu',
				'fields_options' => array(
					'border' => array(
						'default' => '',
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
					'color'  => array(
						'default' => '#ffffff',
					),
				),
			)
		);

		$this->add_control(
			'lm_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .sub-menu'      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dropdown-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '6',
					'right'    => '6',
					'bottom'   => '6',
					'left'     => '6',
					'unit'     => 'px',
					'isLinked' => true,
				),
			)
		);

		$this->add_control(
			'remove_box_shadow',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Disable Box Shadow', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'return_value' => 'hide',
				'prefix_class' => 'mas-nav-menu-shadow-',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'lm_button_box_shadow',
				'selector'       => '{{WRAPPER}} .sub-menu, {{WRAPPER}} .dropdown-menu',
				'fields_options' => array(
					'box_shadow_type'     => array( 'default' => 'yes' ),
					'box_shadow_position' => array(
						'default' => 'outline',
					),
					'box_shadow'          => array(
						'default' => array(
							'horizontal' => 0,
							'vertical'   => 5,
							'blur'       => 12,
							'spread'     => 6,
							'color'      => 'rgba(0, 0, 0, 0.1)',
						),
					),
				),
				'condition' => array(
					'remove_box_shadow!'              => 'hide',
				),
			)
		);

		$this->add_control(
			'mas_nav_submenu_item',
			array(
				'label'     => __( 'Submenu Item', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'lm_tabs_button_style' );

		$this->start_controls_tab(
			'lm_tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'lm_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7b8b8e',
				'selectors' => array(
					'{{WRAPPER}} .sub-menu .menu-item a' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .dropdown-menu .menu-item a' => 'fill: {{VALUE}}; color: {{VALUE}};',
				),
				'condition' => array(
					'enable_submenu_color!' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'li_background',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .sub-menu li, {{WRAPPER}} .dropdown-menu li',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#ffffff',
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'lm_tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'lm_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .sub-menu .menu-item a:hover, {{WRAPPER}} .sub-menu .menu-item a:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sub-menu .menu-item a:hover svg, {{WRAPPER}} .sub-menu .menu-item a:focus svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .dropdown-menu .menu-item a:hover, {{WRAPPER}} .dropdown-menu .menu-item a:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dropdown-menu .menu-item a:hover svg, {{WRAPPER}} .dropdown-menu .menu-item a:focus svg' => 'fill: {{VALUE}};',
				),
				'default'   => '#16181b',
				'condition' => array(
					'enable_submenu_color!' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'li_button_background_hover',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .sub-menu li a:hover, {{WRAPPER}} .sub-menu li a:focus, {{WRAPPER}} .dropdown-menu li a:hover, {{WRAPPER}} .dropdown-menu li a:focus',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#f8f9fa',
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'mas_menu_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .header-menu .sub-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .header-menu .dropdown-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
				'default'    => array(
					'top'      => '4',
					'right'    => '30',
					'bottom'   => '4',
					'left'     => '30',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_menu_icon',
			array(
				'label' => esc_html__( 'Menu Icon', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_font_size_menu_item',
			array(
				'label'          => esc_html__( 'Font Size', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item .mas-icon-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_color_menu_item',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item .mas-icon-wrap i' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_color_menu_item_hover',
			array(
				'label'     => esc_html__( 'Icon Hover Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item a:hover .mas-icon-wrap i' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon__menu_padding',
			array(
				'label'      => esc_html__( 'Icon Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item .mas-icon-wrap i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '6',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Add Data Hover Attribute.
	 *
	 * @param array $atts attributes.
	 * @param array $item item.
	 * @param array $args arguments.
	 * @param int   $depth depth.
	 */
	public function mas_elementor_add_data_hover_attribute( $atts, $item, $args, $depth ) {

		$settings = $this->get_settings_for_display();

		$dropdown_trigger = $settings['nav_action'];

		if ( 'hover' === $dropdown_trigger ) {
			$atts['data-hover'] = 'dropdown';

			if ( isset( $atts['data-bs-toggle'] ) ) {
				unset( $atts['data-bs-toggle'] );
			}
		}

		return $atts;
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		add_filter( 'nav_menu_link_attributes', array( $this, 'mas_elementor_add_data_hover_attribute' ), 10, 4 );
		mas_elementor_get_template( 'widgets/mas-nav-menu/mas-nav-menu.php', array( 'widget' => $this ) );
		remove_filter( 'nav_menu_link_attributes', array( $this, 'mas_elementor_add_data_hover_attribute' ), 10, 4 );
	}
}
