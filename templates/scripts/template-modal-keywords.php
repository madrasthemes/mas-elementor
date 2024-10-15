<?php
/**
 * Templates Keywords Filter
 */

// phpcs:ignoreFile
?>
<#
	if ( ! _.isEmpty( keywords ) ) {
#>
<label><?php echo __( 'Filter by Widget / Addon', 'mas-addons-for-elementor' ); ?></label>
<select id="elementor-template-library-filter-subtype" class="elementor-template-library-filter-select mas-premium-library-keywords" data-elementor-filter="subtype">
	<option value=""><?php echo __( 'All Widgets/Addons', 'mas-addons-for-elementor' ); ?></option>
	<# _.each( keywords, function( title, slug ) { #>
	<option value="{{ slug }}">{{ title }}</option>
	<# } ); #>
</select>
<#
	}
#>
