<?php
/**
 * Template for displaying loop post.
 *
 * @package MASElementor/Templates/loop-post/loop-post.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$temp = $widget->get_settings_for_display();
print( mas_render_template( $temp['select_loop_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped


