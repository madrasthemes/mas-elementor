<?php
/**
 * Comments number.
 *
 * @package MASElementor\Modules\DynamicTags\tags\comments-number.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Comments number class.
 */
class Movie_Rating extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'movie-rating';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Movies and TV Shows Rating', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the group.
	 */
	public function get_group() {
		return Module::COMMENTS_GROUP;
	}
	/**
	 * Get the categories.
	 */
	public function get_categories() {
		return array(
			Module::TEXT_CATEGORY,
			Module::NUMBER_CATEGORY,
		);
	}

	/**
	 * Register controls for post comments number.
	 */
	protected function register_controls() {
		$this->add_control(
			'mas_movie_tv_ratings',
			array(
				'label'   => esc_html__( 'Type', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'movies',
				'options' => array(
					'movies'   => esc_html__( 'Movies', 'mas-addons-for-elementor' ),
					'tv_shows' => esc_html__( 'TV Shows', 'mas-addons-for-elementor' ),
				),
			)
		);
	}

	/**
	 * Render the comments number.
	 */
	public function render() {
		$movie   = masvideos_get_movie( get_the_ID() );
		$tv_show = masvideos_get_tv_show( get_the_ID() );

		$settings = $this->get_settings();
		$rating   = '';
		if ( 'movies' === $settings['mas_movie_tv_ratings'] ) {

			if ( ! empty( $movie ) ) {
				$rating = number_format( $movie->get_average_rating(), 1, '.', '' );
			}
		} else {
			if ( ! empty( $tv_show ) ) {
				$rating = number_format( $tv_show->get_average_rating(), 1, '.', '' );
			}
		}

		echo wp_kses_post( $rating );
	}
}
