<?php
namespace Carbon_Fields\Field;

use Carbon_Fields\Exception\Incorrect_Syntax_Exception;

/**
 * Taxonomy field class.
 */
class Taxonomy_Field extends Grouped_Select_Field
{
    protected $taxonomy;
    protected $save_taxonomy = false;

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
        $this->taxonomy = $taxonomy;
        return $this;
    }

    public function save_taxonomy($save)
    {
        $this->save_taxonomy = $save;
        return $this;
    }

    public function save()
    {
        $value = intval($this->get_value());

        if ( $this->save_taxonomy ) {
            global $post;

            if ( term_exists($value, $this->taxonomy) ) {
                wp_set_post_terms($post->ID, array($value), $this->taxonomy, true);
            }
        }

        $this->set_value($value);
        parent::save();
    }

    public function load_options()
    {
        if ( empty( $this->taxonomy ) ) { return false; }
        if (taxonomy_exists($this->taxonomy)) {
            $this->options = $this->values_for_taxonomy($this->taxonomy);
        } else {
            Incorrect_Syntax_Exception::raise('Only valid taxonomies are allowed in the <code>set_taxonomy()</code> method.');
        }

        return $this->options;
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
