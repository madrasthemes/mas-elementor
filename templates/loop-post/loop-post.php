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
print( mas_template_render( $temp['select_template'], false ) );
?>


<!-- End Col -->