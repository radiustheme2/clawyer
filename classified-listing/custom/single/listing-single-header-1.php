<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RT\Clawyer\Plugins\Listing_Functions;

global $listing;
global $wp_locale;

$images = $listing->get_images();

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

$address = get_post_meta( $listing->get_id(), 'address', true );
$phone = get_post_meta( $listing->get_id(), 'phone', true );
$phone_url = str_replace( ' ', '', $phone );

$social_page = Functions::get_option_item('rtcl_general_social_share_settings', 'social_pages', array('listing'));

?>
<div class="listing-details-header header-v1">
	<?php echo Listing_Functions::listing_details_gallery(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div class="listing-details-head">
		<div class="meta-info-box">
			<div class="title-info">
				<h2 class="rtcl-listing-title">
					<?php the_title(); ?>
				</h2>
                <?php
                    if ( $listing->has_category() && $listing->can_show_category()) {
                        $categories = $listing->get_categories();
                        ?>
                        <div class="rt-categories">
                            <?php foreach ( $categories as $category ){ ?>
                                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="category">
                                    <span class="icon">
                                        <?php echo wp_kses_post( Listing_Functions::listing_cat_icon( $category->term_id,  'icon' ) ); ?>
                                    </span>
                                    <?php
                                        $name = $category->name;
                                        printf(
                                            /* translators: %s: Category name. */
                                            esc_html__( "%s Law Attorney", "clawyer" ),
                                            esc_html( $name )
                                        );
                                    ?>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>

				<?php if (!empty($designation)){ ?>
                    <div class="designation">
						<?php if (!empty($designationIcon['icon']['class'])){ ?>
                            <i class="rt-icon <?php echo esc_attr( $designationIcon['icon']['class'] ); ?>"></i>
						<?php } echo esc_html( $designation ); ?>
                    </div>
				<?php } ?>
			</div>
			<div class="meta-list">
				<?php Listing_Functions::clawyer_single_listing_meta(); ?>
			</div>
		</div>
	    <?php Listing_Functions::clawyer_single_listing_action_button(); ?>
	</div>
</div>
