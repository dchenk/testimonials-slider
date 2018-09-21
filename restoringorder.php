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

define('RESTORING_PLUGIN_VER', '1.0');

// Re-Label Projects to Topics.
// Note, originally this function was named "child_et_pb_register_posttypes".
function custom_divi_register_topic_type() {

	$postTypeArgs = [
		'has_archive'        => true,
		'hierarchical'       => false,
		'labels'             => [
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Topic',
			'all_items'          => 'All Topics',
			'edit_item'          => 'Edit Topic',
			'menu_name'          => 'Speaking Topics',
			'name'               => 'Speaking Topics',
			'new_item'           => 'New Topic',
			'not_found_in_trash' => 'Nothing found in Trash',
			'search_items'       => 'Search Topics',
			'singular_name'      => 'Topic',
			'view_item'          => 'View Topic',
		],
		'menu_icon'          => 'dashicons-megaphone',
		'public'             => true,
		'publicly_queryable' => true,
		'query_var'          => true,
		'show_in_nav_menus'  => true,
		'show_ui'            => true,
		'rewrite'            => apply_filters('et_project_posttype_rewrite_args', [
			'feeds'          => true,
			'slug'           => 'topic',
			'with_front'     => false
		]),
		'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields']
	];

	register_post_type('project', apply_filters('et_project_posttype_args', $postTypeArgs));

	register_taxonomy('project_category', ['project'], [
		'hierarchical'        => true,
		'labels'              => [
			'name'              => 'Categories',
			'singular_name'     => 'Category',
			'search_items'      => 'Search Categories',
			'all_items'         => 'All Categories',
			'parent_item'       => 'Parent Category',
			'parent_item_colon' => 'Parent Category:',
			'edit_item'         => 'Edit Category',
			'update_item'       => 'Update Category',
			'add_new_item'      => 'Add New Category',
			'new_item_name'     => 'New Category Name',
			'menu_name'         => 'Categories',
		],
		'show_ui'             => true,
		'show_admin_column'   => true,
		'query_var'           => true
	]);

	register_taxonomy('project_tag', ['project'], [
		'hierarchical'      => false,
		'labels'            => [
			'name'              => 'Tags',
			'singular_name'     => 'Tag',
			'search_items'      => 'Search Tags',
			'all_items'         => 'All Tags',
			'parent_item'       => 'Parent Tag',
			'parent_item_colon' => 'Parent Tag:',
			'edit_item'         => 'Edit Tag',
			'update_item'       => 'Update Tag',
			'add_new_item'      => 'Add New Tag',
			'new_item_name'     => 'New Tag Name',
			'menu_name'         => 'Tags'
		],
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true
	]);

}
add_action('init', 'custom_divi_register_topic_type', 20);

// Hide the "projects" custom post type created by the Divi theme.
//function remove_divi_projects($args) {
//	return array_merge($args, [
//		'public'             => false,
//		'publicly_queryable' => false,
//		'show_in_nav_menus'  => false,
//		'show_ui'            => false
//	]);
//}
//add_filter('et_project_posttype_args', 'remove_divi_projects', 10, 1);

// Create a shortcode for displaying the previous/next links on testimonial posts.
function testimonial_nav_links() {
	return '<div class="testimonial-nav"><span class="nav-next">' .
		get_next_post_link('%link', 'Next Testimonial <span style="font-family: ETmodules;">&#x3d;</span>', true) .
		'</span><span class="nav-previous">' .
		get_previous_post_link('%link', '<span style="font-family: ETmodules;">&#x3c;</span> Previous Testimonial', true) .
		'</span></div>';
}
add_shortcode('testimonial_nav_links', 'testimonial_nav_links');

function topic_nav_links() {
	return '<div class="topic-nav"><span class="nav-next">' .
		get_next_post_link('%link', 'Next Topic <span style="font-family: ETmodules;">&#x3d;</span>', true, [], 'project_category') .
		'</span><span class="nav-previous">' .
		get_previous_post_link('%link', '<span style="font-family: ETmodules;">&#x3c;</span> Previous Topic', true, [], 'project_category') .
		'</span></div>';
}
add_shortcode('topic_nav_links', 'topic_nav_links');

// Disable woocommerce single product sidebar.
// TODO: Is this necessary?
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

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

// customTestimonialsSlider returns the HTML code for a testimonials slider and enqueues the needed JS and CSS.
function customTestimonialsSlider($atts) {
	$pluginURL = plugins_url('', __FILE__) . '/';
	wp_enqueue_style('testimonials', $pluginURL . 'testimonials-slider.css', [], RESTORING_PLUGIN_VER);
	wp_enqueue_script('testimonials', $pluginURL . 'testimonials-slider.js', ['jquery'], RESTORING_PLUGIN_VER);

	$atts = shortcode_atts([
		'category' => ''
	], $atts);

	$posts = wp_get_recent_posts([
		'numberposts'   => 12,
		'offset'        => 0,
		'category_name' => $atts['category'],
		'orderby'       => 'post_date',
		'order'         => 'DESC',
		'include'       => '',
		'exclude'       => '',
		'meta_key'      => '',
		'meta_value'    => '',
		'post_type'     => 'testimonial',
		'post_status'   => 'publish'
	]);

	$siteURL = site_url();
	$out = '<div class="testimonials-slider"><div class="testimonials-slider-control">';
	$out .= '<span class="testimonials-slider-left">&#xe03b;</span><span class="testimonials-slider-right">&#xe03c;</span>';
	$out .= '</div><div class="testimonials-slider-list">';
	foreach ($posts as $post) {
		$out .= '<a class="testimonial" href="' . $siteURL . '/testimonial/' . $post['post_name'] . '">';
		$url = get_the_post_thumbnail_url($post['ID']);
		if ($url) {
			$out .= '<img src="' . $url . '" alt="' . $post['post_title'] . '">';
		} else {
			$out .= '<div class="testimonial-only-title"><div>' . $post['post_title'] . '</div></div>';
		}
		$out .= '</a>';
	}
	return $out . '</div></div>';
}
add_shortcode('custom_testimonials_slider', 'customTestimonialsSlider');
