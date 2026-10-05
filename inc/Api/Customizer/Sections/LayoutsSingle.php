<?php
/**
 * Theme Customizer - Header
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer\Sections;

use RT\Clawyer\Api\Customizer;
use RTFramework\Customize;
use RT\Clawyer\Traits\LayoutControlsTraits;

/**
 * Customizer class
 */
class LayoutsSingle extends Customizer {

	use LayoutControlsTraits;
	protected string $section_single_layout = 'clawyer_single_layout_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'    => $this->section_single_layout,
			'title' => __( 'Single Layout', 'clawyer' ),
			'panel' => 'rt_layouts_panel',
		] );
		Customize::add_controls( $this->section_single_layout, $this->get_controls() );
	}

	public function get_controls() {
		return $this->get_layout_controls( 'single_post' );
	}

}
