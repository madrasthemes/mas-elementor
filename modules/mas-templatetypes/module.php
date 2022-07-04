<?php
/**
 * MasTemplateTypes.
 *
 * @package MASElementor\Modules\MasTemplateTypes
 */

namespace MASElementor\Modules\MasTemplatetypes;

use Elementor\Core\Admin\Menu\Main as MainMenu;
use Elementor\Core\Base\Module as BaseModule;
use Elementor\Core\Documents_Manager;
use Elementor\Core\Experiments\Manager as Experiments_Manager;
use MASElementor\Modules\MasTemplatetypes\Documents\Post_temp;
use MASElementor\Modules\MasTemplatetypes\Module as Mas_Templatetypes_Module;
use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * The mas template type ( elementor-library-type ) module class
 */
class Module extends BaseModule {

	const DOCUMENT_TYPE   = 'post-temp';
	const CPT             = 'e-post-temp';
	const ADMIN_PAGE_SLUG = 'edit.php?post_type=' . self::CPT;

	/**
	 * Post variable.
	 *
	 * @var $posts post.
	 */
	private $posts;
	/**
	 * Trashed post variable.
	 *
	 * @var $trashed_posts trashed_post.
	 */
	private $trashed_posts;
	/**
	 * New mastemplate type url.
	 *
	 * @var $trashed_posts trashed_post.
	 */
	private $new_lp_url;
	/**
	 * New mastemplate type permalink_structure.
	 *
	 * @var $permalink_structure permalink_structure.
	 */
	private $permalink_structure;
	/**
	 * Get post names.
	 */
	public function get_name() {
		return 'post-temps';
	}

	/**
	 * Get Experimental Data
	 *
	 * Implementation of this method makes the module an experiment.
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */
	public static function get_experimental_data() {
		return array(
			'name'           => 'post-temps',
			'title'          => esc_html__( 'Mas Templates Type', 'mas-elementor' ),
			'description'    => esc_html__( 'Adds a new Elementor content type that allows creating beautiful mas templates type instantly in a streamlined workflow.', 'mas-elementor' ),
			'release_status' => Experiments_Manager::RELEASE_STATUS_STABLE,
			'default'        => Experiments_Manager::STATE_ACTIVE,
			'new_site'       => array(
				'default_active'               => true,
				'minimum_installation_version' => '3.1.0-beta',
			),
		);
	}

	/**
	 * Get Trashed Mas Templates Type Posts
	 *
	 * Returns the posts property of a WP_Query run for Mas Templates Type with post_status of 'trash'.
	 *
	 * @since 3.1.0
	 *
	 * @return array trashed posts
	 */
	private function get_trashed_post_temp_posts() {
		if ( $this->trashed_posts ) {
			return $this->trashed_posts;
		}

		// `'posts_per_page' => 1` is because this is only used as an indicator to whether there are any trashed mas templates type.
		$trashed_posts_query = new \WP_Query(
			array(
				'no_found_rows'  => true,
				'post_type'      => self::CPT,
				'post_status'    => 'trash',
				'posts_per_page' => 1,
				'meta_key'       => '_elementor_template_type',
				'meta_value'     => self::DOCUMENT_TYPE,
			)
		);

		$this->trashed_posts = $trashed_posts_query->posts;

		return $this->trashed_posts;
	}

	/**
	 * Get Mas Templates Type Posts
	 *
	 * Returns the posts property of a WP_Query run for posts with the Mas Templates Type CPT.
	 *
	 * @since 3.1.0
	 *
	 * @return array posts
	 */
	private function get_post_temp_posts() {
		if ( $this->posts ) {
			return $this->posts;
		}

		// `'posts_per_page' => 1` is because this is only used as an indicator to whether there are any mas templates type.
		$posts_query = new \WP_Query(
			array(
				'no_found_rows'  => true,
				'post_type'      => self::CPT,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'meta_key'       => '_elementor_template_type',
				'meta_value'     => self::DOCUMENT_TYPE,
			)
		);

		$this->posts = $posts_query->posts;

		return $this->posts;
	}

