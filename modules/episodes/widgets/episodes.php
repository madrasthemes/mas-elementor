<?php
/**
 * The Episode Widget.
 *
 * @package MASElementor/Modules/Episodes/Widgets
 */

namespace MASElementor\Modules\Episodes\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use MASElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use MASElementor\Core\Controls_Manager as MAS_Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Controls_Stack;
use MASElementor\Base\Traits\Carousel_Traits;
use MASElementor\Modules\CarouselAttributes\Traits\Button_Widget_Trait;
use MASElementor\Modules\CarouselAttributes\Traits\Pagination_Trait;
use Elementor\Plugin;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;
use Elementor\Widget_Base;




if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Episodes Elementor Widget.
 */
class Episodes extends Base_Widget {
	use Carousel_Traits;
	use Button_Widget_Trait;
	use Pagination_Trait;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'episodes';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Episodes', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-related';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'episodes', 'mas' );
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elements' );
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the element requires.
	 *
	 * @since 1.9.0
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'episodes-stylesheet' );
	}

	/**
	 * Register Controls in Layout Section.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$templates = function_exists( 'mas_template_options' ) ? mas_template_options() : array();
		$this->add_control(
			'select_template',
			array(
				'label'   => esc_html__( 'MAS Templates', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
			)
		);

		$this->end_controls_section();

		$this->traits_register_carousel_attributes_controls( $this );
		$this->register_pagination_style_controls( $this, array( 'concat' => '' ) );
		$this->start_controls_section(
			'section_swiper_arrows',
			array(
				'label'     => __( 'Arrows', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'show_arrows' => 'yes',
				),
			)
		);
		$this->register_button_content_controls( $this, array( 'button_concat' => '' ) );
		$this->end_controls_section();
		$this->register_button_style_controls( $this, array( 'button_concat' => '' ) );
	}

	/**
	 * Carousel Loop Header.
	 *
	 * @param array $settings Settings of this widget.
	 */
	public function carousel_loop_header( array $settings = array() ) {

		if ( 'yes' === $settings['enable_carousel'] ) {
			$json = wp_json_encode( $this->traits_get_swiper_carousel_options( $settings, $this ) );
			$this->add_render_attribute( 'post_swiper', 'class', 'swiper-' . $this->get_id() );
			$this->add_render_attribute( 'post_swiper', 'class', 'swiper' );
			$this->add_render_attribute( 'post_swiper', 'data-swiper-options', $json );
			?>
			<div <?php $this->print_render_attribute_string( 'post_swiper' ); ?>>
				<div class="swiper-wrapper">
			<?php
		}

	}

		/**
		 * Carousel Loop Footer.
		 *
		 * @param array $settings Settings of this widget.
		 */
	public function carousel_loop_footer( array $settings = array() ) {
		if ( 'yes' === $settings['enable_carousel'] ) {
			?>
			</div>
			<?php
			$widget_id = $this->get_id();
			if ( ! empty( $widget_id ) && 'yes' === $settings['show_pagination'] ) {
				$this->add_render_attribute( 'swiper-pagination', 'id', 'pagination-' . $widget_id );
			}
			$this->add_render_attribute( 'swiper-pagination', 'class', 'swiper-pagination' );
			$this->add_render_attribute( 'swiper-pagination', 'style', 'position: ' . $settings['mas_swiper_pagination_position'] . ';' );
			if ( 'yes' === $settings['show_pagination'] ) :
				?>
			<div <?php $this->print_render_attribute_string( 'swiper-pagination' ); ?>></div>
				<?php
			endif;
			if ( 'yes' === $settings['show_arrows'] ) :
				$prev_id = ! empty( $widget_id ) ? 'prev-' . $widget_id : '';
				$next_id = ! empty( $widget_id ) ? 'next-' . $widget_id : '';
				?>
				<!-- If we need navigation buttons -->
				<div class="d-flex mas-swiper-arrows">
				<?php
				$this->render_button( $this, $prev_id, $next_id );
				?>
				</div>
				<?php
			endif;
			?>
			</div>
			<?php
		}
	}

	/**
	 * Renders the Nav Tabs widget.
	 */
	protected function render() {

		$episodes = masvideos_get_episode( get_the_ID() );
		if ( empty( $episodes ) ) {
			return;
		}
		$tv_show_id = $episodes->get_tv_show_id();
		$tv_show    = masvideos_get_tv_show( $tv_show_id );

		$seasons = $tv_show->get_seasons();

		$settings = $this->get_settings();
		$test_id  = (string) get_the_ID();

		?>
		<?php
		$this->carousel_loop_header( $settings );
		if ( 'yes' !== $settings['enable_carousel'] ) {
			?>
					<div class="episodes-wrapper">
					<?php
		}
		?>
		<?php
		foreach ( $seasons as $key => $season ) {

			$original_post = $GLOBALS['post'];
			foreach ( $season['episodes'] as $key => $episode_id ) {

				if ( $test_id === $episode_id ) {
					continue;
				}
				if ( 'yes' === $settings['enable_carousel'] ) {
					?>
					<div class="swiper-slide">
					<?php
				}

				$GLOBALS['post'] = get_post( $episode_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				setup_postdata( $GLOBALS['post'] );

				setup_postdata( masvideos_setup_episode_data( $episode_id ) ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
				print( mas_render_template( $settings['select_template'], false ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				if ( 'yes' === $settings['enable_carousel'] ) {
					?>
					</div>
					<?php
				}
			}
			$GLOBALS['post'] = $original_post;// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

			wp_reset_postdata();

		}
		if ( 'yes' !== $settings['enable_carousel'] ) {
			?>
			</div>
			<?php
		}
		$this->carousel_loop_footer( $settings );

		$this->render_script( 'swiper-' . $this->get_id() );

	}

	/**
	 * Render script in the editor.
	 *
	 * @param string $key widget ID.
	 */
	public function render_script( $key = '' ) {
		$settings = $this->get_settings();
		$key      = '.' . $key;
		if ( Plugin::$instance->editor->is_edit_mode() && 'yes' === $settings['enable_carousel'] ) :
			?>
			<script type="text/javascript">
			var swiperCarousel = (() => {
				// forEach function
				let forEach = (array, callback, scope) => {
				for (let i = 0; i < array.length; i++) {
					callback.call(scope, i, array[i]); // passes back stuff we need
				}
				};

				// Carousel initialisation
				let swiperCarousels = document.querySelectorAll("<?php echo esc_attr( $key ); ?>");
				forEach(swiperCarousels, (index, value) => {
					let postUserOptions,
					postsPagerOptions;
				if(value.dataset.swiperOptions != undefined) postUserOptions = JSON.parse(value.dataset.swiperOptions);


				// Pager
				if(postUserOptions.pager) {
					postsPagerOptions = {
					pagination: {
						el: postUserOptions.pager,
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
				let options = {...postUserOptions, ...postsPagerOptions};
				let swiper = new Swiper(value, options);

				// Tabs (linked content)
				if(postUserOptions.tabs) {

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
