<?php
/**
 * @author  RadiusTheme
 *
 * Locationbox style.
 *
 * @package  Classifid-listing
 * @since   2.0.10
 * @version 1.0
 * @var $settings        string
 * @var $count  string
 * @var $permalink      string
 * @var $title      string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Custom text after count (new toolkit setting, falls back to the legacy one).
$count_text = '';
if ( ! empty( $settings['display_text_after_count'] ) ) {
	$count_text = $settings['display_text_after_count'];
} elseif ( ! empty( $settings['count_text'] ) ) {
	$count_text = $settings['count_text'];
}

if ( $count_text ) {
	$count_html = number_format_i18n( $count ) . ' ' . $count_text;
} else {
	/* translators: %s: Number of listings. */
	$count_html = sprintf( _nx( '%s listing', '%s listings', $count, 'Number of Listing Services', 'clawyer' ), number_format_i18n( $count ) );
}

$link_start   = $settings['enable_link'] ? '<a href="' . $permalink . '">' : '';
$link_end     = $settings['enable_link'] ? '</a>' : '';
$location_box = $settings['rtcl_location_style'] ? $settings['rtcl_location_style'] : ' style-1';
$class        = $settings['display_count'] ? ' rtin-has-count ' : '';
$class       .= ' location-box-' . $location_box;

?>

<div class="rtcl-el-listing-location-box location-box-pro <?php echo esc_attr( $class ); ?>">
	<div class="rtcl-image-wrapper">
		<?php echo wp_kses_post( $link_start ); ?>
		<div class="rtin-img"></div>
		<?php echo wp_kses_post( $link_end ); ?>
	</div>

	<div class="rtin-content">
		<?php if ( $settings['display_count'] ) : ?>
            <div class="rtin-counter">
				<?php echo esc_html( $count_html ); ?>
            </div>
		<?php endif; ?>
		<h3 class="rtin-title">
			<?php
                if ( $settings['enable_link'] ) {
                    ?>
                        <a href="<?php echo esc_url( $permalink ); ?>">
                            <?php echo esc_html( $title ); ?>
                        </a>
                        <?php
                } else {
                    echo esc_html( $title );
                }
			?>
		</h3>
		<?php if ( $settings['enable_link'] && !empty( $icon ) ) { ?>
			<a href="<?php echo esc_url( $permalink ); ?>">
				<?php echo wp_kses_post( $icon ); ?>
			</a>
		<?php } ?>
	</div>
</div>
