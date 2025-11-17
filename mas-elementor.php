<?php
/**
 * The MAS Elementor Plugin.
 *
 * @package mas-elementor
 */

use Elementor\plugin;

/**
 * Plugin Name: MAS Addons for Elementor
 * Description: More power to your Elementor powered website with beautifully designed sections, templates, widgets, skins and extensions.
 * Plugin URI: https://github.com/madrasthemes/mas-elementor
 * Author: MadrasThemes
 * Version: 1.2.2
 * Elementor tested up to: 3.27.4
 * Author URI: https://madrasthemes.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: mas-addons-for-elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'MAS_ELEMENTOR_VERSION', '1.2.2' );

define( 'MAS_ELEMENTOR__FILE__', __FILE__ );
define( 'MAS_ELEMENTOR_BASE', plugin_basename( MAS_ELEMENTOR__FILE__ ) );
define( 'MAS_ELEMENTOR_PATH', plugin_dir_path( MAS_ELEMENTOR__FILE__ ) );
define( 'MAS_ELEMENTOR_ASSETS_PATH', MAS_ELEMENTOR_PATH . 'assets/' );
define( 'MAS_ELEMENTOR_MODULES_PATH', MAS_ELEMENTOR_PATH . 'modules/' );
define( 'MAS_ELEMENTOR_URL', plugins_url( '/', MAS_ELEMENTOR__FILE__ ) );
define( 'MAS_ELEMENTOR_ASSETS_URL', MAS_ELEMENTOR_URL . 'assets/' );
define( 'MAS_ELEMENTOR_MODULES_URL', MAS_ELEMENTOR_URL . 'modules/' );
define( 'MAS_ELEMENTOR_TEMPLATES_PATH', MAS_ELEMENTOR_PATH . 'templates/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mas_elementor_load_plugin() {
	load_plugin_textdomain( 'mas-addons-for-elementor' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'mas_elementor_fail_load' );

		return;
	}

	$elementor_version_required = '3.4.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'mas_elementor_fail_load_out_of_date' );

		return;
	}

	$elementor_version_recommendation = '3.4.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'mas_elementor_admin_notice_upgrade_recommendation' );
	}

	require MAS_ELEMENTOR_PATH . 'plugin.php';
}

add_action( 'plugins_loaded', 'mas_elementor_load_plugin' );
add_action( 'plugins_loaded', 'mas_extensions_includes', 20 );

/**
 * Print Error.
 *
 * @since 1.0.0
 *
 * @param string $message Message.
 *
 * @return void
 */
function mas_elementor_print_error( $message ) {
	if ( ! $message ) {
		return;
	}
	$message = '<div class="error">' . $message . '</div>';
	echo wp_kses_post( $message );
}
/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mas_elementor_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( mas_elementor_is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message  = '<h3>' . esc_html__( 'Activate the Elementor Plugin', 'mas-addons-for-elementor' ) . '</h3>';
		$message .= '<p>' . esc_html__( 'Before you can use all the features of MAS Addons for Elementor, you need to activate the Elementor plugin first.', 'mas-addons-for-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Now', 'mas-addons-for-elementor' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message  = '<h3>' . esc_html__( 'Install and Activate the Elementor Plugin', 'mas-addons-for-elementor' ) . '</h3>';
		$message .= '<p>' . esc_html__( 'Before you can use all the features of MAS Addons for Elementor, you need to install and activate the Elementor plugin first.', 'mas-addons-for-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor', 'mas-addons-for-elementor' ) ) . '</p>';
	}

	mas_elementor_print_error( $message );
}

