<?php
/**
 * jetpack.
 *
 * @link https://wordpress.org/plugins/classified-listing/
 */

namespace RT\Clawyer\Plugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Link;
use Rtcl\Models\Listing;
use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns as FnsPro;
use RT\Clawyer\Traits\SingletonTraits;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;
use Rtcl\Controllers\Hooks\TemplateHooks;
use Rtrs\Modules\Review\Helpers\ReviewFns;
use Rtcl\Controllers\BusinessHoursController as BHS;
use RtclClaimListing\Helpers\Functions as ClaimFunctions;
use RtclStore\Controllers\Hooks\TemplateHooks as StoreHooks;
use RtclPro\Controllers\Hooks\TemplateHooks as TemplateHooksPro;
use RadiusTheme\RadiusBooking\Integrations\ClassifiedListing\ListingFrontendHooks as RtrbFrontendHooks;

class Listing_Functions {
	use SingletonTraits;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
		add_action( 'init', [ $this, 'rtcl_action_hook' ] );
		add_action( 'init', [ $this, 'rtcl_filter_hook' ] );
		add_filter( 'rtcl_get_icon_list', [ $this, 'rtcl_get_icon_list_modify' ] );
		add_filter( 'rtcl_get_icon_class_list', [ $this, 'rtcl_get_icon_list_modify' ] );
		add_action('admin_menu', [$this, 'remove_menus'], 99 );
		add_action('init', [$this, 'remove_custom_post_type'] );
	}

	public function remove_menus(){
		remove_submenu_page( 'edit.php?post_type=rtcl_listing', 'rtcl-listing-type' );
	}

	function remove_custom_post_type() {
		unregister_post_type('rtcl_cfg');
	}

	/**
	 *
	 *  Classified listing plugin support.
	 *
	 * @return void
	 */
	public function theme_support() {
		add_theme_support( 'rtcl' );
	}

	public function rtcl_action_hook() {

		/* = Listing Archive Hooks
		=====================================================================================================*/
		//Remove Hooks
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_start' ], 8 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );
		remove_action( 'rtcl_sidebar', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_badges' ], 30 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'breadcrumb' ], 6 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_excerpt' ], 70 );

		if ( FnsPro::is_enable_compare() ) {
			remove_action( 'rtcl_listing_meta_buttons', [ TemplateHooksPro::class, 'add_compare_button' ], 30 );
			add_action( 'rtcl_listing_meta_buttons', [__CLASS__, 'listing_compare_button' ], 30 );
		}

		if ( FnsPro::is_enable_quick_view() ) {
			remove_action( 'rtcl_listing_meta_buttons', [ TemplateHooksPro::class, 'add_quick_view_button' ], 20 );
			add_action( 'rtcl_listing_meta_buttons', [__CLASS__, 'listing_quick_view_button' ], 20 );
		}

		//Add Hooks
		add_filter( 'rtcl_bootstrap_dequeue', '__return_false' );
		add_action( 'rtcl_listing_meta_buttons', [ __CLASS__, 'add_website_button' ], 40 );
		add_action( 'rtcl_listing_meta_buttons', [ __CLASS__, 'add_email_button' ], 50 );
		add_action( 'rtcl_listing_meta_buttons', [ __CLASS__, 'listing_social_share_button' ], 40 );

		/* = Listing Single Hooks
		=====================================================================================================*/
		// remove action
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_gallery' ], 30 );
		add_action( 'rt_pets_list_galley', [ __CLASS__, 'listing_details_gallery' ], 10 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [
			TemplateHooks::class,
			'add_single_listing_inner_sidebar_custom_field'
		], 10 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [
			TemplateHooks::class,
			'add_single_listing_inner_sidebar_action'
		], 20 );
		if ( class_exists( 'RtclStore' ) ) {
			remove_action( 'rtcl_single_store_information', [ StoreHooks::class, 'store_social_media' ], 40 );
			add_action( 'rtcl_single_store_information', [ StoreHooks::class, 'store_social_media' ], 60 );
		}
		// Seller Verification
		if ( class_exists('RtclSellerVerification' ) ) {
			remove_action( 'rtcl_listing_seller_information', [ \RtclSellerActionHooks::class, 'listing_sidebar_verified_author' ], 5 );
		}

		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_title' ], 5 );

		add_action( 'rtcl_shortcode_before_listings_loop_start', function () {
			if (is_active_sidebar('rt-listing-map-archive-sidebar')) { ?>
				<div class="listing-map-search">
					<?php dynamic_sidebar( 'rt-listing-map-archive-sidebar' ); ?>
				</div>
			<?php }
		});

		add_action( 'rtcl_map_localized_options', [__CLASS__, 'clawyer_map_localized_options' ] );

		/* = Booking addon
		=====================================================================================================*/
		/* ===== Radius Booking ===== */
		if ( class_exists( RtrbFrontendHooks::class ) ) {
			// Primary hooks (core CL template).
			remove_action( 'rtcl_single_listing_sidebar', array( RtrbFrontendHooks::class, 'renderBookNowButton' ), 30 );
			// Fallback hooks used by various themes.
			remove_action( 'rtcl_after_single_listing_sidebar', array( RtrbFrontendHooks::class, 'renderBookNowButton' ), 30 );
			remove_action( 'rtcl_single_listing_inner_sidebar', array( RtrbFrontendHooks::class, 'renderBookNowButton' ), 30 );
		}
	}

	public function rtcl_filter_hook() {
		// Override Related Listing Item Number
		add_filter( 'rtcl_related_slider_options', function ( $slider_options ) {
			$slider_options = [
				"loop"         => false,
				"autoplay"     => [
					"delay"                => 3000,
					"disableOnInteraction" => false,
					"pauseOnMouseEnter"    => true
				],
				"speed"        => 1000,
				"spaceBetween" => 20,
				"breakpoints"  => [
					0    => [
						"slidesPerView" => 1
					],
					500  => [
						"slidesPerView" => 2
					],
					1200 => [
						"slidesPerView" => 3
					]
				]
			];

			return $slider_options;
		} );

		add_filter( 'rtcl_single_listing_email_button_text', function () {
			return __("Message to User", "clawyer");
		} );

		add_filter( 'rtcl_get_listing_display_options', function( $options ) {
			$options['address'] = esc_html__( 'Address', 'clawyer' );
			$options['phone'] = esc_html__( 'Phone', 'clawyer' );
			$options['rating'] = esc_html__( 'Review Rating', 'clawyer' );
			$options['status'] = esc_html__( 'Open/Close Status', 'clawyer' );
			$options['book_button'] = esc_html__( 'Booking Button', 'clawyer' );
			return $options;
 		});

        add_filter( 'rtcl_get_listing_detail_page_display_options', function( $options ) {
	        $options['rating'] = esc_html__( 'Review Rating', 'clawyer' );
	        $options['status'] = esc_html__( 'Open/Close Status', 'clawyer' );

	        return $options;
 		});

		if ( FnsPro::is_enable_compare() ) {
			add_filter( 'rtcl_archive_listing_settings_options', function ( $options ) {
				$options['enable_compare_listing_page'] = [
					'title' => esc_html__( 'Compare', 'clawyer' ),
					'type'  => 'checkbox',
					'label' => esc_html__( 'Enable', 'clawyer' ),
				];

				return $options;
			} );

			add_filter( 'rtcl_single_listing_settings_options', function ( $options ) {
				$options['enable_compare_listing_details'] = [
					'title' => esc_html__( 'Compare', 'clawyer' ),
					'type'  => 'checkbox',
					'label' => esc_html__( 'Enable', 'clawyer' ),
				];

				return $options;
			} );
		}
        add_filter('rtrs_review_form_string_list', function( $string_text ){
	        $string_text = [
		        'title_reply'               => esc_html__( 'Add a Review', 'clawyer' ),
                /* translators: %s: User/author name. */
                'title_reply_to'            => wp_kses( __( 'Leave feedback about this to %s', 'clawyer' ), [ 'allow_tag_list' ] ),
                'cancel_reply_link'         => esc_html__( 'Cancel Reply', 'clawyer' ),
                'label_submit'              => esc_html__( 'Submit Your Review', 'clawyer' ),
                'comment_notes_before'      => '',
                'comment_notes_after'       => '',
                'name_field_placeholder'    => esc_html__( 'Name', 'clawyer' ),
                'email_field_placeholder'   => esc_html__( 'Email', 'clawyer' ),
                'website_field_placeholder' => esc_html__( 'Website', 'clawyer' ),
                'title_field_placeholder'   => esc_html__( 'Title', 'clawyer' ),
                'comment_field_placeholder' => esc_html__( 'Write your review', 'clawyer' ),
            ];
            return $string_text;
        });

		add_filter( 'rtcl_misc_map_settings_options', function ( $options ) {
			$options['map_center_point_settings_section'] = [
				'title' => esc_html__( 'Map Center Point Settings', 'clawyer' ),
				'type'  => 'section',
			];

			$options['center_point_lat'] = [
				'title'       => esc_html__( 'Center Point Latitude', 'clawyer' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => 'e.g. 23.8103',
				'description'        => esc_html__( 'Specify the latitude (north-south position) of the map center. Use decimal format. Example: 23.8103', 'clawyer' ),
			];

			$options['center_point_lng'] = [
				'title'       => esc_html__( 'Center Point Longitude', 'clawyer' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => 'e.g. 90.4125',
				'description'        => esc_html__( 'Specify the longitude (east-west position) of the map center. Use decimal format. Example: 90.4125', 'clawyer' ),
			];
			 $options['center_point_map_zoom_level'] = [
				'title'   => esc_html__( 'Map Zoom Level', 'clawyer' ),
				'type'    => 'select',
				'default' => 10,
				'options' => [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18 ]
			];

			return $options;
		});

	}

	public static function get_template_part( $template, $args = [] ) {
		extract( $args );

		$template = '/' . $template . '.php';

		if ( file_exists( get_stylesheet_directory() . $template ) ) {
			$file = get_stylesheet_directory() . $template;
		} else {
			$file = get_template_directory() . $template;
		}
		if ( file_exists( $file ) ) {
			require $file;
		} else {
			return false;
		}
	}

	/**
	 * Custom templates path
	 * @return $template
	 */
	public static function get_custom_listing_template( $template, $echo = true, $args = [], $path = 'custom/' ) {
		$template = 'classified-listing/' . $path . $template;
		if ( $echo ) {
			self::get_template_part( $template, $args );
		} else {
			$template .= '.php';

			return $template;
		}
	}

	/**
	 * @param $icon
	 *
	 * @return void
	 */
	public static function clawyer_listing_categories( $icon ) {
		global $listing;
		if ( $listing->has_category() ){
			$categories = $listing->get_categories();
			foreach ( $categories as $category ) {
			?>
			<a href="<?php echo esc_url( Link::get_category_page_link( $category ) ); ?>" class="category">
				<?php
					if ( $icon != '' ) {
						echo self::listing_cat_icon( $category->term_id, $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				?>
				<?php echo esc_html( $category->name ); ?>
			</a>
		<?php }
        }
	}

	/**
	 * @param $listing
	 *
	 * @return $rating_count
	 */
	public static function clawyer_listing_rating( $listing ) {
		$average_rating = $listing->get_average_rating();
		$rating_count   = $listing->get_rating_count();

		if ( ! empty( $rating_count ) ): ?>
		<div class="listing-ratings">
			<div class="item-icon">
				<?php echo Functions::get_rating_html( $average_rating, $rating_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="item-text"><?php /* translators: %s: Rating count. */ echo wp_kses_post( apply_filters( 'clawyer_rating_count_format', sprintf( __( '(<span>%s</span>)', 'clawyer' ), esc_html( $rating_count ) ) ) ); ?></div>
		</div>
		<?php endif;
	}

	/**
	 * @param $listing
	 *
	 * @return $rating_count
	 */
	public static function clawyer_listing_rating_counting( $listing ) {
		$average_rating = $listing->get_average_rating();
		$average_number = number_format($average_rating, 1);
		$rating_count   = $listing->get_rating_count();

		if ( ! empty( $rating_count ) ): ?>
            <div class="listing-ratings">
                <div class="average-rating">
					<?php echo esc_html( $average_number ); ?>
                </div>
                <div class="item-text"><?php /* translators: %s: Rating count number. */
                echo wp_kses_post( apply_filters( 'clawyer_rating_count_format', sprintf( __( '(<span>%s</span>)', 'clawyer' ), esc_html( $rating_count ) ) ) ); ?></div>
            </div>
		<?php endif;
	}

	/**
	 * @param $listing
	 *
	 * @return $rating_count
	 */
	public static function clawyer_listing_avarage_rating( $listing ) {
		$average_rating = $listing->get_average_rating();
		$average_rating 	= number_format( $average_rating, 1 );
		$rating_count   = $listing->get_rating_count();
		if ( ! empty( $rating_count ) ): ?>
			<div class="listing-ratings">
				<div class="item-icon">
					<span class="rtcl-icon rtcl-icon-star"></span>
				</div>
				<div class="item-text"><?php /* translators: %1\$s: Average rating, %2\$s: Rating count. */ echo wp_kses_post( apply_filters( 'clawyer_rating_count_format', sprintf( __( '%1\$s <span>(%2\$s)</span>', 'clawyer' ), esc_html( $average_rating ), esc_html( $rating_count ) ) ) ); ?></div>
			</div>
		<?php endif;
	}

	/**
	 * @param $listing
	 *
	 * @return array|string[]
	 */
	public static function get_listing_type( $listing ) {
		$listing_types = Functions::get_listing_types();
		$listing_types = empty( $listing_types ) ? [] : $listing_types;

		$type = $listing->get_ad_type();

		if ( $type && ! empty( $listing_types[ $type ] ) ) {
			$result = [
				'label' => $listing_types[ $type ],
				'icon'  => 'fa-tags',
			];
		} else {
			$result = [
				'label' => '',
				'icon'  => 'fa-tags',
			];
		}

		return $result;
	}

	/**
	 * @return listing_details_slider
	 */
	public static function listing_details_gallery() {
		global $listing;

		$video_urls = [];
		if ( empty( clawyer_option('rt_listing_video_visibility') ) ) {
			$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
			$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
		}
		// Image Gallery
		$images              = $listing->get_images();
		$total_gallery_image = count( $images );
		$number = $total_gallery_image - 5;

		if ($total_gallery_image < 2) {
			$item_count = 'items-one';
		} elseif ($total_gallery_image < 3 ) {
			$item_count = 'items-two';
		} elseif ($total_gallery_image < 4) {
			$item_count = 'items-three';
		} elseif ($total_gallery_image < 5) {
			$item_count = 'items-four';
		} else {
			$item_count = 'items-five';
		}
		if ( $total_gallery_image ) {
			?>
			<div class="page-header-gallery">
                <div class="rtcl-listing-badge-top-wrap">
                    <?php if ( $listing->can_show_ad_type() && ! empty( $listing_type ) ): ?>
                        <span class="listing-type-badge">
                            <?php echo wp_kses_post( sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'clawyer' ) ), esc_html( $listing_type['label'] ) ) ); ?>
                        </span>
                    <?php endif; ?>
                    <?php $listing->the_badges(); ?>
                </div>
				<div class="photo-swip-gallery-wrap <?php echo esc_attr($item_count); ?>">
				<?php
				if ( empty( clawyer_option('rt_listing_video_visibility') ) ) {
					if ( ! empty( $video_urls ) ) { ?>
						<div class="listing-gallery-item">
							<div class="video-info rtcl-slider-video-item ratio-16x9">
								<iframe class="rtcl-lightbox-iframe"
										src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_urls[0] ) ); ?>"
										style="height: 404px"
										allowFullScreen></iframe>
							</div>
						</div>
					<?php }
				}
				$counter = 0;
				foreach ( $images as $image ) {
					++$counter;
					if ($total_gallery_image < 2) {
						$img_size = 'full';
					} elseif ($total_gallery_image < 3) {
						$img_size = 'clawyer-1200-650';
					} elseif ($total_gallery_image < 4) {
						$img_size = 'clawyer-500-290';
					} else {
						$img_size = 'rtcl-thumbnail';
					}
					?>
					<div class="listing-gallery-item photoswip-item image-size-<?php echo esc_attr($img_size.' item-'.$counter); ?>">
						<?php
							$img_url = wp_get_attachment_image_url( $image->ID, 'full' );
							$getimagesize = getimagesize($img_url);
							$width = $getimagesize[0];
							$height = $getimagesize[1];
						?>
						<a class="listing-popup-btn" href="<?php echo esc_url( $img_url ); ?>" data-width="<?php echo esc_attr($width); ?>" data-height="<?php echo esc_attr($height); ?>">
							<?php
                                echo wp_get_attachment_image( $image->ID, $img_size );
                                if( ! empty( $number ) && $counter === 5 ) { ?>
                                    <span> <?php esc_html_e('All Photos', 'clawyer'); ?></span>
                               <?php }
							?>
						</a>
					</div>
					<?php
				}
				?>
			</div>
			</div>
		<?php }
	}

	/**
	 * @return listing_details_slider
	 */
	public static function listing_details_gallery_2() {
		global $listing;
		$detailOption = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

		$video_urls = [];
		if ( ! Functions::is_video_urls_disabled() ) {
			$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
			$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
		}
		// Image Gallery
		$images              = $listing->get_images();
		$total_gallery_image = count( $images );
		$number = $total_gallery_image - 5;

		if ($total_gallery_image < 2) {
			$item_count = 'items-one';
		} elseif ($total_gallery_image < 3 ) {
			$item_count = 'items-two';
		} elseif ($total_gallery_image < 4) {
			$item_count = 'items-three';
		} elseif ($total_gallery_image < 5) {
			$item_count = 'items-four';
		} else {
			$item_count = 'items-five';
		}
		if ( $total_gallery_image ) {
			?>
			<div class="page-header-gallery">
				<div class="photo-swip-gallery-wrap <?php echo esc_attr($item_count); ?>">
					<?php
					if ( !in_array('video_url', $detailOption) ){
						if ( ! empty( $video_urls ) ) { ?>
							<div class="listing-gallery-item">
								<div class="video-info rtcl-slider-video-item ratio-16x9">
									<iframe class="rtcl-lightbox-iframe"
											src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_urls[0] ) ); ?>"
											style="height: 404px"
											allowFullScreen></iframe>
								</div>
							</div>
						<?php }
					}
					$counter = 0;
					foreach ( $images as $image ) {
						++$counter;
						if ($total_gallery_image < 2) {
							$img_size = 'full';
						} elseif ($total_gallery_image < 3) {
							$img_size = 'clawyer-1200-650';
						} else {
							$img_size = 'clawyer-500-290';
						}
						?>
						<div class="listing-gallery-item photoswip-item image-size-<?php echo esc_attr($img_size.' item-'.$counter); ?>">
							<?php
							$img_url = wp_get_attachment_image_url( $image->ID, 'full' );
							$getimagesize = getimagesize($img_url);
							$width = $getimagesize[0];
							$height = $getimagesize[1];
							?>
							<a class="listing-popup-btn" href="<?php echo esc_url( $img_url ); ?>" data-width="<?php echo esc_attr($width); ?>" data-height="<?php echo esc_attr($height); ?>">
								<?php
								echo wp_get_attachment_image( $image->ID, $img_size );
								if( ! empty( $number ) && $counter === 5 ) {
									echo '<span>+'.esc_html($number).'</span>';
								}
								?>
							</a>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		<?php }
	}

	/**
	 * @return listing_details_slider
	 */
	public static function listing_details_slider() {
		global $listing;
		$imgNone = '';
		$total_gallery_image = '';
		$total_gallery_videos = '';
		$detailOption = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

		$images = $listing->get_images();
		$videos = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
		$rand   = substr( md5( wp_rand() ), 0, 7 );

		$slider_data = [
			'allowSlideNext' => true,
			'allowSlidePrev' => true,
			'centeredSlides' => true,
			'roundLengths' => true,
			"navigation"     => [
				"nextEl" => ".swiper-button-next",
				"prevEl" => ".swiper-button-prev",
			],
			"loop"           => true,
			"speed"          => 1000,
			"spaceBetween"   => 10,
			"breakpoints"    => [
				0    => [
					"slidesPerView" => 1
				],
				576  => [
					"slidesPerView" => 1
				],
				800  => [
					"slidesPerView" => 1
				],
				1200 => [
					"slidesPerView" => 1
				]
			]
		];
		if ( is_rtl() ) {
			$slider_data['rtl'] = true;
		}
		$data['slider_data'] = json_encode( $slider_data );

		if (!empty($images)) {
			$total_gallery_image = count( $images );
		}
		if (!empty($videos)) {
			$total_gallery_videos = count($videos);
		}

		if (!empty($videos)) {
			$total_gallery_item  = $total_gallery_image + $total_gallery_videos;
		} else {
			$total_gallery_item  = $total_gallery_image;
		}
		if ( $total_gallery_item ) :
			$owl_class = $total_gallery_item > 3 && Functions::is_gallery_slider_enabled() ? " slick-navigation-layout2" : 'not-slider';
			if ($total_gallery_image === 0) {
				$imgNone = 'image-not-set';
			}
			?>
			<!-- Listing Banner Area Start Here -->
			<section class="single-listing-carousel-wrap photo-swip-gallery-wrap <?php echo esc_attr( $imgNone ); ?>">
				<?php if ( $total_gallery_item > 1 && Functions::is_gallery_slider_enabled() ){ ?>
					<div class="<?php echo esc_attr( $owl_class ); ?>"
						 data-carousel-options='<?php echo esc_attr( $data['slider_data'] ); ?>'>
						<div class="rtcl-related-slider rtcl-carousel-slider" id="rtcl-related-slider-banner" data-options="<?php echo esc_attr( $data['slider_data'] ); ?>">
							<div class="swiper-wrapper">
								<?php
								if ( !in_array('video_url', $detailOption) ){
									if ($total_gallery_videos) {
										foreach ($videos as $index => $video_url) { ?>
											<div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
												<iframe class="rtcl-lightbox-iframe"
														src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_url ) ); ?>"
														allowFullScreen></iframe>
											</div>
											<?php
										}
									}
								}
								if ( $total_gallery_image ) {
									foreach ( $images as $index => $image ) :
										$img_url = wp_get_attachment_image_url( $image->ID, 'full' );
										$getimagesize = getimagesize($img_url);
										$width = $getimagesize[0];
										$height = $getimagesize[1];
										?>
										<div class="swiper-slide nav-item photoswip-item">
											<a href="<?php echo esc_url( $img_url ); ?>" data-width="<?php echo esc_attr($width); ?>" data-height="<?php echo esc_attr($height); ?>">
												<?php echo wp_get_attachment_image( $image->ID, 'full' ); ?>
											</a>
										</div>
									<?php endforeach;
								}
								?>
							</div>
							<div class="swiper-button-next"></div>
							<div class="swiper-button-prev"></div>
						</div>
					</div>
				<?php } elseif ( $total_gallery_item > 1 && Functions::is_gallery_slider_enabled() ) {
					if ( $total_gallery_item >= 3 ) {
						$cols = '4';
					} else {
						$cols = '6';
					}
					?>
					<div class="row no-gutters justify-content-center">
						<?php if ( !in_array('video_url', $detailOption) ){
							if ($total_gallery_videos) {
								foreach ($videos as $index => $video_url) { ?>
									<div class="col-md-<?php echo esc_attr( $cols ); ?> image-fit">
										<div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
											<iframe
												class="rtcl-lightbox-iframe"
												src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_url ) ); ?>"
												allowFullScreen></iframe>
										</div>
									</div>
									<?php
								}
							}
						}
						?>
						<?php foreach ( $images as $index => $image ) {
							$img_url = wp_get_attachment_image_url( $image->ID, $size = 'full' );
							?>
							<div class="col-md-<?php echo esc_attr( $cols ); ?> image-fit">
								<div class="swiper-slide nav-item photoswip-item">
									<a href="<?php echo esc_url( $img_url ); ?>">
										<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery' ); ?>
									</a>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } else { ?>
					<div class="row">
						<?php if ( !in_array('video_url', $detailOption) ){
							if ($total_gallery_videos) {
								foreach ($videos as $index => $video_url) { ?>
									<div class="col-md-12 image-fit-full">
										<div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
											<iframe class="rtcl-lightbox-iframe"
													src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_url ) ); ?>"
													allowFullScreen></iframe>
										</div>
									</div>
									<?php
								}
							}
						}
						?>
						<?php foreach ( $images as $index => $image ) { ?>
							<div class="col-md-12 image-fit-full text-center">
								<?php echo wp_get_attachment_image( $image->ID, 'full' ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</section>
			<!-- Listing Banner Area End Here -->
		<?php endif;
	}

	/**
	 * @param $post_id
	 *
	 * @return string|void
	 */
	public static function get_favourites_link( $post_id ) {
		$has_favourites = get_option( 'rtcl_general_settings' );
		if ( isset( $has_favourites['has_favourites'] ) && 'yes' !== $has_favourites['has_favourites'] ) {
			return;
		}
        if ( is_user_logged_in() ) {
            if ( $post_id == 0 ) {
                global $post;
                $post_id = $post->ID;
            }

            $favourites = (array) get_user_meta( get_current_user_id(), 'rtcl_favourites', true );
            if ( in_array( $post_id, $favourites ) ) {
                return '<a href="javascript:void(0)" class="rtcl-favourites" class="rtcl-favourites rtcl-active" data-id="' . $post_id . '"><span class="icon-heart-2"></span></a>';
            } else {
                return '<a href="javascript:void(0)" class="rtcl-favourites" data-id="' . $post_id . '"><i class="icon-heart-2"></i></a>';
            }
        } else {
            return '<a href="#" class="rtcl-favourites" data-bs-toggle="modal" data-target="#logoutModalCenter" title="' . esc_html__( "Favourites", 'clawyer' )
                   . '"><i class="icon-heart-2"></i></a>';
        }
	}

	/**
	 * @param Listing $listing
	 */
	public static function listing_compare_button( $listing ) {
		if ( empty( rtcl()->session ) ) {
			rtcl()->initialize_session();
		}
		$compare_ids = rtcl()->session->get( 'rtcl_compare_ids', [] );
		$selected_class = '';
		if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
			$selected_class = ' selected';
		}
		if ( FnsPro::is_enable_compare() ) {
			if ( is_singular( rtcl()->post_type ) ) {
                $single_settings = Functions::get_option( 'rtcl_single_listing_settings' );
                if ( isset( $single_settings['enable_compare_listing_details'] ) && 'yes' == $single_settings['enable_compare_listing_details'] ) {
                ?>
                    <div class="rtcl-compare clawyer-action-button rtcl-btn <?php echo esc_attr( $selected_class ); ?>" data-tooltip="<?php esc_attr_e( "Add to compare list", "clawyer" ) ?>" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                        <i class="icon-re-load-3"></i>
                    </div>
                <?php }
			} else {
                    $archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
                    if ( isset( $single_settings['rtcl_archive_listing_settings'] ) && 'yes' == $archive_settings['enable_compare_listing_page'] ) {
                ?>
                <div class="rtcl-compare clawyer-action-button rtcl-btn <?php echo esc_attr( $selected_class ); ?>" data-tooltip="<?php esc_attr_e( "Add to compare list", "clawyer" ) ?>" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                    <i class="icon-re-load-3"></i>
                </div>
		        <?php }
		    }
		}
	}

	/**
	 * @param $listing
	 *
	 * @return void
	 */
	public static function listing_quick_view_button( $listing ) {
		if ( FnsPro::is_enable_quick_view() ) {
			?>
            <div class="rtcl-quick-view clawyer-action-button rtcl-btn" data-tooltip="<?php esc_attr_e( "Quick view", "clawyer" ) ?>" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                <i class="icon-eye"></i>
            </div>
			<?php
		}
	}


	/**
	 * @param Listing $listing
	 */
	public static function listing_repost_abuse_button( $listing ) {
		$can_report_abuse = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_report_abuse', '', 'checkbox' ) ? true : false;
		if ( $can_report_abuse ){
			?>
                <div class="single-listing-custom-fields-action clawyer-action-button">
                    <div class="rtcl-report-abuse">
                        <?php if ( is_user_logged_in() ){ ?>
                            <a href="javascript:void(0)" data-toggle="modal" id="rtcl-report-abuse-modal-link">
                                <i class="icon-error"></i>
                            </a>
                        <?php } else { ?>
                            <a href="javascript:void(0)" class="rtcl-require-login">
                                <i class="icon-error"></i>
                            </a>
                        <?php } ?>
                    </div>
                    <?php self::get_report_abuse_modal(); ?>
                </div>
		<?php }   
	}

	/**
	 * @param Listing $listing
	 */
	public static function listing_social_share_button( $listing ) {
		global $post;
		if ( ! isset( $post ) ) {
			return;
		}
		$page_settings = Functions::get_page_ids();
		$page          = '';
		if ( Functions::is_listing() ) {
			$page = 'listing';
		} elseif ( ! empty( $page_settings['listings'] ) ) {
			$page = 'listings';
		}
		if ( Functions::get_option_item( 'rtcl_general_social_share_settings', 'social_pages', $page, 'multi_checkbox' ) ) {
			?>
            <div class="rtcl-social-share clawyer-action-button rtcl-btn">
                <a href="#" class="clawyer-modal-toggle" data-target="#clawyer-modal-share">
                    <i class="icon-setting"></i>
                </a>
            </div>
		    <?php
		    self::get_social_share_modal();
		}
		do_action( 'rtcl_single_listing_after_action', $listing->get_id() );
	}

	/**
	 * @param Listing $listing
	 */
	public static function listing_claim_button() {
		global $listing;
		 if ( function_exists( 'rtclClaimListing' ) && ClaimFunctions::claim_listing_enable() ){ ?>
            <div class='single-listing-custom-fields-action'>
                <?php if ( is_user_logged_in() ): ?>
                <span data-bs-toggle="tooltip" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                    <a class="clawyer-action-button" href="javascript:void(0)" data-toggle="modal" id="rtcl-claim-listing-modal-link">
                        <i class="fa-solid fa-exclamation"></i>
                    </a>
                </span>
                <?php else: ?>
                <a class="clawyer-action-button" href="javascript:void(0)" class="rtcl-require-login">
                    <i class="fas fa-exclamation-circle"></i>
                </a>
                <?php endif; ?>
	            <?php do_action( 'rtcl_single_listing_after_action', $listing->get_id() ); ?>
            </div>
        <?php }
	}

	/**
	 * @return void
	 */
	public static function clawyer_single_listing_meta() {
		global $listing;
		$single_settings = Functions::get_option( 'rtcl_single_listing_settings' );
		$show_rating = ! empty( $single_settings['display_options_detail'] ) && in_array( 'rating', $single_settings['display_options_detail'] );
		$show_status = ! empty( $single_settings['display_options_detail'] ) && in_array( 'status', $single_settings['display_options_detail'] );
        ?>
		<!-- Meta data -->
		<div class="rtcl-listing-meta">

			<?php $listing->the_meta(); ?>

			<?php if ( !empty( $show_rating )) { ?>
                <div class="listing-review"><?php Listing_Functions::clawyer_listing_rating_counting( $listing ); ?></div>
			<?php } ?>

			<?php echo TemplateHooks::listing_price(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<?php if ( ! empty( $show_status ) ) {
				echo self::listing_bhs_status( $listing ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} ?>
		</div>
		<?php
	}

	public static function clawyer_related_listing_meta() {
		global $listing;

		$location_type = Functions::location_type();
		$address = get_post_meta( $listing->get_id(), 'address', true );
		$geo_address = get_post_meta( $listing->get_id(), '_rtcl_geo_address', true );

		$phoneNum = get_post_meta( $listing->get_id(), 'phone', true );
		$phone_url = str_replace(' ', '', $phoneNum);
		?>
        <!-- Meta data -->
        <ul class="rtcl-listing-meta-data">
            <?php if ( !empty(clawyer_option('rt_related_listing_type_visibility')) ) :
                $listing_types = Functions::get_listing_types();
                $types         = ! empty( $listing_types ) && isset( $listing_types[ $listing->get_ad_type() ] ) ? $listing_types[ $listing->get_ad_type() ] : '';
                if ( $types ) {
                    ?>
                <li class="rt-ad ad-type"><i class="rtcl-icon rtcl-icon-tags"></i>&nbsp;<?php echo esc_html( $types ); ?></li>
                <?php } ?>
            <?php endif; ?>

            <?php if ( !empty(clawyer_option('rt_related_listing_author_visibility')) ) : ?>
                <li class="rt-author">
                    <i class="icon-author-2"></i>
                    <?php esc_html_e( 'by ', 'clawyer' ); ?>
                    <?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
                        <a href="<?php echo esc_url( $listing->get_the_author_url() ); ?>"><?php $listing->the_author(); ?></a>
                    <?php else : ?>
                        <?php $listing->the_author(); ?>
                    <?php endif; ?>
                    <?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
                </li>
            <?php endif; ?>

            <?php if ( !empty(clawyer_option('rt_related_listing_location_visibility')) && $listing->has_location() ) : ?>
                <li class="rt-location">
                    <i class="icon-location"></i> <?php $listing->the_locations( true, true ); ?>
                </li>
            <?php endif; ?>

            <?php if ( !empty( clawyer_option('rt_related_listing_address_visibility') ) ) : ?>
                <li class="rt-location">
                    <i class="icon-location"></i>
                    <?php
                        if ( $location_type == 'geo' && !empty( $geo_address ) ) {
                            echo esc_html( $geo_address );
                        } else {
                            echo esc_html( $address );
                        }
                    ?>
                </li>
            <?php endif; ?>

            <?php if ( !empty( clawyer_option('rt_related_listing_phone_visibility') && $phoneNum ) ):
                $phone = sprintf(
                    '<li class="rt-phone"><a href="tel:%s"><i class="icon-icon-phone"></i> %s </a></li>',
                    $phone_url,
                    $phoneNum
                );
                echo wp_kses_post( $phone );
            endif;
            ?>

            <?php if (!empty( clawyer_option('rt_related_listing_time_visibility') ) ) : ?>
                <li class="rt-time"><i class="icon-cloock"></i>&nbsp;<?php $listing->the_time(); ?></li>
            <?php endif; ?>

            <?php if ( !empty( clawyer_option('rt_related_listing_views_visibility') ) ) : ?>
                <li class="rt-views">
                    <i class="icon-eye"></i>
                    <?php /* translators: %s: Number of listing views. */ echo esc_html( sprintf( _n( '%s view', '%s views', $listing->get_view_counts(), 'clawyer' ), number_format_i18n( $listing->get_view_counts() ) ) ); ?>
                </li>
            <?php endif; ?>

		    <?php if ( !empty( clawyer_option('rt_related_listing_rating_visibility') ) ) : ?>
                <li class="rt-rating">
                    <?php self::get_listing_reviews( $listing ); ?>
                </li>
            <?php endif; ?>
        </ul>
		<?php
	}

	public static function clawyer_single_listing_action_button() {
		global $listing;
		$has_favourites = get_option( 'rtcl_general_settings' );
		?>

        <div class="share-review-btn-box">
            <div class="clawyer-action-buttons">
                <?php if ( isset( $has_favourites['has_favourites'] ) && 'yes' == $has_favourites['has_favourites'] ) { ?>
                    <div class="rtcl-tooltip-wrapper clawyer-action-button" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                        <?php echo Functions::get_favourites_link( $listing->get_id() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<!--                        <span class="rtcl-icon-spinner animate-spin"></span>-->
                    </div>
                <?php } ?>
                <?php self::listing_quick_view_button( $listing ); ?>
                <?php self::listing_compare_button( $listing ); ?>
                <?php self::listing_repost_abuse_button( $listing ); ?>
                <?php self::listing_social_share_button( $listing ); ?>
                <?php self::listing_claim_button( $listing ); ?>
            </div>
        </div>
		<?php
	}

	/**
	 * @return get_report_abuse_modal
	 */
	public static function get_report_abuse_modal() {
		?>
        <!-- Modal -->
        <div class="rtcl-popup-wrapper" id="rtcl-report-abuse-modal">
            <div class="rtcl-popup">
                <div class="rtcl-popup-content">
                    <div class="rtcl-popup-header">
                        <h5 class="rtcl-popup-title" id="rtcl-report-abuse-modal-label"><?php esc_html_e( 'Report Abuse', 'clawyer' ); ?></h5>
                        <a href="#" class="rtcl-popup-close">×</a>
                    </div>
                    <div class="rtcl-popup-body">
                        <form id="rtcl-report-abuse-form">
                            <div class="rtcl-form-group">
                                <label class="rtcl-field-label" for="rtcl-report-abuse-message">
									<?php esc_html_e( 'Your Complaint', 'clawyer' ); ?>
                                    <span class="rtcl-star">*</span>
                                </label>
                                <textarea name="message" class="rtcl-form-control" id="rtcl-report-abuse-message" rows="3"
                                          placeholder="<?php esc_attr_e( 'Message... ', 'clawyer' ); ?>"
                                          required></textarea>
                            </div>
                            <div id="rtcl-report-abuse-g-recaptcha"></div>
                            <div id="rtcl-report-abuse-message-display"></div>
                            <button type="submit"
                                    class="rtcl-btn rtcl-btn-primary"><?php esc_html_e( 'Submit', 'clawyer' ); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

		<?php
	}


	/**
	 * @return get_social_share_modal
	 */
	public static function get_social_share_modal() {
		global $listing;
		?>

        <!-- Modal Box -->
        <div class="clawyer-modal social-share fade" id="clawyer-modal-share">
            <div class="clawyer-modal-dialog">
                <div class="clawyer-modal-content">
                    <div class="clawyer-modal-header">
                        <h5 class="modal-title"><?php esc_html_e( 'Share This Link Via', 'clawyer' ); ?></h5>
                        <button type="button" class="clawyer-modal-close">
                            <i class="icon-cancel"></i>
                        </button>
                    </div>
                    <div class="clawyer-modal-body">
                        <div class="share-icon">
		                    <?php $listing->the_social_share(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<?php
	}

	/**
	 * @return bool|void
	 */
	public static function form_builder_custom_group_field_check() {
		global $listing;
		$form = $listing->getForm();
		if (!empty($form)) {
			$fields = $form->getFieldAsGroup( FBField::CUSTOM );
			$fields_available = false;
			if ( count( $fields ) ) {
				foreach ( $fields as $fieldName => $field ) {
					$field = new FBField( $field );
					$value
					       = $field->getFormattedCustomFieldValue( $listing->get_id() );
					if ( ! empty( $value ) ) {
						return true;
					}
				}

				return $fields_available;
			}
		}
	}

	/**
	 * @param $phone
	 * @param $whatsapp_number
	 * @param $telegram
	 *
	 * @return void
	 */
	public static function the_phone( $phone = '', $whatsapp_number = '', $telegram = '' ){
		global $listing;
		$mobileClass = wp_is_mobile() ? " rtcl-mobile" : null;
		$phone_options = [];
		if ( $phone ) {
			$phone_options = [
				'safe_phone'   => mb_substr( $phone, 0, mb_strlen( $phone ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' ),
				'phone_hidden' => mb_substr( $phone, - 3 )
			];
		}
		if ( $whatsapp_number && ! Functions::is_field_disabled( 'whatsapp_number' ) ) {
			$phone_options['safe_whatsapp_number'] = mb_substr( $whatsapp_number, 0, mb_strlen( $whatsapp_number ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' );
			$phone_options['whatsapp_hidden']      = mb_substr( $whatsapp_number, - 3 );
		}
		if ( $telegram ) {
			$phone_options['safe_telegram'] = mb_substr( $telegram, 0, mb_strlen( $telegram ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' );
			$phone_options['telegram_hidden'] = mb_substr( $telegram, - 3 );
		}

		$phone_options = apply_filters( 'rtcl_phone_number_options', $phone_options, [
			'phone'             => $phone,
			'whatsapp_number'   => $whatsapp_number,
			'telegram'          => $telegram
		] );

		$data_id       = 0;
		if ( Functions::is_listing() ) {
			$data_id = $listing->get_id();
		}
		if ( $phone ) { ?>
			<div class='item-number rtcl-contact-reveal-wrapper reveal-phone<?php echo esc_attr( $mobileClass ); ?>' data-options="<?php echo esc_attr( wp_json_encode( $phone_options ) ); ?>" data-id="<?php echo esc_attr( $data_id ); ?>">
				<div class="number-icon">
					<i class="icon-icon-phone"></i>
					<div class='numbers'>
						<?php echo esc_html( $phone_options['safe_phone'] ); ?>
					</div>
				</div>
				<small class='text-muted'><i class="icon-eye"></i></small>
			</div>
		<?php } elseif ( $whatsapp_number ) { ?>
			<div class='item-number rtcl-contact-reveal-wrapper reveal-phone<?php echo esc_attr( $mobileClass ); ?>' data-options="<?php echo esc_attr( wp_json_encode( $phone_options ) ); ?>" data-id="<?php echo esc_attr( $data_id ); ?>">
				<div class="number-icon">
                    <i class="fa-brands fa-whatsapp"></i>
					<div class='numbers'><?php echo esc_html( $phone_options['safe_whatsapp_number'] ); ?></div>
				</div>
				<small class='text-muted'><i class="icon-eye"></i></small>
			</div>
		<?php } elseif ( $telegram ) { ?>
			<div class='item-number rtcl-contact-reveal-wrapper reveal-phone<?php echo esc_attr( $mobileClass ); ?>' data-options="<?php echo esc_attr( wp_json_encode( $phone_options ) ); ?>" data-id="<?php echo esc_attr( $data_id ); ?>">
				<div class="number-icon">
					<i class="fa-brands fa-telegram"></i>
					<div class='numbers'> <?php echo esc_html( $phone_options['safe_telegram'] ); ?></div>
				</div>
				<small class='text-muted'><i class="icon-eye"></i></small>
			</div>
		<?php }
	}

	/**
	 * @param $term_id
	 * @param $icon_type
	 *
	 * @return string|null
	 */
	public static function listing_cat_icon( $term_id, $icon_type = NULL ) {
		$cat_img  = $cat_icon = $icon = '';
		$image_id = get_term_meta( $term_id, '_rtcl_image', true );

		if ( $image_id ) {
			$image_attributes = wp_get_attachment_image_src( (int) $image_id, 'medium' );
			$image            = $image_attributes[0] ?? '';

			if ( !empty( $image ) ) {
				$cat_img = sprintf( '<img src="%s" class="rtcl-cat-img" alt="%s"/>', esc_url($image), esc_attr__( 'Category Image', 'clawyer' ) );
			}
		}

		$icon_id = get_term_meta( $term_id, '_rtcl_icon', true );
		if ( $icon_id ) {
			$cat_icon = sprintf( '<i class="rtcl-cat-icon rtcl-icon rtcl-icon-%s"></i>', esc_attr($icon_id) );
		}

		return !empty($icon_type) && $icon_type === 'icon' ? $cat_icon : $cat_img;
	}

	/**
	 * @param $term_id
	 *
	 * @return int
	 */
	public static function rt_term_post_count( $term_id, $term_name ){
		$args = array(
			'nopaging'            => true,
			'fields'              => 'ids',
			'post_type'           => 'rtcl_listing',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'suppress_filters'    => false,
			'tax_query' => array(
				array(
					'taxonomy' => $term_name,
					'field'    => 'term_id',
					'terms'    => $term_id,
				)
			)
		);
		$posts = get_posts( $args );
		return count( $posts );
	}

	/**
	 * @param $listing
	 *
	 * @return void
	 */
	public static function listing_bhs_status( $listing ) {

		/** @var Listing $listing */
		$defaults = [
			'header'                => true,
			'footer'                => false,
			'day_name'              => 'full',
			'show_closed_day'       => true,
			'show_closed_period'    => true,
			'show_open_status'      => true,
			'open_text'             => esc_html__( 'Open Now', 'clawyer' ),
			'close_text'            => esc_html__( 'Close Now', 'clawyer' ),
		];

		$options = wp_parse_args( apply_filters( 'rtcl_business_hours_display_options', [] ), $defaults );

		if ( !empty( $options['show_open_status'] ) ) {
			if ( self::business_open_close_status( $listing ) ) {
				printf( '<div class="rtclbh-status rtclbh-status-open">%s</div>', !empty( $options['open_text'] ) ? esc_html( $options['open_text'] ) : esc_html__( 'Open Now', 'clawyer' ) );
			} else {
				printf( '<div class="rtclbh-status rtclbh-status-closed">%s</div>', !empty( $options['close_text'] ) ? esc_html( $options['close_text'] ) : esc_html__( 'Closed Now', 'clawyer' ) );
			}
		}
	}


	public static function business_open_close_status( $listing ){
		$form = null;
		if ( FBHelper::isEnabled() ) {
			$_form = $listing->getForm();
			if ( $_form && $_form->getFieldByElement( 'business_hours' ) ) {
				$form = $_form;
			}
		}

		if ( FBHelper::isEnabled() && $form ) {
			$business_hours = BHS::get_business_hours( $listing->get_id() );
			if ( ! empty( $business_hours['bhs'] ) ) {
				$business_hours = $business_hours['bhs'];
			}
		} else {
			$business_hours = BHS::get_old_business_hours( $listing->get_id() );
		}

		return BHS::openStatus( $business_hours );
	}

	/**
	 * Get min and max meta value
	 *
	 * @param $key
	 * @param $type
	 *
	 * @return string|null
	 */
	public static function get_min_max_meta_value( $key, $type = 'max' ) {
		global $wpdb;
		$sql   = "SELECT " . $type . "( cast( meta_value as UNSIGNED ) ) FROM {$wpdb->postmeta} WHERE meta_key='%s'"; // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$query = $wpdb->prepare( $sql, $key ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$value = $wpdb->get_var( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter

		return $value;
	}

	/**
	 * Get Global Price Range (min and max price)
	 * @return array
	 */
	public static function listing_price_range() {
		$global_price_min     = self::get_min_max_meta_value( 'price', 'min' );
		$global_price_max     = absint( self::get_min_max_meta_value( 'price' ) );
		$global_max_price_max = absint( self::get_min_max_meta_value( '_rtcl_max_price' ) );
		$max_price            = max( $global_price_max, $global_max_price_max );
		$price_range          = [];

		$price_range['min_price'] = $global_price_min ?? 0;
		$price_range['max_price'] = $max_price;

		return $price_range;
	}

	public static function get_advanced_search_field_html( $field_id ) {
		$field      = new RtclCFGField( $field_id );
		$field_html = null;

		if ( $field_id && $field ) {
			$id = "rtcl_{$field->getType()}_{$field->getFieldId()}";

			switch ( $field->getType() ) {
				case 'text':
					$field_html = sprintf(
						'<input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" rows="%d" placeholder="%s"></textarea>',
						$id,
						absint( $field->getFieldId() ),
						absint( $field->getRows() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$choices      = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';

					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) && $_GET['filters'][ '_field_' . $field->getFieldId() ] == $choice ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
								$_attr .= ' selected';
							}
							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $key, $_attr, $choice );
						}
					}

					$field_html
						= sprintf(
						'<div class="search-item search-select"><select name="filters[_field_%d]" id="%s" data-placeholder="%s" class="select2">%s</select></div>',
						absint( $field->getFieldId() ),
						$id . wp_rand(),
						$field->getLabel(),
						$options_html
					);
					break;
				case 'checkbox':
					$options       = $field->getOptions();
					$value         = isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ? $_GET['filters'][ '_field_' . $field->getFieldId() ] : []; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( in_array( $key, $value ) ) {
								$_attr .= ' checked="checked"';
							}
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="checkbox" name="filters[_field_%d][]" value="%s"%s><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$_attr,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item checkbox-wrapper">%s</div>', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="radio" name="filters[_field_%d]" value="%s"><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><div class="search-check-box">%s</div></div>', $check_options );
					break;
				case 'number':
					$hidden_field = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[_field_%d][min]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) : '' // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[_field_%d][max]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) : '' // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
					);

					$field_html = sprintf(
                '<div class="search-item price-wrapper">
                            <div class="price-range">
                                <label>%s</label>
                                <input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
                                %s
                            </div>
                         </div>',
						esc_attr( $field->getLabel() ),
						$id,
						$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? sprintf( // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
							'data-from="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
						) : '',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) && ! empty( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? sprintf( // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
							'data-to="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
						) : '',
						$field->getMin() !== '' ? absint( $field->getMin() ) : '',
						! empty( $field->getMax() ) ? absint( $field->getMax() ) : absint( $field->getMin() ) + 100,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
			}
		}

		return $field_html;
	}

    public static function get_listing_reviews( $listing ) {
		if( class_exists( ReviewFns::class ) ){
			$average_rating = ReviewFns::getAvgRatings( get_the_ID() );
			$rating_count   = ReviewFns::getTotalRatings( get_the_ID() );
		} else {
			$average_rating = $listing->get_average_rating();
			$rating_count   = $listing->get_rating_count();
		}

		if ( $rating_count > 1 ) {
			$rating_count_text = esc_html__( ' Reviews', 'clawyer' );
		} else {
			$rating_count_text = esc_html__( ' Review', 'clawyer' );
		}
		if ( ! empty( $rating_count )) { ?>
            <div class="product-rating">
                <div class="item-icon">
                    <i class="fa-solid fa-star"></i>
					<?php echo wp_kses_post( $average_rating ); ?>
                </div>
                <div class="item-text"><?php echo wp_kses_post( apply_filters( 'cl_hotel_rating_count_format', sprintf( '<span>%s %s</span>', esc_html( $rating_count ), esc_html( $rating_count_text ) ) ) ); ?></div>
            </div>
		<?php }
	}


	/**
	 * @param Listing $listing
	 */
	public static function add_website_button( $listing ) {
		    $website = get_post_meta( $listing->get_id(), 'website', true );
            if (!empty($website)){
            ?>
            <div class="rtcl-website rtcl-btn">
                 <a href="<?php echo esc_url( $website ); ?>" target="_blank"><i class="icon-web"></i></a>
            </div>
		<?php }
	}

    /**
	 * @param Listing $listing
	 */
	public static function add_email_button( $listing ) {
		    $email = get_post_meta($listing->get_id(), 'email', true);
            if (!empty($email)){
            ?>
            <div class="rtcl-email rtcl-btn">
                 <a href="mailto:<?php echo esc_attr( $email ); ?>"><i class="icon-comments-2"></i></a>
            </div>
		<?php }
	}

	/**
	 * @param Listing $listing
	 */
	public static function clawyer_map_localized_options( $options ) {
		//Map Center Points Settings
		$map_lat  = Functions::get_option_item( 'rtcl_misc_map_settings', 'center_point_lat' ) ?: 39.7641279;
		$map_lot  = Functions::get_option_item( 'rtcl_misc_map_settings', 'center_point_lng' ) ?: -104.0195573;
		$map_zoom = Functions::get_option_item( 'rtcl_misc_map_settings', 'center_point_map_zoom_level' ) ?: 4;
        $options['cluster_options']['center'] = [
            "lat" => (float) $map_lat,
            "lng" => (float) $map_lot,
        ];
        $options['cluster_options']['zoom']   = (int) $map_zoom;
        return $options;
	}

	/**
	 * @param $icons_lists
	 *
	 * @return array
	 */
	public function rtcl_get_icon_list_modify( $icons_lists ) {
		$new_icons = [
			" icon-right-open",
			" icon-minus",
			" icon-heart-1",
			" icon-business-law",
			" icon-youtube",
			" icon-tags",
			" icon-tag",
			" icon-flash-outline",
			" icon-heart-2",
			" icon-params",
			" icon-calendar-1",
			" icon-law-1",
			" icon-lawyer",
			" icon-searchlow",
			" icon-labour-law",
			" icon-injured-law",
			" icon-long-arrow",
			" icon-right",
			" icon-bulding",
			" icon-down-open",
			" icon-right-open-big",
			" icon-left-open-big",
			" icon-left",
			" icon-book",
			" icon-tax-law",
			" icon-star-empty",
			" icon-comments-2",
			" icon-calander",
			" icon-law-3",
			" icon-lock1",
			" icon-plus",
			" icon-criminal-law",
			" icon-cancel",
			" icon-tax-law-1",
			" icon-clone",
			" icon-mail",
			" icon-heart-empty",
			" icon-picture-outline",
			" icon-mail-1",
			" icon-relod-2",
			" icon-list",
			" icon-dot",
			" icon-star-5",
			" icon-re-load",
			" icon-shock",
			" icon-long-arrow-1",
			" icon-skype-outline",
			" icon-replay",
			" icon-author-round",
			" icon-comments",
			" icon-author-2",
			" icon-layer-bank",
			" icon-clock",
			" icon-law-2",
			" icon-eye",
			" icon-re-load-2",
			" icon-error",
			" icon-re-load-3",
			" icon-heart",
			" icon-flag",
			" icon-setting",
			" icon-arrow-2",
			" icon-location",
			" icon-write",
			" icon-web",
			" icon-search",
			" icon-plus-squared",
			" icon-plus-2",
			" icon-icon-phone",
			" icon-icon-pinterest",
			" icon-icon-instagram",
			" icon-icon-twitter-x",
			" icon-icon-facebook",
			" icon-facebook",
			" icon-angle-left",
			" icon-angle-right",
			" icon-angle-up",
			" icon-angle-down",
			" icon-youtube-1",
			" icon-youtube-play",
			" icon-instagram",
			" icon-vimeo",
			" icon-twitter",
			" icon-pinterest",
			" icon-linkedin",
			" icon-dribbble",
			" icon-skype",
			" icon-behance",
		];

		return array_merge( $new_icons, $icons_lists );
	}

}
