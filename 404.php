<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package clawyer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>
<div class="error-template-content-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div id="primary" class="content-area">
					<main id="main" class="site-main error-404" role="main">

						<?php
							if ( ! empty( clawyer_option( 'rt_error_image' ) ) ) {
								echo wp_get_attachment_image( clawyer_option( 'rt_error_image' ), 'full', true );
							} else {
								clawyer_get_img( '404.png', true, 'width="1167" height="722"' );
							}
						?>

						<div class="error-info">
							<h2 class="error-title"><?php echo wp_kses_post( clawyer_option( 'rt_error_heading' ) ); ?></h2>
							<p><?php echo wp_kses_post( clawyer_option( 'rt_error_text' ) ); ?></p>
							<div class="clawyer-btn">
								<a class="button-style-2" href="<?php echo esc_url( home_url() ) ?>">
                                    <?php echo esc_html( clawyer_option( 'rt_error_button_text' ) ); ?>
                                    <i aria-hidden="true" class=" icon-long-arrow-1"></i>
                                </a>
							</div>
						</div>
					</main><!-- #main -->
				</div><!-- #primary -->
			</div><!-- .col- -->
		</div><!-- .row -->
	</div><!-- .container -->
</div>
<?php
get_footer();
