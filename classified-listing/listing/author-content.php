<?php
/**
 * Author Listing
 *
 * @author     RadiusTheme
 * @package    ClassifiedListing/Templates
 * @version    2.2.1.1
 */

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$author_id = get_the_author_meta('ID');
//user_id = get_current_user_id(); // Get the ID of the currently logged-in user
$user_info = get_userdata( $author_id); // Get the user data based on user ID

if ($user_info) {
	$user_email = $user_info->user_email; // Get the email address from user data
	$row_class = '';
	$cols = '8';
} else {
	$user_email = ''; // Get the email address from user data
	$row_class = 'justify-content-center';
	$cols = '10';
}

$status = apply_filters( 'rtcl_user_offline_text', esc_html__( 'User is offline Now', 'clawyer' ) );
if ( Fns::is_online( $author_id ) ) {
	$status = apply_filters( 'rtcl_user_online_text', esc_html__( 'User is online now!', 'clawyer' ) );
}

$author_name = get_the_author_meta('display_name');
$author_description = get_the_author_meta('description');

$address = get_user_meta( $author_id, '_rtcl_address', true );
$phone = get_user_meta( $author_id, '_rtcl_phone', true );
$website = get_user_meta( $author_id, '_rtcl_website', true );
$authorWebsite = str_replace(['https://', 'http://'], '', $website );

?>
<div class="rtcl-user-single-wrapper rtcl">
	<div class="container">
        <div class="author-banner-info">
            <div class="author-logo-wrapper <?php echo esc_attr( strtolower( $status ) ); ?>">
		        <?php
		            $pp_id = absint( get_user_meta( $author_id, '_rtcl_pp_id', true ) );
		            echo wp_kses_post( $pp_id ? wp_get_attachment_image( $pp_id, [140, 140]) : get_avatar( $author_id, 140 ) );
		        ?>
                <p class="rtcl-user-status <?php echo esc_attr( strtolower( $status ) ); ?>"></p>
            </div>
            <h2 class="author-name">
		        <?php echo esc_html( $author_name ); ?>
            </h2>
	        <?php
                $since = gmdate( "F, Y", strtotime(get_userdata($author_id)->user_registered ));
                /* translators: %s: User registration date (month and year). */
                echo esc_html( sprintf( __( "Member Since : %s", "clawyer" ), $since ) );
	        ?>
        </div>
		<div class="row <?php echo esc_attr( $row_class ); ?>">
			<div class="col-lg-<?php echo esc_attr( $cols ); ?>">
                <div class="author-info">
                    <h3 class="title"><?php esc_html_e( 'About Me', 'clawyer' ); ?></h3>
                    <?php echo wp_kses_post( wpautop( get_the_author_meta( 'description' ) ) ); ?>
                </div>
			</div>

            <div class="col-lg-4">
                <div class="author-general-info">
                    <h3 class="title"><?php esc_html_e( 'Contact Info', 'clawyer' ); ?></h3>
	                <?php if ( $address ){ ?>
                        <div class="author-info-item">
                            <i class="icon-location"></i>
	                        <?php echo esc_html( $address ); ?>
                        </div>
	                <?php } ?>

                    <?php if ( $phone ){ ?>
                        <div class="author-info-item">
                            <i class="icon-icon-phone"></i>
                            <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                        </div>
	                <?php } ?>

	                <?php if ( $user_email ){ ?>
                        <div class="author-info-item">
                            <i class="icon-mail"></i>
                            <a href="mailto:<?php echo esc_attr( $user_email ); ?>"><?php echo esc_html( $user_email ); ?></a>
                        </div>
	                <?php } ?>

	                <?php if ( $website ){ ?>
                        <div class="author-info-item">
                            <i class="icon-web"></i>
                            <a class="rtcl-website-link" href="<?php echo esc_url( $website ); ?>" target="_blank"
		                        <?php echo Functions::is_external( $website ) ? ' rel="nofollow"' : ''; ?>>
		                        <?php echo esc_html( $authorWebsite ); ?>
                            </a>
                        </div>
	                <?php } ?>

	                <?php
	                $social_list = Functions::get_user_social_profile( $author_id );
	                if ( ! empty( $social_list ) ) {
		                ?>
                        <div class="rtcl-user-social">
                            <div class="social-list">
				                <?php
                                    foreach ( $social_list as $key => $value ) {
                                        ?>
                                        <a target="_blank" href="<?php echo esc_url( $value ) ?>" class="<?php echo esc_attr( $key ) ?>">
                                            <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $key ) ?>"></i>
                                        </a>
                                        <?php
                                    }
				                ?>
                            </div>
                        </div>
	                <?php } ?>
	                <?php do_action( 'rtcl_author_details_after_meta', $author_id ); ?>
                </div>
            </div>
		</div>
	</div>
</div>

<div class="rtcl-user-single-wrapper rtcl">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
				<?php Functions::get_template( 'listing/author-listing'); ?>
            </div>
        </div>
    </div>
</div>
