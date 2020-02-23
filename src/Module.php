<?php
namespace Theme\Core;

/** -------------------------------------------
 * Exported module
 * --------------------------------------------
 */
class Module {

    use \Theme\ModuleTrait;

    static $name = 'Core';

    static $opts = [
        'favicon_file' => '/dist/img/favicon.png'
    ];

    static function init ()
    {
        self::_clean();
        self::_ga();
        self::_head();
        self::_og_plugin();
        self::_asset_rules();
    }

    static function _clean ()
    {
        include(__DIR__ . '/methods/_clean.php');
    }

    static function _head ()
    {
        include(__DIR__ . '/methods/_head.php');
    }

    static function _asset_rules ()
    {
        include(__DIR__ . '/methods/_asset_rules.php');
    }

    static function _ga ()
    {
        include(__DIR__ . '/methods/_ga.php');
    }

	static function _og_plugin () {
        $opts = self::$opts;
        if (empty($opts->open_graph_disabled)) {
            include(__DIR__ . '/methods/_og_plugin.php');
        }
    }

    /** -------------------------------------------
     * Exported methods
     * --------------------------------------------
     */
     static function template ($path, $props = [])
    {
        include(__DIR__ . '/exports/template.php');
    }

    static function header ()
    {
        include(__DIR__ . '/exports/header.php');
    }

    static function footer ()
    {
        include(__DIR__ . '/exports/footer.php');
    }

}

/** -------------------------------------------
 * Exported functions
 * --------------------------------------------
 */
function template ($path, $props = [])
{
    return Module::template($path, $props);
}

function header ($props = [])
{
    return Module::header();
}

function footer ()
{
    return Module::footer();
}