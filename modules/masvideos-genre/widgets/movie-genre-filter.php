<?php
/**
 * The Mas Genre filter.
 *
 * @package MASElementor/Modules/MasvideosGenre/Widgets
 */

namespace MASElementor\Modules\MasvideosGenre\Widgets;

use MASElementor\Base\Base_Widget;
use MasVideos_Movies_Genres_Filter_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

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
		return esc_html__( 'Movie Genre Filter', 'mas-elementor' );
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
				'label' => __( 'Layout', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'columns',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => esc_html__( 'Columns', 'mas-elementor' ),
				'default'            => '2',
				'prefix_class'       => 'filter-columns-',
				'options'            => array(
					'1' => esc_html__( '1', 'mas-elementor' ),
					'2' => esc_html__( '2', 'mas-elementor' ),
					'3' => esc_html__( '3', 'mas-elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'     => esc_html__( 'Width', 'mas-elementor' ),
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
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Title', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'filter_text',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'  => '{{WRAPPER}} .filter-text',
			)
		);

		$this->add_responsive_control(
			'title_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Title Spacing', 'mas-elementor' ),
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
				'label' => __( 'List', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'list_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-elementor' ),
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

		$this->end_controls_section();

	}

	/**
	 * Render.
	 */
	public function render() {
		$taxonomy       = 'movie_genre';
		$get_terms_args = array( 'hide_empty' => '1' );

		$orderby = masvideos_attribute_orderby( 'movie', $taxonomy );

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

		$terms = get_terms( $taxonomy, $get_terms_args );
		if ( 0 === count( $terms ) ) {
			return;
		}

		switch ( $orderby ) {
			case 'name_num':
				usort( $terms, '_masvideos_get_movie_terms_name_num_usort_callback' );
				break;
			case 'parent':
				usort( $terms, '_masvideos_get_movie_terms_parent_usort_callback' );
				break;
		}
		?>
		<div class="filter" style="display:flex; flex-wrap:wrap">
			<h1 class="filter-text">Filter by Genre</h1>
			<ul style="display:block;">
				<?php foreach ( $terms as $term ) : ?>
					<li>
						<a href="<?php echo esc_attr( get_term_link( $term->name, $taxonomy ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php

	}
}
