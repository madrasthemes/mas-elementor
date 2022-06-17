<?php
/**
 * Template for displaying Post Grid widget.
 *
 * @package MASElementor/Templates/Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="col mb-5 mb-md-0">
    <!-- Card -->
    <a class="card card-ghost card-transition-zoom h-100" href="<?php echo esc_url( get_permalink() ); ?>">
        <div class="card-pinned card-transition-zoom-item">
            <img class="card-img" src="<?php the_post_thumbnail( 'full' ); ?>" alt="Image Description">
            <span class="badge bg-dark text-white card-pinned-top-end">Sponsored</span>
        </div>
        
        <div class="card-body">
            <h4><?php the_title(); ?></h4>
            <p class="card-text"><?php the_excerpt(); ?></p>
        </div>

        <div class="card-footer">
            <span class="card-link"><?php echo esc_html( apply_filters( 'prefix_text', 'Explore' ) ); ?> <?php the_title(); ?></span>
        </div>
    </a>
    <!-- End Card -->
</div>
<!-- End Col -->