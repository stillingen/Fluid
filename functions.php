<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Fluid' );
define( 'CHILD_THEME_URL', '<http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.0.1' );

//* Enqueue Lato Google font
add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
function genesis_sample_google_fonts() {
	wp_enqueue_style( 'google-font-lato', '//fonts.googleapis.com/css?family=Lato:300,700', array(), CHILD_THEME_VERSION );
}
//* Enqueue Woocommcerce script
add_action( 'wp_enqueue_scripts', 'custom_load_custom_style_sheet' );
function custom_load_custom_style_sheet() {
	wp_enqueue_style( 'custom-stylesheet', CHILD_URL . '/woocommerce.css', array(), PARENT_THEME_VERSION );
}
//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for Woocommerce 
add_theme_support( 'woocommerce' );

//* Register footer header widget
function genesischild_footerwidgetheader() {

    genesis_register_sidebar( array(
    'id' => 'footerwidgetheader',
    'name' => __( 'Footer Widget Header', 'Fluid' ),
    'description' => __( 'This is for the Footer Widget Headline', 'Fluid' ),
    ) );
}
add_action ('widgets_init','genesischild_footerwidgetheader');

//Position footer header widget
function genesischild_footerwidgetheader_position ()  {
    echo '<div class="footerwidgetheader-container"><div class="wrap">';
    genesis_widget_area ('footerwidgetheader');
    echo '</div></div>';
 
}
 
add_action ('genesis_before_footer','genesischild_footerwidgetheader_position', 1 );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Register Home Slider widget area
genesis_register_sidebar( array(
	'id'			=> 'home-slider',
	'name'			=> 'Home Slider',
	'description'	=> 'This is the home slider section'
) );


add_action( 'genesis_after_header', 'sk_home_featured' );
/**
 * Display Home Slider widget area's contents below Navigation on homepage.
 *
 * @author Sridhar Katakam
 * @link   http://sridharkatakam.com/full-width-soliloquy-slider-genesis/
 */
function sk_home_featured() {
	if ( is_home() || is_front_page() ) {
		genesis_widget_area( 'home-slider', array(
			'before'	=> '<div class="home-slider widget-area">',
			'after'		=> '</div>',
		) );
	}
}
//* Register full width widget for front page
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home - Top', 'Fluid' ),
	'description' => __( 'This is the top section of the Home page.', 'Fluid' ),
) );

genesis_register_sidebar( array(
	'id'          => 'home-middle',
	'name'        => __( 'Home - Middle', 'Fluid' ),
	'description' => __( 'This is the middle section of the Home page.', 'Fluid' ),
) );

genesis_register_sidebar( array(
	'id'          => 'home-bottom',
	'name'        => __( 'Home - Bottom', 'Fluid' ),
	'description' => __( 'This is the bottom section of the Home page.', 'Fluid' ),
) );

//* Customize the credits
add_filter( 'genesis_footer_creds_text', 'sp_footer_creds_text' );
function sp_footer_creds_text() {
	echo '<div class="creds"><p>';
	echo 'Copyright &copy; ';
	echo date('Y');
	echo ' &middot; <a href="http://www.fluid.no">fluid.no</a>';
	echo '</p></div>';
}
//* Adding next and previous navigation previews on post
add_action('genesis_entry_footer', 'wpsites_image_nav_links', 25 );

function wpsites_image_nav_links() {

if( !is_singular('post') ) 
      return;

if( $prev_post = get_previous_post() ): 
echo'<div class="single-post-nav previous-post-link">';
echo'<h2>Forrige blogginnlegg</h2>';
$prevpost = get_the_post_thumbnail( $prev_post->ID, 'medium', array('class' => 'pagination-previous')); 
previous_post_link( '%link',"$prevpost", TRUE ); 
previous_post_link( '<div class="prev-link">%link</div>', '%title' );
echo'</div>';
endif; 

if( $next_post = get_next_post() ): 
echo'<div class="single-post-nav next-post-link">';
echo'<h2>Neste blogginnlegg</h2>';
$nextpost = get_the_post_thumbnail( $next_post->ID, 'medium', array('class' => 'pagination-next')); 
next_post_link( '%link',"$nextpost", TRUE );
next_post_link( '<div class="next-link">%link</div>', '%title' );
echo'</div>';
endif; 
} 
//* Remove the post meta function
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

//* Reordering redering order for footer header wiget(1), and footer widgets(10)
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action ('genesis_before_footer','genesis_footer_widget_areas', 10 );

//* Removing woocommerce sub category thumbnails
	function woocommerce_nested_category_products_content_section( $categories, $product_category_ids ) {
		global $woocommerce, $wp_query, $wc_nested_category_layout;

		$title = '';
		$term = '';

		// Build up the sub-category title, starting with the title of the current page category
		if ( is_product_category() ) {
			$term = get_term_by( 'slug', get_query_var( $wp_query->query_vars['taxonomy'] ), $wp_query->query_vars['taxonomy'] );
			$title = '<span>' . $term->name . '</span>';
		}

		// add any saved up category titles, along with the current
		foreach ( $categories as $title_cat ) {
			$url = esc_attr( get_term_link( $title_cat ) );
			$title .= ( $title ? ' - ' : '' ) . '<a href="' . $url . '">' . wptexturize( $title_cat->name ) . '</a>';
		}

		// subcategory header
		echo wp_kses_post( apply_filters( 'wc_nested_category_layout_category_title_html', sprintf( '<h2>%s</h2>', $title ), $categories, $term ) );


		// optional thumbnail/description of the category
		$category = $categories[ count( $categories ) - 1 ];
		
		// Optional category description
		if ( $category->description ) {
			echo '<div class="subcategory-term_description term_description">' . wpautop( wptexturize( $category->description ) ) . '</div>';
		}

		woocommerce_get_template( 'loop/nested-category.php', array( 'woocommerce_product_category_ids' => $product_category_ids, 'category' => $title_cat ), '', $wc_nested_category_layout->plugin_path() . '/templates/' );
	}
?>