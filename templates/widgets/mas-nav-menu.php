<?php
/**
 * Template for displaying Mas Nav Menu widget.
 *
 * @package MASElementor/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	$settings = $widget->get_settings_for_display();

	$available_menus = $widget->get_available_menus();

if ( ! $available_menus ) {
	return;
}

	$settings = $widget->get_active_settings();

if ( ! empty( $settings['menu'] ) ) {
	$widget->add_render_attribute( 'main-menu', 'class', 'mas-elementor-mas-nav-menu' );
}
	$walker = new WP_Bootstrap_Navwalker();
	$args   = array(
		'echo'        => false,
		'menu'        => $settings['menu'],
		'menu_class'  => 'mas-elementor-nav-menu',
		'menu_id'     => 'menu-' . $widget->get_nav_menu_index() . '-' . $widget->get_id(),

		'fallback_cb' => '__return_empty_string',
		'container'   => '',

	);
	if ( 'bootstrap' === $settings['walker'] ) {
		$args['walker'] = $walker;
	}

	// General Menu.
	$menu_html = wp_nav_menu( $args );

	if ( empty( $menu_html ) ) {
		return;
	}

	?>
	<?php echo wp_kses_post( $menu_html ); ?>
	<?php

	$settings = $widget->get_settings_for_display();
	require plugin_dir_path( MAS_ELEMENTOR__FILE__ ) . 'classes/class-wp-bootstrap-navwalker.php';

	$available_menus = $widget->get_available_menus();

	if ( ! $available_menus ) {
		return;
	}

	$settings = $widget->get_active_settings();

	if ( ! empty( $settings['menu'] ) ) {
		$widget->add_render_attribute( 'main-menu', 'class', 'mas-elementor-mas-nav-menu' );
	}
	$walker = new MAS_Bootstrap_Navwalker();
	$args   = array(
		'echo'        => false,
		'menu'        => $settings['menu'],
		'menu_class'  => 'mas-elementor-nav-menu',
		'menu_id'     => 'menu-' . $widget->get_nav_menu_index() . '-' . $widget->get_id(),

		'fallback_cb' => '__return_empty_string',
		'container'   => '',

	);
	if ( 'bootstrap' === $settings['walker'] ) {
		$args['walker'] = apply_filters( 'mas_nav_menu_walker', $walker );
	}

	// General Menu.
	$menu_html = wp_nav_menu( $args );

	if ( empty( $menu_html ) ) {
		return;
	}

	?>
	<?php echo wp_kses_post( $menu_html ); ?>
	<?php
