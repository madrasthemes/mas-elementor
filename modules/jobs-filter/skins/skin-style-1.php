<?php
namespace MASElementor\Modules\JobsFilter\Skins;

use Elementor;
use Elementor\Skin_Base;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly.
}

class Skin_Style_1 extends Skin_Base {


    public function get_id() {
        return 'jobs-filter-skin-1';
    }

    public function get_title() {
        return esc_html__( 'Style 1', 'mas-elementor' );
    }

    /**
     * Render.
     */
    public function render(){
        $jobs_page_url = get_page_link( get_option( 'job_manager_jobs_page_id' ) );
        $widget    = $this->parent;   
        $settings  = $widget->get_settings_for_display();

        $widget->add_render_attribute( 
			'icon-align',
			'class', 
            [
                'mas-job-search-icon',
                'mas-job-search-icon-' . $settings['search_icon_align'],
                $settings['search_icon']['value']
            ]
		);

        ?>
        <form method="GET" action="<?php echo esc_url( $jobs_page_url ); ?>" class="mas-search-form" style="display:flex;">
            <div class="mas-job-search-keywords">
                <label class="sr-only" for="search_keywords"><?php echo esc_html( 'keyword' ); ?></label>
                <input type="text" id="search_keywords" name="search_keywords" placeholder="<?php echo esc_html( $settings['keyword_placeholder'] ); ?>" class="mas-elementor-search-keywords ui-autocomplete-input" autocomplete="off">
                <?php if( ! empty( $settings['job_title_icon']['value'] ) ):
                    ?><i class="<?php echo esc_attr( $settings['job_title_icon']['value'] ); ?>"></i>
                <?php endif;?>
            </div>
            <?php
            if( 'yes' === $settings['show_category'] && '2' === $settings['category_position'] ):?>
                <div class="mas-job-search-category">
                    <label class="sr-only" for="search_category"><?php echo esc_html__( 'Category', 'mas-elementor' ); ?></label>
                    <select id="search_category" name="search_category">
                        <option value=""><?php echo esc_html( $settings['category_placeholder'] ); ?></option>
                        <?php foreach ( get_job_listing_categories() as $cat ) : ?>
                        <option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif;?>
            <div class="mas-job-search-location">
                <label class="sr-only" for="search_location"><?php echo esc_html( 'location'); ?></label>
                <input type="text" id="search_location" name="search_location" placeholder="<?php echo esc_html( $settings['location_placeholder'] ); ?>" class="mas-elementor-search-location pac-target-input">
                <?php if( ! empty( $settings['location_icon']['value'] ) ):
                    ?><i class="<?php echo esc_attr( $settings['location_icon']['value'] ); ?>"></i>
                <?php endif;?>
            </div>
            <?php
            if( 'yes' === $settings['show_category'] && '3' === $settings['category_position'] ):?>
                <div class="mas-job-search-category">
                    <label class="sr-only" for="search_category"><?php echo esc_html__( 'Category', 'mas-elementor' ); ?></label>
                    <select id="search_category" name="search_category">
                        <option value=""><?php echo esc_html( $settings['category_placeholder'] ); ?></option>
                        <?php foreach ( get_job_listing_categories() as $cat ) : ?>
                        <option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif;?>
            <div class="mas-job-search-submit job-search-submit">
                <button type="submit" value="Search">
                <?php if( ! empty( $settings['search_icon']['value'] ) ):
                    ?>
                    <i <?php $widget->print_render_attribute_string( 'icon-align' ); ?>></i>
                <?php endif;?>
                <?php if( ! empty( $settings['search_text'] ) ):
                    ?><span class="mas-job-search-text"><?php echo esc_html( $settings['search_text'] ); ?></span>
                <?php endif;?>
                </button>
            </div>
            <input type="hidden" name="post_type" value="job_listing">
        </form><?php
    }

}
