<?php
/**
 * This file is used for all the styling options in the admin
 * All custom color options are output to the <head> tag
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

if ( ! function_exists( 'wpex_responsive_widths' ) ) {
	function wpex_responsive_widths() {
	
		// Vars
		$css = $add_css = '';

		/*-----------------------------------------------------------------------------------*/
		/*	- Desktop Width
		/*-----------------------------------------------------------------------------------*/
		
		// Main Container With
		if ( ( $width = get_theme_mod( 'main_container_width', '980px' ) ) && '980px' != $width ) {
			if ( 'gaps' == wpex_active_skin() ) {
				$add_css .= '.is-sticky .fixed-nav,#wrap{width:'. $width .'!important;}';
			} else {
				$add_css .= '.container,.vc_row-fluid.container,.boxed-main-layout #wrap,.boxed-main-layout .is-sticky #site-header,.boxed-main-layout .is-sticky .fixed-nav { width: '. $width .' !important; }';
			}
		}
		
		// Left container width
		if ( ( $width = get_theme_mod( 'left_container_width', '680px' ) ) && $width != '680px' ) {
			$add_css .= '.content-area{width:'. $width .';}';
		}

		// Sidebar width
		if ( ( $width = get_theme_mod( 'sidebar_width', '250px' ) ) && '250px' != $width ) {
			$add_css .= '#sidebar{width: '. $width .';}';
		}

		// Add to $css var
		if ( $add_css ) {
			$css .= '@media only screen and (min-width: 1281px){'. $add_css .'}';
			$add_css = '';
		}


		/*-----------------------------------------------------------------------------------*/
		/*	- Tablet Landscape & Small Screen Widths
		/*-----------------------------------------------------------------------------------*/

		// Main Container With
		if ( ( $width = get_theme_mod( 'tablet_landscape_main_container_width', '980px' ) ) && '980px' != $width ) {
			if ( 'gaps' == wpex_active_skin() ) {
				$add_css .= '.is-sticky .fixed-nav,#wrap{width:'. $width .'!important;}';
			} else {
				$add_css .= '.container,.vc_row-fluid.container,.boxed-main-layout #wrap,.boxed-main-layout .is-sticky #site-header,.boxed-main-layout .is-sticky .fixed-nav { width: '. $width .' !important; }';
			}
		}

		// Left container width
		if ( ( $width = get_theme_mod( 'tablet_landscape_left_container_width', '680px' ) ) && $width != '680px' ) {
			$add_css .= '.content-area{width:'. $width .';}';
		}

		// Sidebar width
		if ( ( $width = get_theme_mod( 'tablet_landscape_sidebar_width', '250px' ) ) && '250px' != $width ) {
			$add_css .= '#sidebar{width: '. $width .';}';
		}

		// Add to $css var
		if ( $add_css ) {
			$css .= '@media only screen and (min-width: 960px) and (max-width: 1280px){'. $add_css .'}';
			$add_css = '';
		}
		

		/*-----------------------------------------------------------------------------------*/
		/*	- Tablet Widths
		/*-----------------------------------------------------------------------------------*/

		// Main Container With
		if ( ( $width = get_theme_mod( 'tablet_main_container_width', '700px' ) ) && '700px' != $width ) {
			if ( 'gaps' == wpex_active_skin() ) {
				$add_css .= '.is-sticky .fixed-nav,#wrap{width:'. $width .'!important;}';
			} else {
				$add_css .= '.container,.vc_row-fluid.container,.boxed-main-layout #wrap,.boxed-main-layout .is-sticky #site-header,.boxed-main-layout .is-sticky .fixed-nav { width: '. $width .' !important; }';
			}
		}

		// Left container width
		if ( ( $width = get_theme_mod( 'tablet_left_container_width', '100%' ) ) && $width != '100%' ) {
			$add_css .= '.content-area{width:'. $width .';}';
		}

		// Sidebar width
		if ( ( $width = get_theme_mod( 'tablet_sidebar_width', '100%' ) ) && '100%' != $width ) {
			$add_css .= '#sidebar{width: '. $width .';}';
		}

		// Add to $css var
		if ( $add_css ) {
			$css .= '@media only screen and (min-width: 768px) and (max-width: 959px){'. $add_css .'}';
			$add_css = '';
		}


		/*-----------------------------------------------------------------------------------*/
		/*	- Phone Widths
		/*-----------------------------------------------------------------------------------*/
		
		// Phone Portrait
		$mobile_portrait_main_container_width = get_theme_mod( 'mobile_portrait_main_container_width' );
		if ( $mobile_portrait_main_container_width && '90%' != $mobile_portrait_main_container_width  ) {
			$css .= '@media only screen and (max-width: 767px) {
				.container { width: '. $mobile_portrait_main_container_width .' !important; min-width: 0; }
			}';
		}
		
		// Phone Landscape
		$mobile_landscape_main_container_width = get_theme_mod( 'mobile_landscape_main_container_width' );
		if ( $mobile_landscape_main_container_width && '90%' != $mobile_landscape_main_container_width ) {
			$css .= '@media only screen and (min-width: 480px) and (max-width: 767px) {
				.container { width: '. $mobile_landscape_main_container_width .' !important; }
			}';
		}
	
		// Return custom CSS
		if ( '' != $css && ! empty( $css ) ) {
			$css = '/*RESPONSIVE WIDTHS START*/'. $css;
			return $css;
		}
		
	}
}