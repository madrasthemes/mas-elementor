(function($, window){
    'use strict';

    $( document ).ready( function() {

        if( mas_job_filter_options.enable_live_search ) {
            $( '.mas-job-search-form #search_keywords' ).autocomplete({
                source: function(req, response){
                    $.getJSON( mas_job_filter_options.ajax_url + '?callback=?&action=mas_live_search_jobs_suggest', req, response);
                },
                select: function(event, ui) {
                    location.href = ui.item.link;
                },
                open: function(event, ui) {
                    $(this).autocomplete("widget").width($(this).innerWidth());
                },
                minLength: 3
            });

            $( '.resume-search-form #search_keywords' ).autocomplete({
                source: function(req, response){
                    $.getJSON( mas_job_filter_options.ajax_url + '?callback=?&action=mas_live_search_resumes_suggest', req, response);
                },
                select: function(event, ui) {
                    location.href = ui.item.link;
                },
                open: function(event, ui) {
                    $(this).autocomplete("widget").width($(this).innerWidth());
                },
                minLength: 3
            });
        }

        if( mas_job_filter_options.enable_location_geocomplete ) {
            $( '#search_location, .location-search-field' ).geocomplete(mas_job_filter_options.location_geocomplete_options).bind( "geocode:result", function( event, result ) {
                $( this ).closest("form").submit();
            } );
        }
    
    });

})(jQuery);