<?php
/**
 * WooCommerce Nested Category Layout
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@foxrunsoftware.net so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Nested Category Layout to newer
 * versions in the future. If you wish to customize WooCommerce Nested Category Layout for your
 * needs please refer to http://www.foxrunsoftware.net/shop/wordpress-plugins/woocommerce-nested-category-layout/ for more information.
 *
 * @package     WC-Nested-Category-Layout/Templates
 * @author      Justin Stern
 * @copyright   Copyright (c) 2012-2013, Justin Stern
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

/**
 * The Template for displaying nested category products
 *
 * Override this template by copying it to yourtheme/woocommerce/loop/nested-category.php
 *
 * @global array $woocommerce_product_category_ids an array of product id to containing category ids
 * @global object $category current category object
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce_loop;

$class = isset( $category->depth ) ? 'product-category-level-' . ( $category->depth + 2 ) : 'product-category-level-1';
$see_more = false;
$woocommerce_loop['loop'] = 0;
?>
<span itemscope itemtype="http://schema.org/ItemList">
<meta itemprop="itemListOrder" content="Descending" />
<ul class="subcategory-products products <?php echo $class; ?>">

	<?php

	if ( ! is_object( $category ) ) $term_id = 0;
	else $term_id = $category->term_id;

	// loop through all products
	if ( have_posts() ) : while ( have_posts() ) : the_post();

		global $product;
		$product_category_ids = $woocommerce_product_category_ids[ $product->id ];

		// ensure that the product is visible, and belongs to this category
		if ( ! $product->is_visible() || ! in_array( $term_id, $product_category_ids ) ) continue;

		// "view more" link
		if ( $woocommerce_loop['loop'] > get_option( 'woocommerce_subcat_posts_per_page' ) - 1 && isset( $woocommerce_loop['see_more'] ) && $woocommerce_loop['see_more'] ) {
			$see_more = true;
			break;
		}

		// display the product thumbnail content
		woocommerce_get_template_part( 'content', 'product' );

	endwhile; endif;

	?>

</ul>
</span>
<div style="clear:both;">
<div class="woocommerce-nested-category-layout-see-more-container"><?php if ( $see_more ) : ?><a class="woocommerce-nested-category-layout-see-more" href="<?php echo esc_attr( get_term_link( $category ) ) ?>"><?php echo apply_filters( 'wc_nested_category_layout_see_more_message', __( 'Se flere', WC_Nested_Category_Layout::TEXT_DOMAIN ), $category ); ?></a><?php endif; ?></div>
</div>
