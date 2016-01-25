<?php
$output = $el_class = $add_bg_style = '';
extract( shortcode_atts( array(
	'id'					=> '',
	'el_class'				=> '',
	'css_animation'			=> '',
	'visibility'			=> '',
	'tablet_fullwidth_cols'	=> '',
	'center_row'			=> '',
	'min_height'			=> '',
	'style'					=> '',
	'bg_color'				=> '',
	'bg_image'				=> '',
	'bg_style'				=> '',
	'border_color'			=> '',
	'border_style'			=> '',
	'border_width'			=> '',
	'margin_top'			=> '',
	'margin_bottom'			=> '',
	'margin_left'			=> '',
	'margin_right'			=> '',
	'padding_top'			=> '',
	'padding_bottom'		=> '',
	'padding_left'			=> '',
	'padding_right'			=> '',
	'border'				=> '',
	'video_bg'				=> '',
	'video_bg_mp4'			=> '',
	'video_bg_ogv'			=> '',
	'video_bg_webm'			=> '',
	'video_bg_overlay'		=> 'dashed-overlay',
	'parallax_speed'		=> '',
	'parallax_direction'	=> '',
	'parallax_style'		=> '',
	'parallax_mobile'		=> false,
	'css'					=> '',
	'no_margins'			=> '',
	'column_spacing'		=> '',
), $atts ) );

// Disable on mobile
if ( 'visible-desktop' == $visibility && wp_is_mobile() ) {
	return;
}

// Load VC js
wp_enqueue_script( 'wpb_composer_front_js' );

// Get extra classes
$el_class = $this->getExtraClass( $el_class );

// Column sizes
if( $column_spacing ) {
	$el_class .= ' column-padding-'. $column_spacing;
}

// No margins class
if( 'true' == $no_margins ) {
	$el_class .= ' no-margins';
}

// ID
if ( $id ) {
	$id = 'id="'. $id .'"';
}

// Prevent center rown when not full-screen
$wpex_post_id = wpex_get_the_ID();
if ( $wpex_post_id && 'full-screen' != wpex_get_post_layout_class( $wpex_post_id ) ) {
	$center_row = false;
}

// Animation
$css_animation_class = $css_animation !=='' ? 'wpb_animate_when_almost_visible wpb_'. $css_animation .'' : '';

