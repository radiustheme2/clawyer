;(function ($) {

    'use strict';

    var Clawyer = {

        _init: function () {

            var offCanvas = {
                menuBar: $('.trigger-off-canvas'),
                drawer: $('.clawyer-offcanvas-drawer'),
                drawerClass: '.clawyer-offcanvas-drawer',
                menuDropdown: $('.dropdown-menu.depth_0'),
            };

			Clawyer.Preloader();
            Clawyer.menuDrawerOpen(offCanvas);
            Clawyer.offcanvasMenuToggle(offCanvas);
            Clawyer.headerSearchOpen();
            Clawyer.menuOffset();
            Clawyer.backToTop();
            Clawyer.swiperSlider();
            Clawyer.magnificPopup();
            Clawyer.photoSwip();
            Clawyer.isotope();
            Clawyer.mouseParallax();
            Clawyer.wowAnimation();
            Clawyer.advancedSearchToggle();
            Clawyer.clawyerIonRangeSlider();
            Clawyer.rtElementorParallax();
            Clawyer.clawyerModal();
        },

		Preloader: function () {
			if ($('#pageoverlay').length) {
				document.getElementById('pageoverlay').className = 'pageoverlay';
				$('#pageoverlay').delay(800).fadeOut();
			}
		},

		menuDrawerOpen: function (offCanvas) {
			offCanvas.menuBar.on('click', e => {
				e.preventDefault();
				offCanvas.menuBar.toggleClass('is-open')
				offCanvas.drawer.toggleClass('is-open');
				e.stopPropagation()
			});

			$(document).on('click', e => {
				if (!$(e.target).closest(offCanvas.drawerClass).length) {
					offCanvas.drawer.removeClass('is-open');
					offCanvas.menuBar.removeClass('is-open')
				}
			});
		},

		offcanvasMenuToggle: function (offCanvas) {
			offCanvas.drawer.each(function () {
				const caret = $(this).find('.caret');
				caret.on('click', function (e) {
					e.preventDefault();
					$(this).closest('li').toggleClass('is-open');
					$(this).parent().next().slideToggle(300);
				})
			})
		},

		headerSearchOpen: function () {
			$('.clawyer-search-trigger').on('click', function (e) {
				e.preventDefault();
				$(this).parent().toggleClass('show');
				e.stopPropagation()
			})
			$(document).on('click', function (e) {
				if (!$(e.target).closest('.clawyer-search-form').length) {
					$('.clawyer-search-popup.show').removeClass('show')
				}
			});
		},

		menuOffset: function () {
			$(".dropdown-menu > li").each(function () {
				var $this = $(this),
					$win = $(window);

				if ($this.offset().left + ($this.width() + 30) > $win.width() + $win.scrollLeft() - $this.width()) {
					$this.addClass("dropdown-inverse");
				} else if ($this.offset().left < ($this.width() + 30)) {
					$this.addClass("dropdown-inverse-left");
				} else {
					$this.removeClass("dropdown-inverse");
				}
			});
		},

		HeadroomStickyHeader: function(){
			var header = document.querySelector(".headroom-sticky-header");
			if(header) {
				var headroom = new Headroom(header, {
					tolerance: {
						down: 10,
						up: 20
					},
					offset: 15
				});
				headroom.init();
			}
		},

		backToTop: function () {
			/* Scroll to top */
			$('.scrollToTop').on('click', function () {
				$('html, body').animate({scrollTop: 0}, 800);
				return false;
			});
		},

		backTopTopScroll: function () {
			if ($(window).scrollTop() > 100) {
				$('.scrollToTop').addClass('show');
			} else {
				$('.scrollToTop').removeClass('show');
			}
		},

		swiperSlider: function () {
			$('.rt-swiper-slider').each(function () {
				var $this = $(this);
				var settings = $this.data('slider-options');
				var autoplayconditon = settings['auto'];
				var $pagination = $this.find('.swiper-pagination')[0];
				var $next = $this.find('.swiper-button-next')[0];
				var $prev = $this.find('.swiper-button-prev')[0];
				var swiper = new Swiper(this, {
					autoplay: autoplayconditon ? { delay:settings['autoplay']['delay'] } : false,
					speed: settings['speed'],
					loop: settings['loop'],
					pauseOnMouseEnter: true,
					effect: typeof settings['effect'] == "undefined" ? 'slide' : settings['effect'],
					slidesPerView: settings['slidesPerView'],
					spaceBetween: settings['spaceBetween'],
					centeredSlides: settings['centeredSlides'],
					slidesPerGroup: settings['slidesPerGroup'],
					pagination: {
						el: $pagination,
						clickable: true,
						type: 'bullets',
					},
					navigation: {
						nextEl: $next,
						prevEl: $prev,
					},
					breakpoints: {
						0: {
							slidesPerView: settings['breakpoints']['0']['slidesPerView'],
						},
						425: {
							slidesPerView: settings['breakpoints']['425']['slidesPerView'],
						},
						576: {
							slidesPerView: settings['breakpoints']['576']['slidesPerView'],
						},
						768: {
							slidesPerView: settings['breakpoints']['768']['slidesPerView'],
						},
						992: {
							slidesPerView: settings['breakpoints']['992']['slidesPerView'],
						},
						1200: {
							slidesPerView: settings['breakpoints']['1200']['slidesPerView'],
						},
						1600: {
							slidesPerView: settings['breakpoints']['1600']['slidesPerView'],
						},
					},
				});
				swiper.init();
			});
		},

		magnificPopup: function (){
			var yPopup = $(".popup-youtube");

			if (yPopup.length) {
				yPopup.magnificPopup({
					disableOn: 700,
					type: 'iframe',
					mainClass: 'mfp-fade',
					removalDelay: 160,
					preloader: false,
					fixedContentPos: false
				});
			}
		},

		photoSwip: function(){
			// Init empty gallery array
			var container = [];
			// Loop over gallery items and push it to the array
			$('.photo-swip-gallery-wrap').find('.photoswip-item').each(function() {
				var $link = $(this).find('a'),
					item = {
						src: $link.attr('href'),
						w: $link.attr('data-width'),
						h: $link.attr('data-height')
					};
				container.push(item);
			});

			// Define click event on gallery item
			$('.photo-swip-gallery-wrap .photoswip-item a').click(function(event) {

				// Prevent location change
				event.preventDefault();

				// Define object and gallery options
				var $pswp = $('.pswp')[0],
					options = {
						index: $(this).parent('.photoswip-item').index(),
						bgOpacity: 0.85,
						showHideOpacity: true
					};

				// Initialize PhotoSwipe
				var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
				gallery.init();
			});
		},

		isotope: function () {
			// init Isotope
			$('.isotope-items').isotope({
				itemSelector: '.item',
				layoutMode: 'fitRows'
			});

			$('.isotope-menu ul li').click(function() {
				$('.isotope-menu ul li').removeClass('active');
				$(this).addClass('active');

				var selector = $(this).attr('data-filter');
				$('.isotope-items').isotope({
					filter: selector
				});
				return false;
			});

			$('.rt-masonry-grid').isotope({
				itemSelector: '.rt-grid-item',
			});
		},

		mouseParallax: function () {
			var parallaxInstances = [];
			$('.rt-mouse-parallax').each(function(index, element) {
				var $this = $(this);
				$this.attr('id', "rt-parallax-instance-" + index);
				parallaxInstances[index] = new Parallax($("#rt-parallax-instance-" + index).get(0), {
					// hoverOnly: true,
					// relativeInput: true,
				});
			})
		},

		rtElementorParallax: function () {
			if ($(".rt-parallax-bg-yes").length) {
				$(".rt-parallax-bg-yes").each(function () {
					var speed = $(this).data('speed');
					$(this).parallaxie({
						speed: speed ? speed : 0.5,
						offset: 0,
					});
				})
			}
		},

		wowAnimation: function () {
			var wow = new WOW({
				boxClass: "wow",
				animateClass: "animate__animated",
				offset: 0,
				mobile: false,
				live: true,
				scrollContainer: null,
			});
			wow.init();
		},

		advancedSearchToggle: function () {
			$(".advanced-btn").on("click", function () {
				$(this).toggleClass("collapsed");
				$("#advanced-search").toggleClass("show");
			});
		},

		clawyerIonRangeSlider: function () {
			if ($.fn.ionRangeSlider) {
				$(".ion-rangeslider").each(function () {
					var $this = $(this);

					var rangeType = $this.data('type');
					$this.ionRangeSlider({
						type: rangeType || "double",
						drag_interval: true,
						min_interval: null,
						max_interval: null,
						prettify_enabled: true,
						prettify_separator: ",",
						onChange: function (data) {
							var $inp = data.input;
							$inp.parent().find('.min-volumn').val(data.from);
							$inp.parent().find('.max-volumn').val(data.to);
						},
					});
				});
			}
		},

		clawyerModal: function (){

			jQuery(document).ready(function ($) {
				// Open modal
				$('.clawyer-modal-toggle').on('click', function (e) {
					e.preventDefault();
					var target = $(this).data('target');
					$(target).addClass('show');
				});

				// Close modal by close button
				$(document).on('click', '.clawyer-modal-close', function () {
					$(this).closest('.clawyer-modal').removeClass('show');
				});

				// Close modal by outside click
				$(document).on('click', '.clawyer-modal', function (e) {
					if ($(e.target).hasClass('clawyer-modal')) {
						$(this).removeClass('show');
					}
				});

				// Close modal on Escape key
				$(document).on('keyup', function (e) {
					if (e.key === "Escape") {
						$('.clawyer-modal.show').removeClass('show');
					}
				});
			});
		},
};

$(document).ready(function (e) {
	Clawyer._init();
	Clawyer.HeadroomStickyHeader();
});

$(document).on('load', () => {
	Clawyer.menuOffset();
})

$(window).on('scroll', (event) => {
	Clawyer.HeadroomStickyHeader();
	Clawyer.backTopTopScroll(event);

	if ($(window).scrollTop() >= $("body").offset().top + 50) {
		$("body").addClass("mn-top");
	} else {
		$("body").removeClass("mn-top");
	}
});

$(window).on('resize', () => {
	Clawyer.menuOffset($);
});

$(window).on('elementor/frontend/init', () => {
	if (elementorFrontend.isEditMode()) {
		//For all widgets
		elementorFrontend.hooks.addAction('frontend/element_ready/widget', () => {
			Clawyer._init();
		});
	}
});

window.Clawyer = Clawyer;

})(jQuery);
