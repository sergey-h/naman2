<?php
/**
 * Registers the skillbar shortcode and adds it to the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.4.1
 */

if ( ! function_exists('vcex_spacing_shortcode') ) {
	function vcex_spacing_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'size'			=> '20px',
			'class'			=> '',
			'visibility'	=> '',
		),
		$atts ) );
		$classes = 'vcex-spacing';
		if ( $class ) {
			$add_classes .= ' '. $class;
		}
		if ( $visibility ) {
			$add_classes .= ' '. $visibility;
		}
		if ( wpex_is_front_end_composer() ) {
			return '<div class="vc-spacing-shortcode '. $classes .'" style="height: '. $size .'"></div>';
		} else {
			return '<hr class="'. $classes .'" style="height: '. $size .'" />';
		}
	}
}
add_shortcode( 'vcex_spacing', 'vcex_spacing_shortcode' );

if ( ! function_exists( 'vcex_spacing_shortcode_vc_map' ) ) {
	function vcex_spacing_shortcode_vc_map() {
		vc_map( array(
			'name'					=> __( 'Spacing', 'wpex' ),
			'description'			=> __( 'Adds spacing anywhere you need it.', 'wpex' ),
			'base'					=> 'vcex_spacing',
			'category'				=> WPEX_THEME_BRANDING,
			'icon'					=> 'vcex-spacing',
			'params'				=> array(
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Spacing', 'wpex' ),
					'param_name'	=> 'size',
					'value'			=> '30px',
				),
				array(
					'type'			=> 'textfield',
					'admin_label'	=> true,
					'heading'		=> __( 'Classname', 'wpex' ),
					'param_name'	=> 'class',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> __( 'Visibility', 'wpex' ),
					'param_name'	=> 'visibility',
					'value'			=> wpex_visibility_array(),
				),
			)
		) );
	}
}
add_action( 'admin_init', 'vcex_spacing_shortcode_vc_map' );