/**
 * Fail Out of Date.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mas_elementor_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message      = '<p>' . esc_html__( 'MAS Addons for Elementor is not working because you are using an old version of Elementor.', 'mas-addons-for-elementor' ) . '</p>';
	$message     .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, esc_html__( 'Update Elementor Now', 'mas-addons-for-elementor' ) ) . '</p>';

	mas_elementor_print_error( $message );
}

/**
 * Upgrade Recommendation.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mas_elementor_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message      = '<p>' . esc_html__( 'A new version of Elementor is available. For better performance and compatibility of MAS Addons for Elementor, we recommend updating to the latest version.', 'mas-addons-for-elementor' ) . '</p>';
	$message     .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, esc_html__( 'Update Elementor Now', 'mas-addons-for-elementor' ) ) . '</p>';

	mas_elementor_print_error( $message );
}

if ( ! function_exists( 'mas_elementor_is_elementor_installed' ) ) {

	/**
	 * Elementor Install Check.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function mas_elementor_is_elementor_installed() {
		$file_path         = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

if ( ! function_exists( 'mas_elementor_is_elementor_activated' ) ) {

	/**
	 * Elementor Install Check.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function mas_elementor_is_elementor_activated() {
		return is_plugin_active( 'elementor/elementor.php' );
	}
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 */
function mas_elementor_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	global $mas_elementor_version;
	$cache_key = sanitize_key( implode( '-', array( 'template', $template_name, $template_path, $default_path, $mas_elementor_version ) ) );
	$template  = (string) wp_cache_get( $cache_key, 'mas-addons-for-elementor' );

	if ( ! $template ) {
		$template = mas_elementor_locate_template( $template_name, $template_path, $default_path );

		// Don't cache the absolute path so that it can be shared between web servers with different paths.
		$cache_path = mas_elementor_tokenize_path( $template, mas_elementor_get_path_define_tokens() );

		mas_elementor_set_template_cache( $cache_key, $cache_path );
	} else {
		// Make sure that the absolute path to the template is resolved.
		$template = mas_elementor_untokenize_path( $template, mas_elementor_get_path_define_tokens() );
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'mas_elementor_get_template', $template, $template_name, $args, $template_path, $default_path );

	if ( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			/* translators: %s template */
			_doing_it_wrong( __FUNCTION__, sprintf( esc_html__( '%s does not exist.', 'mas-addons-for-elementor' ), '<code>' . esc_html( $filter_template ) . '</code>' ), '2.1' );
			return;
		}
		$template = $filter_template;
	}

	$action_args = array(
		'template_name' => $template_name,
		'template_path' => $template_path,
		'located'       => $template,
		'args'          => $args,
	);

	if ( ! empty( $args ) && is_array( $args ) ) {
		if ( isset( $args['action_args'] ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				esc_html__( 'action_args should not be overwritten when calling mas_elementor_get_template.', 'mas-addons-for-elementor' ),
				'3.6.0'
			);
			unset( $args['action_args'] );
		}
        extract( $args ); // @codingStandardsIgnoreLine
	}

	do_action( 'mas_elementor_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

	include $action_args['located'];

	do_action( 'mas_elementor_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 * @return string
 */
function mas_elementor_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = 'templates/';
	}

	if ( ! $default_path ) {
		$default_path = 'templates/';
	}

	// Look within passed path within the theme - this is priority.
	if ( false !== strpos( $template_name, 'product_cat' ) || false !== strpos( $template_name, 'product_tag' ) ) {
		$cs_template = str_replace( '_', '-', $template_name );
		$template    = locate_template(
			array(
				trailingslashit( $template_path ) . $cs_template,
				$cs_template,
			)
		);
	}

	if ( empty( $template ) ) {
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);
	}

	// Get default template/.
	if ( ! $template ) {
		if ( empty( $cs_template ) ) {
			$template = $default_path . $template_name;
		} else {
			$template = $default_path . $cs_template;
		}
	}

	// Return what we found.
	return apply_filters( 'mas_elementor_locate_template', $template, $template_name, $template_path );
}

/**
 * Given a tokenized path, this will expand the tokens to their full path.
 *
 * @param string $path The absolute path to expand.
 * @param array  $path_tokens An array keyed with the token, containing paths that should be expanded.
 * @return string The absolute path.
 */
function mas_elementor_untokenize_path( $path, $path_tokens ) {
	foreach ( $path_tokens as $token => $token_path ) {
		$path = str_replace( '{{' . $token . '}}', $token_path, $path );
	}

	return $path;
}

/**
 * Given a path, this will convert any of the subpaths into their corresponding tokens.
 *
 * @param string $path The absolute path to tokenize.
 * @param array  $path_tokens An array keyed with the token, containing paths that should be replaced.
 * @return string The tokenized path.
 */
