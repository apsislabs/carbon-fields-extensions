<?php
namespace Carbon_Fields\Field;

use Carbon_Fields\Exception\Incorrect_Syntax_Exception;

/**
 * Taxonomy field class.
 */
class Taxonomy_Field extends Grouped_Select_Field
{
    protected $taxonomy;

	public static function admin_enqueue_scripts() {
        $dir = plugin_dir_url( __FILE__ );

        wp_enqueue_style( 'chosen_styles' );

        wp_enqueue_script( 'chosen' );
        wp_enqueue_script( 'carbon-field-Taxonomy_Field', $dir . '/field.js', array( 'carbon-fields', 'jquery', 'chosen' ) );
    }

    /**
     * Stores the taxonomy for the dropdown
     *
     * @var string
     **/
    public function set_taxonomy($taxonomy)
    {
        if ( taxonomy_exists($taxonomy) ) {
            $this->min = $this->add_options($this->values_for_taxonomy($taxonomy));
        } else {
            Incorrect_Syntax_Exception::raise('Only valid taxonomies are allowed in the <code>set_taxonomy()</code> method.');
        }

        return $this;
    }

    private function values_for_taxonomy($taxonomy)
    {
        $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));

        $terms = array_reduce($terms, function(&$result, $item) {
            $result[$item->term_id] = $item->name;
            return $result;
        });

        return array($this->get_labels_for_taxonomy($taxonomy) => $terms);
    }

    private function get_labels_for_taxonomy($taxonomy)
    {
        if ( taxonomy_exists($taxonomy) ) {
            return get_taxonomy($taxonomy)->labels->name;
        }

        return $taxonomy;
    }
}
