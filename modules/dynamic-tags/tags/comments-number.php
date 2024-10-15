<?php
/**
 * Comments number.
 *
 * @package MASElementor\Modules\DynamicTags\tags\comments-number.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Comments number class.
 */
class Comments_Number extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'comments-number';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Comments Number', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the group.
	 */
	public function get_group() {
		return Module::COMMENTS_GROUP;
	}
	/**
	 * Get the categories.
	 */
	public function get_categories() {
		return array(
			Module::TEXT_CATEGORY,
			Module::NUMBER_CATEGORY,
		);
	}
	/**
	 * Register controls for post comments number.
	 */
	protected function register_controls() {
		$this->add_control(
			'format_no_comments',
			array(
				'label'   => esc_html__( 'No Comments Format', 'mas-addons-for-elementor' ),
				'default' => esc_html__( 'No Responses', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'format_one_comments',
			array(
				'label'   => esc_html__( 'One Comment Format', 'mas-addons-for-elementor' ),
				'default' => esc_html__( 'One Response', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'format_many_comments',
			array(
				'label'   => esc_html__( 'Many Comment Format', 'mas-addons-for-elementor' ),
				'default' => esc_html__( '{number} Responses', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'   => esc_html__( 'Link', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''              => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'comments_link' => esc_html__( 'Comments Link', 'mas-addons-for-elementor' ),
				),
			)
		);
	}
	/**
	 * Render the comments number.
	 */
	public function render() {
		$settings = $this->get_settings();

		$comments_number = get_comments_number();

		if ( ! $comments_number ) {
			$count = $settings['format_no_comments'];
		} elseif ( 1 === (int) $comments_number ) {
			$count = $settings['format_one_comments'];
		} else {
			$count = strtr(
				$settings['format_many_comments'],
				array(
					'{number}' => number_format_i18n( $comments_number ),
				)
			);
		}

		if ( 'comments_link' === $this->get_settings( 'link_to' ) ) {
			$count = sprintf( '<a href="%s">%s</a>', get_comments_link(), $count );
		}

		echo wp_kses_post( $count );
	}
}
