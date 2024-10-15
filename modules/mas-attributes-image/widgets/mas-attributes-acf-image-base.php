<?php
/**
 * The MAS Nav Tab Widget.
 *
 * @package MASElementor/Modules/MasEpisodes/Widgets
 */

namespace MASElementor\Modules\MasAttributesImage\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use MASElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use MASElementor\Core\Controls_Manager as MAS_Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Stack;
use WP_Term_Query;
use MASElementor\Modules\DynamicTags\ACF\Module as ACFModule;
use Elementor\Widget_Image;



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Episodes Elementor Widget.
 */
abstract class Mas_Attributes_ACF_Image_Base extends Widget_Image {
	/**
	 * Register Controls in Layout Section.
	 */
	protected function register_controls() {
		parent::register_controls();
		$controls = array(
			'section_image',

		);
		foreach ( $controls as $control ) {
			$this->remove_control( $control );
		}

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'select_taxonomies',
			array(
				'label'   => esc_html__( 'Select Taxonomy', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_taxonomy_options(),
			)
		);

		$this->add_control(
			'key',
			array(
				'label'  => esc_html__( 'Key', 'mas-addons-for-elementor' ),
				'type'   => Controls_Manager::SELECT,
				'groups' => ACFModule::get_control_options( array( 'image' ) ),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'attr_image',
				'default'   => 'thumbnail',
				'separator' => 'none',
			)
		);

		$this->add_responsive_control(
			'attr_align',
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
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}
	/**
	 * Render.
	 */
	public function get_taxonomy_options() {
		$args       = array(
			'object_type' => array( 'movie' ),
		);
		$taxonomies = get_taxonomies( $args, 'objects' );
		$options    = array();
		foreach ( $taxonomies as $taxonomy ) {
			if ( 'movie_visibility' !== $taxonomy->name ) {
				$options[ $taxonomy->name ] = $taxonomy->label;
			}
		}
		return $options;
	}
	/**
	 * Render.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$terms    = get_the_terms( get_the_ID(), $settings['select_taxonomies'] );
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return '';
		}
		if ( ! empty( $settings['key'] ) && ! empty( $terms ) ) {
			$pair               = explode( ':', $settings['key'] );
			list( $key, $name ) = $pair;
			?><div class="acf-attr-image" style="display:flex;">
			<?php
			foreach ( $terms as $term ) {
				$acf = get_field( $key, $term );
				if ( is_array( $acf ) ) {
					$settings['image_key'] = array(
						'id'  => $acf['id'],
						'url' => $acf['url'],
					);
				} elseif ( wp_http_validate_url( $acf ) ) {
					$settings['image_key'] = array(
						'id'  => attachment_url_to_postid( $acf ),
						'url' => $acf,
					);
				} else {
					$settings['image_key'] = array(
						'id'  => $acf,
						'url' => wp_get_attachment_url( $acf ),
					);
				}
				Group_Control_Image_Size::print_attachment_image_html( $settings, 'attr_image', 'image_key' );
			}
			?>
			</div>
			<?php
		}
	}

	/**
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 */
	protected function content_template() {

	}
}
