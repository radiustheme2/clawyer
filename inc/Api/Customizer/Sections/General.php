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
class General extends Customizer {
	protected string $section_general = 'clawyer_general_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_general,
			'title'       => __( 'General', 'clawyer' ),
			'description' => __( 'Clawyer General Section', 'clawyer' ),
			'priority'    => 20
		] );
		Customize::add_controls( $this->section_general, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_general_controls', [

			'rt_svg_enable' => [
				'type'  => 'switch',
				'label' => __( 'Enable SVG Upload', 'clawyer' ),
				'default' => 1,
			],

			'rt_preloader' => [
				'type'  => 'switch',
				'label' => __( 'Preloader', 'clawyer' ),
			],

			'rt_preloader_image' => [
				'type'         => 'image',
				'label'        => __( 'Preloader Image', 'clawyer' ),
				'description'  => __( 'Upload preloader animate image for your site.', 'clawyer' ),
				'button_label' => __( 'Upload', 'clawyer' ),
				'condition'    => [ 'rt_preloader' ]
			],

			'rt_back_to_top' => [
				'type'  => 'switch',
				'label' => __( 'Back to Top', 'clawyer' ),
			],

			'rt_remove_admin_bar' => [
				'type'        => 'switch',
				'label'       => __( 'Remove Admin Bar', 'clawyer' ),
				'description' => __( 'This option not work for administrator role.', 'clawyer' ),
			],

			'rt_social_icon_style' => [
				'type'    => 'select',
				'label'   => __( 'Social Icon Style', 'clawyer' ),
				'default' => '',
				'choices' => [
					''        => __( 'Default Icon', 'clawyer' ),
					'-square' => __( 'Square Icon', 'clawyer' ),
				]
			],

		] );

	}

}
