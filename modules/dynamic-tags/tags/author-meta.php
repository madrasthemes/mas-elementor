<?php
/**
 * Author Meta.
 *
 * @package MASElementor\Modules\DynamicTags\tags\author-meta.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Author Info class.
 */
class Author_Meta extends Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'author-meta';
	}

	/**
	 * Get tag title.
	 */
	public function get_title() {
		return esc_html__( 'Author Meta', 'mas-addons-for-elementor' );
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
	 * Get panel template setting.
	 */
	public function get_panel_template_setting_key() {
		return 'key';
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
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'key',
			array(
				'label' => esc_html__( 'Meta Key', 'mas-addons-for-elementor' ),
			)
		);
	}
}
