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

use RT\Clawyer\Options\Opt;

?>
<article data-post-id="<?php the_ID(); ?>" <?php post_class( clawyer_post_class() ); ?>>
	<div class="article-inner-wrapper">

		<?php if ( ! in_array( Opt::$single_style, [ '2', '3', '4' ] ) ) : ?>
			<?php clawyer_post_single_thumbnail(); ?>
		<?php endif; ?>

		<div class="entry-wrapper">
			<?php clawyer_single_entry_header(); ?>

			<div class="entry-content">
				<?php clawyer_entry_content() ?>
			</div>

			<?php clawyer_entry_footer(); ?>
		</div>
	</div>
</article>
