<?php

namespace RT\Clawyer\Setup;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clawyer\Traits\SingletonTraits;

class Setup {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'clawyer_i18n_textdomain' ], 20 );
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
		add_action( 'site_prealoader', [ $this, 'preloader' ] );

		add_action( 'woocommerce_checkout_before_customer_details', [$this, 'custom_checkout_columns_start'], 10 );
		add_action( 'woocommerce_checkout_after_customer_details', [$this, 'custom_checkout_columns_end'], 10 );

		add_filter( 'wp_img_tag_add_auto_sizes', function(){
			return false;
		} );

		add_filter( 'upload_mimes', [ $this, 'clawyer_mime_types' ] );
	}

	/**
	 * Theme Translation
	 * @return void
	 */
	public function clawyer_i18n_textdomain() {
		load_theme_textdomain( 'clawyer', get_template_directory() . '/languages' );
	}

	function custom_checkout_columns_start() {
		echo '<div class="custom-columns-wrapper" style="display: flex; gap: 20px;">';
	}

	function custom_checkout_columns_end() {
		echo '</div>';
	}

	/**
	 * Setup Theme
	 * @return void
	 */
	public function setup() {
		$this->add_theme_support();
		$this->add_image_size();
	}


	/**
	 * Add Image Size
	 * @return void
	 */
	private function add_image_size() {
		$sizes = [
			'clawyer-440-350' => [ 440, 350, true ],
			'clawyer-450-420' => [ 450, 420, true ],
			'clawyer-700-455' => [ 700, 455, true ],
		];

		$sizes = apply_filters( 'clawyer_image_size', $sizes );

		foreach ( $sizes as $size => $value ) {
			add_image_size( $size, $value[0], $value[1], $value[2] );
		}
	}

	/**
	 * Add Theme Support
	 * @return void
	 */
	private function add_theme_support() {
		/*
		 * Default Theme Support options better have
		 */
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'custom-logo' );
		add_theme_support( "custom-header" );
		add_theme_support( "custom-background" );

		/*
		 * Activate Post formats if you need
		 */
		add_theme_support( 'post-formats', [
			'aside',
			'gallery',
			'link',
			'image',
			'quote',
			'status',
			'video',
			'audio',
			'chat',
		] );
	}

	/*
		Define a max content width to allow WordPress to properly resize your images
	*/
	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'content_width', 1440 );
	}

	public function preloader() {
		$loading = wp_get_attachment_image( clawyer_option( 'rt_preloader_image' ), 'full' );
		echo '<div id="pageoverlay" class="pageoverlay"><span class="pageLoader">'. wp_kses_post( $loading ) . '</span></div>';
	}

	/**
	 * Enable svg upload
	 *
	 * @param $mimes
	 *
	 * @return mixed
	 */
	function clawyer_mime_types( $mimes ) {
		if ( ! clawyer_option( 'rt_svg_enable' ) ) {
			return $mimes;
		}
		$mimes['svg'] = 'image/svg+xml';

		return $mimes;
	}
}
