<?php
/**
 * Theme Customizer - MyAccount
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer\Sections;

use RTFramework\Customize;
use RT\Clawyer\Api\Customizer;

/**
 * Customizer class
 */
class ListingMyAccount extends Customizer {

	protected string $section_listing_my_account_archive = 'clawyer_listing_my_account_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_listing_my_account_archive,
			'title'       => __( 'My Account', 'clawyer' ),
			'description' => __( 'Clawyer Listing My Account', 'clawyer' ),
			'priority'    => 3,
			'panel' => 'rt_listing_panel',
		]);

		Customize::add_controls( $this->section_listing_my_account_archive, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'clawyer_listing_my_account_controls', [

			'rt_my_account_bg_file' => [
				'type'    => 'text',
				'label'   => __( 'Background Video Link', 'clawyer' ),
			],

		] );
	}

}
