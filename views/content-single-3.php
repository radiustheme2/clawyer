<?php
/**
 * Template part for displaying content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clawyer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$meta_list = clawyer_option( 'rt_single_meta', '', true );
$meta      = clawyer_option( 'rt_blog_above_cat_visibility' );
$meta      = clawyer_option( 'rt_single_above_meta_style' );
if ( clawyer_option( 'rt_single_above_cat_visibility' ) ) {
	$category_index = array_search( 'category', $meta_list );
	unset( $meta_list[ $category_index ] );
}

?>

<article data-post-id="<?php the_ID(); ?>" <?php post_class( clawyer_post_class() ); ?>>
	<div class="article-inner-wrapper">

		<div class="entry-wrapper">
			<?php if ( clawyer_option( 'rt_blog_content_visibility' ) ) : ?>
				<div class="entry-content">
					<?php clawyer_entry_content() ?>
				</div>
			<?php endif; ?>

			<?php clawyer_entry_footer(); ?>
		</div>
	</div>
</article>