function mas_elementor_tokenize_path( $path, $path_tokens ) {
	// Order most to least specific so that the token can encompass as much of the path as possible.
	uasort(
		$path_tokens,
		function ( $a, $b ) {
			$a = strlen( $a );
			$b = strlen( $b );

			if ( $a > $b ) {
				return -1;
			}

			if ( $b > $a ) {
				return 1;
			}

			return 0;
		}
	);

	foreach ( $path_tokens as $token => $token_path ) {
		if ( 0 !== strpos( $path, $token_path ) ) {
			continue;
		}

		$path = str_replace( $token_path, '{{' . $token . '}}', $path );
	}

	return $path;
}

/**
 * Fetches an array containing all of the configurable path constants to be used in tokenization.
 *
 * @return array The key is the define and the path is the constant.
 */
function mas_elementor_get_path_define_tokens() {
	$defines = array(
		'ABSPATH',
		'WP_CONTENT_DIR',
		'WP_PLUGIN_DIR',
		'WPMU_PLUGIN_DIR',
		// 'PLUGINDIR',
		'WP_THEME_DIR',
	);

	$path_tokens = array();
	foreach ( $defines as $define ) {
		if ( defined( $define ) ) {
			$path_tokens[ $define ] = constant( $define );
		}
	}

	return apply_filters( 'mas_elementor_get_path_define_tokens', $path_tokens );
}

/**
 * Add a template to the template cache.
 *
 * @param string $cache_key Object cache key.
 * @param string $template Located template.
 */
function mas_elementor_set_template_cache( $cache_key, $template ) {
	wp_cache_set( $cache_key, $template, 'mas_elementor' );

	$cached_templates = wp_cache_get( 'cached_templates', 'mas_elementor' );
	if ( is_array( $cached_templates ) ) {
		$cached_templates[] = $cache_key;
	} else {
		$cached_templates = array( $cache_key );
	}

	wp_cache_set( 'cached_templates', $cached_templates, 'mas_elementor' );
}

if ( ! function_exists( 'mas_template_options' ) ) {
	/**
	 * Get local templates.
	 *
	 * Retrieve local templates saved by the user on his site.
	 *
	 * @param string $template_format  template format.
	 * @return array Local templates.
	 */
	function mas_template_options( $template_format = 'mas-post' ) {

		$mas_template = array();
		$args         = array(
			'post_type'              => 'elementor_library',
			'post_status'            => 'publish',
			'limit'                  => '-1',
			'posts_per_page'         => '-1',
			'elementor_library_type' => $template_format,
		);

		$mas_templates = get_posts( $args );

		if ( ! empty( $mas_templates ) ) {
			$options = array( '' => esc_html__( '— None —', 'mas-addons-for-elementor' ) );
			foreach ( $mas_templates as $mas_template ) {
				$template                 = get_page_by_path( $mas_template->post_name, OBJECT, $args['post_type'] );
				$options[ $template->ID ] = $mas_template->post_title;
			}
		} else {
			$options = array( '' => esc_html__( 'No Templates Found', 'mas-addons-for-elementor' ) );
		}

		return $options;
	}
}

if ( ! function_exists( 'mas_template_slug_options' ) ) {
	/**
	 * Get local templates.
	 *
	 * Retrieve local templates saved by the user on his site.
	 *
	 * @param string $template_format  template format.
	 * @return array Local templates.
	 */
	function mas_template_slug_options( $template_format = 'mas-post' ) {

		$mas_template = array();
		$args         = array(
			'post_type'              => 'elementor_library',
			'post_status'            => 'publish',
			'limit'                  => '-1',
			'posts_per_page'         => '-1',
			'elementor_library_type' => $template_format,
		);

		$mas_templates = get_posts( $args );

		if ( ! empty( $mas_templates ) ) {
			$options = array( '' => esc_html__( '— None —', 'mas-addons-for-elementor' ) );
			foreach ( $mas_templates as $mas_template ) {
				$template                        = get_page_by_path( $mas_template->post_name, OBJECT, $args['post_type'] );
				$options[ $template->post_name ] = $mas_template->post_title;
			}
		} else {
			$options = array( '' => esc_html__( 'No Templates Found', 'mas-addons-for-elementor' ) );
		}

		return $options;
	}
}

