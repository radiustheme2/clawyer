<?php
/**
 * Result Count
 *
 * @var Listing $listing
 */

use Rtcl\Helpers\Functions;
use Rtcl\Controllers\Hooks\TemplateHooks;
use RT\Clawyer\Plugins\Listing_Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $listing;

if ( ! $listing ) {
	return;
}

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$view = sanitize_text_field( wp_unslash( $_GET['view'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

?>

<div class="listing-thumb">
    <div class="clawyer-listing-actions-buttons">
		<?php if ( $listing->can_show_ad_type() && ! empty( $listing_type ) ): ?>
            <span class="listing-type-badge">
                <?php echo wp_kses_post( sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'clawyer' ) ), esc_html( $listing_type['label'] ) ) ); ?>
            </span>
		<?php endif; ?>
		<?php TemplateHooks::loop_item_badges(); ?>
	    <?php if (is_singular('rtcl_listing') && !empty( clawyer_option('rt_related_listing_cat_visibility') )){ ?>
            <div class="listing-categories inner-actions-buttons">
                <?php Listing_Functions::clawyer_listing_categories( 'icon' ); ?>
            </div>
        <?php } else {
            if ($listing->can_show_category()){
            ?>
            <div class="listing-categories inner-actions-buttons">
			    <?php Listing_Functions::clawyer_listing_categories( 'icon' ); ?>
            </div>
	    <?php }
        } ?>
    </div>

    <div class="listing-thumb-inner">
		<a href="<?php $listing->the_permalink(); ?>" class="rtcl-media grid-thumbnail"><?php $listing->the_thumbnail('clawyer-450-420'); ?></a>
		<a href="<?php $listing->the_permalink(); ?>" class="rtcl-media list-thumbnail"><?php $listing->the_thumbnail('clawyer-450-420'); ?></a>
		<?php
            /**
             * Hook: rtcl_after_listing_thumbnail.
             *
             * @hooked loop_item_meta_buttons - 10
             */
            do_action( 'rtcl_after_listing_thumbnail' );
		?>
	</div>
	<?php if ( $listing->can_show_category() ): ?>
        <div class="listing-categories outer-actions-buttons">
			<?php Listing_Functions::clawyer_listing_categories( 'icon' ); ?>
        </div>
	<?php endif; ?>
</div>
