<?php
/**
 * Function used to resize and crop images
 * 
 * @package		Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

if ( ! function_exists( 'wpex_image_resize' ) ) {
	function wpex_image_resize( $url, $width, $height = null, $crop = null, $return = 'url' ) {
		
		// Validate inputs
		if ( ! $url OR ! $width ) {
			false;
		}
		
		// Set dimensions
		$aq_width	= $width;
		$aq_height	= $height;
		
		// Define upload path & dir
		$upload_info	= wp_upload_dir();
		$upload_dir		= $upload_info['basedir'];
		$upload_url		= $upload_info['baseurl'];

		// WPML tweak
		global $sitepress;
		if ( $sitepress ) {
			$upload_url = $sitepress->convert_url( $upload_url );
		}
		
		// check if $img_url is local if not return full img
		if ( strpos( $url, $upload_url ) === false ) {
			if ( 'array' == $return ) {
				return array(
					'url'		=> $url,
					'width'		=> '',
					'height'	=> ''
				);
			} else {
				return $url;
			}
		}
		
		//define path of image
		$rel_path = str_replace( $upload_url, '', $url );
		$img_path = $upload_dir . $rel_path;
		
		// check if img path exists, and is an image indeed if not return full img
		if ( !file_exists( $img_path ) OR !getimagesize( $img_path ) ) {
			if ( 'array' == $return ) {
				return array(
					'url'		=> $url,
					'width'		=> '',
					'height'	=> ''
				);
			} else {
				return $url;
			}
		}
		
		//get image info
		$info						= pathinfo( $img_path );
		$ext						= $info['extension'];
		list( $orig_w, $orig_h )	= getimagesize( $img_path );
				
		//get image size after cropping
		$dims	= image_resize_dimensions( $orig_w, $orig_h, $aq_width, $aq_height, $crop );
		$dst_w	= $dims[4];
		$dst_h	= $dims[5];
		
		//use this to check if cropped image already exists, so we can return that instead
		$suffix = "{$dst_w}x{$dst_h}";
		$dst_rel_path = str_replace( '.'.$ext, '', $rel_path );
		$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";
		
		//can't resize, so return original url
		if ( ! $dst_h ) {
			$img_url	= $url;
			$dst_w		= $orig_w;
			$dst_h		= $orig_h;
		}
		
		//else check if cache exists
		elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
			$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
		}
		
		//else, we resize the image and return the new resized image url
		else {
			
			$editor = wp_get_image_editor( $img_path );
			
			// Return nothing if there is an error
			if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $aq_width, $aq_height, $crop ) ) ) {
				return false;
			}

			// Ger resized file
			$resized_file = $editor->save();

			// Return the resized image URL
			if ( ! is_wp_error( $resized_file ) ) {
				$resized_rel_path	= str_replace( $upload_dir, '', $resized_file['path'] );
				$img_url			= $upload_url . $resized_rel_path;
			}

			// Return nothing if there is an error
			else {
				return false;
			}
			
		}
		
		//retina support
		if ( get_theme_mod( 'retina' ) ) {
			
			// Define retina widths
			$retina_w	= $dst_w*2;
			$retina_h	= $dst_h*2;
			
			//get image size after cropping
			$dims_x2	= image_resize_dimensions( $orig_w, $orig_h, $retina_w, $retina_h, $crop );
			$dst_x2_w	= $dims_x2[4];
			$dst_x2_h	= $dims_x2[5];
			
			// If possible lets make the @2x image
			if ( $dst_x2_h ) {
			
				// @2x image url
				$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}@2x.{$ext}";
				
				// Check if retina image exists
				if ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
					// Already exists, do nothing
				} else {
					// Doesnt exist, lets create it
					$editor = wp_get_image_editor( $img_path );
					if ( ! is_wp_error( $editor ) ) {
						$editor->resize( $retina_w, $retina_h, $crop );
						$filename	= $editor->generate_filename( $dst_w . 'x' . $dst_h . '@2x' );
						$editor		= $editor->save( $filename );
					}
				}
			
			}
		
		}
		
		// Validate url, width, height
		$img_url	= isset( $img_url ) ? $img_url : $url;
		$dst_w		= isset( $dst_w ) ? $dst_w : '';
		$dst_h		= isset( $dst_h ) ? $dst_h : '';

		// Return Image data
		if ( 'url' == $return ) {
			return $img_url;
		} elseif ( 'array' == $return ) {
			return array(
				'url'		=> $img_url,
				'width'		=> $dst_w,
				'height'	=> $dst_h
			);
		} else {
			return $img_url;
		}

	}
}