if ( ! function_exists( 'mas_template_override_options' ) ) {
	/**
	 * Get local templates.
	 *
	 * Retrieve local templates saved by the user on his site.
	 *
	 * @param string $template_format  template format.
	 * @return array Local templates.
	 */
	function mas_template_override_options( $template_format = 'page' ) {

		$mas_template = array();
		$args         = array(
			'post_type'              => 'elementor_library',
			'post_status'            => 'publish',
			'limit'                  => '-1',
			'posts_per_page'         => '-1',
			'elementor_library_type' => $template_format,
		);

		$mas_templates = get_posts( $args );

		if ( ! empty( $mas_templates ) ) {
			$options = array( '' => esc_html__( '— None —', 'mas-addons-for-elementor' ) );
			foreach ( $mas_templates as $mas_template ) {
				$options[ $mas_template->ID ] = $mas_template->post_name;
			}
		} else {
			$options = array();
		}

		return $options;
	}
}

if ( ! function_exists( 'mas_render_template' ) ) {
	/**
	 * MAS Template Render.
	 *
	 * @param array $post_id  post ID.
	 * @param bool  $echo  echo.
	 */
	function mas_render_template( $post_id, $echo = false ) {
		if ( did_action( 'elementor/loaded' ) ) {
			$content = Plugin::instance()->frontend->get_builder_content_for_display( $post_id );
		} else {
			$content = get_the_content( null, false, $post_id );
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );
		}

		if ( $echo ) {
			echo wp_kses_post( $content );
		} else {
			return $content;
		}
	}
}

if ( ! function_exists( 'mas_render_content' ) ) {
	/**
	 * MAS render content.
	 *
	 * @param array $post_id  post ID.
	 * @param bool  $echo  echo.
	 */
	function mas_render_content( $post_id, $echo = false ) {
		if ( did_action( 'elementor/loaded' ) ) {
			$content = Plugin::instance()->frontend->get_builder_content_for_display( $post_id );
		} else {
			$content = get_the_content( null, false, $post_id );
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );
		}
		if ( $echo ) {
			echo wp_kses_post( $content );
		} else {
			return $content;
		}
	}
}

if ( ! function_exists( 'mas_option_enabled_post_types' ) ) {

	/**
	 * Option enabled post types.
	 *
	 * @return array
	 */
	function mas_option_enabled_post_types() {
		$post_types = array( 'post', 'page', 'jetpack-portfolio' );
		if ( class_exists( 'MasVideos' ) ) {
			$movie      = array(
				'movie',
				'tv_show',
				'episode',
				'video',
				'person',
			);
			$post_types = array_merge( $post_types, $movie );

		}
		if ( class_exists( 'Woocommerce' ) ) {
			$product    = array(
				'product',
			);
			$post_types = array_merge( $post_types, $product );

		}
		if ( class_exists( 'WP_Job_Manager' ) ) {
			$job        = array(
				'job_listing',
			);
			$post_types = array_merge( $post_types, $job );

		}

		return apply_filters( 'mas_option_enabled_post_types', $post_types );
	}
}

