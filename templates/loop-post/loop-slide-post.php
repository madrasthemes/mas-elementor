<?php
/**
 * Template for displaying loop slide post.
 *
 * @package MASElementor/Templates/loop-post/loop-slide-post.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="swiper-slide pt-4 pb-8">
    <a class="card card-transition" href="<?php echo esc_url( get_permalink() ); ?>">
        <div class="card-pinned">
            <?php the_post_thumbnail( 'full', array( 'class' => 'card-img-top' ) ); ?>
            <?php
            $category_list = get_the_category();
            foreach( $category_list as $item ) {
                ?><span class="badge bg-dark text-white card-pinned-top-end mas-post-category"><?php echo $item->cat_name; ?></span><?php
            }
            ?>
        </div>
        <div class="card-body">
            <h5 class="card-title mas-post-title"><?php the_title(); ?></h5>
            <p class="card-text mas-post-excerpt"><?php echo  get_the_excerpt(); ?></p>
        </div>
        <div class="card-footer pt-0">
            <span class="card-link mas-post-action-text"><?php echo esc_html( apply_filters( 'prefix_text', 'Read more' ) ); ?></span>
        </div>
    </a>

    <!-- <a class="card card-transition bg-primary" href="#" style="background-image: url(./assets/svg/components/wave-pattern-light.svg);">
        <div class="card-body">
            <span class="card-subtitle text-white mb-3">Product</span>
            <h3 class="card-title text-white lh-base">Assessing Constraints: Making Products for all Users</h3>
        </div>
        <div class="card-footer pt-0">
            <span class="card-link link-light">Read more</span>
        </div>
    </a> -->
</div>
    