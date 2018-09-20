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

// Re-Label Projects to Topics.
// Note, originally this function was named "child_et_pb_register_posttypes".
function custom_divi_register_topic_type() {

	$postTypeArgs = array(
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
	);

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

	$labels = [
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
	];

	register_taxonomy('project_tag', ['project'], [
		'hierarchical'      => false,
		'labels'            => $labels,
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
