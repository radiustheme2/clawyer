<?php
/**
 * Theme Customizer - Header
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer\Sections;

use RT\Clawyer\Api\Customizer;
use RT\Clawyer\Helpers\Fns;
use RTFramework\Customize;

/**
 * Customizer class
 */
class Footer extends Customizer {
	protected string $section_footer = 'clawyer_footer_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_footer,
			'title'       => __( 'Footer', 'clawyer' ),
			'description' => __( 'Clawyer Footer Section', 'clawyer' ),
			'priority'    => 38
		] );

		Customize::add_controls( $this->section_footer, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_footer_controls', [

			'rt_footer_style' => [
				'type'    => 'image_select',
				'label'   => __( 'Choose Layout', 'clawyer' ),
				'default' => '1',
				'choices' => Fns::image_placeholder( 'footer', 1, 'jpg' )
			],

			'rt_footer_width' => [
				'type'    => 'select',
				'label'   => __( 'Footer Width', 'clawyer' ),
				'default' => '',
				'choices' => [
					''       => __( 'Box Width', 'clawyer' ),
					'-fluid' => __( 'Full Width', 'clawyer' ),
				]
			],

			'rt_footer_max_width' => [
				'type'        => 'number',
				'label'       => __( 'Footer Max Width (PX)', 'clawyer' ),
				'description' => __( 'Enter a number greater than 992.', 'clawyer' ),
				'condition'   => [ 'rt_footer_width', '==', '-fluid' ]
			],

			'rt_sticy_footer' => [
				'type'        => 'switch',
				'label'       => __( 'Sticky Footer', 'clawyer' ),
				'description' => __( 'Show footer at the top when scrolling down', 'clawyer' ),
			],

			'rt_footer_heading1' => [
				'type'  => 'heading',
				'label' => __( 'Footer Copyright Section', 'clawyer' ),
			],

			'rt_footer_copyright' => [
				'type'        => 'tinymce',
				'label'       => __( 'Footer Copyright Text', 'clawyer' ),
				'default'     => __( '©Copyright [y] Clawyer by <a href="https://radiustheme.com/">RadiusTheme</a>', 'clawyer' ),
				'description' => __( 'Add [y] flag anywhere for dynamic year.', 'clawyer' ),
			],
			'rt_footer_social' => [
				'type'        => 'switch',
				'label'       => __( 'Footer Social', 'clawyer' ),
				'description' => __( 'Show footer social beside at copyright text', 'clawyer' ),
			],
			'rt_footer_copyright_alignment' => [
				'type'    => 'select',
				'label'   => __( 'Copyright & Socials Alignment', 'clawyer' ),
				'default' => 'align-default',
				'choices' => [
					'align-default'          => __( 'Default from style', 'clawyer' ),
					'justify-content-start'  => __( 'Left', 'clawyer' ),
					'justify-content-center' => __( 'Center', 'clawyer' ),
					'justify-content-end'    => __( 'Right', 'clawyer' ),
					'justify-content-between'  => __( 'Between', 'clawyer' ),
					'justify-content-around'  => __( 'Around', 'clawyer' ),
				],
			],
			'rt_footer_menu' => [
				'type'        => 'switch',
				'label'       => __( 'Footer menu', 'clawyer' ),
				'description' => __( 'Show footer menu above at copyright', 'clawyer' ),
				'condition'   => [ 'rt_footer_style', '==', '2' ]
			],
			'rt_footer_menu_alignment' => [
				'type'    => 'select',
				'label'   => __( 'Footer Menu Alignment', 'clawyer' ),
				'default' => 'align-default',
				'choices' => [
					'align-default'          => __( 'Default from style', 'clawyer' ),
					'justify-content-start'  => __( 'Left', 'clawyer' ),
					'justify-content-center' => __( 'Center', 'clawyer' ),
					'justify-content-end'    => __( 'Right', 'clawyer' ),
				],
				'condition'   => [ 'rt_footer_style', '==', '2' ]
			],

		] );

	}

}
