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
class Contact extends Customizer {
	protected string $section_contact = 'clawyer_contact_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_contact,
			'panel'       => 'rt_contact_social_panel',
			'title'       => __( 'Contact Information', 'clawyer' ),
			'description' => __( 'Clawyer Contact Address Section', 'clawyer' ),
			'priority'    => 1
		] );
		Customize::add_controls( $this->section_contact, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_contact_controls', [

			'rt_phone' => [
				'type'  => 'text',
				'label' => __( 'Phone', 'clawyer' ),
			],

			'rt_email' => [
				'type'  => 'text',
				'label' => __( 'Email', 'clawyer' ),
			],

			'rt_website' => [
				'type'  => 'text',
				'label' => __( 'Website', 'clawyer' ),
			],

			'rt_contact_address' => [
				'type'        => 'textarea',
				'label'       => __( 'Address', 'clawyer' ),
				'description' => __( 'Enter company address here.', 'clawyer' ),
			],

		] );
	}
}
