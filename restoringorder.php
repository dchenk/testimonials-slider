<?php
/*
Plugin Name: Restoring Order
Plugin URI: https://widerwebs.com
Description: Custom features for the RestoringOrder.com website.
Version: 1.0
Author: Wider Webs (Dmitriy)
Author URI: https://widerwebs.com
License: Private
*/

// Hide the "projects" custom post type created by the Divi theme.
function remove_divi_projects($args) {
	return array_merge($args, [
		'public'             => false,
		'publicly_queryable' => false,
		'show_in_nav_menus'  => false,
		'show_ui'            => false
	]);
}
add_filter('et_project_posttype_args', 'remove_divi_projects', 10, 1);

// Create a shortcode for displaying the previous/next links on testimonial posts.
function testimonial_nav_links() {
	return '<div class="testimonial-nav"><span class="nav-next">' .
		get_next_post_link('%link', 'Next Testimonial <span style="font-family: ETmodules !important;">&#x3d;</span>', true) .
		'</span><span class="nav-previous">' .
		get_previous_post_link('%link', '<span style="font-family: ETmodules !important;">&#x3c;</span> Previous Testimonial', true) .
		'</span></div>';
}
add_shortcode('testimonial_nav_links', 'testimonial_nav_links');

// Woocommerce breadcrumbs home URL.
function woo_custom_breadcrumb_home_url() {
	return '/shop/';
}
add_filter('woocommerce_breadcrumb_home_url', 'woo_custom_breadcrumb_home_url');

function woocommerce_change_breadcrumb_home_text($defaults) {
	if (is_product_category()) {
		$defaults['home'] = 'Shop';
	}
	return $defaults;
}
add_filter('woocommerce_breadcrumb_defaults', 'woocommerce_change_breadcrumb_home_text');
