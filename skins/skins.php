<?php
/**
 * Returns theme skins
 *
 * @package 	Total
 * @subpackage	Skins
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.5.4
 */

/**
 * Array of theme skins
 *
 * @since Total 1.5.4
 */
if ( ! function_exists( 'wpex_skins' ) ) {
	function wpex_skins() {

		// Create an array of theme skins
		$skins = array(
			'base'	=> array (
				'core'			=> true,
				'name'			=> __( 'Base', 'wpex' ),
				'screenshot'	=> WPEX_SKIN_DIR_URI .'classes/base/screenshot.jpg',
			),
			'agent'	=> array(
				'core'			=> true,
				'name'			=> __( 'Agent', 'wpex' ),
				'class'			=> WPEX_SKIN_DIR .'classes/agent/agent-skin.php',
				'screenshot'	=> WPEX_SKIN_DIR_URI .'classes/agent/screenshot.jpg',
			),
			'neat'	=> array(
				'core'			=> true,
				'name'			=> __( 'Neat', 'wpex' ),
				'class'			=> WPEX_SKIN_DIR .'classes/neat/neat-skin.php',
				'screenshot'	=> WPEX_SKIN_DIR_URI .'classes/neat/screenshot.jpg',
			),
			'flat'	=> array(
				'core'			=> true,
				'name'			=> __( 'Flat', 'wpex' ),
				'class'			=> WPEX_SKIN_DIR .'classes/flat/flat-skin.php',
				'screenshot'	=> WPEX_SKIN_DIR_URI .'classes/flat/screenshot.jpg',
			),
			'gaps'	=> array(
				'core'			=> true,
				'name'			=> __( 'Gaps', 'wpex' ),
				'class'			=> WPEX_SKIN_DIR .'classes/gaps/gaps-skin.php',
				'screenshot'	=> WPEX_SKIN_DIR_URI .'classes/gaps/screenshot.jpg',
			),
			'minimal-graphical'	=> array(
				'core'			=> true,
				'name'			=> __( 'Minimal Graphical', 'wpex' ),
				'class'			=> WPEX_SKIN_DIR .'classes/minimal-graphical/minimal-graphical-skin.php',
				'screenshot'	=> WPEX_SKIN_DIR_URI .'classes/minimal-graphical/screenshot.jpg',
			),
		);

		// Add filter so you can create more skins via child themes or plugins
		$skins = apply_filters( 'wpex_skins', $skins );

		// Return skins
		return $skins;

	}
}

/**
 * Setup Skins Admin Panel
 *
 * @since Total 1.5.4
 */
require_once( WPEX_SKIN_DIR . 'admin/skins-admin.php' );

/**
 * Get active skin
 *
 * @since Total 1.5.4
 */
if ( ! function_exists( 'wpex_active_skin') ) {
	function wpex_active_skin() {

		// Get skin from theme mod
		$skin = get_theme_mod( 'theme_skin', 'base' );

		// Fallback for old Redux skin option
		if ( ! $skin ) {
			$data = get_option( 'wpex_options' );
			if ( ! empty ( $data['site_theme'] ) ) {
				$skin = $data['site_theme'];
			}
		}

		// Sanitize
		$skin = ! empty( $skin ) ? $skin : 'base';

		// Return current skin
		return $skin;
	}
}

/**
 * Get active class file for selected skin
 *
 * @since Total 1.5.4
 */
if ( ! function_exists( 'wpex_active_skin_class_file') ) {
	function wpex_active_skin_class_file() {

		// Get active skin
		$active_skin = wpex_active_skin();

		// Lets bail if the active skin is the base skin
		if ( 'base' == $active_skin || ! $active_skin ) {
			return;
		}

		// Get currect skin class to load later
		$skins				= wpex_skins();
		$active_skin_array	= wp_array_slice_assoc( $skins, array( $active_skin ) );
		if ( is_array( $active_skin_array ) ) {
			$is_core	= ! empty( $active_skin_array[$active_skin]['core'] ) ? true : false;
			$class_file	= ! empty( $active_skin_array[$active_skin]['class'] ) ? $active_skin_array[$active_skin]['class'] : false;
		}

		// Return class file if one exists
		if ( $is_core && $class_file ) {
			return $class_file;
		}

	}
}

/**
 * Include the active skin class file
 *
 * @since Total 1.6.0
 */
if ( ! function_exists( 'wpex_load_active_skin_class') ) {
	function wpex_load_active_skin_class() {
		if ( $class = wpex_active_skin_class_file() ) {
			require_once( $class );
		}
	}
}

// Load the skin class file
wpex_load_active_skin_class();	