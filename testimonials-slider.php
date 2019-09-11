<?php
/*
Plugin Name: Testimonials Slider
Plugin URI: https://widerwebs.com
Description: A simple testimonials slider
Version: 2.0
Author: Wider Webs
Author URI: https://widerwebs.com
License: Private
*/

define('TESTIMONIALS_PLUGIN_VER', '2.0');

// customTestimonialsSlider returns the HTML code for a testimonials slider and enqueues the needed JS and CSS.
function customTestimonialsSlider($atts) {
	$pluginURL = plugins_url('', __FILE__) . '/';
	wp_enqueue_style('testimonials', $pluginURL . 'testimonials-slider.css', [], TESTIMONIALS_PLUGIN_VER);
	wp_enqueue_script('testimonials', $pluginURL . 'testimonials-slider.js', ['jquery'], TESTIMONIALS_PLUGIN_VER);

	$atts = shortcode_atts([
		'category' => '',
	], $atts);

	$posts = wp_get_recent_posts([
		'numberposts' => 15,
		'offset'      => 0,
		'tax_query'   => [
			[
				'taxonomy' => 'testimonial_category',
				'field'    => 'slug',
				'terms'    => $atts['category'],
			],
		],
		'orderby'     => 'post_date',
		'order'       => 'DESC',
		'include'     => '',
		'exclude'     => '',
		'meta_key'    => '',
		'meta_value'  => '',
		'post_type'   => 'testimonial',
		'post_status' => 'publish',
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

function customTestimonialsSliderSimple($atts) {
	$atts = shortcode_atts([], $atts);

	$posts = wp_get_recent_posts([
		'numberposts' => 60,
		'offset'      => 0,
		'orderby'     => 'post_date',
		'order'       => 'DESC',
		'include'     => '',
		'exclude'     => '',
		'meta_key'    => '',
		'meta_value'  => '',
		'post_type'   => 'testimonial',
		'post_status' => 'publish',
	]);

	$siteURL = site_url();
	$out = '<div class="testimonials-slider-simple">';
	foreach ($posts as $post) {
		$out .= '<a class="testimonial-simple" href="' . $siteURL . '/testimonial/' . $post['post_name'] . '">';
		$url = get_the_post_thumbnail_url($post['ID']);
		if ($url) {
			$out .= '<img src="' . $url . '" alt="' . $post['post_title'] . '">';
		} else {
			$out .= '<div class="testimonial-only-title"><div>' . $post['post_title'] . '</div></div>';
		}
		$out .= '</a>';
	}
	return $out . '</div>';
}
add_shortcode('custom_testimonials_slider_simple', 'customTestimonialsSliderSimple');

// Create a shortcode for displaying the previous/next links on testimonial posts.
function testimonial_nav_links() {
	return '<div class="testimonial-nav"><span class="nav-next">' .
		get_next_post_link('%link', 'Next Testimonial <span style="font-family: ETmodules;">&#x3d;</span>', true, [], 'testimonial_category') .
		'</span><span class="nav-previous">' .
		get_previous_post_link('%link', '<span style="font-family: ETmodules;">&#x3c;</span> Previous Testimonial', true, [], 'testimonial_category') .
		'</span></div>';
}
add_shortcode('testimonial_nav_links', 'testimonial_nav_links');
