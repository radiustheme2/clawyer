<?php
/**
 * @package Saervlisting/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clawyer\Plugins\Listing_Functions;

$style = clawyer_option('rt_listing_archive_style');

Listing_Functions::get_custom_listing_template( 'archive/grid/grid-'.$style );

?>
