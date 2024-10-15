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
class Author_URL extends Data_Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'author-url';
	}

	/**
	 * Get tag group.
	 */
	public function get_group() {
		return Module::AUTHOR_GROUP;
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
		return esc_html__( 'Author URL', 'mas-addons-for-elementor' );
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
		$value = '';

		if ( 'archive' === $this->get_settings( 'url' ) ) {
			global $authordata;

			if ( $authordata ) {
				$value = get_author_posts_url( $authordata->ID, $authordata->user_nicename );
			}
		} else {
			$value = get_the_author_meta( 'url' );
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
				'default' => 'archive',
				'options' => array(
					'archive' => esc_html__( 'Author Archive', 'mas-addons-for-elementor' ),
					'website' => esc_html__( 'Author Website', 'mas-addons-for-elementor' ),
				),
			)
		);
	}
}
