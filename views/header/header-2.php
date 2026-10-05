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

$logo_h1      = ! is_singular( [ 'post' ] );
$menu_classes = clawyer_option( 'rt_menu_alignment' );

?>

	<div class="main-header-section header-layout-2">
		<div class="header-container rt-container<?php echo esc_attr( clawyer_option( 'rt_header_width' ) ) ?>">

			<div class="row align-middle m-0 menu-items-row">

				<div class="site-branding pr-15">
					<?php echo clawyer_site_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div><!-- .site-branding -->

				<nav class="clawyer-navigation pl-15 pr-15 <?php echo esc_attr( $menu_classes ) ?>" role="navigation">
					<?php
					wp_nav_menu( [
						'theme_location' => 'primary',
						'menu_class'     => 'clawyer-navbar',
						'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'fallback_cb'    => 'clawyer_custom_menu_cb',
						'walker'         => has_nav_menu( 'primary' ) ? new RT\Clawyer\Core\WalkerNav() : '',
					] );
					?>
				</nav><!-- .clawyer-navigation -->

				<?php clawyer_menu_icons_group(); ?>

			</div><!-- .row -->

		</div><!-- .container -->
	</div>
<?php

