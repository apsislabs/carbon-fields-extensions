<?php
namespace Carbon_Fields\Field;

use Carbon_Fields\Exception\Incorrect_Syntax_Exception;

/**
* Number field class.
*/
class Number_Field extends Field
{
    protected $min;
    protected $max;
	protected $step;
    protected $pattern;

    /**
    * Returns an array that holds the field data, suitable for JSON representation.
    * This data will be available in the Underscore template and the Backbone Model.
    *
    * @param bool $load  Should the value be loaded from the database or use the value from the current instance.
    * @return array
    */
    public function to_json($load)
    {
        $field_data = parent::to_json($load);

        return array_merge($field_data, array(
            'min' => $this->min,
            'max' => $this->max,
            'pattern' => $this->pattern
        ));
    }

    /**
     * Stores the min int value
     *
     * @var int|string
     **/
    public function set_min($min)
    {
        $this->min = $this->parse_numeric($min, "set_min");
        return $this;
    }

    /**
     * Stores the max int value
     *
     * @var int|string
     **/
    public function set_max($max)
    {
        $this->max = $this->parse_numeric($max, "set_max");
        return $this;
    }

    /**
     * Stores the min and max values in one method
     *
     * @var int|string
     **/
    public function set_range($min, $max)
    {
        $this->set_min($min);
        $this->set_max($max);

        return $this;
    }

    /**
     * Stores the field pattern
     *
     * @var string
     **/
    public function set_pattern($pattern)
    {
        $this->pattern($pattern);
        return $this;
    }

    /**
     * Stores the step size
     *
     * @var string
     **/
    public function set_step($step)
    {
        $this->step(floatval($step));
        return $this;
    }

    /**
     * Underscore template of this field.
     */
    public function template()
    {
        ?>
            <input id="{{{ id }}}" type="number" name="{{{ name }}}" value="{{ value }}" min="{{ min }}" max="{{ max }}" pattern="{{ pattern }}" class="regular-text" />
        <?php

    }

    /**
     * Parse number or raise an exception for invalid input
     */
    private function parse_numeric($number, $field_name)
    {
        if (is_numeric($number)) {
            return intval($number);
        } else {
            Incorrect_Syntax_Exception::raise("Only numeric values are allowed in the <code>$field_name()</code> method.");
        }
    }
}
