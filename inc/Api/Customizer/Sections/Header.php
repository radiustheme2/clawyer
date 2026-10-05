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
class Header extends Customizer {
	protected string $section_header = 'clawyer_header_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_header,
			'panel'       => 'rt_header_panel',
			'title'       => __( 'Header Menu', 'clawyer' ),
			'description' => __( 'Clawyer Header Section', 'clawyer' ),
			'priority'    => 2,
			'edit-point'  => ''
		] );
		Customize::add_controls( $this->section_header, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_header_controls', [

			'rt_header_style' => [
				'type'      => 'image_select',
				'label'     => __( 'Choose Layout', 'clawyer' ),
				'default'   => '1',
				'edit-link' => '.site-branding',
				'choices'   => Fns::image_placeholder( 'header', 1)
			],

			'rt_menu_alignment' => [
				'type'    => 'select',
				'label'   => __( 'Menu Alignment', 'clawyer' ),
				'default' => '',
				'choices' => [
					''                       => __( 'Menu Alignment', 'clawyer' ),
					'justify-content-start'  => __( 'Left Alignment', 'clawyer' ),
					'justify-content-center' => __( 'Center Alignment', 'clawyer' ),
					'justify-content-end'    => __( 'Right Alignment', 'clawyer' ),
				]
			],

			'rt_header_width' => [
				'type'    => 'select',
				'label'   => __( 'Header Width', 'clawyer' ),
				'default' => '',
				'choices' => [
					''       => __( 'Box Width', 'clawyer' ),
					'-fluid' => __( 'Full Width', 'clawyer' ),
				]
			],

			'rt_header_max_width' => [
				'type'        => 'number',
				'label'       => __( 'Header Max Width (PX)', 'clawyer' ),
				'description' => __( 'Enter a number greater than 1440. Remove value for 100%', 'clawyer' ),
				'condition'   => [ 'rt_header_width', '==', '-fluid' ]
			],

			'rt_sticy_header' => [
				'type'        => 'switch',
				'label'       => __( 'Sticky Header', 'clawyer' ),
				'description' => __( 'Show header at the top when scrolling down', 'clawyer' ),
			],

			'rt_tr_header' => [
				'type'  => 'switch',
				'label' => __( 'Transparent Header', 'clawyer' ),
			],

			'rt_tr_header_color' => [
				'type'    => 'select',
				'label'   => __( 'Transparent color', 'clawyer' ),
				'default' => 'tr_header_light',
				'choices' => [
					'tr-header-light'       => __( 'Light Color', 'clawyer' ),
					'tr-header-dark' => __( 'Dark Color', 'clawyer' ),
				],
				'condition' => [ 'rt_tr_header' ]
			],

			'rt_tr_header_shadow' => [
				'type'  => 'switch',
				'label' => __( 'Header Dark Shadow', 'clawyer' ),
			],

			'rt_header_border' => [
				'type'    => 'switch',
				'label'   => __( 'Header Border', 'clawyer' ),
				'default' => 1
			],
			'rt_header_sep1'   => [
				'type' => 'separator',
				'edit-link' => '.menu-icon-wrapper',
			],

			'rt_header_login_button' => [
				'type'    => 'switch',
				'label'   => __( 'User Login ?', 'clawyer' ),
				'default' => '',
			],

			'rt_header_login_link' => [
				'type'    => 'text',
				'label'   => __( 'Login/Register Link', 'clawyer' ),
				'condition' => [ 'rt_header_login_button' ]
			],

			'rt_header_search' => [
				'type'    => 'switch',
				'label'   => __( 'Search Icon ?', 'clawyer' ),
				'default' => '',
			],

			'rt_header_bar' => [
				'type'        => 'switch',
				'label'       => __( 'Hamburger Menu', 'clawyer' ),
				'description' => __( 'It will be hide only for desktop.', 'clawyer' ),
				'default'     => '',
			],

			'rt_header_separator' => [
				'type'    => 'switch',
				'label'   => __( 'Icon Separator', 'clawyer' ),
				'default' => 1,
			],

			'rt_header_sep2' => [
				'type' => 'separator',
			],

			'rt_get_started_button' => [
				'type'    => 'switch',
				'label'   => __( 'Get Started Button ?', 'clawyer' ),
				'default' => ''
			],

			'rt_get_started_button_url' => [
				'type'    => 'text',
				'label'   => __( 'Button Link', 'clawyer' ),
				'condition' => [ 'rt_get_started_button' ]
			],

		] );

	}

}
