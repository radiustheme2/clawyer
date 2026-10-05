<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.1.0
 */

namespace RT\Clawyer\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RT\Clawyer\Traits\SingletonTraits;

require_once get_template_directory() . '/inc/Lib/class-tgm-plugin-activation.php';

class TGMConfig {
	use SingletonTraits;

	public $base;
	public $path;

	public function __construct() {
		$this->base = 'clawyer';
		$this->path = get_template_directory() . '/plugin-bundle/';

		add_action( 'tgmpa_register', [ $this, 'register_required_plugins' ] );
	}

	public function register_required_plugins() {
		$plugins = [

			// Repository
			[
				'name'     => esc_html__('Classified Listing','clawyer'),
				'slug'     => 'classified-listing',
				'required' => true,
			],
			[
				'name'     => 'Classified Listing Toolkits',
				'slug'     => 'classified-listing-toolkits',
				'required' => true,
			],
			[
				'name'     => esc_html__('Elementor Page Builder','clawyer'),
				'slug'     => 'elementor',
				'required' => false,
			],
			[
				'name'     => esc_html__('Fluent Forms','clawyer'),
				'slug'     => 'fluentform',
				'required' => true,
			],
			[
				'name'     => esc_html__('Review Schema','clawyer'),
				'slug'     => 'review-schema',
				'required' => true,
			],
			[
				'name'     => 'Radius Booking',
				'slug'     => 'radius-booking',
				'required' => false,
			],
			[
				'name'      => 'Easy Demo Importer',
				'slug'      => 'easy-demo-importer',
				'required'  => false,
			],

			// Bundled
			[
				'name'     => 'Clawyer Core',
				'slug'     => 'clawyer-core',
				'source'   => 'clawyer-core.1.2.1.zip',
				'required' => true,
				'version'  => '1.2.1'
			],
			[
				'name'     => 'RT Framework',
				'slug'     => 'rt-framework',
				'source'   => 'rt-framework.zip',
				'required' => true,
				'version'  => '3.0.3'
			],
			[
				'name'         => 'Classified Listing Pro',
				'slug'         => 'classified-listing-pro',
				'source'       => 'classified-listing-pro.4.2.5.zip',
				'required'     =>  true,
				'version'      => '4.2.5'
			],
			[
				'name'         => 'Classified Listing Store',
				'slug'         => 'classified-listing-store',
				'source'       => 'classified-listing-store.3.2.1.zip',
				'required'     =>  false,
				'version'      => '3.2.1'
			],
			[
				'name'         => 'Review Schema Pro',
				'slug'         => 'review-schema-pro',
				'source'       => 'review-schema-pro.2.0.1.zip',
				'required'     =>  false,
				'version'      => '2.0.1'
			],
		];

		$config = [
			'id'           => $this->base,
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => $this->path,
			// Default absolute path to bundled plugins.
			'menu'         => $this->base . '-install-plugins',
			// Menu slug.
			'has_notices'  => true,
			// Show admin notices or not.
			'dismissable'  => true,
			// If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',
			// If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,
			// Automatically activate plugins after installation or not.
			'message'      => '',
			// Message to output right before the plugins table.
		];

		tgmpa( $plugins, $config );
	}
}
