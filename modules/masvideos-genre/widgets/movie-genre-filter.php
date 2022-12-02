<?php
/**
 * The Mas Genre filter.
 *
 * @package MASElementor/Modules/MasvideosGenre/Widgets
 */

namespace MASElementor\Modules\MasvideosGenre\Widgets;

use MASElementor\Base\Base_Widget;
use MasVideos_Movies_Genres_Filter_Widget;

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
		?><ul>
			<?php foreach ( $terms as $term ) : ?>
				<li>
					<a href="<?php echo esc_attr( get_term_link( $term->name, $taxonomy ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php

	}
}
