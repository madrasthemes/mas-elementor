<?php
/**
 * Multipurpose Text Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\ReviewForm;

use MasVideos;
use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();

		add_filter( 'comments_template', array( __CLASS__, 'comments_template_loader' ) );
	}

	/**
	 * Get the widget of the module.
	 *
	 * @return string
	 */
	public function get_widgets() {
		return array(
			'Mas_Review_Form',
		);
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-review-form';
	}

	/**
	 * Load comments template.
	 *
	 * @param string $template template to load.
	 * @return string
	 */
	public static function comments_template_loader( $template ) {
		$post_type = get_post_type();

		if ( ! in_array( $post_type, array( 'episode', 'tv_show', 'video', 'movie' ), true ) ) {
			return $template;
		}

		$check_dirs = array(
			trailingslashit( get_stylesheet_directory() ) . MasVideos()->template_path(),
			trailingslashit( get_template_directory() ) . MasVideos()->template_path(),
			trailingslashit( get_stylesheet_directory() ),
			trailingslashit( get_template_directory() ),
			trailingslashit( MasVideos()->plugin_path() ) . 'templates/',
		);

		if ( MASVIDEOS_TEMPLATE_DEBUG_MODE ) {
			$check_dirs = array( array_pop( $check_dirs ) );
		}

		foreach ( $check_dirs as $dir ) {
			switch ( $post_type ) {
				case 'episode':
					$file_name = 'single-episode-reviews.php';
					break;
				case 'tv_show':
					$file_name = 'single-tv-show-reviews.php';
					break;
				case 'video':
					$file_name = 'single-video-reviews.php';
					break;
				case 'movie':
					$file_name = 'single-movie-reviews.php';
					break;
				default:
					$file_name = 'single-video-reviews.php';
					break;
			}

			if ( file_exists( trailingslashit( $dir ) . $file_name ) ) {
				return trailingslashit( $dir ) . $file_name;
			}
		}
	}
}
