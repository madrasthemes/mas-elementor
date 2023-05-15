<?php
/**
 * Template for displaying Multipurpose Text widget.
 *
 * @package MASElementor/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$settings = $widget->get_settings_for_display();

if ( ! empty( $settings['typing_text'] ) ) {
	$animation_typed_text_array = explode( "\n", $settings['typing_text'] );

	$animation_typed_text = array(
		'strings' => $animation_typed_text_array,
	);
}

if ( '' === $settings['highlighted_text'] && '' === $settings['before_title'] ) {
	return;
}

$widget->add_render_attribute( 'title', 'class', 'mas-elementor-multipurpose-text__title' );

if ( ! empty( $settings['title_css'] ) ) {
	$widget->add_render_attribute( 'title', 'class', $settings['title_css'] );
}

$widget->add_render_attribute( 'highlight', 'class', 'mas-elementor-multipurpose-text__highlighted-text' );

if ( ! empty( $settings['highlighted_css'] ) ) {
	$widget->add_render_attribute( 'highlight', 'class', $settings['highlighted_css'] );
}

if ( ! empty( $settings['highlighted_text'] ) ) {
	$highlighted_text = '<span ' . $widget->get_render_attribute_string( 'highlight' ) . '>' . $settings['highlighted_text'] . '</span>';
} else {
	$highlighted_text = '';
}

/**
 * Typed Text.
 */

$typing_text = '';

$widget->add_render_attribute( 'typing_text', 'class', array( 'mas-elementor-headline-dynamic-text', 'mas-elementor-headline-dynamic-text_underline' ) );

if ( ! empty( $settings['typing_text_classes'] ) ) {
			$widget->add_render_attribute( 'typing_text', 'class', $settings['typing_text_classes'] );
}

if ( ! empty( $settings['typing_text'] ) && 'yes' === $settings['enable_typing_text'] ) {
	$typing_text = '<span ' . $widget->get_render_attribute_string( 'typing_text' ) . '></span>';

}


/**
 * Wrap before text.
 */
$before_text = '';

if ( ! empty( $settings['before_css'] ) ) {
	$widget->add_render_attribute( 'before_text', 'class', $settings['before_css'] );
}

if ( ! empty( $settings['before_title'] ) ) {
	$before_text = '<span ' . $widget->get_render_attribute_string( 'before_text' ) . '>' . $settings['before_title'] . '</span>';
}

/**
 * Wrap After text.
 */
$after_text = '';
$widget->add_render_attribute( 'after_text', 'class', 'mas-multipurpose-text__before' );
if ( ! empty( $settings['after_css'] ) ) {
	$widget->add_render_attribute( 'after_text', 'class', $settings['after_css'] );
}

if ( ! empty( $settings['after_title'] ) ) {
	$after_text = '<span ' . $widget->get_render_attribute_string( 'after_text' ) . '>' . $settings['after_title'] . '</span>';
}

$title_text = $before_text . $highlighted_text . $typing_text . $after_text;

if ( ! empty( $settings['link']['url'] ) ) {
	$widget->add_link_attributes( 'url', $settings['link'] );
	$widget->add_render_attribute( 'url', 'class', array( 'text-decoration-none', $settings['link_css'] ) );

	$title_text = sprintf( '<a %1$s>%2$s</a>', $widget->get_render_attribute_string( 'url' ), $title_text );
}

$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $widget->get_render_attribute_string( 'title' ), $title_text );

echo wp_kses_post( $title_html );
