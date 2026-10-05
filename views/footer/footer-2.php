<?php
/**
 * Template part for displaying footer
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clawyer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$footer_container = 'container' . clawyer_option( 'rt_footer_width' );

?>

<?php if ( clawyer_option('rt_footer_menu') && has_nav_menu( 'footer' ) ) : ?>
	<div class="footer-menu-wrapper">
		<div class="footer-container <?php echo esc_attr( $footer_container ) ?>">
			<div class="row">

				<?php clawyer_scroll_top(); ?>

				<nav id="footer-menu" class="clawyer-navigation col-md-12 <?php echo esc_attr( clawyer_option( 'rt_footer_menu_alignment' ) ) ?>" role="navigation">
					<?php
					wp_nav_menu( [
						'theme_location' => 'footer',
						'menu_class'     => 'clawyer-navbar',
						'items_wrap'     => '<ul id="%1$s" class="%2$s clawyer-footer-menu">%3$s</ul>',
						'fallback_cb'    => 'clawyer_custom_menu_cb',
						'walker'         => has_nav_menu( 'footer' ) ? new RT\Clawyer\Core\WalkerNav() : '',
					] );
					?>
				</nav><!-- .footer-navigation -->
			</div>
		</div>
	</div><!-- .footer-fop -->
<?php endif; ?>

<?php if ( ! empty( clawyer_option( 'rt_footer_copyright' ) ) ) : ?>
	<div class="footer-copyright-wrapper">
		<div class="footer-container <?php echo esc_attr( $footer_container ) ?>">
			<div class="row align-items-center">
				<div class="col-md-6">
					<div class="footer-copyright-logo text-left">
						<?php echo clawyer_footer_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="copyright-text text-right">
						<?php echo clawyer_html( str_replace( '[y]', gmdate( 'Y' ), clawyer_option( 'rt_footer_copyright' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</div>
		</div>

	</div>
<?php endif; ?>
