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
		return esc_html__( 'MAS Videos', 'mas-addons-for-elementor' );
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
				'label'   => esc_html__( 'MAS Videos Post Types', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'movie',
				'options' => array(
					'tv_show' => esc_html__( 'TV Shows', 'mas-addons-for-elementor' ),
					'movie'   => esc_html__( 'Movies', 'mas-addons-for-elementor' ),
				),
			)
		);
		$this->add_control(
			'mas_movie_text_options',
			array(
				'label'     => esc_html__( 'Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'time_duration',
				'options'   => array(
					'release_date'  => esc_html__( 'Release Date', 'mas-addons-for-elementor' ),
					'time_duration' => esc_html__( 'Time Duration', 'mas-addons-for-elementor' ),
					'censor_rating' => esc_html__( 'Censor Rating', 'mas-addons-for-elementor' ),
					'imdb_id'       => esc_html__( 'IMDB ID', 'mas-addons-for-elementor' ),
					'tmdb_id'       => esc_html__( 'TMDB ID', 'mas-addons-for-elementor' ),
					'casts_crew'    => esc_html__( 'Casts and Crew', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'mas_videos_post_types' => 'movie',
				),
			)
		);

		$this->add_control(
			'mas_tv_show_text_options',
			array(
				'label'     => esc_html__( 'Type', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'imdb_id',
				'options'   => array(
					'imdb_id'           => esc_html__( 'IMDB ID', 'mas-addons-for-elementor' ),
					'tmdb_id'           => esc_html__( 'TMDB ID', 'mas-addons-for-elementor' ),
					'season_names'      => esc_html__( 'Season Names', 'mas-addons-for-elementor' ),
					'season_count'      => esc_html__( 'Season Count', 'mas-addons-for-elementor' ),
					'season_start_year' => esc_html__( 'Seasons Start Year', 'mas-addons-for-elementor' ),
					'season_end_year'   => esc_html__( 'Seasons End Year', 'mas-addons-for-elementor' ),
					'casts_crew'        => esc_html__( 'Casts and Crew', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'mas_videos_post_types' => 'tv_show',
				),
			)
		);

		$this->add_control(
			'season_names_separator',
			array(
				'label'     => esc_html__( 'Separator', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ', ',
				'condition' => array(
					'mas_videos_post_types'    => 'tv_show',
					'mas_tv_show_text_options' => 'season_names',
				),

			)
		);

		$this->add_control(
			'mas_videos_person_options',
			array(
				'label'      => esc_html__( 'Persons', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'crew',
				'options'    => array(
					'casts' => esc_html__( 'Casts', 'mas-addons-for-elementor' ),
					'crew'  => esc_html__( 'Crew', 'mas-addons-for-elementor' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_post_types',
									'operator' => '===',
									'value'    => 'movie',
								),
								array(
									'name'     => 'mas_movie_text_options',
									'operator' => '===',
									'value'    => 'casts_crew',
								),
							),
						),
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_post_types',
									'operator' => '===',
									'value'    => 'tv_show',
								),
								array(
									'name'     => 'mas_tv_show_text_options',
									'operator' => '===',
									'value'    => 'casts_crew',
								),
							),
						),
					),
				),
			)
		);

		$crew_options = array();
		for ( $i = 0; $i < 15; $i++ ) {
			$suffix             = $i + 1;
			$crew_options[ $i ] = 'Person ' . $suffix;
		}

		$this->add_control(
			'mas_movie_person_ids',
			array(
				'label'       => esc_html__( 'Select Person', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Person here shown are in movie crew options and in ordered', 'mas-addons-for-elementor' ),
				'default'     => '0',
				'options'     => $crew_options,
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_post_types',
									'operator' => '===',
									'value'    => 'movie',
								),
								array(
									'name'     => 'mas_movie_text_options',
									'operator' => '===',
									'value'    => 'casts_crew',
								),
							),
						),
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_post_types',
									'operator' => '===',
									'value'    => 'tv_show',
								),
								array(
									'name'     => 'mas_tv_show_text_options',
									'operator' => '===',
									'value'    => 'casts_crew',
								),
							),
						),
					),
				),

			)
		);

		$this->add_control(
			'mas_movie_person_crew_options',
			array(
				'label'      => esc_html__( 'Person options', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'name',
				'options'    => array(
					'name' => esc_html__( 'Name', 'mas-addons-for-elementor' ),
					'job'  => esc_html__( 'Job', 'mas-addons-for-elementor' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_post_types',
									'operator' => '===',
									'value'    => 'movie',
								),
								array(
									'name'     => 'mas_videos_person_options',
									'operator' => '===',
									'value'    => 'crew',
								),
								array(
									'name'     => 'mas_movie_text_options',
									'operator' => '===',
									'value'    => 'casts_crew',
								),
							),
						),
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_post_types',
									'operator' => '===',
									'value'    => 'tv_show',
								),
								array(
									'name'     => 'mas_videos_person_options',
									'operator' => '===',
									'value'    => 'crew',
								),
								array(
									'name'     => 'mas_tv_show_text_options',
									'operator' => '===',
									'value'    => 'casts_crew',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'mas_movie_person_cast_options',
			array(
				'label'      => esc_html__( 'Person options', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'name',
				'options'    => array(
					'name'      => esc_html__( 'Name', 'mas-addons-for-elementor' ),
					'character' => esc_html__( 'Character', 'mas-addons-for-elementor' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_post_types',
									'operator' => '===',
									'value'    => 'movie',
								),
								array(
									'name'     => 'mas_videos_person_options',
									'operator' => '===',
									'value'    => 'casts',
								),
								array(
									'name'     => 'mas_movie_text_options',
									'operator' => '===',
									'value'    => 'casts_crew',
								),
							),
						),
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_post_types',
									'operator' => '===',
									'value'    => 'tv_show',
								),
								array(
									'name'     => 'mas_videos_person_options',
									'operator' => '===',
									'value'    => 'casts',
								),
								array(
									'name'     => 'mas_tv_show_text_options',
									'operator' => '===',
									'value'    => 'casts_crew',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'     => esc_html__( 'Date Format', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'F j, Y' => gmdate( 'F j, Y' ),
					'Y-m-d'  => gmdate( 'Y-m-d' ),
					'm/d/Y'  => gmdate( 'm/d/Y' ),
					'd/m/Y'  => gmdate( 'd/m/Y' ),
					'custom' => esc_html__( 'Custom', 'mas-addons-for-elementor' ),
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
				'label'     => esc_html__( 'Custom Format', 'mas-addons-for-elementor' ),
				'default'   => gmdate( 'Y-m-d' ),
				'condition' => array(
					'date_format'            => 'custom',
					'mas_movie_text_options' => 'release_date',
					'mas_videos_post_types'  => 'movie',
				),
			)
		);

	}
	/**
	 * Render the post  title.
	 */
	public function render() {
		$movie   = masvideos_get_movie( get_the_ID() );
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
			if ( 'crew' === $settings['mas_videos_person_options'] && 'casts_crew' === $settings['mas_movie_text_options'] ) {
				$crews = $movie->get_crew();
				if ( ! empty( $crews ) ) {
					foreach ( $crews as $key => $crew ) {
						if ( $key === (int) $settings['mas_movie_person_ids'] && 'name' === $settings['mas_movie_person_crew_options'] ) {
							$text_output = get_the_title( $crew['id'] );
						}
						if ( $key === (int) $settings['mas_movie_person_ids'] && 'job' === $settings['mas_movie_person_crew_options'] ) {
							$text_output = $crew['job'];
						}
					}
				}
			}
			if ( 'casts' === $settings['mas_videos_person_options'] && 'casts_crew' === $settings['mas_movie_text_options'] ) {
				$casts = $movie->get_cast();
				if ( ! empty( $casts ) ) {
					foreach ( $casts as $key => $cast ) {
						if ( $key === (int) $settings['mas_movie_person_ids'] && 'name' === $settings['mas_movie_person_cast_options'] ) {
							$text_output = get_the_title( $cast['id'] );
						}
						if ( $key === (int) $settings['mas_movie_person_ids'] && 'character' === $settings['mas_movie_person_cast_options'] ) {
							$text_output = $cast['character'];
						}
					}
				}
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
				} elseif ( 'season_count' === $settings['mas_tv_show_text_options'] && ! empty( $season_names ) ) {
					$text_output = 1 === count( $season_names ) ? count( $season_names ) . ' Season' : count( $season_names ) . ' Seasons';
				}
			}
			if ( 'crew' === $settings['mas_videos_person_options'] && 'casts_crew' === $settings['mas_tv_show_text_options'] ) {
				$crews = $tv_show->get_crew();
				if ( ! empty( $crews ) ) {
					foreach ( $crews as $key => $crew ) {
						if ( $key === (int) $settings['mas_movie_person_ids'] && 'name' === $settings['mas_movie_person_crew_options'] ) {
							$text_output = get_the_title( $crew['id'] );
						}
						if ( $key === (int) $settings['mas_movie_person_ids'] && 'job' === $settings['mas_movie_person_crew_options'] ) {
							$text_output = $crew['job'];
						}
					}
				}
			}
			if ( 'casts' === $settings['mas_videos_person_options'] && 'casts_crew' === $settings['mas_tv_show_text_options'] ) {
				$casts = $tv_show->get_cast();
				if ( ! empty( $casts ) ) {
					foreach ( $casts as $key => $cast ) {
						if ( $key === (int) $settings['mas_movie_person_ids'] && 'name' === $settings['mas_movie_person_cast_options'] ) {
							$text_output = get_the_title( $cast['id'] );
						}
						if ( $key === (int) $settings['mas_movie_person_ids'] && 'character' === $settings['mas_movie_person_cast_options'] ) {
							$text_output = $cast['character'];
						}
					}
				}
			}
		}
		echo wp_kses_post( $text_output );
	}
}
