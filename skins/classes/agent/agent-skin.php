<?php
/**
 * Agent Skin Class
 *
 * @package WordPress
 * @subpackage Total
 * @since Total 1.3
*/

if ( !class_exists( "Total_Agent_Skin" ) ) {
	class Total_Agent_Skin {

		// Constructor
		function __construct() {
			add_action( 'wp_enqueue_scripts', array( &$this, 'load_styles' ), 11 );
		}

		// Load Styles
		public function load_styles() {
			wp_enqueue_style( 'agent-skin', WPEX_SKIN_DIR_URI .'classes/agent/css/agent-style.css', array( 'wpex-style' ), '1.0', 'all' );
		}

	}

}
$wpex_skin_class = new Total_Agent_Skin();