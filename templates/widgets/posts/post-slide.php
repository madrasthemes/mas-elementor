<?php
/**
 * Template for displaying Post Slide Swiper widget.
 *
 * @package MASElementor/Templates/widgets/Posts
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
<!-- Swiper Slider -->
<div class="swiper-center-mode-end">
    <div class="js-swiper-card-grid swiper swiper-equal-height ps-4">
        <div class="swiper-wrapper">
            <!-- Slide -->
            <?php

            // It's the global `wp_query` it self. and the loop was started from the theme.
            if ( $query->in_the_loop ) {
                    $widget->current_permalink = get_permalink();
                    mas_elementor_get_template( 'loop-post/loop-slide-post-1.php');
            } else {
                $i = 0;
                while ( $query->have_posts() ) {
                    $query->the_post();

                    $widget->current_permalink = get_permalink();
                    if ( 0 === $i % 2 ) {
                        mas_elementor_get_template( 'loop-post/loop-slide-post-1.php');
                    }
                    if (  0 !== $i % 2 ) {
                        mas_elementor_get_template( 'loop-post/loop-slide-post-2.php');
                    }
                    $i++;
                }
            }
            wp_reset_postdata();
            ?>
            <!-- End Slide -->
        </div>

        <!-- Arrows -->
        <div class="js-swiper-card-grid-button-prev swiper-button-prev swiper-static-button-prev"></div>
        <div class="js-swiper-card-grid-button-next swiper-button-next swiper-static-button-next"></div>

        <!-- Preloader -->
        <div class="js-swiper-preloader swiper-preloader">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div> 
        <!-- End Preloader -->
    </div>
</div>
<!-- End Swiper Slider -->
