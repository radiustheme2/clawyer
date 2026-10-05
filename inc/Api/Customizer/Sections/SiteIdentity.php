<?php
/**
 * Theme Customizer - Header
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer\Sections;

use RT\Clawyer\Api\Customizer;
use RTFramework\Customize;

/**
 * Customizer class
 */
class SiteIdentity extends Customizer {

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_controls( 'title_tagline', $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_title_tagline_controls', [

			'rt_logo' => [
				'type'         => 'image',
				'label'        => __( 'Light Logo', 'clawyer' ),
				'description'  => __( 'Upload main logo for your site.', 'clawyer' ),
				'button_label' => __( 'Logo', 'clawyer' ),
			],

			'rt_logo_second' => [
				'type'         => 'image',
				'label'        => __( 'Dark Logo', 'clawyer' ),
				'description'  => __( 'Upload Secondary logo for your site. It should a dark logo', 'clawyer' ),
				'button_label' => __( 'Second Logo', 'clawyer' ),
			],

			'rt_logo_mobile' => [
				'type'         => 'image',
				'label'        => __( 'Mobile Logo', 'clawyer' ),
				'description'  => __( 'Upload, if you need a different logo for mobile device..', 'clawyer' ),
				'button_label' => __( 'Mobile Logo', 'clawyer' ),
			],

			'rt_logo_offcanvas' => [
				'type'         => 'image',
				'label'        => __( 'Offcanvas Logo', 'clawyer' ),
				'description'  => __( 'Upload, if you need a different logo for offcanvas slide menu.', 'clawyer' ),
				'button_label' => __( 'Offcanvas Logo', 'clawyer' ),
			],

			'rt_logo_width_height' => [
				'type'      => 'text',
				'label'     => __( 'Logo Dimension', 'clawyer' ),
				'description'     => __( 'Enter the width and height value separate by comma (,). Eg. 180px,45px', 'clawyer' ),
				'transport' => '',
			],

		] );

	}

}
