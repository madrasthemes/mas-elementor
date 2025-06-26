<?php
/**
 * The Base Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Button;
use MASElementor\Base\Base_Widget_Trait;
use MASElementor\Modules\QueryControl\Module;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Add_To_Compare
 */
class Add_To_Compare extends Widget_Button {

	use Base_Widget_Trait;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-add-to-compare';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Add To Compare', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-woocommerce';
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elements' );
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'compare', 'product', 'button', 'add to compare' );
	}

	/**
	 * Return the script dependencies of the module.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mas-add-to-compare' );
	}

	/**
	 * On Export.
	 *
	 * @param array $element element.
	 * @return array
	 */
	public function on_export( $element ) {
		unset( $element['settings']['product_id'] );

		return $element;
	}

	/**
	 * Unescape HTML.
	 *
	 * @param string $safe_text safe_text.
	 * @param string $text text.
	 * @return string
	 */
	public function unescape_html( $safe_text, $text ) {
		return $text;
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$this->add_to_compare_render_button();
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @param \Elementor\Widget_Base|null $instance instance.
	 */
	protected function add_to_compare_render_button( Widget_Base $instance = null ) {
		global $yith_woocompare;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		$product_id = $product->get_id();

		if ( empty( $instance ) ) {
			$instance = $this;
		}

		$settings = $instance->get_settings_for_display();

		$instance->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

		$instance->add_render_attribute( 'button', 'class', 'elementor-button' );

		if ( ! empty( $settings['link']['url'] ) ) {
			// $instance->add_link_attributes( 'button', $settings['link'] );
			$instance->add_render_attribute( 'button', 'data-compare_url', $settings['link']['url'] );
			$instance->add_render_attribute( 'button', 'class', 'elementor-button-link' );
		} else {
			$instance->add_render_attribute( 'button', 'role', 'button' );
		}

		if ( ! empty( $settings['button_css_id'] ) ) {
			$instance->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$instance->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
		}

		if ( ! empty( $settings['hover_animation'] ) ) {
			$instance->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}
		
		// Check if YITH WooCommerce Compare plugin is active
		if ( class_exists( '\YITH_WooCompare_Form_handler' ) ) {
			$instance->add_render_attribute( 'button', 'href', \YITH_WooCompare_Form_handler::get_add_action_url( $product_id ) );
		} else {
			$instance->add_render_attribute( 'button', 'href', '#' );
		}
		
		$instance->add_render_attribute( 'button', 'class', 'add-to-compare-link' );
		$instance->add_render_attribute( 'button', 'data-product_id', $product_id );

		?>
		<div <?php $instance->print_render_attribute_string( 'wrapper' ); ?>>
			<a <?php $instance->print_render_attribute_string( 'button' ); ?>>
				<?php $this->render_text( $instance ); ?>
			</a>
		</div>
		<?php
	}

}
