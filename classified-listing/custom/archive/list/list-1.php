<?php
/**
 * Listing Archive Layout
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use Rtcl\Controllers\Hooks\TemplateHooks;
use RT\Clawyer\Plugins\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;

global $listing;

$phone = get_post_meta( $listing->get_id(), 'phone', true );
$phone_url = str_replace(' ', '', $phone);

$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
$show_rating = ! empty( $archive_settings['display_options'] ) && in_array( 'rating', $archive_settings['display_options'] );
$show_status = ! empty( $archive_settings['display_options'] ) && in_array( 'status', $archive_settings['display_options'] );
$show_desc = ! empty( $archive_settings['display_options'] ) && in_array( 'excerpt', $archive_settings['display_options'] );
$book_button = ! empty( $archive_settings['display_options'] ) && in_array( 'book_button', $archive_settings['display_options'] );

$listing_form = $listing->getForm();
// Check if $listing_form is valid before proceeding
if ($listing_form) {
	$designation = get_post_meta($listing->get_id(), 'designation', true);
	$designationIcon = $listing_form->getFieldByName('designation');
} else {
	// Handle the case where $listing_form is null
	$designation = null;
	$designationIcon = null;
}
?>
<?php
/**
 * Hook: rtcl_before_listing_loop_item.
 *
 * @hooked rtcl_template_loop_product_link_open - 10
 */
do_action( 'rtcl_before_listing_loop_item' );

/**
 * Hook: rtcl_listing_loop_item.
 *
 * @hooked listing_thumbnail - 10
 */
do_action( 'rtcl_listing_loop_item_start' );

/**
 * Hook: rtcl_listing_loop_item.
 *
 * @hooked loop_item_wrap_start - 10
 * @hooked loop_item_listing_title - 20
 * @hooked loop_item_labels - 30
 * @hooked loop_item_listable_fields - 40
 * @hooked loop_item_meta - 50
 * @hooked loop_item_excerpt - 60
 * @hooked loop_item_wrap_end - 100
 */

?>

<?php TemplateHooks::loop_item_wrapper_start(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<div class="all-meta-info-box">
	<?php TemplateHooks::loop_item_meta_buttons(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php TemplateHooks::loop_item_listing_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

    <?php if (!empty($designation)){ ?>
    <div class="designation">
        <?php if (!empty($designationIcon['icon']['class'])){ ?>
        <i class="rt-icon <?php echo esc_attr( $designationIcon['icon']['class'] ); ?>"></i>
        <?php } echo esc_html( $designation ); ?>
    </div>
    <?php } ?>
	<?php if ( $show_rating ){ ?>
        <div class="listing-review">
			<?php Listing_Functions::get_listing_reviews( $listing ); ?>
        </div>
	<?php } ?>
	<?php TemplateHooks::loop_item_excerpt(); ?>
    <div class="meta-rating">
		<?php TemplateHooks::loop_item_meta(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
	<?php ProTemplateHooks::loop_item_listable_fields(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<?php if ( ! empty( $show_status ) || $listing->can_show_price() ){ ?>
<div class="listing-footer">
    <div class="price-status">
		<?php TemplateHooks::listing_price(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( ! empty( $show_status ) ) {
            Listing_Functions::listing_bhs_status( $listing ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} ?>
    </div>
</div>
<?php }
if ( function_exists( 'rtrb_cl_booking_button' ) ) {
	if ( $book_button ) {
		rtrb_cl_booking_button();
	}
}
TemplateHooks::loop_item_wrapper_end(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<?php
/**
 * Hook: rtcl_after_listing_loop_item.
 *
 * @hooked listing_loop_map_data - 50
 */
do_action( 'rtcl_after_listing_loop_item' );
?>
