<?php
/**
 * Build Gutenberg Blocks
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clawyer\Traits\SingletonTraits;

/**
 * Customizer class
 */
class Gutenberg {
	use SingletonTraits;

	/**
	 * Register default hooks and actions for WordPress
	 *
	 * @return WordPress add_action()
	 */
	public function __construct() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		add_action( 'init', [ $this, 'gutenberg_init' ] );

	}

	/**
	 * Custom Gutenberg settings
	 * @return
	 */
	public function gutenberg_init() {
		add_theme_support( 'gutenberg', [
			// Theme supports responsive video embeds
			'responsive-embeds' => true,
			// Theme supports wide images, galleries and videos.
			'wide-images'       => true,
		] );

		add_theme_support( 'editor-color-palette', [
			[
				'name'  => __( 'Primary', 'clawyer' ),
				'slug'  => 'clawyer-primary',
				'color' => '#2d68ff',
			],
			[
				'name'  => __( 'White', 'clawyer' ),
				'slug'  => 'clawyer-white',
				'color' => '#ffffff',
			],
			[
				'name'  => __( 'Black', 'clawyer' ),
				'slug'  => 'clawyer-black',
				'color' => '#333333',
			],
			[
				'name'  => __( 'Gold', 'clawyer' ),
				'slug'  => 'clawyer-gold',
				'color' => '#FCBB6D',
			],
			[
				'name'  => __( 'Pink (Primary)', 'clawyer' ),
				'slug'  => 'clawyer-pink',
				'color' => '#f80a0a',
			],
			[
				'name'  => __( 'clawyer-grey', 'clawyer' ),
				'slug'  => 'grey',
				'color' => '#b8c2cc',
			],
		] );
	}
}
