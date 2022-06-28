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

if ( 'evergreen' === $instance['countdown_type'] ) {
	$widget->add_render_attribute( 'div', 'data-evergreen-interval', $widget->get_evergreen_interval( $instance ) );
} else {
	// Handle timezone ( we need to set GMT time ).
	$gmt      = get_gmt_from_date( $due_date . ':00' );
	$due_date = strtotime( $gmt );
	$dated    = gmdate( 'd M Y H:i:s', $due_date );
}

$actions = false;

if ( ! MASPlugin::elementor()->editor->is_edit_mode() ) {
	$actions = $widget->get_actions( $instance );
}

if ( $actions ) {
	$widget->add_render_attribute( 'div', 'data-expire-actions', json_encode( $actions ) );
}

$widget->add_render_attribute(
	'div',
	array(
		'class'     => 'mas-elementor-countdown-wrapper',
		'data-date' => $dated,
	)
);

?>
<div <?php $widget->print_render_attribute_string( 'div' ); ?>>
	<?php echo wp_kses_post( $string ); ?>
</div>
<?php
if ( $actions && is_array( $actions ) ) {
	foreach ( $actions as $act ) {
		if ( 'message' !== $act['type'] ) {
			continue;
		}
		echo wp_kses_post( '<div class="elementor-countdown-expire--message">' . $instance['message_after_expire'] . '</div>' );
	}
}
