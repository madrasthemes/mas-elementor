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
class Mas_Videos_Episodes_Text_Fields extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-episodes';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'MAS Episodes', 'mas-addons-for-elementor' );
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
			'mas_episodes_text_options',
			array(
				'label'   => esc_html__( 'Type', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'time_duration',
				'options' => array(
					'episode_number' => esc_html__( 'Episode Number', 'mas-addons-for-elementor' ),
					'release_date'   => esc_html__( 'Release Date', 'mas-addons-for-elementor' ),
					'time_duration'  => esc_html__( 'Time Duration', 'mas-addons-for-elementor' ),
					'imdb_id'        => esc_html__( 'IMDB ID', 'mas-addons-for-elementor' ),
					'tmdb_id'        => esc_html__( 'TMDB ID', 'mas-addons-for-elementor' ),
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
					'mas_episodes_text_options' => 'release_date',
				),
			)
		);

		$this->add_control(
			'custom_format',
			array(
				'label'     => esc_html__( 'Custom Format', 'mas-addons-for-elementor' ),
				'default'   => gmdate( 'Y-m-d' ),
				'condition' => array(
					'date_format'               => 'custom',
					'mas_episodes_text_options' => 'release_date',
				),
			)
		);
	}

	/**
	 * Render the post  title.
	 */
	public function render() {
		$episodes = masvideos_get_episode( get_the_ID() );

		$settings = $this->get_settings();

		$text_output = '';
		if ( ! empty( $episodes ) ) {
			if ( 'episode_number' === $settings['mas_episodes_text_options'] ) {
				$text_output = $episodes->get_episode_number();
			}
			if ( 'release_date' === $settings['mas_episodes_text_options'] ) {
				$date_format  = 'custom' !== $settings['date_format'] ? $settings['date_format'] : $settings['custom_format'];
				$release_date = $episodes->get_episode_release_date();
				$release_date = gmdate( $date_format, strtotime( $release_date ) );
				$text_output  = $release_date;
			}
			if ( 'time_duration' === $settings['mas_episodes_text_options'] ) {
				$text_output = $episodes->get_episode_run_time();
			}
			if ( 'imdb_id' === $settings['mas_episodes_text_options'] ) {
				$text_output = $episodes->get_imdb_id();
			}
			if ( 'tmdb_id' === $settings['mas_episodes_text_options'] ) {
				$text_output = $episodes->get_tmdb_id();
			}
		}
		echo wp_kses_post( $text_output );
	}
}
