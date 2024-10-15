<?php
/**
 * Author url.
 *
 * @package MASElementor\Modules\DynamicTags\tags\author-url.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Data_Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Author Profile Picture class.
 */
class Mas_Videos_Url_Fields extends Data_Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'mas-videos-url';
	}

	/**
	 * Get tag group.
	 */
	public function get_group() {
		return Module::MEDIA_GROUP;
	}

	/**
	 * Get Categories.
	 */
	public function get_categories() {
		return array( Module::URL_CATEGORY );
	}

	/**
	 * Get tag title.
	 */
	public function get_title() {
		return esc_html__( 'MAS Videos URL', 'mas-addons-for-elementor' );
	}

	/**
	 * Get panel template setting.
	 */
	public function get_panel_template_setting_key() {
		return 'url';
	}

	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		$movie = masvideos_get_movie( get_the_ID() );
		$video = masvideos_get_video( get_the_ID() );

		$settings = $this->get_settings();
		$value    = '';
		if ( ! empty( $movie ) && 'movie' === $settings['url'] ) {
			if ( 'movie_url' === $movie->get_movie_choice() ) {
				$value = $movie->get_movie_url_link();
			}
			if ( 'movie_file' === $movie->get_movie_choice() ) {
				$value = wp_get_attachment_url( $movie->get_movie_attachment_id() );
			}
		}
		if ( ! empty( $video ) && 'video' === $settings['url'] ) {
			if ( 'video_url' === $video->get_video_choice() ) {
				$value = $video->get_video_url_link();
			}
			if ( 'video_file' === $video->get_video_choice() ) {
				$value = wp_get_attachment_url( $video->get_video_attachment_id() );
			}
		}

		return $value;
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'url',
			array(
				'label'   => esc_html__( 'URL', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'movie',
				'options' => array(
					'movie' => esc_html__( 'Movies', 'mas-addons-for-elementor' ),
					'video' => esc_html__( 'Videos', 'mas-addons-for-elementor' ),
				),
			)
		);
	}
}
