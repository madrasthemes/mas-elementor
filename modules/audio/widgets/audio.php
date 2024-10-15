<?php
/**
 * Audio Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Audio\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use MASElementor\Base\Base_Widget;
use Elementor\Plugin;
use MASElementor\Plugin as MASPlugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CountDown Widget.
 */
class Audio extends Base_Widget {
	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-audio';
	}

	/**
	 * Get the name of the title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Audio', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the name of the icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-audio';
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
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mas-jplayer' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'mas-jplayer' );
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'audio', 'mp3' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_audio_acf',
			array(
				'label' => __( 'ACF Keys', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'mp3_acf_key',
			array(
				'label' => __( 'MP3 Acf Key', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'ogg_acf_key',
			array(
				'label' => __( 'OGG Acf Key', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'embed_acf_key',
			array(
				'label' => __( 'Embedded Acf Key', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'display_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'display_height',
			array(
				'label'      => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		global $post;

		$settings = $this->get_settings_for_display();
		$post_id  = esc_attr( ( ! empty( $post_id ) ? $post_id : $post->ID ) );

		// Get the player media.
		$mp3    = get_post_meta( $post_id, $settings['mp3_acf_key'], true );
		$ogg    = get_post_meta( $post_id, $settings['ogg_acf_key'], true );
		$embed  = get_post_meta( $post_id, $settings['embed_acf_key'], true );
		$height = $settings['display_height'];
		$width  = $settings['display_width'];

		if ( ! empty( $embed ) ) {
			// Embed Audio.
			if ( ! empty( $embed ) && ! is_array( $embed ) ) {
				// run oEmbed for known sources to generate embed code from audio links.
				echo $GLOBALS['wp_embed']->autoembed( stripslashes( htmlspecialchars_decode( $embed ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				return; // and.... Done!
			}
		} elseif ( ! empty( $mp3 ) || ! empty( $ogg ) ) {

			// Other audio formats. ?>

			<script type="text/javascript">

				jQuery(document).ready(function(){

					if(jQuery().jPlayer) {
						jQuery("#jquery_jplayer_<?php echo esc_attr( $post_id ); ?>").jPlayer({
							ready: function (event) {

								// set media.
								jQuery(this).jPlayer("setMedia", {
									<?php
									if ( ! empty( $mp3 ) ) :
										echo 'mp3: "' . $mp3 . '",'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											endif;
									if ( ! empty( $ogg ) ) :
										echo 'oga: "' . $ogg . '",'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											endif;
									?>
									end: ""
								});
							},
									<?php if ( ! empty( $poster ) ) { ?>
							size: {
								width: "<?php echo esc_js( $width ); ?>px",
								height: "<?php echo esc_js( $height . 'px' ); ?>"
							},
							<?php } ?>
							swfPath: "<?php echo get_template_directory_uri(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>/assets/js",
							cssSelectorAncestor: "#jp_interface_<?php echo esc_attr( $post_id ); ?>",
							supplied: "<?php if ( ! empty( $ogg ) ) : ?>
								oga,<?php endif; ?><?php if ( ! empty( $mp3 ) ) : ?>
									mp3, <?php endif; ?> all"
						});

					}
				});
			</script>

			<div id="jquery_jplayer_<?php echo esc_attr( $post_id ); ?>" class="jp-jplayer jp-jplayer-audio"></div>

			<div class="jp-audio-container">
				<div class="jp-audio">
					<div class="jp-type-single">
						<div id="jp_interface_<?php echo esc_attr( $post_id ); ?>" class="jp-interface">
							<ul class="jp-controls">
								<li><div class="seperator-first"></div></li>
								<li><div class="seperator-second"></div></li>
								<li><a href="#" class="jp-play" tabindex="1"><i class="fa fa-play"></i><span>play</span></a></li>
								<li><a href="#" class="jp-pause" tabindex="1"><i class="fa fa-pause"></i><span>pause</span></a></li>
								<li><a href="#" class="jp-mute" tabindex="1"><i class="fa fa-volume-up"></i><span>mute</span></a></li>
								<li><a href="#" class="jp-unmute" tabindex="1"><i class="fa fa-volume-off"></i><span>unmute</span></a></li>
							</ul>
							<div class="jp-progress-container">
								<div class="jp-progress">
									<div class="jp-seek-bar">
										<div class="jp-play-bar"></div>
									</div>
								</div>
							</div>
							<div class="jp-volume-bar-container">
								<div class="jp-volume-bar">
									<div class="jp-volume-bar-value"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
