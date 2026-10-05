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
class Error extends Customizer {
	protected $section_labels = 'clawyer_404_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_labels,
			'title'       => __( 'Error Page', 'clawyer' ),
			'description' => __( 'Clawyer error section.', 'clawyer' ),
			'priority'    => 39
		] );
		Customize::add_controls( $this->section_labels, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_labels_controls', [

			'rt_error_image' => [
				'type'         => 'image',
				'label'        => __( 'Error Image', 'clawyer' ),
				'description'  => __( 'Upload error image for your site.', 'clawyer' ),
				'button_label' => __( 'Error image', 'clawyer' ),
			],

			'rt_error_heading' => [
				'type'        => 'text',
				'label'       => __( 'Error Heading', 'clawyer' ),
				'default'     => __( 'Oops, something went wrong.', 'clawyer' ),
			],

			'rt_error_text' => [
				'type'        => 'text',
				'label'       => __( 'Error Text', 'clawyer' ),
				'default'     => __( 'Sorry! This Page Is Not Available!', 'clawyer' ),
			],

			'rt_error_button_text' => [
				'type'        => 'text',
				'label'       => __( 'Error Button Text', 'clawyer' ),
				'default'     => __( 'Back To Home Page', 'clawyer' ),
			],

		] );
	}
}
