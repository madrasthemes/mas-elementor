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
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
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
		return [ 'menu', 'nav', 'button', 'mas' ];
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
		return [ 'smartmenus' ];
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

		$options = [];

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
			[
				'label' => __( 'Nav Menu', 'mas-elementor' ),
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'        => __( 'Menu', 'mas-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'separator'    => 'after',
					'description'  => sprintf( /* translators: %1$s: Link to Menu Link. */ __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'mas-elementor' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . __( 'There are no menus in your site.', 'mas-elementor' ) . '</strong><br>' . sprintf( /* translators: %1$s: Link to Menu Link. */__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'mas-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
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
		// $this->add_control(
		// 	'view',
		// 	[
		// 		'label'          => esc_html__( 'Layout', 'mas-elementor' ),
		// 		'type'           => Controls_Manager::CHOOSE,
		// 		'default'        => 'traditional',
		// 		'options'        => [
		// 			'traditional' => [
		// 				'title' => esc_html__( 'Default', 'mas-elementor' ),
		// 				'icon'  => 'eicon-editor-list-ul',
		// 			],
		// 			'inline'      => [
		// 				'title' => esc_html__( 'Inline', 'mas-elementor' ),
		// 				'icon'  => 'eicon-ellipsis-h',
		// 			],
		// 		],
		// 	]
		// );

		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label' => esc_html__( 'Main Menu', 'mas-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .mas-elementor-nav-menu .menu-item a',
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'color_menu_item',
			[
				'label' => esc_html__( 'Text Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item a' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'color_menu_item_hover',
			[
				'label' => esc_html__( 'Text Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item a:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active',
			[
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'color_menu_item_active',
			[
				'label' => esc_html__( 'Text Color', 'mas-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mas-elementor-nav-menu .menu-item a:active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
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
