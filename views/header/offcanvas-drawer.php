<?php
/**
 * Template part for displaying header offcanvas
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clawyer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clawyer\Options\Opt;

?>

<div class="clawyer-offcanvas-drawer">
	<div class="offcanvas-header">
		<div class="site-branding pr-15">
			<?php echo clawyer_offcanvas_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div><!-- .site-branding -->
		<a class="menu-bar trigger-off-canvas" href="#"><i class="icon-cancel"></i></a>
	</div>
	<nav class="offcanvas-navigation" role="navigation">
		<?php
		if ( has_nav_menu( 'primary' ) ) :
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'walker'         => new RT\Clawyer\Core\WalkerNav(),
				)
			);
		endif;
		?>
	</nav><!-- .clawyer-navigation -->
	<?php if(! Opt::$has_top_bar) { ?>
	<div class="header-top-info">
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
		<ul class="topbar-right">
			<li class="social-label"><?php echo esc_html( clawyer_option( 'rt_follow_us_label' ) ) ?></li>
			<li class="social-icon">
				<?php clawyer_get_social_html( '#555' ); ?>
			</li>
		</ul>
	</div>
	<?php } ?>
</div><!-- .container -->

<div class="clawyer-body-overlay"></div>
