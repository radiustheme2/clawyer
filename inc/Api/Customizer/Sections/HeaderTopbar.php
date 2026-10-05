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
class HeaderTopbar extends Customizer {
	protected string $section_topbar = 'clawyer_topbar_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_topbar,
			'panel'       => 'rt_header_panel',
			'title'       => __( 'Header Topbar', 'clawyer' ),
			'description' => __( 'Clawyer Topbar Section', 'clawyer' ),
			'priority'    => 1
		] );

		Customize::add_controls( $this->section_topbar, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_topbar_controls', [

			'rt_top_bar' => [
				'type'      => 'switch',
				'label'     => __( 'Topbar Visibility', 'clawyer' ),
				'default'   => '',
				'edit-link' => '.topbar-row',
			],

			'rt_topbar_style' => [
				'type'      => 'image_select',
				'label'     => __( 'Topbar Style', 'clawyer' ),
				'default'   => '1',
				'choices'   => Fns::image_placeholder( 'topbar', '1', 'jpg' ),
				'condition' => [ 'top_bar' ]
			],

			'rt_topbar_address' => [
				'type'    => 'switch',
				'label'   => __( 'Topbar Address ?', 'clawyer' ),
				'default' => 1,
				'condition' => [ 'rt_top_bar' ]
			],
			'rt_topbar_phone' => [
				'type'    => 'switch',
				'label'   => __( 'Topbar Phone ?', 'clawyer' ),
				'default' => 1,
				'condition' => [ 'rt_top_bar' ]
			],
			'rt_topbar_email' => [
				'type'    => 'switch',
				'label'   => __( 'Topbar Email ?', 'clawyer' ),
				'default' => 1,
				'condition' => [ 'rt_top_bar' ]
			],

		] );

	}

}
