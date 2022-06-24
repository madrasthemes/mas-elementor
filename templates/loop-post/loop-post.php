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

<div class="mas-dummy">
    <!-- Card -->
    <a class="mas-dummy" href="<?php echo esc_url( get_permalink() ); ?>">
        <div class="mas-dummy">
            <?php the_post_thumbnail( 'full', array( 'class' => 'mas-dummy' ) ); ?>
            <?php
            $category_list = get_the_category();
            foreach( $category_list as $item ) {
                ?><span class="mas-post-category"><?php echo $item->cat_name; ?></span><?php
            }
            ?>
        </div>
        
        <div class="mas-dummy">
            <h4 class="mas-post-title"><?php the_title(); ?></h4>
            <p class="mas-dummy mas-post-excerpt"><?php echo  get_the_excerpt(); ?></p>
        </div>

        <div class="mas-dummy">
            <span class="mas-post-action-text"><?php echo esc_html( apply_filters( 'prefix_text', 'Explore' ) ); ?> <?php the_title(); ?></span>
        </div>
    </a>
    <!-- End Card -->
</div>
<!-- End Col -->