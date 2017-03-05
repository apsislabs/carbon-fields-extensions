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
        add_action('admin_footer', array( static::class, 'adminHookScripts' ), 5 );
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

    public static function adminHookScripts()
    {
        wp_localize_script( 'cfe_l10n', 'cfel10n',
            array(
                'message_validation_failed_invalid_url' => __( 'Please enter a valid URL.', 'carbon-fields-extensions' ),
                'message_validation_failed_number_min'  => __( 'Value must be greater than or equal to %s.', 'carbon-fields-extensions' ),
                'message_validation_failed_number_max'  => __( 'Value must be less than or equal to %s.', 'carbon-fields-extensions' ),
                'message_validation_failed_number_step' => __( 'Please enter a valid value. The two nearest valid values are %1$s and %2$s.', 'carbon-fields-extensions' )
            )
        );
    }
}

CarbonFieldsExtensions::init();
