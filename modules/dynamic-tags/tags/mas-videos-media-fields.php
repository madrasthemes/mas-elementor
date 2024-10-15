<?php
/**
 * Post time.
 *
 * @package MASElementor\Modules\DynamicTags\tags\post-time.php
 */

namespace  MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use  MASElementor\Modules\DynamicTags\Tags\Base\Data_Tag;
use  MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Post Featured Image Tag Class.
 */
class Mas_Videos_Media_Fields extends \Elementor\Core\DynamicTags\Data_Tag {
	/**
	 * Get post-featured-mage tag name.
	 */
	public function get_name() {
		return 'mas-videos-media-fields';
	}
	/**
	 * Get post-featured-mage tag group.
	 */
	public function get_group() {
		return Module::MEDIA_GROUP;
	}
	/**
	 * Get post-featured-mage categories name.
	 */
	public function get_categories() {
		return array( Module::IMAGE_CATEGORY );
	}
	/**
	 * Get post-title tag name.
	 */
	public function get_title() {
		return esc_html__( 'MAS Videos Featured Image', 'mas-addons-for-elementor' );
	}
	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		$movie   = masvideos_get_movie( get_the_ID() );
		$tv_show = masvideos_get_tv_show( get_the_ID() );
		$video   = masvideos_get_video( get_the_ID() );

		$settings   = $this->get_settings();
		$image_data = $this->get_settings( 'fallback' );
		if ( ! empty( $movie ) && 'person' === $settings['mas_videos_media_url'] ) {
			if ( 'crew' === $settings['mas_videos_casts_crew'] ) {
				$crews = $movie->get_crew();
				if ( ! empty( $crews ) ) {
					foreach ( $crews as $key => $crew ) {
						if ( $key === (int) $settings['mas_movie_person_ids'] ) {
							$person     = masvideos_get_person( $crew['id'] );
							$media_id   = $person->get_image_id();
							$media_url  = wp_get_attachment_url( $media_id );
							$image_data = array(
								'id'  => $person->get_image_id(),
								'url' => $media_url,
							);
						}
					}
				}
			} else {
				$casts = $movie->get_cast();
				if ( ! empty( $casts ) ) {
					foreach ( $casts as $key => $cast ) {
						if ( $key === (int) $settings['mas_movie_person_ids'] ) {
							$person     = masvideos_get_person( $cast['id'] );
							$media_id   = $person->get_image_id();
							$media_url  = wp_get_attachment_url( $media_id );
							$image_data = array(
								'id'  => $person->get_image_id(),
								'url' => $media_url,
							);
						}
					}
				}
			}
		}
		if ( ! empty( $tv_show ) && 'tv_person' === $settings['mas_videos_media_url'] ) {
			if ( 'crew' === $settings['mas_videos_casts_crew'] ) {
				$crews = $tv_show->get_crew();
				if ( ! empty( $crews ) ) {
					foreach ( $crews as $key => $crew ) {
						if ( $key === (int) $settings['mas_movie_person_ids'] ) {
							$person     = masvideos_get_person( $crew['id'] );
							$media_id   = $person->get_image_id();
							$media_url  = wp_get_attachment_url( $media_id );
							$image_data = array(
								'id'  => $person->get_image_id(),
								'url' => $media_url,
							);
						}
					}
				}
			} else {
				$casts = $tv_show->get_cast();
				if ( ! empty( $casts ) ) {
					foreach ( $casts as $key => $cast ) {
						if ( $key === (int) $settings['mas_movie_person_ids'] ) {
							$person     = masvideos_get_person( $cast['id'] );
							$media_id   = $person->get_image_id();
							$media_url  = wp_get_attachment_url( $media_id );
							$image_data = array(
								'id'  => $person->get_image_id(),
								'url' => $media_url,
							);
						}
					}
				}
			}
		}
		if ( ! empty( $movie ) && 'movie' === $settings['mas_videos_media_url'] ) {
			$image_data = array(
				'id'  => $movie->get_image_id(),
				'url' => wp_get_attachment_url( $movie->get_image_id() ),
			);
		}
		if ( ! empty( $video ) && 'video' === $settings['mas_videos_media_url'] ) {
			$image_data = array(
				'id'  => $video->get_image_id(),
				'url' => wp_get_attachment_url( $video->get_image_id() ),
			);
		}
		if ( ! empty( $tv_show ) && 'tv_show' === $settings['mas_videos_media_url'] ) {
			$image_data = array(
				'id'  => $tv_show->get_image_id(),
				'url' => wp_get_attachment_url( $tv_show->get_image_id() ),
			);
		}

		return $image_data;
	}
	/**
	 * Register control.
	 */
	protected function register_controls() {
		$this->add_control(
			'mas_videos_media_url',
			array(
				'label'   => esc_html__( 'Type', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'movie',
				'options' => array(
					'movie'     => esc_html__( 'Movies', 'mas-addons-for-elementor' ),
					'tv_show'   => esc_html__( 'TV Show', 'mas-addons-for-elementor' ),
					'video'     => esc_html__( 'Videos', 'mas-addons-for-elementor' ),
					'person'    => esc_html__( 'Movie Casts and Crew', 'mas-addons-for-elementor' ),
					'tv_person' => esc_html__( 'TV Show Casts and Crew', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'mas_videos_casts_crew',
			array(
				'label'      => esc_html__( 'Casts or Crew', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'cast',
				'options'    => array(
					'cast' => esc_html__( 'Casts', 'mas-addons-for-elementor' ),
					'crew' => esc_html__( 'Crew', 'mas-addons-for-elementor' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_media_url',
									'operator' => '===',
									'value'    => 'person',
								),
							),
						),
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_media_url',
									'operator' => '===',
									'value'    => 'tv_person',
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
				'default'     => '0',
				'description' => esc_html__( 'Person here shown are in movie crew options and in ordered', 'mas-addons-for-elementor' ),
				'options'     => $crew_options,
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_media_url',
									'operator' => '===',
									'value'    => 'person',
								),
							),
						),
						array(
							'terms' => array(
								array(
									'name'     => 'mas_videos_media_url',
									'operator' => '===',
									'value'    => 'tv_person',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'mas-fallback',
			array(
				'label' => esc_html__( 'Fallback', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
	}
}
