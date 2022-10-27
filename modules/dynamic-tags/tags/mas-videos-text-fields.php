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
					'imdb_id'           => esc_html__( 'IMDB ID', 'mas-elementor' ),
					'tmdb_id'           => esc_html__( 'TMDB ID', 'mas-elementor' ),
					'season_names'      => esc_html__( 'Season Names', 'mas-elementor' ),
					'season_start_year' => esc_html__( 'Seasons Start Year', 'mas-elementor' ),
					'season_end_year'   => esc_html__( 'Seasons End Year', 'mas-elementor' ),
				),
				'condition' => array(
					'mas_videos_post_types' => 'tv_show',
				),
			)
		);

		$this->add_control(
			'season_names_separator',
			array(
				'label'     => esc_html__( 'Separator', 'mas-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ', ',
				'condition' => array(
					'mas_videos_post_types'    => 'tv_show',
					'mas_tv_show_text_options' => 'season_names',
				),

			)
		);
	}
	/**
	 * Render the post  title.
	 */
	public function render() {
		$movie = masvideos_get_movie( get_the_ID() );
		$tv_show = masvideos_get_tv_show( get_the_ID() );

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
			if ( ! empty( $settings['mas_tv_show_text_options'] ) ) {
				$seasons      = $tv_show->get_seasons();
				$start_year   = '';
				$end_year     = '';
				$season_names = array();
				if ( ! empty( $seasons ) ) {
					foreach ( $seasons as $key => $season ) {
						if ( 0 === $key ) {
							$start_year = $season['year'];
							$end_year   = $season['year'];
						} else {
							if ( $end_year < $season['year'] ) {
								$end_year = $season['year'];
							}
							if ( $start_year > $season['year'] ) {
								$start_year = $season['year'];
							}
						}
						$season_names[] = $season['name'];

					}
				}
				if ( 'season_start_year' === $settings['mas_tv_show_text_options'] ) {
					$text_output = $start_year;
				} elseif ( 'season_end_year' === $settings['mas_tv_show_text_options'] ) {
					$text_output = $end_year;
				} elseif ( 'season_names' === $settings['mas_tv_show_text_options'] ) {
					$season_names = implode( $settings['season_names_separator'], $season_names );
					$text_output  = $season_names;
				}
			}
		}
		echo wp_kses_post( $text_output );
	}
}
