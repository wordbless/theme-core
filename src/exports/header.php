<?php
/**
 * Function: Theme\Core\header
 * @var $props
 */
$opts = self::$opts;
$props = [];
if (!empty($opts->header_setup)) {
    $props = self::call_opt_func($opts->header_setup);
}
$props = self::make_obj($props);

?>
<!DOCTYPE html>
<html <?php echo apply_filters('theme_core_html_tag_attrs', (!empty($props->lang)) ? 'lang="' . $props->lang . '"' : ''); ?>>
<head>
    <?php if (!empty($props->title)) {
        ?>
        <title><?php echo $props->title; ?>></title>
        <?php
    } ?>
    <?php if (!empty($props->description)) {
        ?>
            <meta name="description" content="<?php echo $props->description; ?>">
        <?php
    } ?>
    <?php wp_head(); ?>
</head>
<body>