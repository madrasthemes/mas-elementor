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
class Mas_Videos_Tv_Shows_Episode_Url_Fields extends Data_Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'mas-tv-shows-episode-url';
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
		return esc_html__( 'MAS Tv-Shows Episode URL', 'mas-addons-for-elementor' );
	}

	/**
	 * Get value.
	 *
	 * @param array $options control opions.
	 */
	public function get_value( array $options = array() ) {
		$tv_show = masvideos_get_tv_show( get_the_ID() );
		if ( empty( $tv_show ) ) {
			return;
		}
		$seasons = $tv_show->get_seasons();
		$value   = '';
		$index   = 1;
		if ( ! empty( $seasons ) ) {
			foreach ( $seasons as $key => $season ) {
				if ( ! empty( $season['episodes'] ) ) {
					foreach ( $season['episodes'] as $key => $episode_id ) {
						if ( 1 === $index ) {
							$episode = masvideos_get_episode( $episode_id );
							$value   = $episode->get_episode_url_link();
						}
						$index++;
					}
				}
			}
		}
		return $value;
	}
}
