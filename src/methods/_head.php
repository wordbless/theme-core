<?php
/**
 * Function: Theme\Amp\Module\_head
 */

add_action('wp_head', function() {
	?>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<?php
});
