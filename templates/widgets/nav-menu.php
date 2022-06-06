<?php
/**
 * Template for displaying Nav Menu widget.
 *
 * @package MASElementor/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


    $available_menus = $this->get_available_menus();

    if ( ! $available_menus ) {
        return;
    }

    $settings = $this->get_active_settings();

    $ul_class = '';
    if ( $settings['ul_class'] ) {
        $ul_class .= ' ' . $settings['ul_class'];
    }

    $li_class = '';
    if ( $settings['li_class'] ) {
        $li_class .= ' ' . $settings['li_class'];
    }

    $anchor_class = '';
    if ( $settings['anchor_class'] ) {
        $anchor_class .= ' ' . $settings['anchor_class'];
    }

    $args = [
        'echo'         => false,
        'menu'         => $settings['menu'],
        'menu_class'   => $ul_class,
        'menu_id'      => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
        'fallback_cb'  => '__return_empty_string',
        'container'    => '',
        'add_li_class' => $li_class,
        'add_a_class'  => $anchor_class,
    ];

    add_filter( 'nav_menu_css_class', [ $this, 'add_additional_class_on_li' ], 1, 3 );
    add_filter( 'nav_menu_link_attributes', [ $this, 'add_additional_class_on_a' ], 1, 3 );

    if ( 'inline' === $settings['view'] ) {
        $args['menu_class'] .= ' flex-column';
    }

    // General Menu.
    $menu_html = wp_nav_menu( $args );

    if ( empty( $menu_html ) ) {
        return;
    }

    ?>
    <?php echo wp_kses_post( $menu_html ); ?>
    <?php
