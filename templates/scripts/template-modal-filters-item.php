<?php
/**
 * Template Library Filter Item
 */

// phpcs:ignoreFile
?>
<label class="mas-premium-template-filter-label">
	<input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="mas-premium-template-filter">
	<span>{{ title.replace('&amp;', '&') }}</span>
</label>
