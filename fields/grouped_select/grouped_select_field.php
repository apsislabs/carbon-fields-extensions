<?php
namespace Carbon_Fields\Field;
use Carbon_Fields\Exception\Incorrect_Syntax_Exception;

/**
 * Grouped Select field class.
 */
class Grouped_Select_Field extends Select_Field
{
    public static function admin_enqueue_scripts() {
        $dir = plugin_dir_url( __FILE__ );

        wp_enqueue_style( 'chosen_styles' );

        wp_enqueue_script( 'chosen' );
        wp_enqueue_script( 'carbon-field-Grouped_Select_Field', $dir . '/field.js', array( 'carbon-fields', 'jquery', 'chosen' ) );
    }

    /**
     * Changes the options array structure. This is needed to keep the array items order when it is JSON encoded.
     * Will also work with a callable that returns an array.
     *
     * @param array|callable $options
     * @return array
     */
    public function parse_options( $options )
    {
        $parsed = array();

        if ( is_callable( $options ) ) {
            $options = call_user_func( $options );
        }

        // Check if we need to format with optgroups
        $optgroup = $this->needs_optgroup($options);

        if ( $optgroup ) {
            // Parse into option groups
            foreach ( $options as $key => $value ) {
                $parsed[] = array(
                    'group' => $key,
                    'options' => $this->format_options($value)
                );
            }
        } else {
            // Parse as normal select
            foreach ( $options as $key => $value ) {
                $parsed[] = $this->format_option($key, $value);
            }
        }

        return $parsed;
    }

    /**
     * Loads options for parsing by parse_options()
     */
    public function load_options()
    {
        if ( empty( $this->options ) ) { return false; }

        if ( is_callable( $this->options ) ) {
            $options = call_user_func( $this->options );

            if ( ! is_array( $options ) ) {
                $options = array();
            }

            $this->options = $options;
        }
    }

    /**
     * Adds options to the options array without replacing existing options
     *
     * @param  [array] $options options array to add
     * @return [this]           returns self
     */
    public function add_options($options)
    {
        if ( is_array($options) ) {
            $this->options = array_merge($this->options, $options);
        } else {
            Incorrect_Syntax_Exception::raise('Only arrays are allowed in the <code>add_options()</code> method.');
        }

        return $this;
    }

    /**
     * Checks if the $options array is formatted in a way to require
     * rendering the select with <optgroup> tags.
     *
     * @param  [array] $options options array for select
     * @return [bool]           if optgroups are needed
     */
    private function needs_optgroup($options)
    {
        if ( is_array($options) ) {
            foreach ($options as $key => $value) {
                if ( is_array($value) ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Formats options into `value`/`name` pairs for rendering
     * by the underscore template.
     *
     * @param  [array] $options array of options for formatting
     * @return [array]          array of formatted options
     */
    private function format_options($options)
    {
        $formatted = array();

        foreach ( $options as $key => $value ) {
            $formatted[] = $this->format_option($key, $value);
        }

        return $formatted;
    }

    /**
     * Format values into `value`/`name` pair for rendering.
     *
     * @param  [int|string] $value value for the `value` attribute of the <option> tag
     * @param  [string]     $name  label for the <option> tag
     * @return [array]             formatted option object
     */
    private function format_option($value, $name)
    {
        return array('value' => $value, 'name' => $name);
    }

    /**
     * The main Underscore template of this field.
     */
    public function template() {
        ?>
        <# if (_.isEmpty(options)) { #>
            <em><?php _e( 'no options', 'carbon-fields' ); ?></em>
        <# } else { #>
            <select id="{{{ id }}}" name="{{{ name }}}">
                <# _.each(options, function(opt) { #>
                    <# if (_.has(opt, 'group')) { #>
                        <optgroup label="{{{ opt.group }}}">
                            <# _.each(opt.options, function(opt) { #>
                                <option value="{{ opt.value }}" {{{ opt.value == value ? 'selected="selected"' : '' }}}>
                                    {{{ opt.name }}}
                                </option>
                            <# }) #>
                        </optgroup>
                    <# } else { #>
                        <option value="{{ opt.value }}" {{{ opt.value == value ? 'selected="selected"' : '' }}}>
                            {{{ opt.name }}}
                        </option>
                    <# } #>
                <# }) #>
            </select>
        <# } #>
        <?php
    }
}
