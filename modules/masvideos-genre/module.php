<?php
/**
 * The MAS Breadcrumb Widget.
 *
 * @package MASElementor/Modules/MasBreadcrumb/Widgets
 */

namespace MASElementor\Modules\MasvideosGenre;

use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {
	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		$widgets = array();
		if ( class_exists( 'MasVideos' ) ) {
			$widgets[] = 'Movie_Genre_Filter';
		}
		return $widgets;
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masvideos-genre-filter';
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );

	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_register_style(
			'genre-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'masvideos-genre/assets/css/genre-filter.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}
}
