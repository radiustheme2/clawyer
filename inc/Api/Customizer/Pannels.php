<?php
/**
 * Theme Customizer Pannels
 *
 * @package clawyer
 */

namespace RT\Clawyer\Api\Customizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clawyer\Traits\SingletonTraits;
use RTFramework\Customize;

/**
 * Customizer class
 */
class Pannels {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'add_panels' ] );
	}

	/**
	 * Add Panels
	 * @return void
	 */
	public function add_panels() {
		Customize::add_panels(
			[
				[
					'id'          => 'rt_header_panel',
					'title'       => esc_html__( 'Header - Topbar - Menu', 'clawyer' ),
					'description' => esc_html__( 'Clawyer Header', 'clawyer' ),
					'priority'    => 22,
				],
				[
					'id'          => 'rt_typography_panel',
					'title'       => esc_html__( 'Typography', 'clawyer' ),
					'description' => esc_html__( 'Clawyer Typography', 'clawyer' ),
					'priority'    => 24,
				],
				[
					'id'          => 'rt_color_panel',
					'title'       => esc_html__( 'Colors', 'clawyer' ),
					'description' => esc_html__( 'Clawyer Color Settings', 'clawyer' ),
					'priority'    => 28,
				],
				[
					'id'          => 'rt_layouts_panel',
					'title'       => esc_html__( 'Layout Settings', 'clawyer' ),
					'description' => esc_html__( 'Clawyer Layout Settings', 'clawyer' ),
					'priority'    => 34,
				],
				[
					'id'          => 'rt_listing_panel',
					'title'       => esc_html__( 'Listing Settings', 'clawyer' ),
					'description' => esc_html__( 'Clawyer Listing Settings', 'clawyer' ),
					'priority'    => 35,
				],
				[
					'id'          => 'rt_contact_social_panel',
					'title'       => esc_html__( 'Contact & Socials', 'clawyer' ),
					'description' => esc_html__( 'Clawyer Contact & Socials', 'clawyer' ),
					'priority'    => 24,
				],

			]
		);
	}

}
