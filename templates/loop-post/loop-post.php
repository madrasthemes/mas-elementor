<?php
/**
 * Template for displaying loop post.
 *
 * @package MASElementor/Templates/loop-post/loop-post.php
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
            $category_list = get_the_category();
            foreach( $category_list as $item ) {
                ?><span class="badge bg-dark text-white card-pinned-top-end mas-post-category"><?php echo $item->cat_name; ?></span><?php
            }
            ?>
        </div>
        
        <div class="card-body">
            <h4 class="mas-post-title"><?php the_title(); ?></h4>
            <p class="card-text mas-post-excerpt"><?php echo  get_the_excerpt(); ?></p>
        </div>

        <div class="card-footer" style="margin-top: -2px;">
            <span class="card-link mas-post-action-text"><?php echo esc_html( apply_filters( 'prefix_text', 'Explore' ) ); ?> <?php the_title(); ?></span>
        </div>
    </a>
    <!-- End Card -->
</div>
<!-- End Col -->