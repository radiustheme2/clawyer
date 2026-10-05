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
class ColorBanner extends Customizer {

	protected string $section_banner_color = 'clawyer_banner_color_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_banner_color,
			'panel'       => 'rt_color_panel',
			'title'       => __( 'Banner / Breadcrumb Colors', 'clawyer' ),
			'description' => __( 'Clawyer Banner Color Section', 'clawyer' ),
			'priority'    => 6
		] );

		Customize::add_controls( $this->section_banner_color, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_site_color_controls', [

			'rt_banner_bg' => [
				'type'    => 'color',
				'label'   => __( 'Banner Background', 'clawyer' ),
			],

			'rt_breadcrumb_color' => [
				'type'    => 'color',
				'label'   => __( 'Link Color', 'clawyer' ),
			],

			'rt_breadcrumb_active' => [
				'type'    => 'color',
				'label'   => __( 'Link Active Color', 'clawyer' ),
			],

		] );
	}
}
