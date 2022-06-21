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
		return 'posts';
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
			'paged'          => $this->get_current_page(),
		);

		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, 'posts', $query_args, array() );
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

		$dir     = MAS_ELEMENTOR_PATH . 'templates/widgets/posts/';
		$templates = Utils::get_mas_post_templates($dir);

		$this->add_control(
			'select_template',
			[
				'label'   => esc_html__( 'Mas Templates', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
				'default' => array_key_first($templates),
			]
		);

		$this->end_controls_section();
	}

	/**
	* Register Controls in post section in style tab.
	*/
	protected function register_post_section_controls() {
		// Title style controls for post title.
		$this->start_controls_section(
			'post-style',
			[
				'label'     => esc_html__( 'Post', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mas_title_typography',
				'label'    => __( 'Title', 'mas-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .mas-post-title',
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-post-title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mas_post_excerpt_typography',
				'label'    => __( 'Excerpt', 'mas-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .mas-post-excerpt',
			]
		);

		$this->add_control(
			'post_excerpt_color',
			[
				'label'     => esc_html__( 'Excerpt Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-post-excerpt' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mas_post_action_text_typography',
				'label'    => __( 'Action Text', 'mas-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .mas-post-action-text',
			]
		);

		$this->add_control(
			'post_action_text_color',
			[
				'label'     => esc_html__( 'Action Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mas-post-action-text' => 'color: {{VALUE}} !important;',
				],
			]
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
	 * Render.
	 */
	public function render() {
		$settings       = $this->get_settings_for_display();
		$args           = apply_filters( 'mas_post_object', array( 'widget' => $this, ) );
		mas_elementor_get_template( 'widgets/posts/' . $settings['select_template'] , $args );
	}
}
