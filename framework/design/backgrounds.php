<?php
/**
 * Custom backgrounds for your site
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0
 */

/**
 * Per-page custom backgrounds
 *
 * @since Total 1.6.0
 */
if ( ! function_exists( 'wpex_site_background' ) ) {
	function wpex_site_background() {
		
		// VARS
		$css        = '';
		$color		= get_theme_mod( 'background_color' );
		$image      = get_theme_mod( 'background_image' );
		$pattern    = get_theme_mod( 'background_pattern' );

		// Color
		if ( $color ) {
			$css .= 'background-color:#'. $color .' !important;';
		}
		
		// Image
		if ( $image && ! $pattern ) {
			$css .= 'background-image:url('. $image .');';
			if ( $style = get_theme_mod( 'background_style' ) ) {
				if ( $style == 'stretched' ) {
					$css .= '-webkit-background-size: cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;background-position:center center;background-attachment:fixed;background-repeat:no-repeat;}';
				}
				elseif ( $style == 'repeat' ) {
					$css .= 'background-repeat:repeat;';
				}
				elseif ( $style == 'fixed' ) {
					$css .= 'background-repeat:no-repeat;background-position:center center;background-attachment:fixed;';
				}
			}
		}
		
		// Pattern
		if ( $pattern ) {
			$css .= 'background-image:url('. $pattern .');background-repeat:repeat;';
		}

		// Combine CSS and apply to body tag
		if ( $css ) {
			$css = 'body {'. $css .'}';
			$css = '/*CUSTOM SITE BACKGROUND CSS*/'. $css;
			return $css;
		}

	}
}

/**
 * Per-page custom backgrounds
 *
 * @since Total 1.60
 */
if ( ! function_exists( 'wpex_page_backgrounds' ) ) {
	function wpex_page_backgrounds( $title = '' ) {

		// Get current ID
		$post_id = wpex_get_the_id();

		// Return if there isn't an ID
		if( ! $post_id ) {
			return;
		}

		// CSS variable
		$css = '';

		// Background Color
		$bg_color = get_post_meta( $post_id, 'wpex_page_background_color', true );
		if ( $bg_color && '#' != $bg_color ) {
			$css .= 'background-color:'. $bg_color .' !important;';
		}

		// Background image
		$bg_img = get_post_meta( $post_id, 'wpex_page_background_image_redux', true );
		if ( is_array( $bg_img ) ) {
			if ( ! empty( $bg_img['url'] ) ) {
				$bg_img = $bg_img['url'];
			} else {
				$bg_img = '';
			}
		}

		// Fallback for old meta
		if ( ! $bg_img ) {
			$bg_img = get_post_meta( $post_id, 'wpex_page_background_image', true );
			if ( $bg_img && ! is_array($bg_img) ) {
				$bg_img = $bg_img;
			} else {
				$bg_img = '';
			}
		}

		// Return if no background image is defined
		if  ( ! $bg_img && ! $bg_color ) {
			return;
		}
		
		// Background Image
		if ( $bg_img ) {
			
			// Get Background image style
			$bg_img_style = get_post_meta( $post_id, 'wpex_page_background_image_style', true );
			$bg_img_style = $bg_img_style ? $bg_img_style : 'stretched';

			// Apply background image style
			if ( 'repeat' == $bg_img_style ) {
				$css .= 'background-image: url('. $bg_img .') !important; background-repeat: repeat;';
			}
			if ( 'fixed' == $bg_img_style ) {
				$css .= 'background-image: url('. $bg_img .') !important; background-position: center top; background-attachment fixed; background-repeat no-repeat;';
			}
			if ( 'stretched' == $bg_img_style || 'streched' == $bg_img_style  ) {
				$css .= 'background-image: url('. $bg_img .') !important; background-repeat: no-repeat; background-position: center center;  background-attachment: fixed; -webkit-background-size: cover !important; -moz-background-size: cover !important; -o-background-size: cover !important; background-size: cover !important;';
			}

		}
		
		// If CSS var isn't empty output CSS otherwise return nothing
		if ( '' != $css ) {
			$css = 'body {'. $css .'}';
			$css = '/*CUSTOM PAGE BACKGROUNDS CSS*/'. $css;
			return $css;
		}
		
	}
}