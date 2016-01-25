<?php
/**
 * The page header displays at the top of all single pages and posts
 * See framework/page-header.php for all page header related functions.
 *
 * @package		Total
 * @subpackage	Partials/Page Header
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

// Disabled for author archives
if ( is_author() ) {
	return;
}

// Return if disabled on the store via the admin panel
if ( is_post_type_archive( 'product' ) && ! get_theme_mod( 'woo_shop_title', true ) ) {
	return;
}

// Define main vars
$classes				= '';
$post_id				= wpex_get_the_id();
$display_title			= true;
$display_breadcrumbs	= true;
$display_breadcrumbs	= apply_filters( 'wpex_page_header_breadcrumbs', $display_breadcrumbs );

// If title is empty return false
if ( ! wpex_page_title( $post_id ) ) {
	return;
}

// Return if page header is disabled via custom field
if ( $post_id ) {

	// Get title style
	$title_style = wpex_page_header_style( $post_id );

	// Return if page header is disabled and there isn't a page header background defined
	if ( 'on' == get_post_meta( $post_id, 'wpex_disable_title', true ) && 'background-image' != $title_style ) {
		return;
	}
	
	// Custom Classes
	if ( $title_style ) { 
		$classes .= ' '. $title_style .'-page-header';
	}

	// Disable title if the page header is disabled but the page header background is defined
	if ( 'on' == get_post_meta( $post_id, 'wpex_disable_title', true ) && 'background-image' == $title_style ) {
		$display_title = false;
	}
}

// Before Hook
wpex_hook_page_header_before(); ?>
	<header class="page-header<?php echo $classes; ?>">
		<?php
		// Top Hook
		wpex_hook_page_header_top(); ?>
		<div class="container clr page-header-inner">
			<?php
			// Inner hook
			wpex_hook_page_header_inner();

			//  Display header and subheading if enabled
			if ( $display_title ) :

				// Display the main header title
				$heading = apply_filters( 'wpex_page_header_heading', 'h1');
				echo '<'. $heading .' class="page-header-title">'. wpex_page_title( $post_id ) .'</'. $heading .'>';
			
				// Function used to display the subheading defined in the meta options
				wpex_post_subheading( $post_id );

			endif;
			
			// Display built-in breadcrumbs - see functions/breadcrumbs.php
			if ( $display_breadcrumbs ) :
				wpex_display_breadcrumbs( $post_id );
			endif; ?>
		</div><!-- .page-header-inner -->
		<?php
		// Page header overlay
		wpex_page_header_overlay( $post_id );
		// Bottom Hook
		wpex_hook_page_header_bottom(); ?>
	</header><!-- .page-header -->
<?php
// After Hook
wpex_hook_page_header_after(); ?>