if ( ! function_exists( 'mas_elementor_breadcrumb' ) ) {

	/**
	 * Output the MAS Breadcrumb.
	 *
	 * @param array $args Arguments.
	 */
	function mas_elementor_breadcrumb( $args = array() ) {
		$args = wp_parse_args(
			$args,
			apply_filters(
				'mas_breadcrumb_defaults',
				array(
					'delimiter'   => '',
					'wrap_before' => '<nav aria-label="breadcrumb" class="mas-breadcrumb"><ol>',
					'wrap_after'  => '</ol></nav>',
					'before'      => '<li class="mas_breadcrumb_li">',
					'after'       => '</li>',
					'home'        => _x( 'Home', 'breadcrumb', 'mas-addons-for-elementor' ),
				)
			)
		);

		require_once plugin_dir_path( MAS_ELEMENTOR__FILE__ ) . 'classes/class-mas-breadcrumb.php';

		$breadcrumbs = new Mas_Breadcrumb_Class();

		if ( ! empty( $args['home'] ) ) {
			$breadcrumbs->add_crumb( $args['home'], apply_filters( 'mas_breadcrumb_home_url', home_url() ) );
		}

		$args['breadcrumb'] = $breadcrumbs->generate();

		do_action( 'mas_breadcrumb', $breadcrumbs, $args );

		if ( ! empty( $args['breadcrumb'] ) ) {

			$output = wp_kses_post( $args['wrap_before'] );

			foreach ( $args['breadcrumb'] as $key => $crumb ) {
				$args['before'] = 0 === $key ? $args['before'] : $args['delimiter'] . $args['before'];

				if ( ! empty( $crumb[1] ) && count( $args['breadcrumb'] ) !== $key + 1 ) {
					$output .= wp_kses_post(
						sprintf(
							'%s<a href="%s" class="mas_breadcrumb_link">%s</a>%s',
							$args['before'],
							esc_url( $crumb[1] ),
							$crumb[0],
							$args['after']
						)
					);
				} else {
					$output .= $args['delimiter'] . '<li class="mas_breadcrumb_li"><span>' . esc_html( $crumb[0] ) . '</span></li>';
				}
			}

			$output .= wp_kses_post( $args['wrap_after'] );

			echo wp_kses_post( apply_filters( 'mas_breadcrumb_html', $output ) );
		}
	}
}

if ( ! function_exists( 'mas_extensions_includes' ) ) {

	/**
	 * Include required files
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function mas_extensions_includes() {
		/**
		 * Class autoloader.
		 */
		include_once MAS_ELEMENTOR_PATH . 'portfolio/includes/class-mas-autoloader.php';

		/**
		 * Core classes.
		 */
		require MAS_ELEMENTOR_PATH . 'portfolio/includes/functions.php';

		/**
		 * Custom Post Types
		 */
		require MAS_ELEMENTOR_PATH . 'portfolio/modules/classes/class-mas-jetpack-portfolio.php';

		if ( mas_is_request( 'admin' ) ) {
			include_once MAS_ELEMENTOR_PATH . 'portfolio/includes/admin/class-mas-admin.php';
		}
	}
}

if ( ! function_exists( 'mas_is_request' ) ) {

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or masend.
	 * @return bool
	 */
	function mas_is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'masend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! is_rest_api_request();
		}
	}
}

if ( ! function_exists( 'mas_clean' ) ) {
	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @param string|array $var Data to sanitize.
	 * @return string|array
	 */
	function mas_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'mas_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}

if ( ! function_exists( 'mas_option_enabled_taxonomies' ) ) {

	/**
	 * Option enabled post types.
	 *
	 * @return array
	 */
	function mas_option_enabled_taxonomies() {
		$taxonomies = array( 'category', 'post_tag' );
		if ( class_exists( 'MasVideos' ) ) {
			$movie      = array(
				'movie_genre',
				'movie_tag',
				'tv_show_genre',
				'tv_show_tag',
				'video_cat',
				'video_tag',
			);
			$taxonomies = array_merge( $taxonomies, $movie );

		}
		if ( class_exists( 'Woocommerce' ) ) {
			$product    = array(
				'product_cat',
				'product_tag',
			);
			$taxonomies = array_merge( $taxonomies, $product );

		}
		if ( class_exists( 'WP_Job_Manager' ) ) {
			$job        = array(
				'job_listing_type',
			);
			$taxonomies = array_merge( $taxonomies, $job );

		}

		return apply_filters( 'mas_option_enabled_taxonomies', $taxonomies );
	}
}

if ( ! function_exists( 'mas_elementor_get_field' ) ) {
	/**
	 * Wrapper for ACF's get_field function.
	 *
	 * @param string   $field custom field key.
	 * @param int|bool $post_id ID of the post.
	 * @param bool     $format_value should format the meta value or not.
	 * @return mixed
	 */
	function mas_elementor_get_field( $field, $post_id = false, $format_value = true ) {
		if ( function_exists( 'get_field' ) ) {
			return get_field( $field, $post_id, $format_value );
		}

		return false;
	}
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}


