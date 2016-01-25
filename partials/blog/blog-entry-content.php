<?php
/**
 * Blog entry layout
 *
 * @package		Total
 * @subpackage	Partials/Blog
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.6.0
 * @version		1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="blog-entry-excerpt entry">
	<?php
	// Display excerpt if auto excerpts are enavled in the admin
	if ( get_theme_mod( 'blog_exceprt', true ) ) :
		// Setup excerpt args
		$args = array(
			'length'	=> wpex_excerpt_length(),
			'post_id'	=> get_the_ID(),
		);
		// Get excerpt length & output excerpt
		wpex_excerpt( $args );
	else :
	// Display full content
		the_content( __( 'Read More <span class="meta-nav">&rarr;</span>', 'wpex' ) );
	endif; ?>
</div><!-- .blog-entry-excerpt -->