<?php
namespace Carbon_Fields\Field;
use Carbon_Fields\Exception\Incorrect_Syntax_Exception;

class Post_Object_Field extends Grouped_Select_Field
{
    protected $post_type = array();

    public static function admin_enqueue_scripts() {
        $dir = plugin_dir_url( __FILE__ );
        wp_enqueue_style( 'chosen_styles' );
        wp_enqueue_script( 'chosen' );
        wp_enqueue_script( 'carbon-field-Post_Object_Field', $dir . '/field.js', array( 'carbon-fields', 'jquery', 'chosen' ) );
    }

    /**
     * Stores the post type as an array
     *
     * @var array|string
     */
    public function set_post_type( $post_type )
	{
        if ( ! is_array( $post_type ) ) {
            $post_type = array( $post_type );
        }

        $this->post_type = $post_type;

        return $this;
    }

    /**
     * Generate the item options for the select field.
     *
     * @return array $options The selectable options.
     */
    public function load_options() {
        $options = array();

        foreach ( $this->post_type as $post_type ) {
            if (post_type_exists($post_type)) {
                $options = array_merge( $options, $this->options_for_post_type($post_type) );
            } else {
                Incorrect_Syntax_Exception::raise('Only valid post types are allowed in the <code>set_post_type()</code> method.');
            }
        }

        $this->options = $options;
    }

    /**
     * Get posts for each post type passed to field type
     *
     * @return array $posts The selectable options of the relationship field.
     */
    private function options_for_post_type($post_type)
    {
        $filter_name = 'carbon_relationship_options_' . $this->get_name() . '_post_' . $post_type;

        $args = apply_filters( $filter_name, array(
            'post_type' => $post_type,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'suppress_filters' => false,
        ));

        $posts = array_reduce(get_posts($args), function(&$result, $id) {
            $result[$id] = $this->get_title_by_type( $id, 'post', $post_type );
            return $result;
        });

        return array($this->get_post_type_name($post_type) => $posts);
    }

    /**
     * Used to get the title of an item.
     *
     * Can be overriden or extended by the `carbon_relationship_title` filter.
     *
     * @param int     $id      The database ID of the item.
     * @param string  $type    Item type (post, term, user, comment, or a custom one).
     * @param string  $subtype The subtype - "page", "post", "category", etc.
     * @return string $title The title of the item.
     */
    public function get_title_by_type( $id, $type, $subtype = '' ) {
        $title = get_the_title( $id );

        if ( ! $title ) {
            $title = '(no title) - ID: ' . $id;
        }

        /**
         * Filter the title of the relationship item.
         *
         * @param string $title   The unfiltered item title.
         * @param string $name    Name of the relationship field.
         * @param int    $id      The database ID of the item.
         * @param string $type    Item type (post, term, user, comment, or a custom one).
         * @param string $subtype Subtype - "page", "post", "category", etc.
         */
        return apply_filters( 'carbon_relationship_title', $title, $this->get_name(), $id, $type, $subtype );
    }

    /**
     * Get the plural label for a given post type
     * @param  string $post_type The id for the post type
     * @return string            The post type's plural name, or slug if not found
     */
    public function get_post_type_name($post_type)
    {
        if ( post_type_exists($post_type) ) {
            return get_post_type_object($post_type)->labels->name;
        }

        return $post_type;
    }
}
