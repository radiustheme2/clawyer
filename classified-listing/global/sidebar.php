<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package clawyer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RT\Clawyer\Options\Opt;

if ( ( Functions::is_listings() || Functions::is_listing_taxonomy() ) && is_active_sidebar( Opt::$sidebar ) ) {
	if ( is_active_sidebar( Opt::$sidebar ) ) {
		clawyer_sidebar( Opt::$sidebar );
	} else {
		clawyer_sidebar('rtcl-archive-sidebar' );
	}
} else if ( Functions::is_listing() && is_active_sidebar( Opt::$sidebar ) ) {
	if ( is_active_sidebar( Opt::$sidebar ) ) {
		clawyer_sidebar( Opt::$sidebar );
	} else {
		clawyer_sidebar('rtcl-single-sidebar' );
	}
} else {

}
