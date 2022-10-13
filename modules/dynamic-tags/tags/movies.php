<?php
/**
 * Post Title.
 *
 * @package MASElementor\Modules\DynamicTags\tags\post-title.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Post title class.
 */
class Movies extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-movies';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Movies', 'mas-elementor' );
	}
	/**
	 * Get the group.
	 */
	public function get_group() {
		return Module::POST_GROUP;
	}
	/**
	 * Get the categories.
	 */
	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}
	/**
	 * Register controls for post comments number.
	 */
	protected function register_controls() {
		$this->add_control(
			'mas_movie_text_options',
			array(
				'label'   => esc_html__( 'Type', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'time_duration',
				'options' => array(
					'release_date'  => esc_html__( 'Release Date', 'mas-elementor' ),
					'time_duration' => esc_html__( 'Time Duration', 'mas-elementor' ),
					'censor_rating' => esc_html__( 'Censor Rating', 'mas-elementor' ),
					'imdb_id'       => esc_html__( 'IMDB ID', 'mas-elementor' ),
					'tmdb_id'       => esc_html__( 'TMDB ID', 'mas-elementor' ),
				),
			)
		);
		$this->add_control(
			'date_format',
			array(
				'label'     => esc_html__( 'Date Format', 'mas-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Y-m-d',
				'condition' => array(
					'mas_movie_text_options' => 'release_date',
				),
			)
		);
	}
	/**
	 * Render the post  title.
	 */
	public function render() {
		global $movie;

		$settings    = $this->get_settings();
		$text_output = '';
		if ( ! empty( $movie ) ) {
			if ( 'release_date' === $settings['mas_movie_text_options'] ) {
				$release_date = $movie->get_movie_release_date();
				$release_date = gmdate( $settings['date_format'], strtotime( $release_date ) );
				$text_output  = $release_date;
			}
			if ( 'time_duration' === $settings['mas_movie_text_options'] ) {
				$text_output = $movie->get_movie_run_time();
			}
			if ( 'censor_rating' === $settings['mas_movie_text_options'] ) {
				$text_output = $movie->get_movie_censor_rating();
			}
			if ( 'imdb_id' === $settings['mas_movie_text_options'] ) {
				$text_output = $movie->get_imdb_id();
			}
			if ( 'tmdb_id' === $settings['mas_movie_text_options'] ) {
				$text_output = $movie->get_tmdb_id();
			}
		}
		echo wp_kses_post( $text_output );
	}
}
