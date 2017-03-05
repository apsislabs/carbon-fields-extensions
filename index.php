<?php
/**
* Plugin Name: Carbon Fields Extensions
* Version: 1.0.0
* Author: Apsis Labs
* Author URI: www.apsis.io
*/

namespace CarbonFields;

class CarbonFieldsExtensions
{
    public static function init()
    {
        $dir = dirname(__FILE__);
        static::require_all("$dir/fields");
        static::addActions();
    }

    public static function addActions()
    {
        add_action('admin_enqueue_scripts',  array(static::class, 'loadAdminAssets'));
    }

    public static function loadAdminAssets()
    {
        wp_register_style( 'carbon_input_group', plugins_url( '/assets/css/input-group.css', __FILE__ ));
        wp_register_style( 'chosen_styles', plugins_url( '/assets/vendor/chosen/chosen.min.css', __FILE__ ));

        wp_register_script( 'jquery_maskmoney', plugins_url( '/assets/vendor/jquery.maskmoney.min.js', __FILE__ ), array('jquery'));
        wp_register_script( 'chosen', plugins_url( '/assets/vendor/chosen/chosen.jquery.min.js', __FILE__ ), array('jquery'));
    }

    protected static function require_all($dir) {
        $scan = glob("$dir/*");

        foreach ($scan as $path) {
            if (preg_match('/\.php$/', $path)) {
                require_once $path;
            }
            elseif (is_dir($path)) {
                static::require_all($path, $depth+1);
            }
        }
    }
}

CarbonFieldsExtensions::init();
