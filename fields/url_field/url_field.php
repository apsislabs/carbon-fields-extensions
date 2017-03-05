<?php
namespace Carbon_Fields\Field;

use Carbon_Fields\Exception\Incorrect_Syntax_Exception;

/**
* Number field class.
*/
class Url_Field extends Field
{
    public static function admin_enqueue_scripts() {
        $dir = plugin_dir_url( __FILE__ );
        wp_enqueue_style('carbon_input_group');
        wp_enqueue_script( 'carbon-field-Url_Field', $dir . 'field.js', array( 'carbon-fields' ) );
    }

    /**
     * Underscore template of this field.
     */
    public function template()
    {
        ?>
        <div class="input-group">
            <span class="group-addon">
                <span class="dashicons dashicons-admin-site"></span>
            </span>
            <input id="{{{ id }}}" type="text" name="{{{ name }}}" value="{{ value }}" class="regular-text url-field" />
        </div>
        <?php
    }

    /**
     * Parse number or raise an exception for invalid input
     */
    public function save()
    {
        $name = $this->get_name();
        $value = $this->get_value();

        $this->set_name($name);

        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
            $this->set_value($value);
        }

        parent::save();
    }
}
