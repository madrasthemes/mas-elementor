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
		return esc_html__( 'Mas Videos Featured Image', 'mas-elementor' );
	}
	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		if ( is_movie() || is_video() || is_tv_show() ) {
			wp_reset_postdata();
		}
		global $movie;
		global $video;
		global $tv_show;
		$settings   = $this->get_settings();
		$image_data = $this->get_settings( 'fallback' );
		if ( ! empty( $movie ) && 'person' === $settings['mas_videos_media_url'] ) {
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
				'label'   => esc_html__( 'URL', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'movie',
				'options' => array(
					'movie'   => esc_html__( 'Movies', 'mas-elementor' ),
					'tv_show' => esc_html__( 'TV Show', 'mas-elementor' ),
					'video'   => esc_html__( 'Videos', 'mas-elementor' ),
					'person'  => esc_html__( 'Movie Persons', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Select Person', 'mas-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'description' => esc_html__( 'Person here shown are in movie crew options and in ordered', 'mas-elementor' ),
				'options'     => $crew_options,
				'condition'   => array(
					'mas_videos_media_url' => 'person',
				),
			)
		);

		$this->add_control(
			'mas-fallback',
			array(
				'label' => esc_html__( 'Fallback', 'mas-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
	}
}
