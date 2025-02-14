<?php
/**
 * Template for displaying MAS Nav Menu widget.
 *
 * @package MASElementor/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	$settings = $widget->get_settings_for_display();
	require MAS_ELEMENTOR_PATH . 'classes/class-wp-bootstrap-navwalker.php';

	$available_menus = $widget->get_available_menus();

if ( ! $available_menus ) {
	return;
}

	$settings = $widget->get_active_settings();
	$layout_condition = 'horizontal' === $settings['layout'] || 'vertical' === $settings['layout'];

if ( ! empty( $settings['menu'] ) ) {
	$widget->add_render_attribute( 'main-menu', 'class', 'mas-elementor-mas-nav-menu' );
}
	$walker = new MAS_Bootstrap_Navwalker();
	$args   = array(
		'echo'        => false,
		'menu'        => $settings['menu'],
		'menu_class'  => 'mas-elementor-nav-menu menu',
		'menu_id'     => 'menu-' . $widget->get_nav_menu_index() . '-' . $widget->get_id(),
		'fallback_cb' => '__return_empty_string',
		'container'   => '',


	);

	if ( $layout_condition || 'default' === $settings['walker'] ) {
		$args['menu_class'] .= ' header-menu mas-elementor-nav-menu--main';
	}

	if ( 'dropdown' === $settings['layout'] ) {
		$args['menu_class'] .= ' handheld-header-menu header-menu mas-elementor-nav-menu--dropdown';
	}
	$wrap_class = 'mas-nav-menu main-navigation';
	if ( 'bootstrap' === $settings['walker'] ) {
		$args['walker'] = apply_filters( 'mas_nav_menu_walker', $walker );
		$wrap_class    .= ' mas-no-default';
	}

	// General Menu.
	$menu_html = wp_nav_menu( $args );

	if ( empty( $menu_html ) ) {
		return;
	}

	$dropdown_trigger = $settings['nav_action'];

	if ( ( $layout_condition && 'bootstrap' === $settings['walker'] ) || 'default' === $settings['walker'] ) {
		?>
		<div class="<?php echo esc_attr( $wrap_class ); ?>">
			<?php if ( 'click' === $dropdown_trigger ) { ?>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>" aria-controls="<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>" aria-expanded="false" aria-label="Toggle navigation">
					<i class="eicon-menu-bar"></i>
					<span class="navbar-toggler-icon"></span>
				</button>
				<?php
			} else {
				?>
					<button class="navbar-toggler" type="button" data-hover="dropdown" data-bs-target="#<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>" aria-controls="<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>" aria-expanded="false" aria-label="Toggle navigation">
					<i class="eicon-menu-bar"></i>
					<span class="navbar-toggler-icon"></span>
				</button>
					<?php
			}
			?>
			<div class="mas-align collapse handheld horizontal" id="<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>">

		<?php
		echo wp_kses_post( $menu_html );
		?>
		</div>
	</div>
		<?php
	}
	if ( ( 'dropdown' === $settings['layout'] && 'bootstrap' === $settings['walker'] ) ) {


		?>
		<div class="mas-hamburger-menu">
			<nav class="navbar mas-elementor-menu-toggle">
				<?php if ( 'click' === $dropdown_trigger ) { ?>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>" aria-controls="<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>" aria-expanded="false" aria-label="Toggle navigation">
					<i class="eicon-menu-bar"></i>
					<span class="navbar-toggler-icon"></span>
				</button>
					<?php
				} else {
					?>
					<button class="navbar-toggler" type="button" data-hover="dropdown" data-bs-target="#<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>" aria-controls="<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>" aria-expanded="false" aria-label="Toggle navigation">
					<i class="eicon-menu-bar"></i>
					<span class="navbar-toggler-icon"></span>
				</button>
					<?php
				}
				?>
				<div class="collapse handheld" id="<?php echo esc_attr( 'toggle-' . $widget->get_id() ); ?>">
					<?php echo wp_kses_post( $menu_html ); ?>
				</div>
			</nav>
		</div>
			<?php
	}
