<?php
/**
 * Theme Customizer - Listing Archive
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer\Sections;

use RTFramework\Customize;
use RT\Clawyer\Helpers\Fns;
use RT\Clawyer\Api\Customizer;

/**
 * Customizer class
 */
class ListingArchive extends Customizer {

	protected string $section_listing_archive = 'clawyer_listing_archive_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_listing_archive,
			'title'       => __( 'Archive', 'clawyer' ),
			'description' => __( 'Clawyer Listing Section', 'clawyer' ),
			'priority'    => 1,
			'panel' => 'rt_listing_panel',
		] );

		Customize::add_controls( $this->section_listing_archive, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'clawyer_listing_archive_controls', [

			'rt_listing_archive_style' => [
				'type'      => 'image_select',
				'label'     => __( 'Choose Layout', 'clawyer' ),
				'default'   => '1',
				'choices'   => Fns::image_placeholder( 'listing-archive', 1)
			],

			'rt_listing_archive_column' => [
				'type'        => 'select',
				'label'       => __( 'Grid Column', 'clawyer' ),
				'description' => __( 'This option works only for large device', 'clawyer' ),
				'default'     => '3',
				'choices'     => [
					'default'   => __( 'Default From Theme', 'clawyer' ),
					'1' => __( '1 Column', 'clawyer' ),
					'2'  => __( '2 Column', 'clawyer' ),
					'3'  => __( '3 Column', 'clawyer' ),
					'4'  => __( '4 Column', 'clawyer' ),
				]
			],

		] );
	}

}
