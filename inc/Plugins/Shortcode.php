<?php
/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */

namespace RT\Clawyer\Plugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use RT\Clawyer\Options\Opt;
use Rtcl\Controllers\BusinessHoursController;

/**
 * ThemeJetpack Class
 */
class Shortcode {

	protected static $instance = null;

	public static $shortcode_list = [
		'header'    => [
			'tag'      => 'clawyer_listing_header',
			'callback' => 'render_listing_header',
		],
		'sidebar'    => [
			'tag'      => 'clawyer_listing_sidebar',
			'callback' => 'render_listing_sidebar',
		],
		'description'    => [
			'tag'      => 'clawyer_listing_description',
			'callback' => 'render_listing_description',
		],
        'custom_fields'    => [
			'tag'      => 'clawyer_listing_custom_fields',
			'callback' => 'render_listing_custom_fields',
		],
		'video'    => [
			'tag'      => 'clawyer_listing_video',
			'callback' => 'render_listing_video',
		],
        'map'    => [
			'tag'      => 'clawyer_listing_map',
			'callback' => 'render_listing_map',
		],
        'review'    => [
			'tag'      => 'clawyer_listing_review',
			'callback' => 'render_listing_review',
		],
	];

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		add_filter(
			'rtcl/fb/single_layout/fields',
			function ( $fields ) {
				$shortcode_hints = '';

				foreach ( self::$shortcode_list as $label => $shortcode ) {
					$display = isset( $shortcode['hint'] ) ? $shortcode['hint'] : $shortcode['tag'];
				    $shortcode_hints .= '<br><b>' . ucfirst( $label ) . ":</b> [{$display}]";
				}

				$fields['shortcode']['editor']['hints']['value'] = 'Available Shortcodes: ' . $shortcode_hints;

				return $fields;
			}
		);

		foreach ( self::$shortcode_list as $shortcode ) {
			add_shortcode( $shortcode['tag'], [ $this, $shortcode['callback'] ] );
		}
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * @return false|string
	 */
	public function render_listing_header() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			Listing_Functions::get_custom_listing_template( 'single/listing-single-header-1' );

			return ob_get_clean();
		}

		return '';
	}

    /**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
	public function render_listing_sidebar() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			ob_start();
			?>

            <div class="listing-sidebar">
	            <?php do_action('rtcl_after_single_listing_sidebar', $listing->get_id()); // This hook for booking ?>
	            <?php $listing->the_user_info(); ?>
	            <?php if ( ! empty( BusinessHoursController::get_business_hours( $listing->get_id() ) ) ) { ?>
                    <div class="business-hour-box widget">
                        <h3 class="title"><?php esc_html_e( 'Business Hours', 'clawyer' ); ?></h3>
                        <div class="single-business-hour">
                            <div class="main-content">
					            <?php do_action( 'rtcl_single_listing_business_hours' ); ?>
                            </div>
                        </div>
                    </div>
	            <?php } ?>

	            <?php
	            if ( is_active_sidebar( Opt::$sidebar ) ) {
		            if ( is_active_sidebar( Opt::$sidebar ) ) {
			            clawyer_sidebar( Opt::$sidebar );
		            } else {
			            clawyer_sidebar('rtcl-single-sidebar' );
		            }
	            }
	            ?>
            </div>

            <?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
    public function render_listing_description() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			ob_start();
			?>
            <div class="rtcl-single-listing-details">
                <div class="rtcl-listing-description">
                    <h3 class="desc-title"><?php esc_html_e('Overview', 'clawyer'); ?></h3>
                    <?php $listing->the_content(); ?>
                </div>
            </div>
            <?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
	public function render_listing_custom_fields() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			ob_start();

			$listing->custom_fields();

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_video() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			$video_urls       = [];
			if ( !empty( clawyer_option('rt_listing_video_visibility') ) ) {
				$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
				$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
			}

			?>

            <!-- Listing Video -->
			<?php if ( ! empty( clawyer_option('rt_listing_video_visibility') && $video_urls ) ){ ?>
                <div class="rtcl-single-listing-details video-box">
                    <div class="rtcl-main-content-wrapper">
						<?php if (!empty(clawyer_option('rt_listing_video_title'))){ ?>
                            <h3 class="desc-title">
								<?php echo esc_html( clawyer_option('rt_listing_video_title') ); ?>
                            </h3>
						<?php } ?>
                        <div class="video-info ratio-16x9 mt-3">
                            <iframe class="rtcl-lightbox-iframe" src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_urls[0] ) ); ?>"></iframe>
                        </div>
                    </div>
                </div>
			<?php } ?>

			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_map() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			$hide_listing_map = get_post_meta( get_the_ID(), 'hide_map', true );

            if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ){
                do_action( 'rtcl_single_listing_content_end', $listing );
            }

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_review() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			if (Functions::get_option_item('rtcl_single_listing_settings', 'enable_review_rating', false, 'checkbox')) {
        ?>
            <div class="rtcl-single-listing-details review-box">
                <div class="rtcl-main-content-wrapper">
					<?php do_action( 'rtcl_single_listing_review' ) ?>
                </div>
            </div>
        <?php
			}
			return ob_get_clean();
		}

		return '';
	}

}

Shortcode::instance();
