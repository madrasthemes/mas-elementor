<?php
/**
 * Icon.
 *
 * @package MASElementor\Modules\Shortcode
 */

namespace MASElementor\Modules\Shortcode;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module
 */
class Module extends Module_Base {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	/**
	 * Get Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'shortcode';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/widget/shortcode/skins_init', array( $this, 'init_skins' ), 10 );
	}

	/**
	 * Add Action.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function init_skins( $widget ) {
		$widget->add_skin( new Skins\MAS_Static_Shortcode( $widget ) );
	}

}
