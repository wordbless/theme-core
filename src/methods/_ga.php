<?php
/**
 * Function: Theme\Amp\Module\_ga
 */

$opts = self::$opts;

if (empty($opts->google_analytics_key)) return;
$key = $opts->google_analytics_key;
add_action('wp_head', function() use($opts, $key) {
	?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $key; ?>"></script>
    <script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '<?php echo $key; ?>');
    </script>
	<?php
});
