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

// Only used for header style 2
if ( 'two' != wpex_get_header_style() ) {
	return;
} ?>

<aside id="header-aside" class=" header-two-aside clr">
	<?php if ( $content = get_theme_mod( 'header_aside' ) ) { ?>
		<div class="header-aside-content clr">
			<?php echo do_shortcode( $content ); ?>
		</div><!-- .header-aside-content -->
	<?php }
	// Show header search field if enabled in the theme options panel
	if ( get_theme_mod( 'main_search', true ) ) { ?>
		<div id="header-two-search" class="clr">
			<form method="get" class="header-two-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
				<input type="search" id="header-two-search-input" name="s" value="<?php _e( 'search', 'wpex' ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
				<button type="submit" value="" id="header-two-search-submit" />
					<span class="fa fa-search"></span>
				</button>
			</form><!-- #header-two-searchform -->
		</div><!-- #header-two-search -->
	<?php } ?>
</aside><!-- #header-two-aside -->