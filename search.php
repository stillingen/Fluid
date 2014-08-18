<?php
/**
 * Child Theme Search Results Page for Genesis
 *
 * @package Genesis\Templates
 * @author  Brad Dalton
 * @license GPL-2.0+
 * @copyright 2014 WP Sites
 * @example   http://wpsites.net/web-design/genesis-custom-search-results-page/
 */

//* Add searcg page body class 
add_filter( 'body_class', 'search_results_body_class' );
function search_results_body_class( $classes ) {

	$classes[] = 'search-results';
	return $classes;

}

//* Force full width content layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action( 'genesis_entry_content', 'genesis_prev_next_post_nav', 12 );

//* Run the Genesis loop
genesis();