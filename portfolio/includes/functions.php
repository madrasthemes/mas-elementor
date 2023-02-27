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
		$mimes['json'] = 'application/json';
		$mimes['svg']  = 'image/svg+xml';
		return $mimes;
	}
}

add_filter( 'upload_mimes', 'mas_cc_mime_types' );

add_filter( 'mas_single_post_show_content_title', '__return_true' );

add_filter( 'mas_load_min_css', '__return_true' );
add_filter( 'mas_load_min_js', '__return_true' );
