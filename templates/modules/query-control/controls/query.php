<?php
/**
 * The Query.
 *
 * @package MASElementor/Modules/QueryControl/Controls
 */

namespace MASElementor\Modules\QueryControl\Controls;

use Elementor\Control_Select2;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Query
 *
 * @deprecated since 2.5.0, use class Control_Select2
 */
class Query extends Control_Select2 {

	/**
	 * Get the type.
	 *
	 * @return string
	 */
	public function get_type() {
		return 'query';
	}

	/**
	 * 'query' can be used for passing query args in the structure and format used by WP_Query.
	 *
	 * @return array
	 */
	protected function get_default_settings() {
		return array_merge(
			parent::get_default_settings(),
			array(
				'query' => '',
			)
		);
	}

	/**
	 * Enqueue Scripts.
	 *
	 * @return void
	 */
	public function enqueue() {
		// script.
		wp_register_script( 'mas-ajaxchoose-control', MAS_ELEMENTOR_MODULES_URL . 'query-control/assets/js/ajaxchoose.js', array(), MAS_ELEMENTOR_VERSION, true );
		wp_enqueue_script( 'mas-ajaxchoose-control' );
	}

	/**
	 * Content Template.
	 *
	 * @return void
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
				<label for="<?php echo esc_html( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# 
					var multiple = ( data.multiple ) ? 'multiple' : '';
				#>
				<select id="<?php echo esc_html( $control_uid ); ?>" class="elementor-select2" data-autocomplete="{{ JSON.stringify( data.autocomplete ) }}" type="select2" {{ multiple }} data-setting="{{ data.name }}">
					<# _.each( data.options, function( option_title, option_value ) {
						var value = data.controlValue;
						if ( typeof value == 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var value = _.values( value );
							var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
						}
						#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
