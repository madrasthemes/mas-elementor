<?php
/**
 * Column.
 *
 * @package MASElementor\Modules\Column
 */

namespace MASElementor\Modules\Column;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The column module class
 */
class Module extends Module_Base {

	/**
	 * Initialize the column module object.
	 */
	public function __construct() {
		parent::__construct();

		$this->add_actions();
		
	}

	/**
	 * Return the script dependencies of the module.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'scrollspy-script', 'sticky-block-script' );
	}

	/**
	 * Return the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'override-column';
	}

	/**
	 * Add actions to override column element.
	 */
	public function add_actions() {
		add_action( 'elementor/element/column/layout/before_section_start', array( $this, 'add_column_controls' ), 10 );
		add_action( 'elementor/element/column/section_advanced/before_section_end', array( $this, 'add_widget_wrap_controls' ) );
		add_action( 'elementor/element/after_add_attributes', array( $this, 'modify_attributes' ), 20 );
		add_filter( 'elementor/column/print_template', array( $this, 'print_template' ) );
		// add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		//add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );

	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_widget_wrap_controls( $element ) {

		$element->add_control(
			'mas_widget_wrapper_css',
			array(
				'label'       => esc_html__( 'CSS Classes', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Applied to elementor-widget-wrap element.', 'mas-elementor' ),
			)
		);

		$element->add_control(
			'scrollspy',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Scrollspy ?', 'mas-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'No', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Yes', 'mas-elementor' ),
			]
		);

		$element->add_control(
			'parent_id',
			array(
				'label'       => esc_html__( 'Parent ID', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => [
					'scrollspy' => 'yes',
				],
			)
		);

		$element->add_control(
			'target_id',
			array(
				'label'       => esc_html__( 'Data Target ID', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => [
					'scrollspy' => 'yes',
				],
			)
		);

		$element->add_control(
			'breakpoint',
			array(
				'label'   => esc_html__( 'Breakpoint', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'xs'    => 'XS',
					'sm'    => 'SM',
					'md'    => 'MD',
					'lg'    => 'LG',
				),
				'default' => 'md',
				'condition'   => [
					'scrollspy' => 'yes',
				],
			)
		);

		$element->add_control(
			'start_point_id',
			array(
				'label'       => esc_html__( 'Startpoint ID', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => [
					'scrollspy' => 'yes',
				],
			)
		);

		$element->add_control(
			'end_point_id',
			array(
				'label'       => esc_html__( 'Endpoint ID', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => [
					'scrollspy' => 'yes',
				],
			)
		);

		$element->add_control(
            'offset',
            [
                'label'       => esc_html__( 'Offset', 'finder-elementor' ),
                'type'        => Controls_Manager::SLIDER,
                'range'  => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
				'condition'   => [
					'scrollspy' => 'yes',
				],
            ]
        );
	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_enqueue_script(
			'scrollspy-script',
			MAS_ELEMENTOR_MODULES_URL . 'column/assets/js/scrollspy.min.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_enqueue_script(
			'sticky-block-script',
			MAS_ELEMENTOR_MODULES_URL . 'column/assets/js/sticky-block.min.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_enqueue_script(
			'scrollspy-init-script',
			MAS_ELEMENTOR_MODULES_URL . 'column/assets/js/scroll-init.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
		

	}


	/**
	 * Add column controls to the Column Element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_column_controls( $element ) {}

	/**
	 * Modify attributes rendered to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function modify_attributes( $element ) {
		if ( 'column' === $element->get_name() ) {
			$settings = $element->get_settings_for_display();

			if ( ! empty( $settings['mas_widget_wrapper_css'] ) ) {
				$element->add_render_attribute( '_widget_wrapper', 'class', $settings['mas_widget_wrapper_css'] );
			}

			$args = [
				'parentSelector' => $settings['parent_id'],
				'targetSelector' => $settings['target_id'],
				'breakpoint' => $settings['breakpoint'],
				'startPoint' => $settings['start_point_id'],
				'endPoint' => $settings['end_point_id'],
				'stickyOffsetTop' => $settings['offset']['size'],
			];

			if ( 'yes' ===  $settings['scrollspy'] )  {
				$element->add_render_attribute( '_widget_wrapper', [
					'class' => 'js-sticky-block js-scrollspy',
					'data-hs-sticky-block-options' => wp_json_encode( $args )
				]);
		
			}
		}
	}

	/**
	 * Print the column.
	 */
	public function print_template() {
		ob_start();
		$this->content_template();
		return ob_get_clean();
	}

	/**
	 * The column template.
	 */
	public function content_template() {
		$is_dom_optimization_active = Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' );
		$wrapper_element            = $is_dom_optimization_active ? 'widget' : 'column';

		?>
		<# 
			let wrapper_class = '';

			if( '' != settings.widget_wrapper_css ) {
				wrapper_class = ` ${ settings.widget_wrapper_css }`;
			}

		#>
		<div class="elementor-<?php echo esc_attr( $wrapper_element ); ?>-wrap{{ wrapper_class }}">
			<div class="elementor-background-overlay"></div>
			<?php if ( ! $is_dom_optimization_active ) { ?>
				<div class="elementor-widget-wrap"></div>
			<?php } ?>
		</div>
		<?php
	}
	
}
