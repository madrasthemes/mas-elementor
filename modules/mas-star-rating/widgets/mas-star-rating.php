<?php
/**
 * MAS Star Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\MasStarRating\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use MASElementor\Base\Base_Widget;
use Elementor\Plugin;
use MASElementor\Plugin as MASPlugin;
use Elementor\Widget_Icon_List;
use Elementor\Icons_Manager;
use Elementor\Widget_Star_Rating;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Star Widget.
 */
class MAS_Star_Rating extends Widget_Star_Rating {
	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-star-rating';
	}

	 /**
	  * Get the name of the widget.
	  *
	  * @return string
	  */
	public function get_group_name() {
		return 'star-rating';
	}

	/**
	 * Get the name of the title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'MAS Star Rating', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the name of the icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-rating';
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
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'star', 'rating', 'rate', 'review' );
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['rating'] ) ) {
			parent::render();
		}
	}
}
