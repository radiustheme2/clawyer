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
class Labels extends Customizer {
	protected string $section_labels = 'clawyer_labels_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_labels,
			'title'       => __( 'Modify Static Text', 'clawyer' ),
			'description' => __( 'You can change all static text of the theme.', 'clawyer' ),
			'priority'    => 999
		] );
		Customize::add_controls( $this->section_labels, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'clawyer_labels_controls', [

			'rt_header_labels' => [
				'type'  => 'heading',
				'label' => __( 'Header Labels', 'clawyer' ),
			],

			'rt_login_signup_label' => [
				'type'        => 'text',
				'label'       => __( 'Login / Sign Up', 'clawyer' ),
				'default'     => __( 'My Account', 'clawyer' ),
				'description' => __( 'Context: Login Button', 'clawyer' ),
			],

			'rt_get_started_label' => [
				'type'        => 'text',
				'label'       => __( 'Get Started', 'clawyer' ),
				'default'     => __( 'Get Started', 'clawyer' ),
				'description' => __( 'Context: Menu Button', 'clawyer' ),
			],

			'rt_follow_us_label' => [
				'type'        => 'text',
				'label'       => __( 'Follow Us On:', 'clawyer' ),
				'default'     => __( 'Follow Us On:', 'clawyer' ),
				'description' => __( 'Context: Topbar icon label', 'clawyer' ),
			],

			'rt_blog_labels'          => [
				'type'  => 'heading',
				'label' => __( 'Blog Labels', 'clawyer' ),
			],
			'rt_author_prefix' => [
				'type'        => 'text',
				'label'       => __( 'By', 'clawyer' ),
				'default'     => 'by',
				'description' => __( 'Context: Meta Author Prefix', 'clawyer' ),
			],
			'rt_tags'                 => [
				'type'        => 'text',
				'label'       => __( 'Tags:', 'clawyer' ),
				'default'     => __( 'Tags:', 'clawyer' ),
				'description' => __( 'Context: Single blog footer tags label', 'clawyer' ),
			],
			'rt_share'                 => [
				'type'        => 'text',
				'label'       => __( 'Share:', 'clawyer' ),
				'default'     => __( 'Share:', 'clawyer' ),
				'description' => __( 'Context: Single blog footer share label', 'clawyer' ),
			],

		] );
	}

}
