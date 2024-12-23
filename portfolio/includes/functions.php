<?php
/**
 * Functions required in our theme.
 *
 * @package MASExtensions
 */

if ( ! function_exists( 'mas_cc_mime_types' ) ) {
	/**
	 * Enable JSON and SVG mime types.
	 *
	 * @param array $mimes mimes.
	 * @return array
	 */
	function mas_cc_mime_types( $mimes ) {
		// Ensure $mimes is an array.
		if ( ! is_array( $mimes ) ) {
			$mimes = array();
		}
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
}

if ( mas_elementor_is_elementor_activated() ) {
	add_filter( 'upload_mimes', 'mas_cc_mime_types' );
	add_filter( 'wp_handle_upload_prefilter', 'handle_mas_elementor_wp_media_upload' );
}

add_filter( 'mas_single_post_show_content_title', '__return_true' );

add_filter( 'mas_load_min_css', '__return_true' );
add_filter( 'mas_load_min_js', '__return_true' );

if ( ! function_exists( 'handle_mas_elementor_wp_media_upload' ) ) {
	/**
	 * Handle media upload.
	 *
	 * @param array $file file.
	 * @return array
	 */
	function handle_mas_elementor_wp_media_upload( $file ) {
		if ( ! mas_elementor_is_elementor_activated() ) {
			return;
		}
		$result = svg_validate_file( $file );

		if ( is_wp_error( $result ) ) {
			$file['error'] = $result->get_error_message();
		}

		return $file;
	}
}

if ( ! function_exists( 'svg_validate_file' ) ) {
	/**
	 * Svg Validation.
	 *
	 * @param array $file file.
	 * @return bool|\WP_Error
	 */
	function svg_validate_file( array $file ) {
		if ( ! mas_elementor_is_elementor_activated() ) {
			return;
		}
		$uploaded_file_name = isset( $file['name'] ) ? $file['name'] : $file['tmp_name'];

		$file_extension = pathinfo( $uploaded_file_name, PATHINFO_EXTENSION );

		if ( 'svg' !== $file_extension ) {
			return true;

		}
		// Here is each file type handler's chance to run its own specific validations.
		return ( new \Elementor\Core\Files\File_Types\Svg() )->validate_file( $file );
	}
}