// Is parallax allowed?
$parallax_class = '';
if ( wpex_is_front_end_composer() ) {
	$parallax_allowed = false;
} else {
	$parallax_allowed = true;
	if ( $bg_image ) {
		if ( 'parallax-advanced' == $bg_style || 'parallax'  == $bg_style ) {
			$parallax_class = 'row-with-parallax';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/*	- Background Image
/*-----------------------------------------------------------------------------------*/
if ( $bg_image && empty($video_bg) ) {
	$bg_img_url = wp_get_attachment_url( $bg_image );
	$bg_style = $bg_style ? $bg_style : 'stretch';
} else {
	$bg_img_url = NULL;
}
if ( $bg_style && $bg_image ) {
	$bg_style_class = 'vcex-background-'. $bg_style;
} else {
	$bg_style_class = '';
}

/*-----------------------------------------------------------------------------------*/
/*	- Advanced Parallax
/*-----------------------------------------------------------------------------------*/
if ( $parallax_allowed ) {

	$parallax_data_attr = $parallax_style_attr = '';

	// Disable parallax on mobile
	if ( wp_is_mobile() && 'on' != $parallax_mobile ) {
		$parallax_allowed = false;
	}

	// Create parallax data attributes and style
	if ( 'parallax-advanced' == $bg_style && $bg_image ) {

		// Define advanced parallax style
		if ( $parallax_style ) {
			$parallax_style = $parallax_style;
		} else {
			$parallax_style = 'fixed-repeat';
		}

		// Parallax Direction
		if ( $parallax_direction ) {
			$parallax_direction = $parallax_direction;
		} else {
			$parallax_direction = 'up';
		}

		// Parallax Speed
		if ( $parallax_speed ) {
			$parallax_speed = $parallax_speed;
		} else {
			$parallax_speed = '0.5';
		}

		// Set parallax data attributes
		if ( $parallax_allowed ) {
			$parallax_data_attr = 'data-direction="'. $parallax_direction .'" data-velocity="-'. abs( $parallax_speed ) .'"';
		}

		// Add parallax styles
		$parallax_style_attr = 'style="background-image: url('. $bg_img_url .');"';

	}

} elseif ( $bg_img_url && 'yes' != $video_bg && 'parallax-advanced' == $bg_style ) {
	$add_bg_style = 'background-image: url('. $bg_img_url .');';
}

/*-----------------------------------------------------------------------------------*/
/*	- Row Style - Margins
/*-----------------------------------------------------------------------------------*/
$vc_row_style = array();

if ( $margin_top ) {
	$vc_row_style[] = 'margin-top: ' . intval($margin_top) . 'px;';
}

if ( $margin_bottom ) {
	$vc_row_style[] = 'margin-bottom: ' . intval($margin_bottom) . 'px;';
}

if ( $margin_left ) {
	$vc_row_style[] = 'margin-left: ' . intval($margin_left) . 'px;';
}

if ( $margin_right ) {
	$vc_row_style[] = 'margin-right: ' . intval($margin_right) . 'px;';
}


$vc_row_style = implode('', $vc_row_style);

if ( $vc_row_style ) {
	$vc_row_style = wp_kses( $vc_row_style, array() );
	$vc_row_style = ' style="' . esc_attr( $vc_row_style ) . '"';
}

/*-----------------------------------------------------------------------------------*/
/*	- Main row Styling
/*-----------------------------------------------------------------------------------*/
$add_style = array();

if ( $min_height ) {
	$add_style[] = 'min-height: '. intval( $min_height ) .'px;';
}

if ( $bg_img_url && 'yes' != $video_bg && 'parallax-advanced' != $bg_style ) {
	$add_style[] = 'background-image: url('. $bg_img_url .');';
}

if ( isset( $add_bg_style ) ) {
	$add_style[] = $add_bg_style;
}

if ( $bg_color && 'yes' != $video_bg ) {
	$add_style[] = 'background-color: '. $bg_color .';';
} 

if ( $border_color && $border_style && $border_width ) {
	$add_style[] = 'border-color: '. $border_color .';';
	$add_style[] = 'border-style: '. $border_style .';';
	$add_style[] = 'border-width: '. $border_width .';';
}

if ( $padding_top ) {
	$add_style[] = 'padding-top: ' . intval( $padding_top ) . 'px;';
}

if ( $padding_bottom ) {
	$add_style[] = 'padding-bottom: ' . intval( $padding_bottom ) . 'px;';
}

if ( $padding_left ) {
	$add_style[] = 'padding-left: ' . intval( $padding_left ) . 'px;';
}

if ( $padding_right ) {
	$add_style[] = 'padding-right: ' . intval( $padding_right ) . 'px;';
}

$add_style = implode('', $add_style);

if ( $add_style ) {
	$add_style = wp_kses( $add_style, array() );
	$add_style = ' style="' . esc_attr( $add_style ) . '"';
}

// Full Width Columns on tablet class
if ( 'yes' == $tablet_fullwidth_cols ) {
	$el_class .= ' tablet-fullwidth-columns';
}

// Main VC class
if( function_exists( 'vc_shortcode_custom_css_class' ) ) {
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_row wpb_row '. ( $this->settings('base')==='vc_row_inner' ? 'vc_inner ' : '' ) . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
} else {
	$css_class =  apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row '.get_row_css_class().$el_class, $this->settings['base']);
}

// Skin Style
if ( $style ) {
	$style = ' vcex-skin-'. $style;
}

	/*-----------------------------------------------------------------------------------*/
	/*	- Output the row
	/*-----------------------------------------------------------------------------------*/
	$output .= '<div '. $id .' class="'. $css_class .' '. $css_animation_class . $visibility .' '. $parallax_class .'" '. $vc_row_style .'>';

		// Open wrap for video bgs
		if ( 'yes' == $video_bg ) {
			$output .= '<div class="vcex-video-bg-wrap clr '. $visibility .'">';
		}

		// Open background area div
		if ( $bg_image || $bg_color ) {
			$output .= '<div class="'. $bg_style_class .' vcex-row-bg-container clr '. $style .' '. $visibility .' '. $el_class .'" '. $add_style .'>';
		} elseif( $style || $el_class || $add_style ) {
			$output .= '<div class="clr'. $style .'" '. $add_style .'>';
		}

			// Center the row
			if ( $center_row == 'yes' ) {
				$output .= '<div class="container clr"><div class="center-row-inner clr">';
			}
			
				// Main Output
				$output .= wpb_js_remove_wpautop($content);

			// Center the row
			if ( $center_row == 'yes' ) {
				$output .= '</div></div>';
			}

			// Advanced Parallax Background
			if ( 'parallax-advanced' == $bg_style && $bg_img_url ) {
				$output .= '<div class="vcex-parallax-div '. $parallax_style .'" '. $parallax_style_attr .' '. $parallax_data_attr .'></div>';
			}

		// Close background area div
		if ( $bg_image || $bg_color || $style || $el_class || $add_style ) {
			$output .= '</div>';
		}

		/*-----------------------------------------------------------------------------------*/
		/*	- Video Background
		/*-----------------------------------------------------------------------------------*/
		if ( 'yes' == $video_bg ) {
			$output .= '<video class="vcex-video-bg" poster="'. $bg_image .'" preload="auto" autoplay="true" loop="loop" muted volume="0">';
				if ( $video_bg_webm !== '' ) {
					$output .= '<source src="'. $video_bg_webm .'" type="video/webm"/>';
				}
				if ( $video_bg_ogv !== '' ) {
					$output .= '<source src="'. $video_bg_ogv .'" type="video/ogg ogv" />';
				}
				if ( $video_bg_mp4 !== '' ) {
					$output .= '<source src="'. $video_bg_mp4 .'" type="video/mp4"/>';
				}
			$output .= '</video>';
			if ( $video_bg_overlay && $video_bg_overlay !== 'none' ) {
				$output .= '<span class="vcex-video-bg-overlay '. $video_bg_overlay .'-overlay"></span>';
			}
		}
		
		// Close video bg wrap
		if ( 'yes' == $video_bg ) {
			$output .= '</div>';
		}

	/*-----------------------------------------------------------------------------------*/
	/*	- Close Row
	/*-----------------------------------------------------------------------------------*/
	$output .= '</div>';

echo $output;