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
class LayoutsWooArchive extends Customizer {

	use LayoutControlsTraits;

	protected string $section_woocommerce_archive_layout = 'clawyer_woocommerce_archive_layout_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'    => $this->section_woocommerce_archive_layout,
			'title' => __( 'Woocommerce Archive', 'clawyer' ),
			'panel' => 'rt_layouts_panel',
		] );
		Customize::add_controls( $this->section_woocommerce_archive_layout, $this->get_controls() );
	}

	public function get_controls() {
		return $this->get_layout_controls( 'woo_archive' );
	}

}
