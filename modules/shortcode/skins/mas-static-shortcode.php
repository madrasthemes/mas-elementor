<?php
/**
 * Skin Shortcode.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Shortcode\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Widget_Base;
use Elementor\Plugin;

/**
 * Skin Button MAS
 */
class MAS_Static_Shortcode extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'mas-static-shorcode';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Static', 'mas-addons-for-elementor' );
	}

	/**
	 * Render shortcode widget output on the frontend.
	 */
	public function render() {
		$shortcode = $this->parent->get_settings_for_display( 'shortcode' );

		if ( empty( $shortcode ) ) {
			return;
		}

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
		}

		?>
		<div class="elementor-shortcode"><?php echo wp_kses_post( $shortcode ); ?></div>
		<?php
	}
}
