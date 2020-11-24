<?php
/**
 * Class AE_critical
 */
class AE_critical {

    /**
     * Field Constructor.
     *
     * @param array $field
     * - id
     * - name
     * - placeholder
     * - readonly
     * - class
     * - text
     * @param $value
     * @param $parent
     * @since AEFramework 1.0.0
     */
    function __construct( $field = array(), $value , $parent ) {

        $this->parent = $parent;
        $this->field = $field;
        $this->value = $value;

    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since AEFramework 1.0.0
     */
    function render() {
        // var_dump($this->field);
        $args = array(
            'hide_empty' => false,
            'parent' => 0
        );
        $terms = get_terms('place_category', $args);
        echo '<div class=" title group-de_multirating_order_by">'.$this->field['title'].'</div>';
        echo '<div class="desc"><span class="group-desc">'.$this->field['desc'].'</span></div>';
        echo '<div class="wrapper-critical">';
        foreach ($terms as $term) {
            $selected = Critical_category::get_critical_options($term->term_id);
            echo'<div class="critical_cate">
                    <p><strong>'.$term->name.'</strong></p>
                    <input class="category_item" type="hidden" id="'.$term->term_id.'" value="'.$term->term_id.'">';
            ae_tax_dropdown('review_criteria', array(
                    'attr' => 'multiple data-placeholder="'.__("Choose categories", ET_DOMAIN).'"',
                    'class' => 'chosen criteria_tax',
                    'hide_empty' => false,
                    'hierarchical' => true ,
                    'id' => 'place_category' ,
                    'show_option_all' => false,
                    'value' => 'id',
                    'selected' => $selected
                )
            );
            echo '</div>';
        }
        echo '</div>';
    }//render

}
