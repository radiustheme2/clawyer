<?php
/**
 * Template Name: RT Icons
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package clawyer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>
	<div class="container">
		<div class="row pt-50 pb-50 d-flex gap-15">
			<?php
				echo clawyer_get_svg( 'search' );
				echo clawyer_get_svg( 'facebook' );
				echo clawyer_get_svg( 'twitter' );
				echo clawyer_get_svg( 'linkedin' );
				echo clawyer_get_svg( 'instagram' );
				echo clawyer_get_svg( 'pinterest' );
				echo clawyer_get_svg( 'tiktok' );
				echo clawyer_get_svg( 'youtube' );
				echo clawyer_get_svg( 'snapchat' );
				echo clawyer_get_svg( 'whatsapp' );
				echo clawyer_get_svg( 'reddit' );
			?>
		</div>
	</div>
<?php
get_footer();
