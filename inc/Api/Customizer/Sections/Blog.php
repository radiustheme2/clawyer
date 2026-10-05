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
class Blog extends Customizer {

	protected string $section_blog = 'clawyer_blog_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_blog,
			'title'       => __( 'Blog Archive', 'clawyer' ),
			'description' => __( 'Clawyer Blog Section', 'clawyer' ),
			'priority'    => 25
		] );

		Customize::add_controls( $this->section_blog, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'clawyer_blog_controls', [

			'rt_blog_style' => [
				'type'        => 'select',
				'label'       => __( 'Blog Style', 'clawyer' ),
				'description' => __( 'This option works only for large device', 'clawyer' ),
				'default'     => 'default',
				'choices'     => [
					'default' => __( 'Default From Theme', 'clawyer' ),
					'list-1'    => __( 'List', 'clawyer' ),
					'grid-1'    => __( 'Grid', 'clawyer' ),
				]
			],

			'rt_blog_column' => [
				'type'        => 'select',
				'label'       => __( 'Grid Column', 'clawyer' ),
				'description' => __( 'This option works only for large device', 'clawyer' ),
				'default'     => 'default',
				'choices'     => [
					'default'   => __( 'Default From Theme', 'clawyer' ),
					'col-lg-12' => __( '1 Column', 'clawyer' ),
					'col-lg-6'  => __( '2 Column', 'clawyer' ),
					'col-lg-4'  => __( '3 Column', 'clawyer' ),
					'col-lg-3'  => __( '4 Column', 'clawyer' ),
				]
			],

			'rt_blog_image_size' => [
				'type'    => 'select',
				'label'   => __( 'Blog Image Size', 'clawyer' ),
				'default' => 'full',
				'choices' => Fns::get_formatted_image_sizes()
			],

			'rt_excerpt_limit' => [
				'type'    => 'text',
				'label'   => __( 'Content Limit', 'clawyer' ),
				'default' => '30',
			],

			'rt_meta_heading' => [
				'type'  => 'heading',
				'label' => __( 'Post Meta Settings', 'clawyer' ),
			],

			'rt_blog_meta_style' => [
				'type'    => 'select',
				'label'   => __( 'Meta Style', 'clawyer' ),
				'default' => 'meta-style-default',
				'choices' => Fns::meta_style()
			],

			'rt_single_above_meta_style' => [
				'type'    => 'select',
				'label'   => __( 'Title Above Meta Style', 'clawyer' ),
				'default' => 'meta-style-dash',
				'choices' => Fns::meta_style( [ 'meta-style-dash-bg', 'meta-style-pipe' ] )
			],

			'rt_blog_meta' => [
				'type'        => 'select2',
				'label'       => __( 'Choose Meta', 'clawyer' ),
				'description' => __( 'You can sort meta by drag and drop', 'clawyer' ),
				'placeholder' => __( 'Choose Meta', 'clawyer' ),
				'multiselect' => true,
				'default'     => 'author,date',
				'choices'     => Fns::blog_meta_list(),
			],

			'rt_visibility' => [
				'type'  => 'heading',
				'label' => __( 'Visibility Section', 'clawyer' ),
			],

			'rt_meta_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Meta Visibility', 'clawyer' ),
				'default' => 1
			],

			'rt_blog_above_cat_visibility' => [
				'type'  => 'switch',
				'label' => __( 'Title Above Category Visibility', 'clawyer' ),
			],

			'rt_blog_content_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Entry Content Visibility', 'clawyer' ),
				'default' => ''
			],

			'rt_blog_footer_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Entry Footer Visibility', 'clawyer' ),
				'default' => ''
			],

			'rt_blog_pagination' => [
				'type'  => 'heading',
				'label' => __( 'Pagination Style', 'clawyer' ),
			],
			'rt_blog_pagination_type' => [
				'type'        => 'select',
				'label'       => __( 'Choose Pagination', 'clawyer' ),
				'placeholder' => __( 'Choose Pagination', 'clawyer' ),
				'multiselect' => true,
				'default'     => 'custom',
				'choices'     => [
					'custom' => __( 'Pagination Number', 'clawyer' ),
					'default' => __( 'Pagination Prev/Next', 'clawyer' ),
				],
			],

		] );
	}
}
