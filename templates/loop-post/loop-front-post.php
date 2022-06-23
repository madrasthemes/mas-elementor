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
<!-- End Col -->
<div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <!-- Card -->
    <a class="card card-transition h-100" href="<?php echo esc_url( get_permalink() ); ?>">
    <?php the_post_thumbnail( 'full', array( 'class' => 'card-img-top' ) ); ?>
    <div class="card-body">
        <?php
        $category_list = get_the_category();
        foreach( $category_list as $item ) {
            ?><span class="card-subtitle text-primary mas-post-category"><?php echo $item->cat_name; ?></span><?php
        }
        ?>
        <h5 class="card-text lh-base mas-post-title"><?php the_title(); ?></h5>
    </div>
    </a>
    <!-- End Card -->
</div>