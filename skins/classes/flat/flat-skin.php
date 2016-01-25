<?php
/**
 * Flat Skin Class
 *
 * @package WordPress
 * @subpackage Total
 * @since Total 1.3
*/

if ( !class_exists( "Total_Flat_Skin" ) ) {
	class Total_Flat_Skin {

		// Constructor
		function __construct() {
			add_action( 'wp_enqueue_scripts', array( &$this, 'load_styles' ), 11 );
		}

		// Load Styles
		public function load_styles() {
			wp_enqueue_style( 'flat-skin', WPEX_SKIN_DIR_URI .'classes/flat/css/flat-style.css', array( 'wpex-style' ), '1.0', 'all' );
		}

	}
}

// Start Class
$wpex_skin_class = new Total_Flat_Skin();