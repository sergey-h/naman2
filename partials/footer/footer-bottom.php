<?php
/**
 * Footer bottom content
 *
 * @package		Total
 * @subpackage	Partials/Footer
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

// Lets bail if this section is disabled
if ( ! get_theme_mod( 'footer_bottom', true ) ) {
	return; 
}

// Get copyright info
$copyright = get_theme_mod( 'footer_copyright_text', 'Copyright 2014 <a href="http://wpexplorer-themes.com/total/" target="_blank" title="Total WordPress Theme">Total WordPress Theme</a> - All Rights Reserved' ); ?>

<div id="footer-bottom" class="clr">
	<div id="footer-bottom-inner" class="container clr">
		<?php
		// Display copyright info
		if ( $copyright ) : ?>
			<div id="copyright" class="clr" role="contentinfo">
				<?php echo $copyright; ?>
			</div><!-- #copyright -->
		<?php endif; ?>
		<div id="footer-bottom-menu" class="clr">
			<?php
			// Display footer menu
			wp_nav_menu( array(
				'theme_location'	=> 'footer_menu',
				'sort_column'		=> 'menu_order',
				'fallback_cb'		=> false,
			) ); ?>
            
            <span class="contect-with-us-text">Contact us with</span> 

            <div class="clr top-bar-left social-style-font_icons" id="top-bar-social">
                <a target="_blank" title="Facebook" href="https://www.facebook.com/BarclaysRE?sk=wall"> <img src="<?php bloginfo('template_directory');?>/images/social/facebook.png" /> </a>
                <a target="_blank" title="Google Plus" href="https://plus.google.com/+EliasPatsalos/posts"> <img src="<?php bloginfo('template_directory');?>/images/social/googleplus.png" /></a> 
                <a target="_blank" title="Twitter" href="https://twitter.com/BarclaysRealEst"> <img src="<?php bloginfo('template_directory');?>/images/social/twitter.png" /> </a> 
                <a target="_blank" title="linkedin" href="https://www.linkedin.com/in/eliaspatsalos"> <img src="<?php bloginfo('template_directory');?>/images/social/linkedin-new.png" /> </a>
                <a target="_blank" title="RSS" href="http://warehousesindoral.com/barclay/blog/feed/"> <img src="<?php bloginfo('template_directory');?>/images/social/rss.png" /> </a>
           </div>
		</div><!-- #footer-bottom-menu -->
	</div><!-- #footer-bottom-inner -->
</div><!-- #footer-bottom -->