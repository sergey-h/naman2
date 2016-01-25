<?php
/**
 * Custom excerpt functions
 * 
 * http://codex.wordpress.org/Function_Reference/wp_trim_words
 *
 * @package		Total
 * @subpackage	functions
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Custom Excerpt output function
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_excerpt' ) ) {
	function wpex_excerpt( $args = array() ) {

		// Globals
		global $post;

		// Set main function vars
		$output					= '';
		$post_id				= isset( $args['post_id'] ) ? $args['post_id'] : $post->ID;
		$length					= isset( $args['length'] ) ? $args['length'] : '30';
		$readmore				= isset( $args['readmore'] ) ? $args['readmore'] : false;
		$read_more_text			= isset( $args['read_more_text'] ) ? $args['read_more_text'] : __( 'view post', 'wpex' );
		$post_id				= isset( $args['post_id'] ) ? $args['post_id'] : '';
		$trim_custom_excerpts	= isset( $args['trim_custom_excerpts'] ) ? $args['trim_custom_excerpts'] : get_theme_mod( 'trim_custom_excerpts', true );
		$more					= isset( $args['more'] ) ? $args['more'] : '&hellip;';
		$echo					= isset( $args['echo'] ) ? $args['echo'] : true;

		// Fallback for old method
		$args_count = count( $args );
		if ( 1 == $args_count ) {
			$length = $args;;
		}

		// Prevent more tag bug
		if ( is_home() && is_main_query() && strpos( $post->post_content, '<!--more-->' )  ) {
			the_content( '' );
			return;
		}

		// Get post data
		$custom_excerpt	= $post->post_excerpt;
		$post_content	= get_the_content( $post_id );

		// Display password protected error
		if ( post_password_required( $post_id ) ) {
			$password_protected_excerpt = __( 'This is a password protected post.', 'wpex' );
			$password_protected_excerpt = apply_filters( 'wpex_password_protected_excerpt', $password_protected_excerpt );
			echo '<p>'. $password_protected_excerpt .'</p>'; return;
		}
		// Return The Excerpt
		if ( '0' != $length ) {
			// Custom Excerpt
			if ( $custom_excerpt ) {
				if ( '-1' == $length || ! $trim_custom_excerpts ) {
					$output	= $custom_excerpt;
				} else {
					$output	= '<p>'. wp_trim_words( $custom_excerpt, $length, $more ) .'</p>';
				}
			} else {
				// Return the content
				if ( '-1' ==  $length ) {
					return apply_filters( 'the_content', $post_content );
				}
				// Check if text shortcode in post
				if ( strpos( $post_content, '[vc_column_text]' ) ) {
					$pattern = '{\[vc_column_text\](.*?)\[/vc_column_text\]}is';
					preg_match( $pattern, $post_content, $match );
					if( isset( $match[1] ) ) {
						//$excerpt = str_replace('[vc_column_text]', '', $match[0] );
						//$excerpt = str_replace('[/vc_column_text]', '', $excerpt );
						$excerpt	= wp_trim_words( $match[1], $length, $more );
					} else {
						$content	= strip_shortcodes( $post_content );
						$excerpt	= wp_trim_words( $content, $length, $more );
					}
				} else {
					$content	= strip_shortcodes( $post_content );
					$excerpt	= wp_trim_words( $content, $length, $more );
				}
				// Output Excerpt
				$output .= '<p>'. $excerpt .'</p>';
			}

			// Readmore link
			if ( $readmore ) {
				$readmore_link = '<a href="'. get_permalink( $post_id ) .'" title="'.$read_more_text .'" rel="bookmark" class="vcex-readmore theme-button">'. $read_more_text .' <span class="vcex-readmore-rarr">&rarr;</span></a>';
				$output .= apply_filters( 'vcex_readmore_link', $readmore_link );
			}
			
			// Output
			if ( $echo ) {
				echo $output;
			} else {
				return $output;
			}
		}

	}
}

/**
 * Custom excerpt length for posts
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_excerpt_length' ) ) {
	function wpex_excerpt_length() {

		// Theme panel length setting
		$length = get_theme_mod( 'blog_excerpt_length', '40');

		// Taxonomy setting
		if ( is_category() ) {
			
			// Get taxonomy meta
			$term		= get_query_var('cat');
			$term_data	= get_option("category_$term");
			if ( ! empty( $term_data['wpex_term_excerpt_length'] ) ) {
				$length = $term_data['wpex_term_excerpt_length'];
			}
		}

		// Return length and add filter for quicker child theme editign
		return apply_filters( 'wpex_excerpt_length', $length );

	}
}

/**
 * Change default read more style
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_excerpt_more' ) ) {
	function wpex_excerpt_more($more) {
		global $post;
		return '...';
	}
}
add_filter( 'excerpt_more', 'wpex_excerpt_more' );

/**
 * Change default excerpt length
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_custom_excerpt_length' ) ) {
	function wpex_custom_excerpt_length( $length ) {
		return get_theme_mod( 'excerpt_length', '65' );
	}
}
add_filter( 'excerpt_length', 'wpex_custom_excerpt_length', 999 );

/**
 * Prevent Page Scroll When Clicking the More Link
 * http://codex.wordpress.org/Customizing_the_Read_More
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_remove_more_link_scroll' ) ) {
	function wpex_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'wpex_remove_more_link_scroll' );