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

?>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3"><?php

	// It's the global `wp_query` it self. and the loop was started from the theme.
	if ( $query->in_the_loop ) {

		$widget->current_permalink = get_permalink();
		mas_elementor_get_template( 'loop-post/loop-post.php');

	} else {

		while ( $query->have_posts() ) {
			$query->the_post();

			$widget->current_permalink = get_permalink();
			mas_elementor_get_template( 'loop-post/loop-post.php');
		}
	}
	wp_reset_postdata();
	?>
</div>
