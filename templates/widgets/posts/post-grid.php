<?php
/**
 * Template for displaying Post Card Grid widget.
 *
 * @package MASElementor/Templates/Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$widget->query_posts();

$query = $widget->get_query();

if ( ! $query->found_posts ) {
	return;
}

// It's the global `wp_query` it self. and the loop was started from the theme.
if ( $query->in_the_loop ) {

	$widget->current_permalink = get_permalink();
	mas_elementor_get_template( 'loop-post/loop-post.php', ['widget' => $widget] );
	wp_reset_postdata();

} else {

	while ( $query->have_posts() ) {
		$query->the_post();

		$widget->current_permalink = get_permalink();
		mas_elementor_get_template( 'loop-post/loop-post.php', ['widget' => $widget] );
	}
	wp_reset_postdata();
}
wp_reset_postdata();
