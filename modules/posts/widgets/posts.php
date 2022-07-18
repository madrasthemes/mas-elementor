<?php
/**
 * The Posts Widget.
 *
 * @package MASElementor/Modules/Posts/Widgets
 */

namespace MASElementor\Modules\Posts\Widgets;

use Elementor\Controls_Manager;
use MASElementor\Modules\QueryControl\Module as Module_Query;
use MASElementor\Modules\QueryControl\Controls\Group_Control_Related;
use MASElementor\Modules\Posts\Skins;
use MASElementor\Core\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Posts
 */
class Posts extends Posts_Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-posts';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Posts', 'mas-elementor' );
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' );
	}

	/**
	 * Called on import to override.
	 *
	 * @param array $element The element being imported.
	 */
	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}


	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->register_layout_section_controls();
		$this->register_query_section_controls();
		$this->register_post_section_controls();
		$this->register_pagination_section_controls();
	}

	/**
	 * Set the query variable
	 */
	public function query_posts() {

		$query_args = array(
			'posts_per_page' => $this->get_settings_for_display( 'posts_per_page' ),
			'paged'          => $this->get_current_page(),
		);

		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, $this->get_name(), $query_args, array() );
	}

	/**
	 * Register Controls in Layout Section.
	 */
	protected function register_layout_section_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$templates = function_exists( 'mas_template_options' ) ? mas_template_options() : array();
		$this->add_control(
			'select_template',
			array(
				'label'   => esc_html__( 'Mas Templates', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Posts Per Page', 'mas-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
			)
		);

		$this->add_control(
			'enable_loop_selection',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Loop Selection', 'mas-elementor' ),
				'default'   => 'no',
				'separator' => 'before',
				'condition' => array(
					'select_template!' => '',
				),
			)
		);

		$loop = range( 1, 12 );
		$loop = array_combine( $loop, $loop );

		$this->add_control(
			'select_loop',
			array(
				'label'     => esc_html__( 'Select Loop', 'mas-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => array() + $loop,
				'condition' => array(
					'enable_loop_selection' => 'yes',
				),
			)
		);

		$this->add_control(
			'select_loop_template',
			array(
				'label'       => esc_html__( 'Mas Select Templates', 'mas-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $templates,
				'description' => esc_html__( 'Select Templates for the above selected posts series', 'mas-elementor' ),
				'condition'   => array(
					'enable_loop_selection' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Controls in post section in style tab.
	 */
	protected function register_post_section_controls() {
		// Post style controls in STYLE TAB.
		$this->start_controls_section(
			'post-style',
			array(
				'label' => esc_html__( 'Post', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		// Title controls.
		$this->add_control(
			'post_title_heading_style',
			array(
				'label' => esc_html__( 'Title', 'mas-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mas_title_typography',
				'label'    => __( 'Typography', 'mas-elementor' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .mas-post-title',
			)
		);

		$this->add_control(
			'post_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-post-title' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'post_category_heading_style',
			array(
				'label'     => esc_html__( 'Category', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		// Category controls.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mas_category_typography',
				'label'    => __( 'Typography', 'mas-elementor' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .mas-post-category',
			)
		);

		$this->add_control(
			'post_category_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-post-category' => 'color: {{VALUE}} !important;',
				),
			)
		);
		// Excerpt controls.
		$this->add_control(
			'post_excerpt_heading_style',
			array(
				'label'     => esc_html__( 'Excerpt', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mas_post_excerpt_typography',
				'label'    => __( 'Typography', 'mas-elementor' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .mas-post-excerpt',
			)
		);

		$this->add_control(
			'post_excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-post-excerpt' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'post_action_text_heading_style',
			array(
				'label'     => esc_html__( 'Action Text', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		// Action text controls.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mas_post_action_text_typography',
				'label'    => __( 'Typography', 'mas-elementor' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .mas-post-action-text',
			)
		);

		$this->add_control(
			'post_action_text_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-post-action-text' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'post_action_text_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-post-action-text:hover,{{WRAPPER}}' => 'color: {{VALUE}} !important;',
				),
				'default'   => '#07853a',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register controls in the Query Section
	 */
	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			array(
				'name'    => $this->get_name(),
				'presets' => array( 'full' ),
				'exclude' => array(
					'posts_per_page', // use the one from Layout section.
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Widget render.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$args     = apply_filters(
			'mas_post_object',
			array(
				'widget'   => $this,
				'settings' => $settings,
			)
		);
		mas_elementor_get_template( 'widgets/posts/post-grid.php', $args );
		$this->render_loop_footer();
	}
}
