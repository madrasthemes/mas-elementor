<?php
/**
 * Mas Tab Controls.
 *
 * @package MASElementor\Modules\MasTab
 */

namespace MASElementor\Modules\MasTab;

use Elementor\Controls_Stack;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use MASElementor\Base\Module_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Tab Controls module class
 */
class Module extends Module_Base {

	/**
	 * Return the activation of the elementor-pro.
	 *
	 * @return string
	 */

   	// public static function is_active() {.
	// return ! class_exists( 'ElementorPro\Plugin' );
	// }.

	// /**
	//  * Return the script dependencies of the module.
	//  *
	//  * @return array
	//  */
	// public function get_script_depends() {
	// 	return array( 'swiper-script' );
	// }

	// /**
	//  * Return the style dependencies of the module.
	//  *
	//  * @return array
	//  */
	// public function get_style_depends() {
	// 	return array( 'swiper-stylesheet' );
	// }

	/**
	 * Return the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-tab-attributes';
	}

    /**
    * Instantiate the class.
    */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );

	}

    /**
	 * Add Actions.
	 */
	protected function add_actions() {
		// add_action( 'elementor/element/after_section_end', array( $this, 'register_controls' ), 10, 2 );
		// add_action( 'elementor/frontend/section/before_render', array( $this, 'before_render_section' ), 15 );
		// add_action( 'elementor/frontend/section/after_render', array( $this, 'after_render_section' ), 15 );
		add_action( 'elementor/frontend/column/before_render', array( $this, 'before_render_column' ), 5 );
		add_action( 'elementor/element/column/section_advanced/before_section_end', array( $this, 'add_column_wrapper_controls' ) );
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_column_wrapper_controls( $element ) {

		$element->add_control(
			'enable_tab',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Enable Tab', 'mas-elementor' ),
				'default' => 'no',
			)
		);
	}

	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 *
	 * @return void
	 */
	public function before_render_column( $element ) {

		$settings = $element->get_settings_for_display();
		if ( 'yes' === $settings['enable_slide'] ) {
			$element->add_render_attribute( '_wrapper', 'class', 'swiper-slide' );
		}

	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_enqueue_script(
			'swiper-script',
			MAS_ELEMENTOR_MODULES_URL . 'mas-tab/assets/js/swiper-bundle.min.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_enqueue_style(
			'swiper-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'mas-tab/assets/css/swiper-bundle.min.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}

	/**
	 * Render script in the editor.
	 */
	public function render_script() {
		if ( Plugin::$instance->editor->is_edit_mode() ) :
			?>
			<script type="text/javascript">
			var carousel = (() => {
				// forEach function
				let forEach = (array, callback, scope) => {
				for (let i = 0; i < array.length; i++) {
					callback.call(scope, i, array[i]); // passes back stuff we need
				}
				};

				// Carousel initialisation
				let carousels = document.querySelectorAll('.swiper');
				forEach(carousels, (index, value) => {
					let userOptions,
					pagerOptions;
				if(value.dataset.swiperOptions != undefined) userOptions = JSON.parse(value.dataset.swiperOptions);


				// Pager
				if(userOptions.pager) {
					pagerOptions = {
					pagination: {
						el: userOptions.pager,
						clickable: true,
						bulletActiveClass: 'active',
						bulletClass: 'page-item',
						renderBullet: function (index, className) {
						return '<li class="' + className + '"><a href="#" class="page-link btn-icon btn-sm">' + (index + 1) + '</a></li>';
						}
					}
					}
				}

				// Slider init
				let options = {...userOptions, ...pagerOptions};
				let swiper = new Swiper(value, options);

				// Tabs (linked content)
				if(userOptions.tabs) {

					swiper.on('activeIndexChange', (e) => {
					let targetTab = document.querySelector(e.slides[e.activeIndex].dataset.swiperTab),
						previousTab = document.querySelector(e.slides[e.previousIndex].dataset.swiperTab);

					previousTab.classList.remove('active');
					targetTab.classList.add('active');
					});
				}

				});

				})();
			</script>
			<?php
		endif;
	}
}
