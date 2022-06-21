<?php
/**
 * Template for displaying Post loop.
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
            <?php the_post_thumbnail( 'full', array( 'class' => 'card-img' ) ); ?>
            <?php
            $category_detail = get_the_category();
            foreach( $category_detail as $cd ) {
                ?><span class="badge bg-dark text-white card-pinned-top-end"><?php echo $cd->cat_name; ?></span><?php
            }
            ?>
        </div>
        
        <div class="card-body">
            <h4 class="mas-post-title"><?php the_title(); ?></h4>
            <p class="card-text mas-post-excerpt"><?php echo  get_the_excerpt(); ?></p>
        </div>

        <div class="card-footer">
            <span class="card-link mas-post-action-text"><?php echo esc_html( apply_filters( 'prefix_text', 'Explore' ) ); ?> <?php the_title(); ?></span>
        </div>
    </a>
    <!-- End Card -->
</div>
<!-- End Col -->