<?php
/**
 * Template for displaying countdown widget.
 *
 * @package MASElementor/Templates
 */

use Elementor\Plugin;
use MASElementor\Plugin as MASPlugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$instance = $widget->get_settings_for_display();
$due_date = $instance['due_date'];
$string   = $widget->get_strftime( $instance );


// Handle timezone ( we need to set GMT time ).
$gmt      = get_gmt_from_date( $due_date . ':00' );
$due_date = strtotime( $gmt );
$dated    = gmdate( 'd M Y H:i:s', $due_date );


$actions = false;

if ( ! MASPlugin::elementor()->editor->is_edit_mode() ) {
	$actions = $widget->get_actions( $instance );
}

if ( $actions ) {
	$widget->add_render_attribute( 'div', 'data-expire-actions', wp_json_encode( $actions ) );
}
$wrap_class = 'mas-elementor-countdown-wrapper';
$wrap_class = 'yes' === $instance['disable_last_child_margin'] ? 'cd-mr-child-0 ' . $wrap_class : $wrap_class;
$widget->add_render_attribute(
	'div',
	array(
		'class'     => $wrap_class,
		'data-date' => $dated,
	)
);

$link_url = isset( $instance['expire_redirect_url']['url'] ) ? $instance['expire_redirect_url']['url'] : '';
$widget->add_render_attribute(
	'expire-action',
	array(
		'class'        => 'new-message',
		'data-message' => wp_json_encode( $instance['expire_actions'] ),
		'href'         => $link_url,
	)
);

?>
<div <?php $widget->print_render_attribute_string( 'div' ); ?>>
	<?php echo wp_kses_post( $string ); ?>
	<div style="display:none">
	<a <?php $widget->print_render_attribute_string( 'expire-action' ); ?>>
		<?php echo wp_kses_post( '<div class="mas-elementor-countdown-expire--message">' . $instance['message_after_expire'] . '</div>' ); ?>
	</a>
	</div>
</div>
