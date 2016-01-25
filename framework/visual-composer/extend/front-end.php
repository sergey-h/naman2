<?php
/**
 * Functions used to provide support for the Visual Composer Front End Builder
 *
 * @package		Total
 * @subpackage	Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.5.0
 */

/**
 * Outputs front end JS for masonry/isotope
 *
 * @since Total 1.5.0
 */
if ( ! function_exists( 'vcex_front_end_grid_js' ) ) {
	function vcex_front_end_grid_js( $style = '' ) {
		// Only output on front-end
		if ( ! wpex_is_front_end_composer() ) {
			return;
		} ?>
		<script type="text/javascript">
			jQuery(function($){
				<?php if ( 'isotope' == $style ) { ?>
				// Isotope Containers
				function wpexIsotopeGrid() {
					$('.vcex-isotope-grid').each( function () {
						// Initialize isotope
						var $container = $(this);
						$container.imagesLoaded(function() {
							$container.isotope({
								itemSelector: '.vcex-isotope-entry'
							} );
						} );
						// Isotope filter links
						var $filter = $container.prev('ul.vcex-filter-links');
						var $filterLinks = $filter.find('a');
						$filterLinks.each( function() {
							var $filterableDiv = $(this).data('filter');
							if ( $filterableDiv !== '*' && ! $container.find($filterableDiv).length ) {
								$(this).parent().hide('100');
							}
						} );
						$filterLinks.css({ opacity: 1 } );
						$filterLinks.click(function(){
						var selector = $(this).attr('data-filter');
							$container.isotope({
								filter: selector
							} );
							$(this).parents('ul').find('li').removeClass('active');
							$(this).parent('li').addClass('active');
						return false;
						} );
					} );
				}
				if ($.fn.isotope != undefined) {
					wpexIsotopeGrid();
					var isIE8 = $.browser.msie && +$.browser.version === 8;
					if (isIE8) {
						document.body.onresize = function () {
							wpexIsotopeGrid();
						};
					} else {
						$(window).resize(function () {
							wpexIsotopeGrid();
						} );
						window.addEventListener("orientationchange", function() {
							wpexIsotopeGrid();
						} );
					}
				}
				<?php } ?>
			} );
		</script>
	<?php }
}

/**
 * Outputs front end JS for image sliders
 *
 * @since Total 1.5.0
 */
if ( ! function_exists( 'vcex_front_end_slider_js' ) ) {
	function vcex_front_end_slider_js() {
		if ( ! wpex_is_front_end_composer() ) {
			return;
		} ?>
		<script type="text/javascript">
			jQuery(function($){
				if ( $.fn.imagesLoaded != undefined && $.fn.flexslider != undefined ) {
					$( ".vcex-flexslider, .vcex-galleryslider" ).each( function() {
						var $container = $(this);
						$container.imagesLoaded( function() {
							var animation		= $container.data("animation"),
								randomize		= $container.data("randomize"),
								direction		= $container.data("direction"),
								slideshowSpeed	= $container.data("slideshow-speed"),
								animationSpeed	= $container.data("animation-speed"),
								directionNav	= $container.data("direction-nav"),
								pauseOnHover	= $container.data("pause"),
								smoothHeight	= $container.data("smooth-height"),
								controlNav		= $container.data("control-nav");
							$container.flexslider( {
								slideshow		: false,
								animation		: animation,
								randomize		: randomize,
								direction		: direction,
								slideshowSpeed	: slideshowSpeed,
								animationSpeed	: animationSpeed,
								directionNav	: directionNav,
								pauseOnHover	: pauseOnHover,
								smoothHeight	: smoothHeight,
								controlNav		: controlNav,
								prevText		: '<i class=fa fa-chevron-left"></i>',
								nextText		: '<i class="fa fa-chevron-right"></i>'
							} );
						} );
					} );
				}
			} );
		</script>
	<?php
	}
}

/**
 * Outputs front end JS for carousel
 *
 * @since Total 1.5.0
 */
if ( ! function_exists( 'vcex_front_end_carousel_js' ) ) {
	function vcex_front_end_carousel_js() {
		// Only output on front-end
		if ( ! wpex_is_front_end_composer() ) {
			return;
		} ?>
		<script type="text/javascript">
		jQuery(function($){
			if ( $.fn.owlCarousel != undefined ) {
				$('.wpex-carousel').each( function() {
					var $carousel = $(this);
					$carousel.owlCarousel({
						dots			: false,
						items			: $carousel.data("items"),
						slideBy			: $carousel.data("slideby"),
						center			: $carousel.data("center"),
						loop			: $carousel.data("loop"),
						margin			: $carousel.data("margin"),
						nav				: $carousel.data("nav"),
						autoplay		: $carousel.data("autoplay"),
						autoplayTimeout	: $carousel.data("autoplay-timeout"),
						navText			: [ '<span class="fa fa-chevron-left"><span>', '<span class="fa fa-chevron-right"></span>' ],
						responsive		: {
							0	: {
								items	: $carousel.data("items-mobile-portrait")
							},
							480	: {
								 items	: $carousel.data("items-mobile-landscape")
							},
							768	: {
								items	: $carousel.data("items-tablet")
							},
							960	: {
								items	: $carousel.data("items")
							}
						}
					} );
				} );
			}
		} );
		</script>
	<?php
	}
}