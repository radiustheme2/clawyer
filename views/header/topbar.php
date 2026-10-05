<?php
/**
 * Template part for displaying header
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clawyer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clawyer\Options\Opt;

if(! Opt::$has_top_bar) {
	return;
}

?>

<div class="clawyer-topbar">
	<div class="topbar-container rt-container<?php echo esc_attr( clawyer_option( 'rt_header_width' ) ) ?>">
		<div class="topbar-row d-flex align-items-center justify-content-between">
			<ul class="topbar-left d-flex gap-15 align-items-start">
				<?php if( !empty(clawyer_option( 'rt_contact_address' )) && clawyer_option( 'rt_topbar_address' ) ) { ?>
					<li class="site-address">
						<i class="icon-location-1"></i>
						<?php echo wp_kses( clawyer_option( 'rt_contact_address' ) , 'allowed_html' );?>
					</li>
				<?php } if( !empty(clawyer_option( 'rt_phone' )) &&  clawyer_option( 'rt_topbar_phone' ) ) { ?>
				<li class="site-phone">
					<i class="icon-icon-phone"></i>
					<a href="tel:<?php echo esc_attr( clawyer_option( 'rt_phone' ) );?>"><?php echo wp_kses( clawyer_option( 'rt_phone' ) , 'allowed_html' );?></a>
				</li>
				<?php } if( !empty(clawyer_option( 'rt_email' )) &&  clawyer_option( 'rt_topbar_email' ) ) { ?>
				<li class="site-email">
					<i class="icon-mi_email"></i>
					<a href="mailto:<?php echo esc_attr( clawyer_option( 'rt_email' ) );?>"><?php echo wp_kses( clawyer_option( 'rt_email' ) , 'allowed_html' );?></a>
				</li>
				<?php } ?>
			</ul>
			<ul class="topbar-right d-flex gap-15 align-items-center">
				<li class="social-icon">
					<label><?php echo esc_html( clawyer_option( 'rt_follow_us_label' ) ) ?></label>
					<?php clawyer_get_social_html( '#555' ); ?>
				</li>
			</ul>
		</div>
	</div>
</div>
