<?php
/**
 * Template for displaying loop slide post.
 *
 * @package MASElementor/Templates/loop-post/loop-slide-post-2.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="swiper-slide pt-4 pb-8">
    <a class="card card-transition bg-primary" href="<?php echo esc_url( get_permalink() ); ?>" style="background-image: url( <?php echo the_post_thumbnail_url(); ?> );">
        <div class="card-body">
            <?php
            $category_list = get_the_category();
            foreach( $category_list as $item ) {
                ?><span class="card-subtitle text-white mb-3 mas-post-category"><?php echo $item->cat_name; ?></span><?php
            }
            ?>
            <h3 class="card-title text-white lh-base mas-post-title"><?php the_title(); ?></h3>
        </div>
        <div class="card-footer pt-0">
            <span class="card-link link-light mas-post-action-text"><?php echo esc_html( apply_filters( 'prefix_text', 'Read more' ) ); ?></span>
        </div>
    </a>
</div>