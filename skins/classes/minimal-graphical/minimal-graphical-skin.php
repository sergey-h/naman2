<?php
/**
 * Minimal Graphical Skin Class
 *
 * @package WordPress
 * @subpackage Total
 * @since Total 1.3
*/


if ( !class_exists( "Total_MG_Skin" ) ) {

	class Total_MG_Skin {

		// Constructor
		function __construct() {
			add_action( 'wp_enqueue_scripts', array( &$this, 'load_styles' ), 11 );
		}

		// Load Styles
		public function load_styles() {
			wp_enqueue_style( 'minimal-graphical-skin', WPEX_SKIN_DIR_URI .'classes/minimal-graphical/css/minimal-graphical-style.css', array( 'wpex-style' ), '1.0', 'all' );
		}

	}

}

// Start Class
$wpex_skin_class = new Total_MG_Skin();