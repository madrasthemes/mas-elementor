<?php
/**
 * The MAS Genre filter.
 *
 * @package MASElementor/Modules/MasvideosGenre/Widgets
 */

namespace MASElementor\Modules\MasvideosGenre\Widgets;

use MASElementor\Base\Base_Widget;
use MasVideos_Movies_Genres_Filter_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Episodes Elementor Widget.
 */
class Movie_Genre_Filter extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'movie-genre-filter';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Movie Genre Filter', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-meta-data';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'genre', 'mas', 'filter' );
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
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the element requires.
	 *
	 * @since 1.9.0
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'genre-stylesheet' );
	}

	/**
	 * Register Controls in Layout Section.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'filter_title',
			array(
				'label'   => __( 'Filter Title', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Filter by Genre', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'genre',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Columns', 'mas-addons-for-elementor' ),
				'default' => 'movie_genre',
				'options' => array(
					'movie_genre'   => esc_html__( 'Movie Genre', 'mas-addons-for-elementor' ),
					'tv_show_genre' => esc_html__( 'TV Show Genre', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'columns',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => esc_html__( 'Columns', 'mas-addons-for-elementor' ),
				'default'            => '2',
				'prefix_class'       => 'filter-columns-',
				'options'            => array(
					'1' => esc_html__( '1', 'mas-addons-for-elementor' ),
					'2' => esc_html__( '2', 'mas-addons-for-elementor' ),
					'3' => esc_html__( '3', 'mas-addons-for-elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 50,
						'max' => 2600,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .filter' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Content', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'list_background',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .filter',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'global' => array(
							'default' => Global_Colors::COLOR_ACCENT,
						),
					),
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'           => 'mas_filter_border',
				'selector'       => '{{WRAPPER}} .filter',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => false,
						),
					),
					'color'  => array(
						'default' => '#B7BAC6',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Title', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'filter_text_color',
			array(
				'label'     => __( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .filter-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_text',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .filter-text',
			)
		);

		$this->add_responsive_control(
			'title_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 100,
					'unit' => '%',
				),
				'range'     => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .filter-text' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'     => esc_html__( 'Title Spacing', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .filter-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_list_style',
			array(
				'label' => __( 'List', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'filter_list_color',
			array(
				'label'     => __( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .filter ul li a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_list_hover_color',
			array(
				'label'     => __( 'Hover Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .filter ul li a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 100,
					'unit' => '%',
				),
				'range'     => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .filter ul' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_space_between',
			array(
				'label'     => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .filter ul li' => 'padding-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_spacing',
			array(
				'label'     => esc_html__( 'List Spacing', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .filter ul li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		if ( 'movie_genre' === $settings['genre'] ) {
			$taxonomy = 'movie_genre';
			$posttype = 'movie';
		} else {
			$taxonomy = 'tv_show_genre';
			$posttype = 'tv_show';
		}

		$get_terms_args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		);

		$orderby = masvideos_attribute_orderby( $posttype, $taxonomy );

		switch ( $orderby ) {
			case 'name':
				$get_terms_args['orderby']    = 'name';
				$get_terms_args['menu_order'] = false;
				break;
			case 'id':
				$get_terms_args['orderby']    = 'id';
				$get_terms_args['order']      = 'ASC';
				$get_terms_args['menu_order'] = false;
				break;
			case 'menu_order':
				$get_terms_args['menu_order'] = 'ASC';
				break;
		}

		$terms = get_terms( $get_terms_args );

		if ( 0 === count( $terms ) ) {
			return;
		}

		switch ( $orderby ) {
			case 'name_num':
				usort( $terms, '_masvideos_get_' . $posttype . '_terms_name_num_usort_callback' );
				break;
			case 'parent':
				usort( $terms, '_masvideos_get_' . $posttype . '_terms_parent_usort_callback' );
				break;
		}
		?>
		<div class="filter" style="display:flex; flex-wrap:wrap">
			<div class="filter-genre">
				<h1 class="filter-text"><?php echo esc_html( $settings['filter_title'] ); ?></h1>
				<ul style="display:block; padding-left:0px;">
					<?php foreach ( $terms as $term ) : ?>
						<li style="list-style:none;">
							<a href="<?php echo esc_attr( get_term_link( $term->name, $taxonomy ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php

	}
}
