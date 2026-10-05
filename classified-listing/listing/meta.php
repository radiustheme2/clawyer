<?php
/**
 * Listing meta
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RT\Clawyer\Plugins\Listing_Functions;

if ( ! $listing ) {
	global $listing;
}

if ( empty( $listing ) ) {
	return;
}

$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
$show_address = ! empty( $archive_settings['display_options'] ) && in_array( 'address', $archive_settings['display_options'] );
$show_phone   = ! empty( $archive_settings['display_options'] ) && in_array( 'phone', $archive_settings['display_options'] );
$show_rating = ! empty( $archive_settings['display_options'] ) && in_array( 'rating', $archive_settings['display_options'] );

$location_type = Functions::location_type();
$address = get_post_meta( $listing->get_id(), 'address', true );
$geo_address = get_post_meta( $listing->get_id(), '_rtcl_geo_address', true );

$phoneNum = get_post_meta( $listing->get_id(), 'phone', true );
$phone_url = str_replace(' ', '', $phoneNum);

if ( ! $listing->can_show_date() && ! $listing->can_show_user() && ! $listing->can_show_category() && ! $listing->can_show_location() && ! $listing->can_show_views() ) {
	return;
}

?>

<ul class="rtcl-listing-meta-data">
	<?php
	if ( $listing->can_show_ad_type() ) :
		$listing_types = Functions::get_listing_types();
		$types         = ! empty( $listing_types ) && isset( $listing_types[ $listing->get_ad_type() ] ) ? $listing_types[ $listing->get_ad_type() ] : '';
		if ( $types ) {
			?>
		<li class="rt-ad ad-type"><i class="rtcl-icon rtcl-icon-tags"></i>&nbsp;<?php echo esc_html( $types ); ?></li>
		<?php } ?>
	<?php endif; ?>

	<?php if ( $listing->can_show_user() ) : ?>
		<li class="rt-author">
			<i class="icon-author-2"></i>
			<?php esc_html_e( 'by ', 'clawyer' ); ?>
			<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
				<a href="<?php echo esc_url( $listing->get_the_author_url() ); ?>"><?php $listing->the_author(); ?></a>
			<?php else : ?>
				<?php $listing->the_author(); ?>
			<?php endif; ?>
			<?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
		</li>
	<?php endif; ?>

	<?php
	if ( $listing->has_location() && $listing->can_show_location() ) :
		?>
		<li class="rt-location">
			<i class="icon-location"></i> <?php $listing->the_locations( true, true ); ?>
		</li>
	<?php endif; ?>

	<?php if ( !empty( $address || $geo_address ) && $show_address ) : ?>
		<li class="rt-location">
			<i class="icon-location"></i>
			<?php
				if ( $location_type == 'geo' && !empty( $geo_address ) ) {
					echo esc_html( $geo_address );
				} else {
					echo esc_html( $address );
				}
			?>
		</li>
	<?php endif; ?>

	<?php if ( $show_phone && !empty( $phoneNum ) ){
		$phone = sprintf(
			'<li class="rt-phone"><a href="tel:%s"><i class="icon-icon-phone"></i> %s </a></li>',
			$phone_url,
			$phoneNum
		);
		echo wp_kses_post( $phone );
	}
	?>

    <?php if ( $listing->can_show_date() ) : ?>
		<li class="rt-time"><i class="icon-clock"></i>&nbsp;<?php $listing->the_time(); ?></li>
	<?php endif; ?>

	<?php if ( $listing->can_show_views() ) : ?>
		<li class="rt-views">
			<i class="icon-eye"></i>
			<?php /* translators: %s: Number of listing views. */ echo esc_html( sprintf( _n( '%s view', '%s views', $listing->get_view_counts(), 'clawyer' ), number_format_i18n( $listing->get_view_counts() ) ) ); ?>
		</li>
	<?php endif; ?>

    <?php if ( $show_rating ){ ?>
    <li class="rt-rating">
        <?php Listing_Functions::get_listing_reviews( $listing ); ?>
    </li>
    <?php } ?>
</ul>
