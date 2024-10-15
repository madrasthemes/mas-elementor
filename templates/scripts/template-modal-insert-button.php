<?php
/**
 * Template Insert Button
 */

// phpcs:ignoreFile
?>
<# if ( 'valid' === window.MasPremiumTempsData.license.status || ! pro ) { #>
	<button class="elementor-template-library-template-action mas-premium-template-insert elementor-button elementor-button-success">
		<i class="eicon-file-download"></i><span class="elementor-button-title">
		<?php
			echo __( 'Insert', 'mas-addons-for-elementor' );
		?>
		</span>
	</button>
<# } else { #>
<a class="template-library-activate-license elementor-button elementor-button-go-pro" href="{{{ window.MasPremiumTempsData.license.activateLink }}}" target="_blank">
	<i class="fa fa-external-link" aria-hidden="true"></i>
	{{{ window.MasPremiumTempsData.license.proMessage }}}
</a>
<# } #>
