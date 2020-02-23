<?php
/**
 * Function: Theme\Core\Module\_asset_rules
 */

$opts = self::$opts;
$config = self::config();

// Add favicon
if (empty($opts->favicon_file)) return;
add_action('wp_head', function() use($opts) {
	?>
	<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri() . $opts->favicon_file; ?>" />
	<?php
});

// Add or remove assets
if (empty($opts->asset_rules) || !is_array($opts->asset_rules)) return;
add_action('wp_enqueue_scripts', function() use($opts, $config) {
	function __check_array ($obj, $prop) {
		return !empty($obj->$prop) && is_array($obj->$prop);
	}
	function __check_object ($obj, $prop) {
		return !empty($obj->$prop) && is_object($obj->$prop);
	}
	function __get_path ($str, $config) {
		if (filter_var($str, FILTER_VALIDATE_URL)) {
			return $str;
		}
		$site_url = get_site_url();
		$theme_path = str_replace($site_url, '', get_template_directory_uri());
		return $site_url . ((!empty($config->port)) ? ':' . $config->port : '') . $theme_path . $str;
	}
	foreach ($opts->asset_rules as $a) {
		$condition_met = true;
		if (__check_array($a, 'on_templates')) {
			$condition_met = is_page_template($a->on_templates);
		} else if (__check_array($a, 'unless_templates')) {
			$condition_met = !is_page_template($a->unless_templates);
		}
		if ($condition_met) {
			if (__check_array($a, 'deregister_scripts')) foreach ($a->remove_scripts as $handle) {
				wp_dequeue_script($handle);
				wp_deregister_script($handle);
			}
			if (__check_array($a, 'deregister_styles')) foreach ($a->remove_styles as $handle) {
				wp_deregister_style($handle);
			}
			if (__check_array($a, 'add_script_files')) foreach ($a->add_script_files as $file) {
				wp_enqueue_script(basename($file), __get_path($file, $config), false, $config->build_version, true);
			}
			if (__check_array($a, 'add_style_files')) foreach ($a->add_style_files as $file) {
				wp_enqueue_style(basename($file), __get_path($file, $config), false, $config->build_version, 'all');
			}
			if (__check_object($a, 'add_amp_components')) {
				add_filter('theme_core_html_tag_attrs', function($str) {
					return 'amp ' . $str;
				});
				add_action('wp_head', function() use($opts) {
					?>
					<link rel="preload" as="script" href="https://cdn.ampproject.org/v0.js">
					<link rel="preload" as="script" href="https://cdn.ampproject.org/v0/amp-dynamic-css-classes-0.1.js">
					<script async src="https://cdn.ampproject.org/v0.js"></script>
					<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
					<?php
				});
				foreach ($a->add_amp_components as $package => $version) {
					?>
					<script async custom-element="<?php echo $package; ?>" src="https://cdn.ampproject.org/v0/<?php echo $package; ?>-<?php echo $version; ?>.js"></script>
					<?php
				}
			}
		}
	}
});