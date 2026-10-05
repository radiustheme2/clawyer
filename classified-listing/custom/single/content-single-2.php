<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use Rtcl\Helpers\Functions;
use RT\Clawyer\Plugins\Listing_Functions;


defined('ABSPATH') || exit;

global $listing;

$sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );


$sidebar_class    = [
	'col-lg-3',
	'order-2 sidebar-possition-left'
];
$content_class = [
	'col-lg-9',
	'order-1',
	'listing-content'
];
if ( $sidebar_position == "left" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1';
	$content_class   = array_diff( $content_class, [ 'order-1' ] );
	$content_class[] = 'order-2';
} else if ( $sidebar_position == "bottom" ) {
	$content_class   = array_diff( $content_class, [ 'col-lg-9' ] );
	$sidebar_class   = array_diff( $sidebar_class, [ 'col-lg-3' ] );
	$content_class[] = 'col-sm-12';
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}

$sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );
$listing_type = Listing_Functions::get_listing_type( $listing );

$video_urls       = [];
if ( !empty( clawyer_option('rt_listing_video_visibility') ) ) {
	$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
	$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}

$social_page = Functions::get_option_item('rtcl_general_social_share_settings', 'social_pages', array('listing'));

$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );

?>
<div id="rtcl-listing-<?php the_ID(); ?>" <?php Functions::listing_class( 'listing-details-2', $listing ); ?>>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
	            <?php Listing_Functions::get_custom_listing_template( 'single/listing-single-header-1' ); ?>
            </div>
        </div>
        <div class="row">
			<?php if ( in_array( $sidebar_position, [ 'left' ] ) ) : ?>
                <!-- Sidebar -->
				<?php do_action( 'rtcl_single_listing_sidebar' ); ?>
			<?php endif; ?>
            <div class="<?php echo esc_attr( implode( ' ', $content_class ) ); ?>">
                <div class="listing-details">
                    <div class="rtcl-single-listing-details custom-fields-box">
                        <div class="rtcl-main-content-wrapper">
                            <!-- Description -->
                            <div class="rtcl-listing-description">
                                <h3 class="desc-title"><?php esc_html_e('Overview', 'clawyer'); ?></h3>
								<?php $listing->the_content(); ?>
                            </div>

							<?php if ( $sidebar_position === "bottom" ) : ?>
                                <!-- Sidebar -->
								<?php do_action( 'rtcl_single_listing_sidebar' ); ?>
							<?php endif; ?>

                            <!--  Inner Sidebar -->
							<?php do_action( 'rtcl_single_listing_inner_sidebar', $listing ); ?>
							<?php $listing->custom_fields(); ?>
                        </div>
                    </div>

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

					<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ){

						do_action( 'rtcl_single_listing_content_end', $listing );

					} if (Functions::get_option_item('rtcl_single_listing_settings', 'enable_review_rating', false, 'checkbox')) { ?>
                        <div class="rtcl-single-listing-details review-box">
                            <div class="rtcl-main-content-wrapper">
								<?php do_action( 'rtcl_single_listing_review' ) ?>
                            </div>
                        </div>
					<?php } ?>
                </div>
            </div>
			<?php if ( in_array( $sidebar_position, [ 'right' ] ) ) : ?>
                <!-- Sidebar -->
				<?php do_action( 'rtcl_single_listing_sidebar' ); ?>
			<?php endif; ?>
        </div>
    </div>
</div>