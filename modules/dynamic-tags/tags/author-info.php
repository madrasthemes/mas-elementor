<?php
/**
 * Author Info.
 *
 * @package MASElementor\Modules\DynamicTags\tags\author-info.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Author Info class.
 */
class Author_Info extends Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'author-info';
	}

	/**
	 * Get tag title.
	 */
	public function get_title() {
		return esc_html__( 'Author Info', 'mas-addons-for-elementor' );
	}

	/**
	 * Get tag group.
	 */
	public function get_group() {
		return Module::AUTHOR_GROUP;
	}

	/**
	 * Get tag categories.
	 */
	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}

	/**
	 * Render.
	 */
	public function render() {
		$key = $this->get_settings( 'key' );

		if ( empty( $key ) ) {
			return;
		}

		$value = get_the_author_meta( $key );

		echo wp_kses_post( $value );
	}

	/**
	 * Get Panel settings.
	 */
	public function get_panel_template_setting_key() {
		return 'key';
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'key',
			array(
				'label'   => esc_html__( 'Field', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'description',
				'options' => array(
					'description' => esc_html__( 'Bio', 'mas-addons-for-elementor' ),
					'email'       => esc_html__( 'Email', 'mas-addons-for-elementor' ),
					'url'         => esc_html__( 'Website', 'mas-addons-for-elementor' ),
				),
			)
		);
	}
}
