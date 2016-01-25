<?php
/**
 * Template for the Title + Category Hover overlay style
 *
 * @package		Total
 * @subpackage	Partials/Overlays
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

// Only used for inside position
if ( 'inside_link' != $position ) {
	return;
} ?>

<div class="overlay-title-category-hover">
	<div class="overlay-title-category-hover-inner clr">
		<div class="overlay-title-category-hover-text clr">
			<div class="overlay-title-category-hover-title">
				<?php the_title(); ?>
			</div>
			<div class="overlay-title-category-hover-category">
				<?php
				// Display category
				$post_type = get_post_type();
				if ( 'portfolio' == $post_type ) {
					$taxonomy = 'portfolio_category';
				} elseif( 'staff' == $post_type ) {
					$taxonomy = 'staff_category';
				} elseif ( 'post' == $post_type ) {
					$taxonomy = 'category';
				} else {
					$taxonomy = false;
				}
				if ( ! empty( $taxonomy ) ) {
					$terms			= get_the_terms( get_the_ID(), $taxonomy );
					$terms_array	= array();
					if ( ! is_wp_error( $terms ) ) {
						foreach( $terms as $term ) {
							$terms_array[] = $term->name;
						}
						$terms = implode( ', ', $terms_array );
						echo $terms;
					}
				} ?>
			</div>
		</div>
	</div>
</div>