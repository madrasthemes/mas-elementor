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
class Mas_Videos_Episodes_Url_Fields extends Data_Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'mas-episodes-url';
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
		return esc_html__( 'MAS Episodes URL', 'mas-addons-for-elementor' );
	}

	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		$episodes = masvideos_get_episode( get_the_ID() );

		$settings = $this->get_settings();
		$value    = '';
		if ( ! empty( $episodes ) ) {
			if ( 'episode_url' === $episodes->get_episode_choice() ) {
				$value = $episodes->get_episode_url_link();
			}
			if ( 'episode_file' === $episodes->get_episode_choice() ) {
				$value = wp_get_attachment_url( $episodes->get_episode_attachment_id() );
			}
		}

		return $value;
	}
}
