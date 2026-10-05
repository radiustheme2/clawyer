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

use RT\Clawyer\Options\Opt;

if ( is_singular() && is_active_sidebar( Opt::$sidebar ) ) {
	clawyer_sidebar( Opt::$sidebar );
} else {
	clawyer_sidebar( 'rt-sidebar' );
}