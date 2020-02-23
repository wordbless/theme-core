<?php /**
 * function Theme\Core\template
 * @var $path
 * @var $props
 */

foreach ($props as $k => $v) {
	set_query_var($k, $v);
}
get_template_part($path);