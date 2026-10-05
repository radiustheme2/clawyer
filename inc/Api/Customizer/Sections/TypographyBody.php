<?php
/**
 * Theme Customizer - Body Typography
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer\Sections;

use RT\Clawyer\Api\Customizer;
use RTFramework\Customize;

/**
 * Customizer class
 */
class TypographyBody extends Customizer {

	protected string $section_id = 'clawyer_body_typo_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_id,
			'title'       => __( 'Body Typography', 'clawyer' ),
			'description' => __( 'Clawyer Body Typography Section', 'clawyer' ),
			'panel'       => 'rt_typography_panel',
			'priority'    => 1
		] );
		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'clawyer_body_typo_section', [
			'rt_body_typo' => [
				'type'    => 'typography',
				'label'   => __( 'Body Typography', 'clawyer' ),
				'default' => json_encode(
					[
						'font'          => 'Outfit',
						'regularweight' => 'normal',
						'size'          => '16',
						'lineheight'    => '26',
					]
				)
			],
		] );
	}
}
