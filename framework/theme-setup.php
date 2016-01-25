<?php
/**
 * Main Theme Setup Class
 * Perform basic setup, registration, and init actions for the theme
 * Loads all core back-end and front-end scripts
 *
 * @package 	Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Defines the site content width
 *
 * @since Total 1.0.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980;
}

/**
 * Main Theme Class
 *
 * @since Total 1.6.0
 */
class WPEX_Theme_Setup {

	/**
	 * Define some useful variables
	 */
	private $css_dir_uri	= WPEX_CSS_DIR_UIR;
	private $js_dir_uri		= WPEX_JS_DIR_URI;
	private $theme_branding	= WPEX_THEME_BRANDING;

	/**
	 * Start things up
	 */
	public function __construct() {

		// Vars
		$this->is_responsive_enabled	= get_theme_mod( 'responsive', true );
		$this->lightbox_skin			= apply_filters( 'wpex_lightbox_skin', get_theme_mod( 'lightbox_skin', 'dark' ) );

		// Load text domain
		load_theme_textdomain( 'wpex', get_template_directory() .'/languages' );

		// Perform basic setup, registration, and init actions for a theme.
		add_action( 'after_setup_theme', array( $this, 'setup' ) );

		// Functions called during theme switch
		add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ) );

		// Ads meta tags to wp_head with a higher priority
		add_action( 'wp_head', array( $this, 'meta_viewport' ), 1 );

		// Hook into wp_head
		add_action( 'wp_head', array( $this, 'wp_head' ) );

		// Alter the default wp title tag if Yoast SEO is not enabled
		if ( ! function_exists( 'wpseo_auto_load' ) ) {
			add_action( 'wp_title', array( $this, 'wp_title' ), 10, 2 );
		}

		// Enque Admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Enque Front-End scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'front_end_scripts' ) );

		// Register custom widget areas
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );

		// Alter tag cloud arguments
		add_filter( 'widget_tag_cloud_args', array( $this, 'widget_tag_cloud_args' ) );

		// Alter wp list categories arguments
		add_filter( 'wp_list_categories', array( $this, 'wp_list_categories_args' ) );

		// Exclude categories from the blog
		add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		// LayerSlider tweaks
		add_action( 'layerslider_ready', array( $this, 'layerslider_tweaks' ) );

		// Add new user fields
		add_filter( 'user_contactmethods', array( $this, 'user_fields' ) );

		// Add responsive wrapper around oembeds
		add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html' ), 99, 4 );

		// Remove scripts src
		if ( get_theme_mod( 'remove_scripts_version', true ) ) {
			add_filter( 'style_loader_src', array( $this, 'remove_scripts_version' ), 9999 );
			add_filter( 'script_loader_src', array( $this, 'remove_scripts_version' ), 9999 );
		}

	}

	/**
	 * Functions called during each page load, after the theme is initialized
	 * Perform basic setup, registration, and init actions for the theme
	 *
	 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/after_setup_theme
	 */
	public function setup() {

		// Register navigation menus
		register_nav_menus (
			array(
				'main_menu'			=> __( 'Main', 'wpex' ),
				'mobile_menu'		=> __( 'Mobile Icons', 'wpex' ),
				'mobile_menu_alt'	=> __( 'Mobile Menu Alternative', 'wpex' ),
				'footer_menu'		=> __( 'Footer', 'wpex' ),
			)
		);

		// Enable some useful post formats for the blog
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'quote', 'link' ) );
		
		// Add automatic feed links in the header - for themecheck nagg
		add_theme_support( 'automatic-feed-links' );
		
		// Enable featured image support
		add_theme_support( 'post-thumbnails' );
		
		// And HTML5 support
		add_theme_support( 'html5' );
		
		// Enable excerpts for pages.
		add_post_type_support( 'page', 'excerpt' );
		
		// Add support for WooCommerce - Yay!
		add_theme_support( 'woocommerce' );

		// Add styles to the WP editor
		add_editor_style( 'css/editor-style.css' );

	}

	/**
	 * This function never run, but it does prevent some theme-check nags.
	 */
	private function stop_nagging_me() {
		add_theme_support( 'custom-header' );
	}

	/**
	 * Functions called during initialization
	 */
	public function after_switch_theme() {

		// Flush rewrite rules to prevent 404 errors on custom post types
		flush_rewrite_rules();

	}

	/**
	 * Adds meta tags to the wp_head
	 * Adds responsive meta tags and the main title tag
	 */
	public function meta_viewport() {

		/**
	 	 * Meta Viewport
	 	 *
	 	 * @since 1.6.0
	 	 */

		// Responsive viewport viewport
		if ( get_theme_mod( 'responsive', true ) ) {
			$viewport = '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />';
		}

		// Non responsive meta viewport
		else {
			$viewport = '<meta name="viewport" content="width='. intval( get_theme_mod( 'main_container_width', '980' ) ) .'" />';
		}
		
		// Apply filters to the meta viewport for child theme tweaking
		echo apply_filters( 'wpex_meta_viewport', $viewport );

	}

	/**
	 * Alters the default wp_title output
	 */
	public function wp_title( $title, $sep ) {

		// Global data
		global $paged, $page;

		// Feeds
		if ( is_feed() ) {
			return $title;
		}

		// Add the title
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_front_page() ) ) {
			$title = "$title $sep $site_description";
		}

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 ) {
			$title = sprintf( __( 'Page %s', 'mayer' ), max( $paged, $page ) ) . " $sep $title";
		}

		return $title;
	}

	/**
	 * Output functions to the wp_head hook
	 */
	public function wp_head() {
		$this->ie8_css();
		$this->html5_shiv();
		$this->custom_css();
		$this->tracking();
	}

	/**
	 * Load scripts in the WP admin
	 */
	public function admin_scripts() {

		// Load FontAwesome for use with the Visual Composer backend editor and the Total metabox
		wp_enqueue_style( 'wpex-font-awesome', $this->css_dir_uri .'font-awesome.min.css' );

	}

	/**
	 * Load custom scripts in the front end
	 */
	public function front_end_scripts() {
		$this->theme_css();
		$this->theme_js();
	}

	/**
	 * Load custom scripts in the front end
	 */
	public function theme_css() {
		
		/**
		 * Loads all required CSS for the theme
		 */

		// Load Visual composer CSS first so it's easier to override
		if ( WPEX_VC_ACTIVE ) {
			wp_enqueue_style( 'js_composer_front' );
		}

		// Font Awesome First
		wp_enqueue_style( 'wpex-font-awesome', $this->css_dir_uri .'font-awesome.min.css' );

		// Main Style.css File
		wp_enqueue_style( 'wpex-style', get_stylesheet_uri() );

		// Visual Composer CSS
		if ( WPEX_VC_ACTIVE ) {
			wp_enqueue_style( 'wpex-visual-composer', $this->css_dir_uri .'visual-composer-custom.css', array( 'js_composer_front' ) );
			wp_enqueue_style( 'wpex-visual-composer-extend', $this->css_dir_uri .'visual-composer-extend.css' );
		}

		// WooCommerce CSS
		if ( WPEX_WOOCOMMERCE_ACTIVE ) {
			wp_enqueue_style( 'wpex-woocommerce', $this->css_dir_uri .'woocommerce.css' );
		}

		// BBPress CSS
		if ( WPEX_BBPRESS_ACTIVE && is_bbpress() ) {
			wp_enqueue_style( 'wpex-bbpress', $this->css_dir_uri .'bbpress-edits.css', array( 'bbp-default' ) );
		}

		// Responsive CSS
		if ( $this->is_responsive_enabled && ! wpex_is_front_end_composer() ) {
			wp_enqueue_style( 'wpex-responsive', $this->css_dir_uri .'responsive.css', array( 'wpex-style' ) );
		}

		// Ligthbox skin
		wp_enqueue_style( 'wpex-lightbox-skin', $this->css_dir_uri .'lightbox/'. $this->lightbox_skin .'-skin/skin.css', array( 'wpex-style' ) );

		// Remove unwanted scripts
		wp_deregister_style( 'js_composer_custom_css' );

	}

	/**
	 * Load custom scripts in the front end
	 */
	public function theme_js() {

		// jQuery main script
		wp_enqueue_script( 'jquery' );

		// Retina.js
		if ( get_theme_mod( 'retina' ) ) {
			wp_enqueue_script(
				'retina',
				$this->js_dir_uri .'plugins/retina.js',
				array( 'jquery' ),
				'0.0.2', true
			);
		}

		// Comment reply
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Check RTL
		if( is_rtl() ) {
			$rtl = 'true';
		} else {
			$rtl = 'false';
		}

		// Mobile menu style
		if( $this->is_responsive_enabled ) {
			 $mobile_menu_style = get_theme_mod( 'mobile_menu_style', 'sidr' );
		} else {
			 $mobile_menu_style = 'disabled';
		}

		// Localize array
		$localize_array = array(
			'mobileMenuStyle'		=> $mobile_menu_style,
			'sidrSource'			=> wpex_mobile_menu_source(),
			'lightboxSkin'			=> $this->lightbox_skin,
			'lightboxArrows'		=> get_theme_mod( 'lightbox_arrows', true ),
			'lightboxThumbnails'	=> get_theme_mod( 'lightbox_thumbnails', true ),
			'lightboxFullScreen'	=> get_theme_mod( 'lightbox_fullscreen', true ),
			'lightboxMouseWheel'	=> get_theme_mod( 'lightbox_mousewheel', true ),
			'lightboxTitles'		=> get_theme_mod( 'lightbox_titles', true ),
			'sidrSide'				=> get_theme_mod( 'mobile_menu_sidr_direction', 'left' ),
			'isRTL'					=> $rtl,
		);

		$localize_array = apply_filters( 'wpex_localize_array', $localize_array );

		// Load minified global scripts
		if ( get_theme_mod( 'minify_js', true ) ) {

			// Load super minified total js
			wp_enqueue_script( 'total-min', $this->js_dir_uri .'total-min.js', array( 'jquery' ), '5.13', true );

			// Localize
			wp_localize_script( 'total-min', 'wpexLocalize', $localize_array );

		}
		
		// Load all non-minified js
		else {
			// Core plugins
			wp_enqueue_script( 'wpex-superfish', $this->js_dir_uri .'plugins/superfish.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-supersubs', $this->js_dir_uri .'plugins/supersubs.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-hoverintent', $this->js_dir_uri .'plugins/hoverintent.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-sticky', $this->js_dir_uri .'plugins/sticky.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-tipsy', $this->js_dir_uri .'plugins/tipsy.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-waypoints', $this->js_dir_uri .'plugins/waypoints.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-scrollto', $this->js_dir_uri .'plugins/scrollto.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-images-loaded', $this->js_dir_uri .'plugins/images-loaded.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-isotope', $this->js_dir_uri .'plugins/isotope.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-leanner-modal', $this->js_dir_uri .'plugins/leanner-modal.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-infinite-scroll', $this->js_dir_uri .'plugins/infinite-scroll.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-flexslider', $this->js_dir_uri .'plugins/flexslider.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-touch-swipe', $this->js_dir_uri .'plugins/touch-swipe.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-count-to', $this->js_dir_uri .'plugins/count-to.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-owl-carousel', $this->js_dir_uri .'plugins/owl.carousel.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-appear', $this->js_dir_uri .'plugins/appear.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-sidr', $this->js_dir_uri .'plugins/sidr.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-custom-select', $this->js_dir_uri .'plugins/custom-select.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-scrolly', $this->js_dir_uri .'plugins/scrolly.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-match-height', $this->js_dir_uri .'plugins/match-height.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-mousewheel', $this->js_dir_uri .'plugins/jquery.mousewheel.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-request-animation', $this->js_dir_uri .'plugins/jquery.requestAnimationFrame.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-ilightbox', $this->js_dir_uri .'plugins/ilightbox.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'wpex-global', $this->js_dir_uri .'global.js', array( 'jquery' ), '', true );
			wp_localize_script( 'wpex-global', 'wpexLocalize', $localize_array );
		}

		// Remove scripts
		wp_dequeue_script( 'flexslider' );
		wp_deregister_script( 'flexslider' );

	}

	/**
	 * Remove version numbers from scripts URL
	 */
	public function remove_scripts_version( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}

	/**
	 * Adds CSS for ie8
	 */
	function ie8_css() {
		$ie_8_url	= $this->css_dir_uri .'ie8.css';
		$ie_8_url	= apply_filters( 'wpex_ie_8_url', $ie_8_url );
		echo '<!--[if IE 8]><link rel="stylesheet" type="text/css" href="'. $ie_8_url .'" media="screen"><![endif]-->';
	}

	/**
	 * Load HTML5 dependencies for IE8
	 */
	function html5_shiv() {
		echo '<!--[if lt IE 9]>
			<script src="'. $this->css_dir_uri .'plugins/html5.js"></script>
		<![endif]-->';
	}

	/**
	 * Output tracking code in the header
	 */
	function tracking() {
		$tracking = get_theme_mod( 'tracking' );
		if ( $tracking ) {
			echo $tracking;
		}
	}

	/**
	 * Outputs custom CSS to the wp_head
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	function register_sidebars() {

		// Heading element type
		$sidebar_headings	= get_theme_mod( 'sidebar_headings', 'div' );
		$footer_headings	= get_theme_mod( 'footer_headings', 'div' );

		// Main Sidebar
		register_sidebar( array (
			'name'			=> __( 'Main Sidebar', 'wpex' ),
			'id'			=> 'sidebar',
			'description'	=> __( 'Widgets in this area are used in the default sidebar. This sidebar will be used for your standard blog posts.', 'wpex' ),
			'before_widget'	=> '<div class="sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $sidebar_headings .' class="widget-title">',
			'after_title'	=> '</'. $sidebar_headings .'>',
		) );

		// Pages Sidebar
		if ( get_theme_mod( 'pages_custom_sidebar', '1' ) ) {
			register_sidebar( array (
				'name'			=> __( 'Pages Sidebar', 'wpex' ),
				'id'			=> 'pages_sidebar',
				'before_widget'	=> '<div class="sidebar-box %2$s clr">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<'. $sidebar_headings .' class="widget-title">',
				'after_title'	=> '</'. $sidebar_headings .'>',
			) );
		}

		// Search Results Sidebar
		if ( get_theme_mod( 'search_custom_sidebar', '1' ) ) {
			register_sidebar( array (
				'name'			=> __( 'Search Results Sidebar', 'wpex' ),
				'id'			=> 'search_sidebar',
				'before_widget'	=> '<div class="sidebar-box %2$s clr">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<'. $sidebar_headings .' class="widget-title">',
				'after_title'	=> '</'. $sidebar_headings .'>',
			) );
		}

		// Portfolio Sidebar
		if ( get_theme_mod( 'portfolio_enable', 'on' ) && get_theme_mod( 'portfolio_custom_sidebar', '1' ) ) {
			if ( post_type_exists( 'portfolio' ) ) {
				$obj			= get_post_type_object( 'portfolio' );
				$post_type_name	= $obj->labels->name;
				register_sidebar( array (
					'name'			=> $post_type_name .' '. __( 'Sidebar', 'wpex' ),
					'id'			=> 'portfolio_sidebar',
					'before_widget'	=> '<div class="sidebar-box %2$s clr">',
					'after_widget'	=> '</div>',
					'before_title'	=> '<'. $sidebar_headings .' class="widget-title">',
					'after_title'	=> '</'. $sidebar_headings .'>',
				) );
			}
		}

		// Staff Sidebar
		if ( get_theme_mod( 'staff_enable', 'on' ) && get_theme_mod( 'staff_custom_sidebar', '1' ) ) {
			if ( post_type_exists( 'staff' ) ) {
				$obj			= get_post_type_object( 'staff' );
				$post_type_name	= $obj->labels->name;
				register_sidebar( array (
					'name'			=> $post_type_name .' '. __( 'Sidebar', 'wpex' ),
					'id'			=> 'staff_sidebar',
					'before_widget'	=> '<div class="sidebar-box %2$s clr">',
					'after_widget'	=> '</div>',
					'before_title'	=> '<'. $sidebar_headings .' class="widget-title">',
					'after_title'	=> '</'. $sidebar_headings .'>',
				) );
			}
		}

		// Testimonials Sidebar
		if ( get_theme_mod( 'testimonials_enable', 'on' ) && get_theme_mod( 'testimonials_custom_sidebar', '1' ) ) {
			if ( post_type_exists( 'testimonials' ) ) {
				$obj			= get_post_type_object( 'testimonials' );
				$post_type_name	= $obj->labels->name;
				register_sidebar( array (
					'name'			=> $post_type_name .' '. __( 'Sidebar', 'wpex' ),
					'id'			=> 'testimonials_sidebar',
					'before_widget'	=> '<div class="sidebar-box %2$s clr">',
					'after_widget'	=> '</div>',
					'before_title'	=> '<'. $sidebar_headings .' class="widget-title">',
					'after_title'	=> '</'. $sidebar_headings .'>',
				) );
			}
		}

		// WooCommerce Sidebar
		if ( WPEX_WOOCOMMERCE_ACTIVE && get_theme_mod( 'woo_custom_sidebar', '1' ) ) {
			register_sidebar( array (
				'name'			=> __( 'WooCommerce Sidebar', 'wpex' ),
				'id'			=> 'woo_sidebar',
				'before_widget'	=> '<div class="sidebar-box %2$s clr">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<'. $sidebar_headings .' class="widget-title">',
				'after_title'	=> '</'. $sidebar_headings .'>',
			) );
		}

		// bbPress Sidebar
		if ( WPEX_BBPRESS_ACTIVE && get_theme_mod( 'bbpress_custom_sidebar', '1' ) ) {
			register_sidebar( array (
				'name'			=> __( 'bbPress Sidebar', 'wpex' ),
				'id'			=> 'bbpress_sidebar',
				'before_widget'	=> '<div class="sidebar-box %2$s clr">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<'. $sidebar_headings .' class="widget-title">',
				'after_title'	=> '</'. $sidebar_headings .'>',
			) );
		}

		// Footer Sidebars
		if( get_theme_mod( 'widgetized_footer', '1' ) ) {

			// Footer widget columns
			$footer_columns = get_theme_mod( 'footer_widgets_columns', '4' );
			
			// Footer 1
			register_sidebar( array (
				'name'			=> __( 'Footer 1', 'wpex' ),
				'id'			=> 'footer_one',
				'before_widget'	=> '<div class="footer-widget %2$s clr">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<'. $footer_headings .' class="widget-title">',
				'after_title'	=> '</'. $footer_headings .'>',
			) );
			
			// Footer 2
			if ( $footer_columns > '1' ) {
				register_sidebar( array (
					'name'			=> __( 'Footer 2', 'wpex' ),
					'id'			=> 'footer_two',
					'before_widget'	=> '<div class="footer-widget %2$s clr">',
					'after_widget'	=> '</div>',
					'before_title'	=> '<'. $footer_headings .' class="widget-title">',
					'after_title'	=> '</'. $footer_headings .'>'
				) );
			}
			
			// Footer 3
			if ( $footer_columns > '2' ) {
				register_sidebar( array (
					'name'			=> __( 'Footer 3', 'wpex' ),
					'id'			=> 'footer_three',
					'before_widget'	=> '<div class="footer-widget %2$s clr">',
					'after_widget'	=> '</div>',
					'before_title'	=> '<'. $footer_headings .' class="widget-title">',
					'after_title'	=> '</'. $footer_headings .'>',
				) );
			}
			
			// Footer 4
			if ( $footer_columns > '3' ) {
				register_sidebar( array (
					'name'			=> __( 'Footer 4', 'wpex' ),
					'id'			=> 'footer_four',
					'before_widget'	=> '<div class="footer-widget %2$s clr">',
					'after_widget'	=> '</div>',
					'before_title'	=> '<'. $footer_headings .' class="widget-title">',
					'after_title'	=> '</'. $footer_headings .'>',
				) );
			}
		}

	}

	/**
	 * Outputs custom CSS to the wp_head
	 */
	function custom_css() {

		// Set output Var
		$output = '';

		// Advanced styles
		$output .= wpex_advanced_styling();

		// Site Background
		$output .= wpex_site_background();

		// Per Page Backgrounds
		$output .= wpex_page_backgrounds();

		// Responsive Widths
		$output .= wpex_responsive_widths();

		// Add filter for adding more css via other functions
		$output = apply_filters( 'wpex_custom_css_filter', $output );

		// Output CSS in WP_Head
		if ( $output ) {
			$output = "<!-- TOTAL CSS -->\n<style type=\"text/css\">\n" . $output . "\n</style>";
			echo $output;
		}

	}

	/**
	 * Alters the default WordPress tag cloud widget arguments
	 */
	function widget_tag_cloud_args( $args ) {
		$args['largest']	= 1;
		$args['smallest']	= 1;
		$args['unit']		= 'em';
		return $args;
	}

	/**
	 * Alter wp list categories arguments
	 */
	function wp_list_categories_args( $links ) {
		$links	= str_replace( '</a> (', '</a> <span class="cat-count-span">(', $links );
		$links	= str_replace( ')', ')</span>', $links );
		return $links;
	}

	/**
	 * Pre get posts functions
	 */
	function pre_get_posts() {

		// Exclude categories from the blog
		if ( function_exists( 'wpex_blog_exclude_categories' ) ) {
			wpex_blog_exclude_categories();
		}

	}

	/**
	 * Layerslider Tweaks
	 */
	function layerslider_tweaks() {

		// Disable layerslider auto update box
		$GLOBALS['lsAutoUpdateBox'] = false;

	}

	/**
	 * Add new user fields
	 */
	function user_fields( $contactmethods ) {

		// Add Twitter
		if ( ! isset( $contactmethods['wpex_twitter'] ) ) {
			$contactmethods['wpex_twitter'] = $this->theme_branding .' - Twitter';
		}
		// Add Facebook
		if ( ! isset( $contactmethods['wpex_facebook'] ) ) {
			$contactmethods['wpex_facebook'] = $this->theme_branding .' - Facebook';
		}
		// Add GoglePlus
		if ( ! isset( $contactmethods['wpex_googleplus'] ) ) {
			$contactmethods['wpex_googleplus'] = $this->theme_branding .' - Google+';
		}
		// Add LinkedIn
		if ( ! isset( $contactmethods['wpex_linkedin'] ) ) {
			$contactmethods['wpex_linkedin'] = $this->theme_branding .' - LinkedIn';
		}
		// Add Pinterest
		if ( ! isset( $contactmethods['wpex_pinterest'] ) ) {
			$contactmethods['wpex_pinterest'] = $this->theme_branding .' - Pinterest';
		}
		// Add Pinterest
		if ( ! isset( $contactmethods['wpex_instagram'] ) ) {
			$contactmethods['wpex_instagram'] = $this->theme_branding .' - Instagram';
		}

		// Return contact methods
		return $contactmethods;

	}

	/**
	 * Outputs custom CSS to the wp_head
	 */
	function embed_oembed_html( $html, $url, $attr, $post_id ) {
		return '<div class="responsive-video-wrap entry-video">' . $html . '</div>';
	}

}
new WPEX_Theme_Setup;