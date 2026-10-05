<?php
/**
 * Theme Customizer - Header
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer\Sections;

use RTFramework\Customize;
use RT\Clawyer\Helpers\Fns;
use RT\Clawyer\Api\Customizer;
use RT\Clawyer\Traits\LayoutControlsTraits;

/**
 * Customizer class
 */
class LayoutsListingSingle extends Customizer {

	use LayoutControlsTraits;

	protected string $section_page_layout = 'clawyer_listing_single_layout_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'    => $this->section_page_layout,
			'title' => __( 'Listing Single Layout', 'clawyer' ),
			'panel' => 'rt_layouts_panel',
		] );

		Customize::add_controls( $this->section_page_layout, $this->get_controls() );
	}

	public function get_controls() {
		$prefix = 'listing_single';
		return apply_filters( "clawyer_{$prefix}_layout_controls", [

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
