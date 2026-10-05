<?php
/**
 * Theme Customizer - Menu Typography
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer\Sections;

use RT\Clawyer\Api\Customizer;
use RTFramework\Customize;

/**
 * Customizer class
 */
class TypographyMenu extends Customizer {

	protected string $section_id = 'clawyer_menu_typo_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_id,
			'title'       => __( 'Menu Typography', 'clawyer' ),
			'description' => __( 'Clawyer Menu Typography Section', 'clawyer' ),
			'panel'       => 'rt_typography_panel',
			'priority'    => 3
		] );

		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_menu_typo_section', [

			'rt_menu_typo' => [
				'type'    => 'typography',
				'label'   => __( 'Menu Typography', 'clawyer' ),
				'default' => json_encode(
					[
						'font'          => 'Outfit',
						'regularweight' => '500',
						'size'          => '16',
						'lineheight'    => '22',
					]
				)
			],

		] );

	}
}
