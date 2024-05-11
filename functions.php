<?php

/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0');

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles()
{

	wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);


/**
 * WooCommerce Loop Product Thumbs
 **/
if (!function_exists('woocommerce_template_loop_product_thumbnail')) {
	function woocommerce_template_loop_product_thumbnail()
	{
		echo "<div class='wc-img-wrapper'>";
		echo woocommerce_get_product_thumbnail();
		echo "</div>";
	}
}

/**
 * Add a sidebar.
 */
function wpdocs_theme_slug_widgets_init()
{
	register_sidebar(array(
		'name'          => __('Main Sidebar', 'textdomain'),
		'id'            => 'sidebar-1',
		'description'   => __('Widgets in this area will be shown on all posts and pages.', 'textdomain'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
	));
}
add_action('widgets_init', 'wpdocs_theme_slug_widgets_init');


remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

function action_woocommerce_after_shop_loop_item()
{
?>
	<a href="<?= get_the_permalink() ?>" class="button"> View Product </a>
<?php
}

add_action('woocommerce_after_shop_loop_item', 'action_woocommerce_after_shop_loop_item');


/**
 * @snippet       WooCommerce Hide Prices Except Cart / Checkout
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 5.1
 * @community     https://businessbloomer.com/club/
 */

add_filter('woocommerce_variable_sale_price_html', 'businessbloomer_remove_prices', 9999, 2);

add_filter('woocommerce_variable_price_html', 'businessbloomer_remove_prices', 9999, 2);

add_filter('woocommerce_get_price_html', 'businessbloomer_remove_prices', 9999, 2);

function businessbloomer_remove_prices($price, $product)
{
	if (!is_admin()) $price = '';
	return $price;
}

/*-----------------------------------------------------------------------------------*/
/* Register Carbofields
/*-----------------------------------------------------------------------------------*/
add_action('carbon_fields_register_fields', 'tissue_paper_register_custom_fields');
function tissue_paper_register_custom_fields()
{
	require_once('includes/post-meta.php');
}

/**
 * Disable reviews.
 */
function iconic_disable_reviews()
{
	remove_post_type_support('product', 'comments');
}

add_action('init', 'iconic_disable_reviews');

/**
 * @snippet       New Product Tab @ WooCommerce Single Product
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 8
 * @community     https://businessbloomer.com/club/
 */

add_filter('woocommerce_product_tabs', 'bbloomer_add_product_tab', 9999);

function bbloomer_add_product_tab($tabs)
{
	$products_included = carbon_get_the_post_meta('products_included');
	if ($products_included) {
		$tabs['products_included'] = array(
			'title' => __('Products Included', 'woocommerce'), // TAB TITLE
			'priority' => 50, // TAB SORTING (DESC 10, ADD INFO 20, REVIEWS 30)
			'callback' => 'action_products_included_tab', // TAB CONTENT CALLBACK
		);
	}
	return $tabs;
}

function action_products_included_tab()
{
	$products_included = carbon_get_the_post_meta('products_included');
	if ($products_included) {
		echo '<h2>Products Included</h2>';
		echo do_shortcode('[elementor-template id="436"]');
	}
}



function products_included_query($query)
{

	$products_included = carbon_get_the_post_meta('products_included');

	$p_id = array();

	foreach ($products_included as $product) {
		$p_id[] = $product['id'];
	}


	$query->set('post__in', $p_id);
}
add_action('elementor/query/included_products', 'products_included_query');


/**
 * @snippet       Hide Products From Specific Category @ Shop
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 8
 * @community     https://businessbloomer.com/club/
 */

add_action('woocommerce_product_query', 'bbloomer_hide_products_category_shop');

function bbloomer_hide_products_category_shop($q)
{

	$tax_query = (array) $q->get('tax_query');

	$tax_query[] = array(
		'taxonomy' => 'product_cat',
		'field' => 'term_id',
		'terms' => array(37),
		'operator' => 'NOT IN'
	);


	$q->set('tax_query', $tax_query);
}