	/**
	 * Is Elementor Landing Page.
	 *
	 * Check whether the post is an Elementor Landing Page.
	 *
	 * @since 3.1.0
	 *
	 * @param array \WP_Post $post Post Object.
	 *
	 * @return bool Whether the post was built with Elementor.
	 */
	public function is_elementor_post_temp( $post ) {
		return self::CPT === $post->post_type;
	}
	/**
	 * Getting menu arguments.
	 */
	private function get_menu_args() {
		$posts = $this->get_post_temp_posts();

		// If there are no Mas Templates Type, show the "Create Your First Landing Page" page.
		// If there are, show the pages table.
		if ( ! empty( $posts ) ) {
			$menu_slug = self::ADMIN_PAGE_SLUG;
			$function  = null;
		} else {
			$menu_slug = self::CPT;
			$function  = array( $this, 'print_empty_post_temps_page' );
		}

		return array(
			'menu_slug' => $menu_slug,
			'function'  => $function,
		);
	}
	// PHPCS:ignore.
	private function register_admin_menu( MainMenu $menu ) {
		$post_temps_title = esc_html__( 'MAS Temps', 'mas-elementor' );

		$menu_args = array_merge(
			$this->get_menu_args(),
			array(
				'page_title' => $post_temps_title,
				'menu_title' => $post_temps_title,
				'index'      => 20,
			)
		);

		$menu->add_submenu( $menu_args );
	}

	/**
	 * Add Submenu Page
	 *
	 * Adds the 'Mas Templates Type' submenu item to the 'Templates' menu item.
	 *
	 * @since 3.1.0
	 */
	private function register_admin_menu_legacy() {
		$post_temps_title = esc_html__( 'Posts Templates', 'mas-elementor' );

		$menu_args = $this->get_menu_args();

		add_submenu_page(
			Source_Local::ADMIN_MENU_SLUG,
			$post_temps_title,
			$post_temps_title,
			'manage_options',
			$menu_args['menu_slug'],
			$menu_args['function']
		);
	}

	/**
	 * Get 'Add New' Landing Page URL
	 *
	 * Retrieves the custom URL for the admin dashboard's 'Add New' button in the Mas Templates Type admin screen. This URL
	 * creates a new Mas Templates Type and directly opens the Elementor Editor with the Template Library modal open on the
	 * Mas Templates Type tab.
	 *
	 * @since 3.1.0
	 *
	 * @return string
	 */
	private function get_add_new_post_temp_url() {
		if ( ! $this->new_lp_url ) {
			$this->new_lp_url = Plugin::$instance->documents->get_create_new_post_url( self::CPT, self::DOCUMENT_TYPE ) . '#library';
		}
		return $this->new_lp_url;
	}

