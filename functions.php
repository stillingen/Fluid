<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Fluid' );
define( 'CHILD_THEME_URL', '<http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.0.1' );

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

/* Woocommerce customization
--------------------------------------------- */

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

//* Enqueing and queing Woocommerce default style, to be able to load custom version from template direcotry
add_filter( 'woocommerce_enqueue_styles', 'dequeue_woocommerce_general_stylesheet' );
function dequeue_woocommerce_general_stylesheet( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );	
	return $enqueue_styles;
}
function woocommerce_style_sheet() {
wp_register_style( 'woocommerce', get_stylesheet_directory_uri() . '/woocommerce/woocommerce.css' );
if ( class_exists( 'woocommerce' ) ) {
wp_enqueue_style( 'woocommerce' );
	}
}
add_action('wp_enqueue_scripts', 'woocommerce_style_sheet');

//* Removing woocommerce breadcrums
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

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

//* Enqueue sticky menu script
add_action( 'wp_enqueue_scripts', 'sp_enqueue_script' );
function sp_enqueue_script() {
	wp_enqueue_script( 'sample-sticky-menu', get_bloginfo( 'stylesheet_directory' ) . '/lib/js/sticky.js', array( 'jquery' ), '1.0.0' );
}

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before', 'genesis_do_subnav' );


add_filter( 'wp_nav_menu_items', 'custom_nav_item', 10, 2 );
/**
 * Callback for Genesis 'wp_nav_menu_items' filter.
 * 
 * Add custom left nav item to Genesis primary menu.
 * 
 * @package Genesis
 * @category Nav Menu
 * @author Ryan Meier http://www.rfmeier.net
 * 
 * @param string $menu The menu html
 * @param stdClass $args the current menu args
 * @return string $menu The menu html
 */
function custom_nav_item( $menu, $args ){
        
    // make sure we are in the primary menu
    if ( 'primary' != $args->theme_location )
    	return $menu;
    
    // see if a nav extra was already specified with Theme options
    if( genesis_get_option( 'nav_extras' ) )
            return $menu;
    // return the menu
    return $menu;
        
}
// Filter the title with a custom function
add_filter('genesis_seo_title', 'wap_site_title' );

// Add additional custom style to site header
function wap_site_title( $title ) {

    	// Change $custom_title text as you wish
	$custom_title = '<span class="custom-title">Fluid</span>';

	// Don't change the rest of this on down

	// If we're on the front page or home page, use `h1` heading, otherwise us a `p` tag
	$tag = ( is_home() || is_front_page() ) ? 'h1' : 'p';

	// Compose link with title
	$inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( home_url() ), esc_attr( get_bloginfo( 'name' ) ), $custom_title );

	// Wrap link and title in semantic markup
	$title = sprintf ( '<%s class="site-title" itemprop="headline">%s</%s>', $tag, $inside, $tag );
	return $title;

}
// Force Excerpts length to 30 caracters
add_filter( 'excerpt_length', 'change_excerpt_length' );
function change_excerpt_length($length) {
    return 30; 
}
// Add Read More Link to Excerpts
add_filter('excerpt_more', 'get_read_more_link');
add_filter( 'the_content_more_link', 'get_read_more_link' );
function get_read_more_link() {
   return '...&nbsp;<a href="' . get_permalink() . '">[Les&nbsp;mer]</a>';
}
/* Manipulate the featured image */
add_action( 'genesis_post_content', 'ibfy_post_image', 8 );
function ibfy_post_image() {
global $post;
    if ( is_page() )
        return; // Make pages act normal
 
    //setup thumbnail image args to be used with genesis_get_image();
    $size = 'medium'; // Change this to whatever add_image_size you want
    $default_attr = array(
            'class' => "alignright attachment-$size $size",
            'alt'   => $post->post_title,
            'title' => $post->post_title,
        );
 
    // This is the most important part!  Checks to see if the post has a Post Thumbnail assigned to it. You can delete the if conditional if you want and assume that there will always be a thumbnail
    if ( has_post_thumbnail() && is_home() ) {
        printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), genesis_get_image( array( 'size' => $size, 'attr' => $default_attr ) ) );
    }
 
}
?>