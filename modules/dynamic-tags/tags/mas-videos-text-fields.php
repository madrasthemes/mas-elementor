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
class Mas_Videos_Text_Fields extends \Elementor\Core\DynamicTags\Tag {
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
		return esc_html__( 'Mas Videos', 'mas-elementor' );
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
			'mas_videos_post_types',
			array(
				'label'   => esc_html__( 'Mas Videos Post Types', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'movie',
				'options' => array(
					'tv_show' => esc_html__( 'TV Shows', 'mas-elementor' ),
					'movie'   => esc_html__( 'Movies', 'mas-elementor' ),
				),
			)
		);
		$this->add_control(
			'mas_movie_text_options',
			array(
				'label'     => esc_html__( 'Type', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'time_duration',
				'options'   => array(
					'release_date'  => esc_html__( 'Release Date', 'mas-elementor' ),
					'time_duration' => esc_html__( 'Time Duration', 'mas-elementor' ),
					'censor_rating' => esc_html__( 'Censor Rating', 'mas-elementor' ),
					'imdb_id'       => esc_html__( 'IMDB ID', 'mas-elementor' ),
					'tmdb_id'       => esc_html__( 'TMDB ID', 'mas-elementor' ),
				),
				'condition' => array(
					'mas_videos_post_types' => 'movie',
				),
			)
		);
		$this->add_control(
			'date_format',
			array(
				'label'     => esc_html__( 'Date Format', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'F j, Y' => gmdate( 'F j, Y' ),
					'Y-m-d'  => gmdate( 'Y-m-d' ),
					'm/d/Y'  => gmdate( 'm/d/Y' ),
					'd/m/Y'  => gmdate( 'd/m/Y' ),
					'custom' => esc_html__( 'Custom', 'mas-elementor' ),
				),
				'default'   => 'Y-m-d',
				'condition' => array(
					'mas_movie_text_options' => 'release_date',
					'mas_videos_post_types'  => 'movie',
				),
			)
		);

		$this->add_control(
			'custom_format',
			array(
				'label'     => esc_html__( 'Custom Format', 'mas-elementor' ),
				'default'   => gmdate( 'Y-m-d' ),
				'condition' => array(
					'date_format'            => 'custom',
					'mas_movie_text_options' => 'release_date',
					'mas_videos_post_types'  => 'movie',
				),
			)
		);

		$this->add_control(
			'mas_tv_show_text_options',
			array(
				'label'     => esc_html__( 'Type', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'imdb_id',
				'options'   => array(
					'imdb_id' => esc_html__( 'IMDB ID', 'mas-elementor' ),
					'tmdb_id' => esc_html__( 'TMDB ID', 'mas-elementor' ),
				),
				'condition' => array(
					'mas_videos_post_types' => 'tv_show',
				),
			)
		);
	}
	/**
	 * Render the post  title.
	 */
	public function render() {
		if ( is_movie() || is_tv_show() ) {
			wp_reset_postdata();
		}

		global $movie;
		global $tv_show;
		$settings    = $this->get_settings();
		$text_output = '';
		if ( ! empty( $movie ) && 'movie' === $settings['mas_videos_post_types'] ) {
			if ( 'release_date' === $settings['mas_movie_text_options'] ) {
				$date_format  = 'custom' !== $settings['date_format'] ? $settings['date_format'] : $settings['custom_format'];
				$release_date = $movie->get_movie_release_date();
				$release_date = gmdate( $date_format, strtotime( $release_date ) );
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
		if ( ! empty( $tv_show ) && 'tv_show' === $settings['mas_videos_post_types'] ) {
			if ( 'imdb_id' === $settings['mas_tv_show_text_options'] ) {
				$text_output = $tv_show->get_imdb_id();
			}
			if ( 'tmdb_id' === $settings['mas_tv_show_text_options'] ) {
				$text_output = $tv_show->get_tmdb_id();
			}
		}
		echo wp_kses_post( $text_output );
	}
}
