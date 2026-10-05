<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RT\Clawyer\Options\Opt;
use Rtcl\Controllers\BusinessHoursController;

global $listing;

$sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );
$sidebar_class = [
	'col-lg-3',
	'order-2 sidebar-possition-right'
];

if ( $sidebar_position == "left" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1 sidebar-possition-left';
} else if ( $sidebar_position == "bottom" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'col-lg-3' ] );
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}

?>

<!-- Seller / User Information -->
<div class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
    <div class="listing-sidebar">
	    <?php
            if ( function_exists( 'rtrb_cl_booking_button' ) ) {
                rtrb_cl_booking_button();
            }
	    ?>
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
</div>
