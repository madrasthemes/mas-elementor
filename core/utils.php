<?php
/**
 * The UTILS.
 *
 * @package mas-elementor
 */

namespace MASElementor\Core;

use MASElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Utils
 */
class Utils {

	/**
	 * Get public post types.
	 *
	 * @param array $args arguments.
	 *
	 * @return array
	 */
	public static function get_public_post_types( $args = array() ) {
		$post_type_args = array(
			// Default is the value $public.
			'show_in_nav_menus' => true,
		);

		// Keep for backwards compatibility.
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = array();

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		/**
		 * Public Post types
		 *
		 * Allow 3rd party plugins to filters the public post types elementor should work on
		 *
		 * @since 2.3.0
		 *
		 * @param array $post_types Elementor supported public post types.
		 *
		 * @return array
		 */
		return apply_filters( 'mas_elementor/utils/get_public_post_types', $post_types );
	}

	/**
	 * Get client IP.
	 *
	 * @return string
	 */
	public static function get_client_ip() {
		$server_ip_keys = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		);

		foreach ( $server_ip_keys as $key ) {
			if ( isset( $_SERVER[ $key ] ) && filter_var( $_SERVER[ $key ], FILTER_VALIDATE_IP ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				return $_SERVER[ $key ]; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			}
		}

		// Fallback local ip.
		return '127.0.0.1';
	}

	/**
	 * Get Site Domain.
	 *
	 * @return string
	 */
	public static function get_site_domain() {
		return str_ireplace( 'www.', '', wp_parse_url( home_url(), PHP_URL_HOST ) );
	}

	/**
	 * Get Current Post ID.
	 *
	 * @return string
	 */
	public static function get_current_post_id() {
		if ( isset( Plugin::elementor()->documents ) ) {
			return Plugin::elementor()->documents->get_current()->get_main_id();
		}

		return get_the_ID();
	}

	/**
	 * Get Archive URL.
	 *
	 * @return string
	 */
	public static function get_the_archive_url() {
		$url = '';
		if ( is_category() || is_tag() || is_tax() ) {
			$url = get_term_link( get_queried_object() );
		} elseif ( is_author() ) {
			$url = get_author_posts_url( get_queried_object_id() );
		} elseif ( is_year() ) {
			$url = get_year_link( get_query_var( 'year' ) );
		} elseif ( is_month() ) {
			$url = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
		} elseif ( is_day() ) {
			$url = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
		} elseif ( is_post_type_archive() ) {
			$url = get_post_type_archive_link( get_post_type() );
		}

		return $url;
	}

	/**
	 * Get Page Title.
	 *
	 * @param bool $include_context include context.
	 *
	 * @return string
	 */
	public static function get_page_title( $include_context = true ) {
		$title = '';

		if ( is_singular() ) {
			/* translators: %s: Search term. */
			$title = get_the_title();

			if ( $include_context ) {
				$post_type_obj = get_post_type_object( get_post_type() );
				$title         = sprintf( '%s: %s', $post_type_obj->labels->singular_name, $title );
			}
		} elseif ( is_search() ) {
			/* translators: %s: Search term. */
			$title = sprintf( esc_html__( 'Search Results for: %s', 'mas-addons-for-elementor' ), get_search_query() );

			if ( get_query_var( 'paged' ) ) {
				/* translators: %s is the page number. */
				$title .= sprintf( esc_html__( '&nbsp;&ndash; Page %s', 'mas-addons-for-elementor' ), get_query_var( 'paged' ) );
			}
		} elseif ( is_category() ) {
			$title = single_cat_title( '', false );

			if ( $include_context ) {
				/* translators: Category archive title. 1: Category name */
				$title = sprintf( esc_html__( 'Category: %s', 'mas-addons-for-elementor' ), $title );
			}
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
			if ( $include_context ) {
				/* translators: Tag archive title. 1: Tag name */
				$title = sprintf( esc_html__( 'Tag: %s', 'mas-addons-for-elementor' ), $title );
			}
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';

			if ( $include_context ) {
				/* translators: Author archive title. 1: Author name */
				$title = sprintf( esc_html__( 'Author: %s', 'mas-addons-for-elementor' ), $title );
			}
		} elseif ( is_year() ) {
			$title = get_the_date( _x( 'Y', 'yearly archives date format', 'mas-addons-for-elementor' ) );

			if ( $include_context ) {
				/* translators: Yearly archive title. 1: Year */
				$title = sprintf( esc_html__( 'Year: %s', 'mas-addons-for-elementor' ), $title );
			}
		} elseif ( is_month() ) {
			$title = get_the_date( _x( 'F Y', 'monthly archives date format', 'mas-addons-for-elementor' ) );

			if ( $include_context ) {
				/* translators: Monthly archive title. 1: Month name and year */
				$title = sprintf( esc_html__( 'Month: %s', 'mas-addons-for-elementor' ), $title );
			}
		} elseif ( is_day() ) {
			$title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'mas-addons-for-elementor' ) );

