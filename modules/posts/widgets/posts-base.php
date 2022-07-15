<?php
/**
 * The Posts Base.
 *
 * @package MASElementor/Modules/Posts/Widgets
 */

namespace MASElementor\Modules\Posts\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use MASElementor\Base\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Posts
 */
abstract class Posts_Base extends Base_Widget {

	/**
	 * Query.
	 *
	 *  @var \WP_Query
	 */
	protected $query = null;

	const LOAD_MORE_ON_CLICK        = 'load_more_on_click';
	const LOAD_MORE_INFINITE_SCROLL = 'load_more_infinite_scroll';

	/**
	 * Does the widget has template content
	 *
	 * @var bool
	 */
	protected $_has_template_content = false;

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'imagesloaded' );
	}

	/**
	 * Get the query.
	 *
	 * @return array
	 */
	public function get_query() {
		return $this->query;
	}

	/**
	 * Render
	 */
	public function render() {}

	/**
	 * Register pagination section controls
	 */
	public function register_pagination_section_controls() {
		$this->start_controls_section(
			'section_pagination',
			array(
				'label' => __( 'Pagination', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'pagination_type',
			array(
				'label'   => __( 'Pagination', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''                      => __( 'None', 'mas-elementor' ),
					'numbers'               => __( 'Numbers', 'mas-elementor' ),
					'prev_next'             => __( 'Previous/Next', 'mas-elementor' ),
					'numbers_and_prev_next' => __( 'Numbers', 'mas-elementor' ) . ' + ' . __( 'Previous/Next', 'mas-elementor' ),
				),
			)
		);

		$this->add_control(
			'pagination_page_limit',
			array(
				'label'     => __( 'Page Limit', 'mas-elementor' ),
				'default'   => '5',
				'condition' => array(
					'pagination_type!' => '',
				),
			)
		);

		$this->add_control(
			'pagination_numbers_shorten',
			array(
				'label'     => __( 'Shorten', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'pagination_type' => array(
						'numbers',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_prev_label',
			array(
				'label'     => __( 'Previous Label', 'mas-elementor' ),
				'default'   => __( '&laquo; Previous', 'mas-elementor' ),
				'condition' => array(
					'pagination_type' => array(
						'prev_next',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_next_label',
			array(
				'label'     => __( 'Next Label', 'mas-elementor' ),
				'default'   => __( 'Next &raquo;', 'mas-elementor' ),
				'condition' => array(
					'pagination_type' => array(
						'prev_next',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_align',
			array(
				'label'     => __( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => __( 'Pagination', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'pagination_type!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .elementor-pagination',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
			)
		);

		$this->add_control(
			'pagination_color_heading',
			array(
				'label'     => __( 'Colors', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'pagination_colors' );

		$this->start_controls_tab(
			'pagination_color_normal',
			array(
				'label' => __( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers:not(.dots)' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_hover',
			array(
				'label' => __( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'pagination_hover_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination a.page-numbers:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_active',
			array(
				'label' => __( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'pagination_active_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_spacing',
			array(
				'label'     => __( 'Space Between', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_spacing_top',
			array(
				'label'     => __( 'Spacing', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Query Posts
	 */
	abstract public function query_posts();

	/**
	 * Get the current page.
	 *
	 * @return int
	 */
	public function get_current_page() {
		if ( '' === $this->get_settings( 'pagination_type' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	/**
	 * Get WP Link page
	 *
	 * @param  int $i Page Number.
	 * @return string
	 */
	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post       = get_post();
		$query_args = array();
		$url        = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ), true ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) === 'page' && (int) get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) { //phpcs:ignore
				$query_args['preview_id']    = wp_unslash( $_GET['preview_id'] ); //phpcs:ignore
				$query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] ); //phpcs:ignore
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	/**
	 * Get posts nav link.
	 *
	 * @param int|null $page_limit The max number of pages.
	 * @return array
	 */
	public function get_posts_nav_link( $page_limit = null ) {
		if ( ! $page_limit ) {
			$page_limit = $this->query->max_num_pages;
		}

		$return = array();

		$paged = $this->get_current_page();

		$link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_prev_label' ) );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $this->get_settings( 'pagination_prev_label' ) );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_next_label' ) );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $this->get_settings( 'pagination_next_label' ) );
		}

		return $return;
	}

	/**
	 * Render plain content.
	 */
	public function render_plain_content() {}

	/**
	 * Render loop footer for pagination.
	 */
	protected function render_loop_footer() {
		$parent_settings       = $this->get_settings();
		$using_ajax_pagination = in_array(
			$parent_settings['pagination_type'],
			array(
				self::LOAD_MORE_ON_CLICK,
				self::LOAD_MORE_INFINITE_SCROLL,
			),
			true
		);
		?>
		</div>


		<?php if ( $using_ajax_pagination && ! empty( $parent_settings['load_more_spinner']['value'] ) ) : ?>
			<span class="e-load-more-spinner">
				<?php Icons_Manager::render_icon( $parent_settings['load_more_spinner'], array( 'aria-hidden' => 'true' ) ); ?>
			</span>
		<?php endif; ?>

		<?php

		if ( '' === $parent_settings['pagination_type'] ) {
			return;
		}

		$page_limit = $this->get_query()->max_num_pages;

		// Page limit control should not effect in load more mode.
		if ( '' !== $parent_settings['pagination_page_limit'] && ! $using_ajax_pagination ) {
			$page_limit = min( $parent_settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$this->add_render_attribute( 'pagination', 'class', 'elementor-pagination' );

		$has_numbers   = in_array( $parent_settings['pagination_type'], array( 'numbers', 'numbers_and_prev_next' ) );
		$has_prev_next = in_array( $parent_settings['pagination_type'], array( 'prev_next', 'numbers_and_prev_next' ) );

		$load_more_type = $parent_settings['pagination_type'];

		$current_page = $this->get_current_page();
		$next_page    = intval( $current_page ) + 1;

		$this->add_render_attribute(
			'load_more_anchor',
			array(
				'data-page'      => $current_page,
				'data-max-page'  => $this->get_query()->max_num_pages,
				'data-next-page' => $this->get_wp_link_page( $next_page ),
			)
		);

		?>
		<div class="e-load-more-anchor" <?php $this->print_render_attribute_string( 'load_more_anchor' ); ?>></div>
		<?php

		if ( $using_ajax_pagination ) {
			if ( 'load_more_on_click' === $load_more_type ) {
				// The link-url control is hidden, so default value is added to keep the same style like button widget.
				$this->set_settings( 'link', array( 'url' => '#' ) );

				$this->render_button( $this->parent );
			}

			$this->render_message();
			return;
		}

		$links = array();

		if ( $has_numbers ) {
			$paginate_args = array(
				'type'               => 'array',
				'current'            => $this->get_current_page(),
				'total'              => $page_limit,
				'prev_next'          => false,
				'show_all'           => 'yes' !== $parent_settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="elementor-screen-only">' . esc_html__( 'Page', 'mas-elementor' ) . '</span>',
			);

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$prev_next = $this->get_posts_nav_link( $page_limit );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}

		// PHPCS - Seems that `$links` is safe.
		?>
		<nav class="elementor-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'mas-elementor' ); ?>">
			<?php echo implode( PHP_EOL, $links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</nav>
		<?php
	}
}
