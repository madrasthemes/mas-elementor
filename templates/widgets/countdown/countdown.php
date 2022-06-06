<?php
/**
 * Template for displaying countdown widget.
 *
 * @package MASElementor/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$instance = $widget->get_settings_for_display();
$due_date = $instance['due_date'];
$string   = $widget->get_strftime( $instance );

if ( 'evergreen' === $instance['countdown_type'] ) {
    $widget->add_render_attribute( 'div', 'data-evergreen-interval', $widget->get_evergreen_interval( $instance ) );
} else {
    // Handle timezone ( we need to set GMT time )
    $gmt = get_gmt_from_date( $due_date . ':00' );
    $due_date = strtotime( $gmt );
}

$actions = false;

//if ( ! Plugin::elementor()->editor->is_edit_mode() ) {
    $actions = $widget->get_actions( $instance );
//}

if ( $actions ) {
    $widget->add_render_attribute( 'div', 'data-expire-actions', json_encode( $actions ) );
}

$widget->add_render_attribute( 'div', [
    'class' => 'elementor-countdown-wrapper',
    'data-date' => $due_date,
] );

?>
<div <?php echo $widget->get_render_attribute_string( 'div' ); ?>>
    <?php echo $string; ?>
</div>
<?php
if ( $actions && is_array( $actions ) ) {
    foreach ( $actions as $action ) {
        if ( 'message' !== $action['type'] ) {
            continue;
        }
        echo '<div class="elementor-countdown-expire--message">' . $instance['message_after_expire'] . '</div>';
    }
}