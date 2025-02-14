<?php
/**
 * MAS Nav Menu Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\MasNavMenu;

use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module for Nav-Menu
 */
class Module extends Module_Base {

	/**
	 * Get Widgets.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array(
			'Mas_Nav_Menu',
		);
	}

	/**
	 * Get Widgets Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-nav-menu';
	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_register_style(
			'nav-menu-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'mas-nav-menu/assets/css/mas-nav-menu.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
		wp_register_style(
			'mas-el-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'mas-nav-menu/assets/css/bootstrap-menu.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_scripts() {

		wp_register_script(
			'bootstrap-script',
			MAS_ELEMENTOR_ASSETS_URL . 'bootstrap.min.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_register_script(
			'mas-nav-init',
			MAS_ELEMENTOR_MODULES_URL . 'mas-nav-menu/assets/js/nav-menu.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

	}

	/**
	 * Register frontend styles.
	 */
	public function mas_add_nav_menus() {
		register_nav_menus();
		// Add the mas_icon to the walker class.
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'mas_add_menu_icon_nav_fields' ) );

	}

	/**
	 * Add Actions.
	 */
	protected function add_actions() {
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );
		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'init', array( $this, 'mas_add_nav_menus' ) );

		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'mas_menu_item_icon_class' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'save_mas_menu_item_icon_class' ), 10, 3 );
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

	}

	/**
	 * Menu item icon class input field.
	 *
	 * @param int   $item_id item id.
	 * @param array $item item.
	 * @param int   $depth depth.
	 * @param array $args arguments.
	 */
	public function mas_menu_item_icon_class( $item_id, $item, $depth, $args ) {
		$icon_class = get_post_meta( $item_id, '_mas_menu_item_icon_class', true );
		?>
		<p class="description description-wide">
			<label for="edit-mas-menu-item-icon-class-<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Icon Class', 'mas-addons-for-elementor' ); ?><br>
				<input type="text" id="edit-mas-menu-item-icon-class-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-mas-menu-item-icon-class"
					   name="mas-menu-item-icon-class[<?php echo esc_attr( $item_id ); ?>]"
					   value="<?php echo esc_attr( $icon_class ); ?>">
			</label>
		</p>
		<?php
	}

	/**
	 * Save icon class.
	 *
	 * @param int $menu_id item id.
	 * @param int $menu_item_db_id menu item db id.
	 */
	public function save_mas_menu_item_icon_class( $menu_id, $menu_item_db_id ) {
		// Sanitize nonce field.
		$nonce = isset( $_POST['update-nav-menu-nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['update-nav-menu-nonce'] ) ) : '';

		// Verify nonce.
		if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'update-nav_menu' ) ) {
			// Unsashing and sanitizing the icon class.
			if ( isset( $_POST['mas-menu-item-icon-class'][ $menu_item_db_id ] ) ) {
				$icon_class = sanitize_text_field( wp_unslash( $_POST['mas-menu-item-icon-class'][ $menu_item_db_id ] ) );
				update_post_meta( $menu_item_db_id, '_mas_menu_item_icon_class', $icon_class );
			} else {
				delete_post_meta( $menu_item_db_id, '_mas_menu_item_icon_class' );
			}
		}
	}



	/**
	 * Add the icon class to nav field.
	 *
	 * @param Object $menu_item menu item.
	 */
	public function mas_add_menu_icon_nav_fields( $menu_item ) {

		$menu_item->mas_icon = get_post_meta( $menu_item->ID, '_mas_menu_item_icon_class', true );
		return $menu_item;
	}

}
