<?php
namespace Carbon_Fields\Field;
use Carbon_Fields\Exception\Incorrect_Syntax_Exception;

/**
* Number field class.
*/
class Money_Field extends Field
{
    public static function admin_enqueue_scripts() {
        $dir = plugin_dir_url( __FILE__ );

        wp_enqueue_style( 'carbon_input_group');

        wp_enqueue_script( 'jquery_maskmoney' );
        wp_enqueue_script( 'carbon-field-Money_Field', $dir . '/field.js', array( 'carbon-fields', 'jquery', 'jquery_maskmoney' ) );
    }

    /**
     * Parse the value before saving to the database
     */
    public function save()
    {
        $name = $this->get_name();
        $value = $this->get_value();
        $this->set_name($name);

        $field_value = '';

        if (isset($value) && $value !== '') {
            $field_value = $this->getCentValue($value);
        }

        $this->set_value($field_value);
        parent::save();
    }

    /**
     * Underscore template of this field.
     */
    public function template()
    {
        ?>
            <div class="input-group">
                <span class="group-addon">$</span>
                <input id="{{{ id }}}" type="text" name="{{{ name }}}" value="{{ value }}" class="regular-text input-money" />
            </div>
        <?php
    }

    public function getCentValue($money)
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

        return (float) str_replace(',', '.', $removedThousendSeparator) * 100;
    }
}
