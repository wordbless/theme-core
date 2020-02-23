<?php
/**
 * Function: Theme\Core\Module\_clean
 */

$opts = self::$opts;

// Prevent no index
if (empty($opts->no_index_allowed)) {
	remove_action( 'wp_head', 'wc_page_noindex' );
}

// Disable jQuery
add_action('wp_enqueue_scripts', function() use($opts) {
	$disable_jquery = true;
	if (!empty($opts->jquery_enabled) && $opts->jquery_enabled === true) {
		$disable_jquery = false;
	} else if (!empty($opts->jquery_enabled) && is_array($opts->jquery_enabled)) {
		$tpl_files = $opts->jquery_enabled;
		$disable_jquery = !is_page_template($tpl_files);
	}
	if ($disable_jquery) {
		wp_dequeue_script('jquery');
		wp_deregister_script('jquery');
	}
});

// Prevent port redirection
remove_filter('template_redirect','redirect_canonical');

// Do some more cleaning
add_action('after_setup_theme', function() use($opts) {
	// Remove version number
	if (empty($opts->wp_version_in_head_enabled)) {
		remove_action('wp_head', 'wp_generator', 10);
	}
	// Disable dns-prefetching
	if (empty($opts->wp_dns_prefetching_enabled)) {
		remove_action('wp_head', 'wp_resource_hints', 2);
	}
	// Remove canonical link
	if (empty($opts->cannonicl_links_enabled)) {
		remove_action('wp_head', 'rel_canonical', 10);
	}
	// Remove short link
	if (empty($opts->short_links_enabled)) {
		remove_action('wp_head', 'wp_shortlink_wp_head', 10);
	}
	// Remove prev/next links
	if (empty($opts->auto_prev_next_links_enabled)) {
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
	}
	// Remove unused custom logo
	if (empty($opts->custom_logo_enabled)) {
		remove_action('wp_head', '_custom_logo_header_styles');
	}
	// Disable page icon
	if (empty($opts->page_icon_enabled)) {
		remove_action('wp_head', 'wp_site_icon', 99);
	}
	// Remove unused css
	if (empty($opts->wp_css_enabled)) {
		remove_action('wp_head', 'wp_print_styles', 8);
		remove_action('wp_head', 'locale_stylesheet', 10);
		remove_action('wp_head', 'wp_custom_css_cb', 101);
	}
	// Remove unused js
	if (empty($opts->wp_js_enabled)) {
		// remove_action('wp_head', 'wp_enqueue_scripts', 1);
		remove_action('wp_head', 'wp_post_preview_js', 1);
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_head', 'wp_print_head_scripts', 9);
		remove_action('wp_head', 'wp_oembed_add_host_js', 10);
	}
	// Remove feed links
	if (empty($opts->feed_links_enabled)) {
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'feed_links_extra', 3);
		remove_action('wp_head', 'rsd_link', 10);
		remove_action('wp_head', 'wlwmanifest_link', 10);
	}
	// Disable rest service
	if (empty($opts->rest_api_enabled)) {
		remove_action('wp_head', 'rest_output_link_wp_head', 10);
		remove_action('wp_template_redirect', 'rest_output_link_header', 11, 0);
		remove_action('rest_api_init', 'wp_oembed_register_route');
		remove_action('oembed_dataparse', 'wp_filter_oembed_result', 10);
		remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	}
	// Hide admin bar, opinionated
	if (!empty($opts->admin_bar_disabled)) {
		show_admin_bar(false);
	}
	// Add thumbnails to posts
	if (empty($opts->post_thumbnails_disabled)) {
		add_theme_support( 'post-thumbnails' );
	}
});

// Remove gutenberg block css
if (empty($opts->gutenberg_disabled)) {
	add_action('wp_enqueue_scripts', function() {
		wp_dequeue_style('wp-block-library');
	}, 100);
}