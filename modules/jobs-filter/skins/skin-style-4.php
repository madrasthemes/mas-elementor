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

class Skin_Style_4 extends Skin_Base {


    public function get_id() {
        return 'jobs-filter-skin-4';
    }

    public function get_title() {
        return esc_html__( 'Style 4', 'mas-elementor' );
    }

    /**
     * Render.
     */
    public function render(){
        $jobs_page_url = get_page_link( get_option( 'job_manager_jobs_page_id' ) );
        ?>
        <form method="GET" action="https://jobhunt.madrasthemes.com/jobs/" style="display:flex;">
            <div class="job-search-keywords">
                <label class="sr-only" for="search_keywords">Keywords</label>
                <input type="text" id="search_keywords" name="search_keywords" placeholder="Search Keywords" class="ui-autocomplete-input" autocomplete="off">
            </div>
            <div class="job-search-location">
                <label class="sr-only" for="search_location">Location</label>
                <input type="text" id="search_location" name="search_location" placeholder="City, province or region" class="pac-target-input" autocomplete="off">
            </div>
            <div class="job-search-category">
                <label class="sr-only" for="search_category">Category</label>
                <select id="search_category" name="search_category">
                    <option value="">All specialisms</option>
                    <option value="102">Construction</option>
                    <option value="105">Corodinator</option>
                    <option value="117">Employer</option>
                    <option value="129">Financial Career</option>
                    <option value="137">Information Technology</option>
                    <option value="152">Marketing</option>
                    <option value="165">Quality check</option>
                    <option value="166">Real Estate</option>
                    <option value="171">Sales</option>
                    <option value="177">Supporting</option>
                    <option value="179">Teaching</option>
                </select>
            </div>
            <div class="job-search-submit">
            <button type="submit" value="Search"><i class="la la-search"></i><span class="job-search-text">Search</span></button>
            </div>
            <input type="hidden" name="post_type" value="job_listing">
        </form><?php
    }

}