	/**
	 * Get Empty Mas Templates Type Page
	 *
	 * Prints the HTML content of the page that is displayed when there are no existing mas templates type in the DB.
	 * Added as the callback to add_submenu_page.
	 *
	 * @since 3.1.0
	 */
	public function print_empty_post_temps_page() {
		$template_sources = Plugin::$instance->templates_manager->get_registered_sources();
		$source_local     = $template_sources['local'];
		$trashed_posts    = $this->get_trashed_post_temp_posts();

		?>
		<div class="e-post-temps-empty">
		<?php
		/**
		 * Mas template type.
		 *
		 * @var Source_Local $source_local.
		 */
		$source_local->print_blank_state_template( esc_html__( 'Landing Page', 'mas-elementor' ), $this->get_add_new_post_temp_url(), esc_html__( 'Build Effective Mas Templates Type for your business\' marketing campaigns.', 'mas-elementor' ) );

		if ( ! empty( $trashed_posts ) ) :
			?>
			<div class="e-trashed-items">
				<?php
					printf(
						/* translators: %1$s Link open tag, %2$s: Link close tag. */
						esc_html__( 'Or view %1$sTrashed Items%1$s', 'mas-elementor' ),
						'<a href="' . esc_url( admin_url( 'edit.php?post_status=trash&post_type=' . self::CPT ) ) . '">',
						'</a>'
					);
				?>
			</div>
		<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Is Current Admin Page Edit LP
	 *
	 * Checks whether the current page is a native WordPress edit page for a landing page.
	 */
	private function is_post_temp_admin_edit() {
		$screen = get_current_screen();

		if ( 'post' === $screen->base ) {
			return $this->is_elementor_post_temp( get_post() );
		}

		return false;
	}

	/**
	 * Admin Localize Settings
	 *
	 * Enables adding properties to the globally available elementorAdmin.config JS object in the Admin Dashboard.
	 * Runs on the 'elementor/admin/localize_settings' filter.
	 *
	 * @since 3.1.0
	 *
	 * @param array $settings settings.
	 * @return array|null
	 */
	private function admin_localize_settings( $settings ) {
		$additional_settings = array(
			'urls'         => array(
				'addNewLandingPageUrl' => $this->get_add_new_post_temp_url(),
			),
			'landingPages' => array(
				'landingPagesHasPages'   => array() !== $this->get_post_temp_posts(),
				'isLandingPageAdminEdit' => $this->is_post_temp_admin_edit(),
			),
		);

		return array_replace_recursive( $settings, $additional_settings );
	}

	/**
	 * Register Mas Templates Type CPT
	 *
	 * @since 3.1.0
	 */
	private function register_post_temp_cpt() {
		$labels = array(
			'name'               => esc_html__( 'Mas Templates', 'mas-elementor' ),
			'singular_name'      => esc_html__( 'Landing Page', 'mas-elementor' ),
			'add_new'            => esc_html__( 'Add New', 'mas-elementor' ),
			'add_new_item'       => esc_html__( 'Add New Landing Page', 'mas-elementor' ),
			'edit_item'          => esc_html__( 'Edit Landing Page', 'mas-elementor' ),
			'new_item'           => esc_html__( 'New Landing Page', 'mas-elementor' ),
			'all_items'          => esc_html__( 'All Mas Templates Type', 'mas-elementor' ),
			'view_item'          => esc_html__( 'View Landing Page', 'mas-elementor' ),
			'search_items'       => esc_html__( 'Search Mas Templates Type', 'mas-elementor' ),
			'not_found'          => esc_html__( 'No mas templates type found', 'mas-elementor' ),
			'not_found_in_trash' => esc_html__( 'No mas templates type found in trash', 'mas-elementor' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__( 'Mas Templates Type', 'mas-elementor' ),
		);

		$args = array(
			'labels'          => $labels,
			'public'          => true,
			'show_in_menu'    => 'edit.php?post_type=elementor_library&tabs_group=library',
			'capability_type' => 'post',
			'taxonomies'      => array( Source_Local::TAXONOMY_TYPE_SLUG ),
			'supports'        => array( 'title', 'editor', 'comments', 'revisions', 'author', 'excerpt', 'post-attributes', 'thumbnail', 'post-formats', 'elementor' ),
		);

		register_post_type( self::CPT, $args );
	}

	/**
	 * Remove Post Type Slug
	 *
	 * Mas Templates Type are supposed to act exactly like pages. This includes their URLs being directly under the site's
	 * domain name. Since "Mas Templates Type" is a CPT, WordPress automatically adds the landing page slug as a prefix to
	 * it's posts' permalinks. This method checks if the post's post type is Mas Templates Type, and if it is, it removes
	 * the CPT slug from the requested post URL.
	 *
	 * Runs on the 'post_type_link' filter.
	 *
	 * @since 3.1.0
	 *
	 * @param array $post_link current value.
	 * @param array $post current query.
	 * @param array $leavename leave name.
	 * @return string|string[]
	 */
	private function remove_post_type_slug( $post_link, $post, $leavename ) {
		// Only try to modify the permalink if the post is a Landing Page.
		if ( self::CPT !== $post->post_type || 'publish' !== $post->post_status ) {
			return $post_link;
		}

		// Any slug prefixes need to be removed from the post link.
		return get_home_url() . '/' . $post->post_name . '/';
	}

	/**
	 * Adjust Landing Page Query
	 *
	 * Since Mas Templates Type are a CPT but should act like pages, the WP_Query that is used to fetch the page from the
	 * database needs to be adjusted. This method adds the Mas Templates Type CPT to the list of queried post types, to
	 * make sure the database query finds the correct Landing Page to display.
	 * Runs on the 'pre_get_posts' action.
	 *
	 * @since 3.1.0
	 *
	 * @param \WP_Query $query query.
	 */
	private function adjust_post_temp_query( \WP_Query $query ) {
		// Only handle actual pages.
		if (
			! $query->is_main_query()
			// If the query is not for a page.
			|| ! isset( $query->query['page'] )
			// If the query is for a static home/blog page.
			|| is_home()
			// If the post type comes already set, the main query is probably a custom one made by another plugin.
			// In this case we do not want to intervene in order to not cause a conflict.
			|| isset( $query->query['post_type'] )
		) {
			return;
		}

		// Create the post types property as an array and include the mas templates type CPT in it.
		$query_post_types = array( 'post', 'page', self::CPT );

		// Since WordPress determined this is supposed to be a page, we'll pre-set the post_type query arg to make sure
		// it includes the Landing Page CPT, so when the query is parsed, our CPT will be a legitimate match to the
		// Landing Page's permalink (that is directly under the domain, without a CPT slug prefix). In some cases,
		// The 'name' property will be set, and in others it is the 'pagename', so we have to cover both cases.
		if ( ! empty( $query->query['name'] ) ) {
			$query->set( 'post_type', $query_post_types );
		} elseif ( ! empty( $query->query['pagename'] ) && false === strpos( $query->query['pagename'], '/' ) ) {
			$query->set( 'post_type', $query_post_types );

			// We also need to set the name query var since redirect_guess_404_permalink() relies on it.
			add_filter(
				'pre_redirect_guess_404_permalink',
				function( $value ) use ( $query ) {
					set_query_var( 'name', $query->query['pagename'] );

					return $value;
				}
			);
		}
	}

	/**
	 * Handle 404
	 *
	 * This method runs after a page is not found in the database, but before a page is returned as a 404.
	 * These cases are handled in this filter callback, that runs on the 'pre_handle_404' filter.
	 *
	 * In some cases (such as when a site uses custom permalink structures), WordPress's WP_Query does not identify a
	 * Landing Page's URL as a post belonging to the Landing Page CPT. Some cases are handled successfully by the
	 * adjust_post_temp_query() method, but some are not and still trigger a 404 process. This method handles such
	 * cases by overriding the $wp_query global to fetch the correct landing page post entry.
	 *
	 * For example, since Mas Templates Type slugs come directly after the site domain name, WP_Query might parse the post
	 * as a category page. Since there is no category matching the slug, it triggers a 404 process. In this case, we
	 * run a query for a Landing Page post with the passed slug ($query->query['category_name']. If a Landing Page
	 * with the passed slug is found, we override the global $wp_query with the new, correct query.
	 *
	 * @param array $current_value current value.
	 * @param array $query current query.
	 * @return false
	 */
	private function handle_404( $current_value, $query ) {
		global $wp_query;

		// If another plugin/theme already used this filter, exit here to avoid conflicts.
		if ( $current_value ) {
			return $current_value;
		}

		if (
			// Make sure we only intervene in the main query.
			! $query->is_main_query()
			// If a post was found, this is not a 404 case, so do not intervene.
			|| ! empty( $query->posts )
			// This filter is only meant to deal with wrong queries where the only query var is 'category_name'.
			// If there is no 'category_name' query var, do not intervene.
			|| empty( $query->query['category_name'] )
			// If the query is for a real taxonomy (determined by it including a table to search in, such as the
			// wp_term_relationships table), do not intervene.
			|| ! empty( $query->tax_query->table_aliases )
		) {
			return false;
		}

		// Search for a Landing Page with the same name passed as the 'category name'.
		$possible_new_query = new \WP_Query(
			array(
				'no_found_rows' => true,
				'post_type'     => self::CPT,
				'name'          => $query->query['category_name'],
			)
		);

		// Only if such a Landing Page is found, override the query to fetch the correct page.
		if ( ! empty( $possible_new_query->posts ) ) {
			$wp_query = $possible_new_query; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		return false;
	}
	/**
	 * Template library local source constructor.
	 */
	public function __construct() {
		$this->permalink_structure = get_option( 'permalink_structure' );

		$this->register_post_temp_cpt();

		// If there is a permalink structure set to the site, run the hooks that modify the Mas Templates Type permalinks to
		// match WordPress' native 'Pages' post type.
		if ( '' !== $this->permalink_structure ) {
			// Mas Templates Type' post link needs to be modified to be identical to the pages permalink structure. This
			// needs to happen in both the admin and the front end, since post links are also used in the admin pages.
			add_filter(
				'post_type_link',
				function( $post_link, $post, $leavename ) {
					return $this->remove_post_type_slug( $post_link, $post, $leavename );
				},
				10,
				3
			);

			// The query itself only has to be manipulated when pages are viewed in the front end.
			if ( ! is_admin() || wp_doing_ajax() ) {
				add_action(
					'pre_get_posts',
					function ( $query ) {
						$this->adjust_post_temp_query( $query );
					}
				);

				// Handle cases where visiting a Landing Page's URL returns 404.
				add_filter(
					'pre_handle_404',
					function ( $value, $query ) {
						return $this->handle_404( $value, $query );
					},
					10,
					2
				);
			}
		}

		add_action(
			'elementor/documents/register',
			function( Documents_Manager $documents_manager ) {
				$documents_manager->register_document_type( self::DOCUMENT_TYPE, Post_Temp::get_class_full_name() );
			}
		);

		if ( Plugin::$instance->experiments->is_feature_active( 'admin_menu_rearrangement' ) ) {
			add_action(
				'elementor/admin/menu_registered/elementor',
				function( MainMenu $menu ) {
					$this->register_admin_menu( $menu );
				}
			);
		} else {
			add_action(
				'admin_menu',
				function() {
					$this->register_admin_menu_legacy();
				},
				30
			);
		}

		// Add the custom 'Add New' link for Mas Templates Type into Elementor's admin config.
		add_action(
			'elementor/admin/localize_settings',
			function( array $settings ) {
				return $this->admin_localize_settings( $settings );
			}
		);

		add_filter(
			'elementor/template_library/sources/local/register_taxonomy_cpts',
			function( array $cpts ) {
				$cpts[] = self::CPT;

				return $cpts;
			}
		);

		// In the Mas Templates Type Admin Table page - Overwrite Template type column header title.
		add_action(
			'manage_' . Mas_Templatetypes_Module::CPT . '_posts_columns',
			function( $posts_columns ) {
				/**
				 * Mas template type.
				 *
				 * @var Source_Local $source_local.
				 */
				$source_local = Plugin::$instance->templates_manager->get_source( 'local' );

				return $source_local->admin_columns_headers( $posts_columns );
			}
		);

		// In the Mas Templates Type Admin Table page - Overwrite Template type column row values.
		add_action(
			'manage_' . Mas_Templatetypes_Module::CPT . '_posts_custom_column',
			function( $column_name, $post_id ) {
				/**
				 * Mas template type column name.
				 *
				 * @var Post_Temp $document.
				 */
				$document = Plugin::$instance->documents->get( $post_id );

				$document->admin_columns_content( $column_name );
			},
			10,
			2
		);

		// Overwrite the Admin Bar's 'New +' Landing Page URL with the link that creates the new LP in Elementor
		// with the Template Library modal open.
		add_action(
			'admin_bar_menu',
			function( $admin_bar ) {
				// Get the Landing Page menu node.
				$new_post_temp_node = $admin_bar->get_node( 'new-e-post-temp' );

				if ( $new_post_temp_node ) {
					$new_post_temp_node->href = $this->get_add_new_post_temp_url();

					$admin_bar->add_node( $new_post_temp_node );
				}
			},
			100
		);
	}
}
