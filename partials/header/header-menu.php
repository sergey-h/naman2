<?php
/**
 * Header aside content used in Header Style Two by default
 *
 * @package		Total
 * @subpackage	Partials/Header
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Vars
$wrap_classes	= '';
$classes		= '';
$header_style	= wpex_get_header_style();
$header_height	= intval( get_theme_mod( 'header_height' ) );
$woo_icon		= get_theme_mod( 'woo_menu_icon', '1' );

// Main + Header Style Wrap Classes
$wrap_classes .= 'clr navbar-style-'. $header_style;

// Add the fixed-nav class if the fixed header option is enabled
if ( 'one' != $header_style && get_theme_mod( 'fixed_header', '1' ) ){
	$wrap_classes .= ' fixed-nav';
}

// Add fixed height class if it's header style one and a header height is defined in the admin
if ( 'one' == $header_style && $header_height ) {
	if ( $header_height && '0' != $header_height && 'auto' != $header_height ) {
		$wrap_classes .= ' nav-custom-height';
	}
}

// Add special class if the dropdown top border option in the admin is enabled
if ( get_theme_mod( 'menu_dropdown_top_border' ) ) {
	$wrap_classes .= ' nav-dropdown-top-border';
}

// Add the container div for header style's two and three
if ( 'two' == $header_style || 'three' == $header_style ) {
	$classes .= 'container';
}

// Add classes if the search setting is enabled
if ( get_theme_mod( 'main_search', '1' ) ) {
	$classes .= ' site-navigation-with-search';
	if ( class_exists( 'Woocommerce' ) && $woo_icon ) {
		$classes .= ' site-navigation-with-cart-icon';
	} elseif ( class_exists('Woocommerce') && ! $woo_icon) {
		$classes .= ' site-navigation-without-cart-icon';
	} else {
		$classes .= ' site-navigation-without-cart-icon';
	}
}

// Before main menu hook
wpex_hook_main_menu_before(); ?>

<div id="site-navigation-wrap" class="<?php echo $wrap_classes; ?>">
	<nav id="site-navigation" class="navigation main-navigation clr <?php echo $classes; ?>" role="navigation">
		<?php
		// Top menu hook
		wpex_hook_main_menu_top();

		// Menu Location
		$menu_location = apply_filters( 'wpex_main_menu_location', 'main_menu' );

		// Custom Menu - see framework/header/menu/menu-output.php
		$menu = wpex_custom_menu();

		// Display main menu
		if ( $menu ) {
			wp_nav_menu( array(
				'menu'				=> $menu,
				'theme_location'	=> $menu_location,
				'menu_class'		=> 'dropdown-menu sf-menu',
				'fallback_cb'		=> false,
				'walker'			=> new WPEX_Dropdown_Walker_Nav_Menu()
			) );
		} else {
			wp_nav_menu( array(
				'theme_location'	=> $menu_location,
				'menu_class'		=> 'dropdown-menu sf-menu',
				'walker'			=> new WPEX_Dropdown_Walker_Nav_Menu(),
				'fallback_cb'		=> false,
			) );
		}
		// Botttom main menu hook
		wpex_hook_main_menu_bottom(); ?>
	</nav><!-- #site-navigation -->
</div><!-- #site-navigation-wrap -->

<?php
// After main menu hook
wpex_hook_main_menu_after();