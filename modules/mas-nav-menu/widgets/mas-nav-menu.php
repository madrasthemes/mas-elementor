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
		return __( 'Mas Nav Menu', 'mas-elementor' );
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
	 * Get widget categories.
	 *
	 * Retrieve Nav Menu widget categories.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget categories.
	 */
	public function get_categories() {
		return array( 'mas' );
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
		return array( 'smartmenus' );
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
		return array( 'nav-menu-stylesheet' );
	}

	/**
	 * Get widget element.
	 *
	 * @param array $element element.
	 */
	public function on_export( $element ) {
		unset( $element['settings']['menu'] );

		return $element;
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
				'label' => __( 'Nav Menu', 'mas-elementor' ),
			)
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				array(
					'label'        => __( 'Menu', 'mas-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'separator'    => 'after',
					'description'  => sprintf( /* translators: %1$s: Link to Menu Link. */ __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'mas-elementor' ), admin_url( 'nav-menus.php' ) ),
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . __( 'There are no menus in your site.', 'mas-elementor' ) . '</strong><br>' . sprintf( /* translators: %1$s: Link to Menu Link. */__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'mas-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->add_control(
			'walker',
			array(
				'label'   => esc_html__( 'Walker', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default'   => 'Default',
					'bootstrap' => 'Bootstrap Nav Walker',
				),
				'default' => 'default',
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_main-menu',
			array(
				'label' => esc_html__( 'Main Menu', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'menu_typography',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'       => '{{WRAPPER}} .mas-elementor-nav-menu .menu-item a',
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
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'color_menu_item',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item a' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'color_menu_item_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item a:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
				'default'   => '#ffffff',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'color_menu_item_active',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item a:active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'mas_nav_submenu',
			array(
				'label' => __( 'Submenu', 'mas-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'submenu_typography',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'       => '{{WRAPPER}} .sub-menu .menu-item a',
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
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .sub-menu',
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
				'selector'       => '{{WRAPPER}} .sub-menu',
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
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'lm_button_box_shadow',
				'selector'       => '{{WRAPPER}} .sub-menu',
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
			)
		);

		$this->start_controls_tabs( 'lm_tabs_button_style' );

		$this->start_controls_tab(
			'lm_tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'lm_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7b8b8e',
				'selectors' => array(
					'{{WRAPPER}} .sub-menu .menu-item a' => 'fill: {{VALUE}}; color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'li_background',
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .sub-menu li',
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
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'lm_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .sub-menu .menu-item a:hover, {{WRAPPER}} .sub-menu .menu-item a:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sub-menu .menu-item a:hover svg, {{WRAPPER}} .sub-menu .menu-item a:focus svg' => 'fill: {{VALUE}};',
				),
				'default'   => '#16181b',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'li_button_background_hover',
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .sub-menu li a:hover, {{WRAPPER}} .sub-menu li a:focus',
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
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .header-menu .sub-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		mas_elementor_get_template( 'widgets/mas-nav-menu.php', array( 'widget' => $this ) );
	}
}