			if ( $include_context ) {
				/* translators: Daily archive title. 1: Date */
				$title = sprintf( esc_html__( 'Day: %s', 'mas-addons-for-elementor' ), $title );
			}
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'mas-addons-for-elementor' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'mas-addons-for-elementor' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'mas-addons-for-elementor' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'mas-addons-for-elementor' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'mas-addons-for-elementor' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'mas-addons-for-elementor' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'mas-addons-for-elementor' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'mas-addons-for-elementor' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'mas-addons-for-elementor' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );

			if ( $include_context ) {
				/* translators: Post type archive title. 1: Post type name */
				$title = sprintf( esc_html__( 'Archives: %s', 'mas-addons-for-elementor' ), $title );
			}
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );

			if ( $include_context ) {
				$tax = get_taxonomy( get_queried_object()->taxonomy );
				/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
				$title = sprintf( esc_html__( '%1$s: %2$s', 'mas-addons-for-elementor' ), $tax->labels->singular_name, $title );
			}
		} elseif ( is_archive() ) {
			$title = esc_html__( 'Archives', 'mas-addons-for-elementor' );
		} elseif ( is_404() ) {
			$title = esc_html__( 'Page Not Found', 'mas-addons-for-elementor' );
		}

		/**
		 * The archive title.
		 *
		 * Filters the archive title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Archive title to be displayed.
		 */
		$title = apply_filters( 'elementor/utils/get_the_archive_title', $title );

		return $title;
	}

	/**
	 * Set Global Author Data.
	 *
	 * @return void
	 */
	public static function set_global_authordata() {
		global $authordata;
		if ( ! isset( $authordata->ID ) ) {
			$post       = get_post();
			$authordata = get_userdata( $post->post_author ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}

	/**
	 * Used to overcome core bug when taxonomy is in more then one post type
	 *
	 * @see https://core.trac.wordpress.org/ticket/27918
	 *
	 * @global array $wp_taxonomies The registered taxonomies.
	 *
	 * @param array  $args arguments.
	 * @param string $output output.
	 * @param string $operator operator.
	 *
	 * @return array
	 */
	public static function get_taxonomies( $args = array(), $output = 'names', $operator = 'and' ) {
		global $wp_taxonomies;

		$field = ( 'names' === $output ) ? 'name' : false;

		// Handle 'object_type' separately.
		if ( isset( $args['object_type'] ) ) {
			$object_type = (array) $args['object_type'];
			unset( $args['object_type'] );
		}

		$taxonomies = wp_filter_object_list( $wp_taxonomies, $args, $operator );

		if ( isset( $object_type ) ) {
			foreach ( $taxonomies as $tax => $tax_data ) {
				if ( ! array_intersect( $object_type, $tax_data->object_type ) ) {
					unset( $taxonomies[ $tax ] );
				}
			}
		}

		if ( $field ) {
			$taxonomies = wp_list_pluck( $taxonomies, $field );
		}

		return $taxonomies;
	}

	/**
	 * Get Upload Directory.
	 *
	 * @param string $path path.
	 *
	 * @return string
	 */
	public static function get_ensure_upload_dir( $path ) {
		if ( file_exists( $path . '/index.php' ) ) {
			return $path;
		}

		wp_mkdir_p( $path );

		$files = array(
			array(
				'file'    => 'index.php',
				'content' => array(
					'<?php',
					'// Silence is golden.',
				),
			),
			array(
				'file'    => '.htaccess',
				'content' => array(
					'Options -Indexes',
					'<ifModule mod_headers.c>',
					'	<Files *.*>',
					'       Header set Content-Disposition attachment',
					'	</Files>',
					'</IfModule>',
				),
			),
		);

		foreach ( $files as $file ) {
			if ( ! file_exists( trailingslashit( $path ) . $file['file'] ) ) {
				$content = implode( PHP_EOL, $file['content'] );
				@ file_put_contents( trailingslashit( $path ) . $file['file'], $content ); //PHPCS:ignore
			}
		}

		return $path;
	}

	/**
	 * Remove words from a sentence.
	 *
	 * @param string  $text text.
	 * @param integer $length length.
	 *
	 * @return string
	 */
	public static function trim_words( $text, $length ) {
		if ( $length && str_word_count( $text ) > $length ) {
			$text = explode( ' ', $text, $length + 1 );
			unset( $text[ $length ] );
			$text = implode( ' ', $text );
		}

		return $text;
	}

	/**
	 * Get super global value.
	 *
	 * @param array  $super_global super global.
	 * @param string $key length.
	 *
	 * @return array
	 */
	public static function unstable_get_super_global_value( $super_global, $key ) {
		// Unslaсh the nonce value from the GET request.
		$nonce = isset( $_GET['preview_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['preview_nonce'] ) ) : '';

		// Check if nonce is verified before processing the superglobal data.
		if ( wp_verify_nonce( $nonce, 'elementor_preview_action' ) ) { // Use your specific nonce action.
			return null; // Invalid nonce, reject the request.
		}

		// Proceed with the rest of the logic.
		if ( ! isset( $super_global[ $key ] ) ) {
			return null;
		}

		if ( $_FILES === $super_global ) {
			$super_global[ $key ]['name'] = sanitize_file_name( $super_global[ $key ]['name'] );
			return $super_global[ $key ];
		}

		return wp_kses_post_deep( wp_unslash( $super_global[ $key ] ) );
	}

}
