<?php
/**
 * Loads all functions for the Visual Composer
 *
 * @package		Total
 * @subpackage	Framework/Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 */

// Define paths
define( 'WPEX_VCEX_DIR', get_template_directory() .'/framework/visual-composer/extend/' );
define( 'WPEX_VCEX_DIR_URI', get_template_directory_uri() .'/framework/visual-composer/extend/' );

// Start Class
if ( ! class_exists( 'WPEX_VisualComposer' ) ) {
	class WPEX_VisualComposer {

		/**
		 * Start things up
		 */
		public function __construct() {

			// Framework directory
			$this->vcex_dir	= WPEX_VCEX_DIR;

			// Extend the Visual Composer
			if ( get_theme_mod( 'extend_visual_composer', true ) ) {

				// Include extension file
				require_once( $this->vcex_dir .'extend.php' );

			}

			// Check if we should edit the Visual Composer, if disabled you are on your own
			$this->is_edit_vc_enabled = apply_filters( 'wpex_edit_visual_composer', true );

			// Init
			add_action( 'init', array( $this, 'init' ) );

			// Admin Init
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// Include custom Shortcodes
			add_action( 'vc_before_init', array( $this, 'map_shortcodes' ) );

			// Tweak scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

			// Enque scripts for the admin
			add_action( 'admin_enqueue_scripts',  array( $this, 'admin_scripts' ) );

			// Display notice if the Visual Composer Extension plugin is still enabled
			if ( function_exists( 'visual_composer_extension_run' ) ) {
				add_action( 'admin_notices', array( $this, 'remove_vc_extension_notice' ) );
			}

			// Remove metaboxes
			add_action( 'do_meta_boxes', array( $this, 'remove_metaboxes' ) );

		}

		/**
		 * Run on init
		 */
		public function init() {
			
			// Set the visual composer to run in theme mod
			if ( function_exists( 'vc_set_as_theme' ) && get_theme_mod( 'visual_composer_theme_mode', true ) ) {
				vc_set_as_theme( $disable_updater = true );
			}

			// Remove elements
			$this->remove_elements();

		}

		/**
		 * Run on admin-init
		 */
		public function admin_init() {
			
			// Remove parameters
			$this->remove_params();

			// Add Params
			if ( $this->is_edit_vc_enabled && function_exists( 'vc_add_param' ) ) {
				require_once( $this->vcex_dir .'add-params.php' );
			}

			// Add new shortcode params
			$this->add_shortcode_params();

		}

		/**
		 * Map custom shortcodes
		 */
		public function map_shortcodes() {
			
			// Do nothing yet

		}

		/**
		 * Scripts
		 */
		public function scripts() {
			
			// Remove scripts while in the customizer to prevent the bug with the jQuery UI
			if ( is_customize_preview() ) {
				wp_deregister_script( 'wpb_composer_front_js' );
				wp_dequeue_script( 'wpb_composer_front_js' );
			}

		}

		/**
		 * Admin Scripts
		 */
		public function admin_scripts() {
			
			// Make sure we can edit the visual composer
			if ( ! $this->is_edit_vc_enabled ) {
				return;
			}

			// Load custom admin scripts
			wp_enqueue_style( 'vcex-admin-css', WPEX_VCEX_DIR_URI .'assets/admin.css' );

		}

		/**
		 * Display notice if the Visual Composer Extension plugin is still enabled
		 */
		public function remove_vc_extension_notice() { ?>
			<div class="error">
				<h4><?php _e( 'IMPORTANT NOTICE', 'wpex' ); ?></h4>
				<p><?php _e( 'The Visual Composer Extension Plugin (not WPBakery VC but the extension created by WPExplorer) for this theme is now built-in, please de-activate and if you want delete the plugin.', 'wpex' ); ?>
				<br /><br />
				<a href="<?php echo admin_url( 'plugins.php?plugin_status=active' ); ?>" class="button button-primary" target="_blank"><?php _e( 'Deactivate', 'wpex' ); ?> &rarr;</a></p>
				<p></p>
			</div>
		<?php }

		/**
		 * Remove metaboxes
		 *
		 * @link http://codex.wordpress.org/Function_Reference/do_meta_boxes
		 */
		public function remove_metaboxes() {

			// Make sure we can edit the visual composer
			if ( ! $this->is_edit_vc_enabled ) {
				return;
			}

			// Loop through post types and remove params
			$post_types = get_post_types( '', 'names' ); 
			foreach ( $post_types as $post_type ) {
				remove_meta_box( 'vc_teaser',  $post_type, 'side' );
			}

		}

		/**
		 * Remove modules
		 *
		 * @link http://kb.wpbakery.com/index.php?title=Vc_remove_element
		 */
		public function remove_elements() {

			// Make sure we can edit the visual composer
			if ( ! $this->is_edit_vc_enabled ) {
				return;
			}

			// Remove default Visual Composer Elements until fully tested and they work well
			vc_remove_element( 'vc_teaser_grid' );
			vc_remove_element( 'vc_posts_grid' );
			vc_remove_element( 'vc_posts_slider' );
			vc_remove_element( 'vc_carousel' );
			vc_remove_element( 'vc_wp_tagcloud' );
			vc_remove_element( 'vc_wp_archives' );
			vc_remove_element( 'vc_wp_calendar' );
			vc_remove_element( 'vc_wp_pages' );
			vc_remove_element( 'vc_wp_links' );
			vc_remove_element( 'vc_wp_posts' );
			vc_remove_element( 'vc_separator' );
			vc_remove_element( 'vc_gallery' );
			vc_remove_element( 'vc_wp_categories' );
			vc_remove_element( 'vc_wp_rss' );
			vc_remove_element( 'vc_wp_text' );
			vc_remove_element( 'vc_wp_meta' );
			vc_remove_element( 'vc_wp_recentcomments' );
			vc_remove_element( 'vc_images_carousel' );
			vc_remove_element( 'layerslider_vc' );

		}

		/**
		 * Remove params
		 *
		 * @link http://kb.wpbakery.com/index.php?title=Vc_remove_param
		 */
		public function remove_params() {

			// Make sure we can edit the visual composer
			if ( ! $this->is_edit_vc_enabled ) {
				return;
			}

			// Rows
			vc_remove_param( 'vc_row', 'font_color' );
			vc_remove_param( 'vc_row', 'padding' );
			vc_remove_param( 'vc_row', 'bg_color' );
			vc_remove_param( 'vc_row', 'bg_image' );
			vc_remove_param( 'vc_row', 'bg_image_repeat' );
			vc_remove_param( 'vc_row', 'margin_bottom' );
			vc_remove_param( 'vc_row', 'css' );

			// Row Inner
			vc_remove_param( 'vc_row_inner', 'css' );
			
			// Single Image
			vc_remove_param( 'vc_single_image', 'alignment' );

			// Seperator w/ Text
			vc_remove_param( 'vc_text_separator', 'color' );
			vc_remove_param( 'vc_text_separator', 'el_width' );
			vc_remove_param( 'vc_text_separator', 'accent_color' );

			// Columns
			vc_remove_param( 'vc_column', 'css' );
			vc_remove_param( 'vc_column', 'font_color' );

			// Column Inner
			vc_remove_param( 'vc_column_inner', 'css' );

			// Text Block
			vc_remove_param( 'vc_column_text', 'css' );

			// Text Block
			vc_remove_param( 'vc_single_image', 'css' );

			// Videos
			vc_remove_param( 'vc_video', 'css' );

		}

		/**
		 * Remove params
		 *
		 * @link http://kb.wpbakery.com/index.php?title=Vc_remove_param
		 */
		public function add_shortcode_params() {

			// Return nothing if function doesn't exist
			if ( ! function_exists( 'add_shortcode_param' ) ) {
				return;
			}

			// Add custom Font Awesome icon param
			add_shortcode_param( 'vcex_icon', array( $this, 'font_awesome_icon_param' ), WPEX_VCEX_DIR_URI .'assets/icon-type.js' );

		}

		/**
		 * Custom Font Awesome Icons param
		 *
		 * @link http://kb.wpbakery.com/index.php?title=Vc_remove_param
		 */
		public function font_awesome_icon_param( $settings, $value ) {
			$dependency = vc_generate_dependencies_attributes( $settings );
			$return = '<div class="my_param_block">
				<div class="vcex-font-awesome-icon-preview"></div>
				<input placeholder="' . __( "Type in an icon name or select one from below", 'wpex' ) . '" name="' . $settings['param_name'] . '"'
			. ' data-param-name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'].' '.$settings['type'].'_field" type="text" value="'. $value .'" ' . $dependency . ' style="width: 100%; vertical-align: top; margin-bottom: 10px" />';
			$return .= '<div class="vcex-font-awesome-icon-select-window">
						<span class="fa fa-times" style="color:red;" data-name="clear"></span>';
							$icons = wpex_get_awesome_icons();
							foreach ( $icons as $icon ) {
								if ( '' != $icon ) {
									if ( $value == $icon ) {
										$active = 'active';
									} else {
										$active = '';
									}
									$return .= '<span class="fa fa-'. $icon .' '. $active .'" data-name="'. $icon .'"></span>';
								}
							}
			$return .= '</div></div><div style="clear:both;"></div>';
			return $return;
		}

	}
}
new WPEX_VisualComposer();