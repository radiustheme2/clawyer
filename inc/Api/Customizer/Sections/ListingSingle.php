<?php
/**
 * Theme Customizer - Listing Single
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer\Sections;

use RTFramework\Customize;
use RT\Clawyer\Api\Customizer;

/**
 * Customizer class
 */
class ListingSingle extends Customizer {

	protected string $section_listing_single_archive = 'clawyer_listing_single_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_listing_single_archive,
			'title'       => __( 'Single', 'clawyer' ),
			'description' => __( 'Clawyer Listing Single Section', 'clawyer' ),
			'priority'    => 2,
			'panel' => 'rt_listing_panel',
		] );

		Customize::add_controls( $this->section_listing_single_archive, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'clawyer_listing_single_controls', [

			'rt_listing_single_style' => [
				'type'        => 'select',
				'label'       => __( 'Select Layout', 'clawyer' ),
				'placeholder' => __( 'Choose Layout', 'clawyer' ),
				'multiselect' => true,
				'default'     => '1',
				'choices'     => [
					'1' => __( 'Layout 1', 'clawyer' ),
					'2' => __( 'Layout 2', 'clawyer' ),
				],
			],
			'single_sidebar_listing_info' => [
				'type'        => 'select',
				'label'       => __( 'Select Info Type', 'clawyer' ),
				'placeholder' => __( 'Choose Info Type', 'clawyer' ),
				'multiselect' => true,
				'default'     => '1',
				'choices'     => [
					'listing_info' => esc_html__( 'Listing Information', 'clawyer' ),
					'listing_owner_info' => esc_html__( 'Listing Owner Information', 'clawyer' ),
				],
			],

			'rt_visibility' => [
				'type'  => 'heading',
				'label' => __( 'Visibility Section', 'clawyer' ),
			],

			'rt_listing_video_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Video Visibility', 'clawyer' ),
				'default' => 1,
				'desc' => esc_html__('If enable video display in content and videe hide form gallery.', 'clawyer'),
			],

			'rt_listing_video_title' => [
				'type'    => 'switch',
				'label'   => __( 'Video Title', 'clawyer' ),
				'default' => 'Video',
			],

			/* = Related Listing = */
			'rt_related' => [
				'type'  => 'heading',
				'label' => __( 'Related Listing', 'clawyer' ),
			],

			'rt_related_listing_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Related Listing Visibility', 'clawyer' ),
				'default' => 1
			],

			'rt_related_listing_type_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Type Visibility', 'clawyer' ),
				'default' => '',
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_author_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Author Visibility', 'clawyer' ),
				'default' => '',
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_location_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Location Visibility', 'clawyer' ),
				'default' => 1,
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_cat_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Category Visibility', 'clawyer' ),
				'default' => '',
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_time_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Time Visibility', 'clawyer' ),
				'default' => '',
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_views_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Views Visibility', 'clawyer' ),
				'default' => '',
				'condition' => [ 'rt_related_listing_visibility' ]
			],

			/* = Custom = */
			'rt_related_listing_address_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Address Visibility', 'clawyer' ),
				'default' => 1,
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_phone_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Phone Visibility', 'clawyer' ),
				'default' => 1,
				'condition' => [ 'rt_related_listing_visibility' ]
			],
			'rt_related_listing_rating_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Rating Visibility', 'clawyer' ),
				'default' => 1,
				'condition' => [ 'rt_related_listing_visibility' ]
			],

		] );
	}

}
