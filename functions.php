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
add_action('after_setup_theme', 'setup_woocommerce_support');

function setup_woocommerce_support()
{
	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
}
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
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 99);

function action_woocommerce_after_shop_loop_item()
{
?>
	<a href="<?= get_the_permalink() ?>" class="button"> View Product </a>
<?php
}

add_action('woocommerce_after_shop_loop_item', 'action_woocommerce_after_shop_loop_item');
