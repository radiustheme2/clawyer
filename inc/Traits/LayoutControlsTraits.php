<?php
/**
 * LayoutControls
 */

namespace RT\Clawyer\Traits;

// Do not allow directly accessing this file.
use RT\Clawyer\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

trait LayoutControlsTraits {
	public function get_layout_controls( $prefix = '' ) {

		$_left_text  = __( 'Left Sidebar', 'clawyer' );
		$_right_text = __( 'Right Sidebar', 'clawyer' );
		$left_text   = $_left_text;
		$right_text  = $_right_text;
		$image_left  = 'sidebar-left.png';
		$image_right = 'sidebar-right.png';

		if ( is_rtl() ) {
			$left_text   = $_right_text;
			$right_text  = $_left_text;
			$image_left  = 'sidebar-right.png';
			$image_right = 'sidebar-left.png';
		}

		return apply_filters( "clawyer_{$prefix}_layout_controls", [

			$prefix . '_layout' => [
				'type'    => 'image_select',
				'label'   => __( 'Choose Layout', 'clawyer' ),
				'default' => 'left-sidebar',
				'choices' => [
					'left-sidebar'  => [
						'image' => clawyer_get_img( $image_left ),
						'name'  => $left_text,
					],
					'full-width'    => [
						'image' => clawyer_get_img( 'sidebar-full.png' ),
						'name'  => __( 'Full Width', 'clawyer' ),
					],
					'right-sidebar' => [
						'image' => clawyer_get_img( $image_right ),
						'name'  => $right_text,
					],
				]
			],

			$prefix . '_sidebar' => [
				'type'    => 'select',
				'label'   => __( 'Choose a Sidebar', 'clawyer' ),
				'default' => 'default',
				'choices' => [
					'default'                        => __( '--Default--', 'clawyer' ),
					'rt-sidebar'                     => __( 'Main Sidebar', 'clawyer' ),
					'rt-single-sidebar'              => __( 'Single Sidebar', 'clawyer' ),
					'rt-footer-sidebar'              => __( 'footer Sidebar', 'clawyer' ),
					'rtcl-archive-sidebar'           => __( 'Listing Archive Sidebar', 'clawyer' ),
					'rtcl-single-sidebar'            => __( 'Listing Single Sidebar', 'clawyer' ),
					'rt-listing-map-archive-sidebar' => __( 'Listing Map Sidenar', 'clawyer' ),
				],
				//'choices' => Fns::sidebar_lists()
			],

			$prefix . '_header_heading' => [
				'type'  => 'heading',
				'label' => __( 'Header Settings', 'clawyer' ),
			],

			$prefix . '_header_style' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Header Layout', 'clawyer' ),
				'choices' => [
					'default' => __( '--Default--', 'clawyer' ),
					'1'       => __( 'Layout 1', 'clawyer' ),
					'2'       => __( 'Layout 2', 'clawyer' ),
					'3'       => __( 'Layout 3', 'clawyer' ),
				],
			],

			$prefix . '_top_bar' => [
				'type'    => 'select',
				'label'   => __( 'Top Bar', 'clawyer' ),
				'default' => 'default',
				'choices' => [
					'default' => __( '--Default--', 'clawyer' ),
					'on'      => __( 'On', 'clawyer' ),
					'off'     => __( 'Off', 'clawyer' ),
				]
			],

			$prefix . '_banner_heading' => [
				'type'  => 'heading',
				'label' => __( 'Banner Settings', 'clawyer' ),
			],

			$prefix . '_banner' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Visibility', 'clawyer' ),
				'choices' => [
					'default' => __( '--Default--', 'clawyer' ),
					'on'      => __( 'On', 'clawyer' ),
					'off'     => __( 'Off', 'clawyer' ),
				],
			],

			$prefix . '_banner_style' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Layout', 'clawyer' ),
				'choices' => [
					'default' => __( '--Default--', 'clawyer' ),
					'1'       => __( 'Layout 1', 'clawyer' ),
					'2'       => __( 'Layout 2', 'clawyer' ),
				],
			],

			$prefix . '_breadcrumb' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Content (Breadcrumb) Visibility', 'clawyer' ),
				'choices' => [
					'default' => __( '--Default--', 'clawyer' ),
					'on'      => __( 'On', 'clawyer' ),
					'off'     => __( 'Off', 'clawyer' ),
				],
			],

			$prefix . '_banner_image' => [
				'type'         => 'image',
				'label'        => __( 'Banner Image', 'clawyer' ),
				'description'  => __( 'Upload Banner Image', 'clawyer' ),
				'button_label' => __( 'Banner Image', 'clawyer' ),
			],

			$prefix . '_footer_heading' => [
				'type'  => 'heading',
				'label' => __( 'Footer Settings', 'clawyer' ),
			],

			$prefix . '_footer_style'  => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Footer Layout', 'clawyer' ),
				'choices' => [
					'default' => __( '--Default--', 'clawyer' ),
					'1'       => __( 'Layout 1', 'clawyer' ),
					'2'       => __( 'Layout 2', 'clawyer' ),
				],
			],
		] );
	}
}
