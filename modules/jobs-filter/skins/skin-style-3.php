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

class Skin_Style_3 extends Skin_Base {


    public function get_id() {
        return 'jobs-filter-skin-3';
    }

    public function get_title() {
        return esc_html__( 'Style 3', 'mas-elementor' );
    }

    /**
     * Render.
     */
    public function render(){
        $jobs_page_url = get_page_link( get_option( 'job_manager_jobs_page_id' ) );
        ?>

        <form method="GET" action="<?php echo esc_url( $jobs_page_url ); ?>" style="display:flex;">
            <div class="resume-search-keywords">
                <label class="sr-only" for="search_keywords">Keywords</label>
                <input type="text" id="search_keywords" name="s" placeholder="Search freelancer services (e.g. logo design)" class="ui-autocomplete-input" autocomplete="off">
            </div>
            <div class="resume-search-location">
                <label class="sr-only" for="search_location">Location</label>
                <input type="text" id="search_location" name="search_location" placeholder="City, province or region" class="pac-target-input" autocomplete="off">
            </div>
            <div class="resume-search-submit">
                <button type="submit" value="Search"><i class="la la-search"></i><span class="resume-search-text">Search</span></button>
            </div>
            <input type="hidden" name="post_type" value="resume">
        </form><?php
    }

}

