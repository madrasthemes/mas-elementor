<?php
/**
 * The Skin Classic.
 *
 * @package MASElementor/Modules/Posts/Skins
 */

namespace MASElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Classic
 */
class Skin_Grid extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'mas-post-grid';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Grid', 'mas-elementor' );
	}

	/**
	 * Render Post.
	 */
	public function render_post() {
		$settings       = $this->parent->get_settings_for_display();
		$skin_name      = $settings['_skin'];
		$template_hooks = str_replace( '-', '_', $skin_name );
		$filter         = $template_hooks . '_template_args';
		$args           = apply_filters(
			$filter,
			array(
				'widget' => $this->parent,
				'skin'   => $this,
			)
		);
		mas_elementor_get_template( 'widgets/post-grid.php', $args );
	}

}
