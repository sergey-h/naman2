<?php
/**
 * Total functions and definitions.
 * Text Domain: wpex
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * Total is a very powerful theme and virtually anything can be customized
 * via a child theme. If you need any help altering a function, just let us know!
 * Customizations aren't included for free but if it's a simple task I'll be sure to help :)
 * 
 * @package		Total
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/*-----------------------------------------------------------------------------------*/
/*	- Define Constants
/*-----------------------------------------------------------------------------------*/

// Assets Paths
define( 'WPEX_JS_DIR_URI', get_template_directory_uri() .'/js/' );
define( 'WPEX_CSS_DIR_UIR', get_template_directory_uri() .'/css/' );

// Skins Paths
define( 'WPEX_SKIN_DIR', get_template_directory() .'/skins/' );
define( 'WPEX_SKIN_DIR_URI', get_template_directory_uri() .'/skins/' );

// Framework Paths
define( 'WPEX_FRAMEWORK_DIR', get_template_directory() .'/framework/' );
define( 'WPEX_FRAMEWORK_DIR_URI', get_template_directory_uri() .'/framework/' );

// Admin Panel Hook
define( 'WPEX_ADMIN_PANEL_HOOK_PREFIX', 'theme-panel_page_' );

// Check if plugins are active
define( 'WPEX_VC_ACTIVE', class_exists( 'Vc_Manager' ) );
define( 'WPEX_BBPRESS_ACTIVE', class_exists( 'bbPress' ) );
define( 'WPEX_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
define( 'WPEX_REV_SLIDER_ACTIVE', class_exists( 'RevSlider' ) );
define( 'WPEX_LAYERSLIDER_ACTIVE', function_exists( 'lsSliders' ) );

// Active post types
define( 'WPEX_PORTFOLIO_IS_ACTIVE', get_theme_mod( 'portfolio_enable', true ) );
define( 'WPEX_STAFF_IS_ACTIVE', get_theme_mod( 'staff_enable', true ) );
define( 'WPEX_TESTIMONIALS_IS_ACTIVE', get_theme_mod( 'testimonials_enable', true ) );

// Define branding constant based on theme options
define( 'WPEX_THEME_BRANDING', get_theme_mod( 'theme_branding', 'Total' ) );

// Set variables for use Below
$wpex_is_admin		= is_admin();
$wpex_framework_dir	= WPEX_FRAMEWORK_DIR;

/*-----------------------------------------------------------------------------------*
/*	- Main Theme Setup Class
/*	- Perform basic setup, registration, and init actions for the theme
/*	- Loads all core back-end and front-end scripts
/*-----------------------------------------------------------------------------------*/
require_once( $wpex_framework_dir .'theme-setup.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Admin Pages + Globals
/*-----------------------------------------------------------------------------------*/

// Theme setup - load_theme_domain, add_theme_support, register_nav_menus
require_once( $wpex_framework_dir .'theme-setup.php' );

// Deprecated functions
require_once( $wpex_framework_dir .'deprecated.php' );

// Core Functions, these are functions used througout the theme and need to load first
require_once( $wpex_framework_dir .'core-functions.php' );

// Conditionals
require_once( $wpex_framework_dir .'conditionals.php' );

// Useful arrays
require_once( $wpex_framework_dir .'arrays.php' );

// Array of Fonts - Font-Awesome & Google Fonts
require_once( $wpex_framework_dir .'fonts.php' );

// HTML elements outputted with PHP to make theming/RTL easier
require_once( $wpex_framework_dir .'elements.php' );

// Image overlays - Load first because its needed in the admin
require_once( $wpex_framework_dir .'overlays.php' );

// Recommend some useful plugins for this theme via TGMA script
require_once( get_template_directory() .'/plugins/class-tgm-plugin-activation.php' );
require_once( get_template_directory() .'/plugins/recommend-plugins.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Migrate to Total 1.6.0 - this function should only run once
/*-----------------------------------------------------------------------------------*/
//delete_option( 'wpex_customizer_migration_complete' ); //<= Uncomment to re-run the migration
if ( ! get_option( 'wpex_customizer_migration_complete' ) ) {
	require_once( WPEX_FRAMEWORK_DIR .'helpers/migrate.php' );
}

/*-----------------------------------------------------------------------------------*/
/*	- Theme Options & Addons
/*-----------------------------------------------------------------------------------*/

// Tweaks addons page + Registers the main addons page
require_once( $wpex_framework_dir .'addons/tweaks.php' );

// Favicons
if ( get_theme_mod( 'favicons_enable', true ) ) {
	require_once( $wpex_framework_dir .'addons/favicons.php' );
}

// Custom 404
if ( get_theme_mod( 'custom_404_enable', true ) ) {
	require_once( $wpex_framework_dir .'addons/custom-404.php' );
}

// Custom widget areas
if ( get_theme_mod( 'widget_areas_enable', true ) ) {
	require_once( $wpex_framework_dir .'addons/widget-areas.php' );
}

// Custom Login
if ( get_theme_mod( 'custom_admin_login_enable', true ) ) {
	require_once( $wpex_framework_dir .'addons/custom-login.php' );
}

// Custom CSS
if ( get_theme_mod( 'custom_css_enable', true ) ) {
	require_once( $wpex_framework_dir .'addons/custom-css.php' );
}

// Skins
if ( get_theme_mod( 'skins_enable', true ) ) {
	require_once( WPEX_SKIN_DIR . 'skins.php' );
}

// Customizer
require_once( $wpex_framework_dir .'customizer/customizer.php' );
//require_once( $wpex_framework_dir .'helpers/customizer-js-generator.php' );

// Import Export Functions
if ( $wpex_is_admin ) {
	require_once( $wpex_framework_dir .'addons/import-export.php' );
}

/*-----------------------------------------------------------------------------------*/
/*	- Hooks - VERY IMPORTANT - DONT DELETE EVER!!
/*-----------------------------------------------------------------------------------*/
require_once( $wpex_framework_dir .'hooks/hooks.php' );
require_once( $wpex_framework_dir .'hooks/actions.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Meta functions
/*-----------------------------------------------------------------------------------*/

// Post Meta Fields
require_once( $wpex_framework_dir .'meta/post-meta/class.php');

// Meta fields for Standard Categories
require_once( $wpex_framework_dir .'meta/taxonomies/category-meta.php');

// Gallery metabox function used to define images for your gallery post format
require_once( $wpex_framework_dir .'meta/gallery-metabox/admin.php' );

// Gallery metabox helper functions
require_once( $wpex_framework_dir .'meta/gallery-metabox/helpers.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Advanced Styles, Custom Layout, Backgrounds
/*-----------------------------------------------------------------------------------*/

// Outputs css for theme panel styling options
require_once( $wpex_framework_dir .'design/advanced-styling.php' );

// Outputs css for layout options (site widths)
require_once( $wpex_framework_dir .'design/layout.php' );

// Custom background options
require_once( $wpex_framework_dir .'design/backgrounds.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Global/Core Functions
/*-----------------------------------------------------------------------------------*/

// Adds classes to the body tag
require_once( $wpex_framework_dir .'body-classes.php' );

// Togglebar functions
require_once( $wpex_framework_dir .'togglebar.php' );
	
// Topbar functions
require_once( $wpex_framework_dir .'topbar.php' );

// Header Output
require_once( $wpex_framework_dir .'header-functions.php' );

// Search functions
require_once( $wpex_framework_dir .'search-functions.php' );

// Built-in breadcrumbs function
require_once( $wpex_framework_dir .'breadcrumbs.php' );
	
// The main page title class - displays title/breadcrumbs/title backgrounds/subheading - etc.
require_once( $wpex_framework_dir .'page-header.php' );

// Main menu functions
require_once( $wpex_framework_dir .'menu-functions.php' );

// Speeds up menus in the dashboard
if ( $wpex_is_admin ) {
	require_once( $wpex_framework_dir .'faster-menu-dashboard.php' );
}

// Resize and crop images
require_once( $wpex_framework_dir .'thumbnails/image-resize.php' );

// Theme Shortcodes
require_once( $wpex_framework_dir .'thumbnails/honor-ssl-for-attachments.php' );

// Theme Shortcodes
require_once( $wpex_framework_dir .'shortcodes/shortcodes.php' );

// Add fields to media items
require_once( $wpex_framework_dir .'thumbnails/media-fields.php' );

// Retuns the correct post layout class
require_once( $wpex_framework_dir .'post-layout.php' );

// Used to tweak the_excerpt() function and also defines the wpex_excerpt() function
require_once( $wpex_framework_dir .'excerpts.php' );

// TinyMCE buttons & edits
require_once( $wpex_framework_dir .'tinymce.php' );

// Function used to display featured images in your dashboard columns
require_once( $wpex_framework_dir .'thumbnails/dashboard-thumbnails.php' );

// Returns the correct cropped or non-cropped featured image URLs
require_once( $wpex_framework_dir .'thumbnails/featured-images.php');

// Returns featured image caption
require_once( $wpex_framework_dir .'thumbnails/featured-image-caption.php');

// Alter the default output of the WordPress gallery shortcode
if ( get_theme_mod( 'custom_wp_gallery', true ) ) {
	require_once( $wpex_framework_dir .'wp-gallery.php');
}

// Blog functions
require_once( $wpex_framework_dir .'blog/blog-functions.php' );

// Footer functions
require_once( $wpex_framework_dir .'footer/footer-functions.php' );

// Comments callback function
require_once( $wpex_framework_dir .'comments-callback.php');

// Used to display your post slider as defined in the wpex_post_slider meta value
require_once( $wpex_framework_dir .'post-slider.php' );

// Outputs the social sharing icons for posts and pages
require_once( $wpex_framework_dir .'social-share.php' );

// Add bbPress post type to search
require_once( $wpex_framework_dir .'bbpress/bbpress-search.php' );

// Function used to alter the number of posts displayed for your custom post type archives
if ( ! $wpex_is_admin ) {
	require_once( $wpex_framework_dir .'posts-per-page.php' );
}

// Alter the password protection form
require_once( $wpex_framework_dir .'password-protection-form.php' );

// Pagination output
require_once( $wpex_framework_dir .'pagination.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Custom Widgets
/*-----------------------------------------------------------------------------------*/
require_once( $wpex_framework_dir . 'widgets/widget-social.php' );
require_once( $wpex_framework_dir . 'widgets/widget-social-fontawesome.php' );
require_once( $wpex_framework_dir . 'widgets/widget-modern-menu.php' );
require_once( $wpex_framework_dir . 'widgets/widget-simple-menu.php' );
require_once( $wpex_framework_dir . 'widgets/widget-flickr.php' );
require_once( $wpex_framework_dir . 'widgets/widget-video.php' );
require_once( $wpex_framework_dir . 'widgets/widget-posts-thumbnails.php' );
require_once( $wpex_framework_dir . 'widgets/widget-recent-posts-thumb-grid.php' );
require_once( $wpex_framework_dir . 'widgets/widget-posts-icons.php' );
require_once( $wpex_framework_dir . 'widgets/widget-comments-avatar.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Portfolio Post Type
/*-----------------------------------------------------------------------------------*/
if ( WPEX_PORTFOLIO_IS_ACTIVE ) {
	
	// Register the portfolio post type
	require_once( $wpex_framework_dir .'portfolio/portfolio-register.php' );

	// Portfolio Admin Page
	require_once( $wpex_framework_dir .'portfolio/portfolio-editor.php' );

	// Useful Portfolio functions
	require_once( $wpex_framework_dir .'portfolio/portfolio-functions.php' );

	// Displays an array of portfolio categories
	require_once( $wpex_framework_dir .'portfolio/portfolio-categories.php' );

	// Portfolio Entry Content
	require_once( $wpex_framework_dir .'portfolio/portfolio-entry.php' );

}

/*-----------------------------------------------------------------------------------*/
/*	- Post Series Taxonomy
/*-----------------------------------------------------------------------------------*/
if ( get_theme_mod( 'post_series_enable', true ) ) {
	require_once( $wpex_framework_dir .'blog/register-post-series.php' );
}

/*-----------------------------------------------------------------------------------*/
/*	- Staff Post Type
/*-----------------------------------------------------------------------------------*/
if ( WPEX_STAFF_IS_ACTIVE ) {
	
	// Register the staff custom post type
	require_once( $wpex_framework_dir .'staff/staff-register.php' );

	// Staff Admin Page
	require_once( $wpex_framework_dir .'staff/staff-editor.php' );
	
}

// Useful staff functions
require_once( $wpex_framework_dir .'staff/staff-functions.php' );

/*-----------------------------------------------------------------------------------*/
/*	- Testimonials Post Type
/*-----------------------------------------------------------------------------------*/
if ( WPEX_TESTIMONIALS_IS_ACTIVE ) {
	
	// Register the testimonials custom post type
	require_once( $wpex_framework_dir .'testimonials/testimonials-register.php' );

	// Testimonials Admin Page
	require_once( $wpex_framework_dir .'testimonials/testimonials-editor.php' );
	
}

/*-----------------------------------------------------------------------------------*/
/*	- Custom Post Type & Taxonomy Functions
/*-----------------------------------------------------------------------------------*/

// Remove custom post type slugs => Experimental feature
if ( get_theme_mod( 'remove_posttype_slugs' ) ) {
	require_once( $wpex_framework_dir .'remove-posttype-slugs.php' );
}

/*-----------------------------------------------------------------------------------*/
/*	- WooCommerce
/*-----------------------------------------------------------------------------------*/
if ( WPEX_WOOCOMMERCE_ACTIVE ) {

	// WooCommerce core tweaks
	require_once( $wpex_framework_dir .'woocommerce/woocommerce-tweaks-class.php' );

	// WooCommerce menu icon and functions
	if ( get_theme_mod( 'woo_menu_icon', true ) ) {
		require_once( $wpex_framework_dir .'woocommerce/woo-menucart.php' );
		require_once( $wpex_framework_dir .'woocommerce/woo-cartwidget-overlay.php' );
		require_once( $wpex_framework_dir .'woocommerce/woo-cartwidget-dropdown.php' );
	}

}

/*-----------------------------------------------------------------------------------*/
/*	- Visual Composer
/*-----------------------------------------------------------------------------------*/
if ( WPEX_VC_ACTIVE ) {
	require_once( $wpex_framework_dir .'visual-composer/visual-composer.php' );
}

/*-----------------------------------------------------------------------------------*/
/*	- Automatic updates
/*-----------------------------------------------------------------------------------*/
if ( get_theme_mod( 'envato_license_key' ) ) {
	require_once( get_template_directory() .'/wp-updates-theme.php');
	new WPUpdatesThemeUpdater_479( 'http://wp-updates.com/api/2/theme', basename( get_template_directory() ), get_theme_mod( 'envato_license_key' ) );
}