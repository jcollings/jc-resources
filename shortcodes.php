<?php
/**
 * Output the current section, and all its resources
 *
 * @param array $atts shortcode arguments
 */
add_shortcode( 'jcr_section', 'jcr_section_shortcode' );
function jcr_section_shortcode($atts){
	extract(shortcode_atts( array(
	    'id' => null,
	    'columns' => 2,
	    'description' => true
    ), $atts ));

    if(empty($id))
        return;

    ob_start();

    $ids = explode(',', $id);

    foreach($ids as $id){

         do_action( 'jcr/show_section_list', apply_filters( 'jcr/shortcode_section_list_args', array(
            'section' => trim($id),
            'columns' => $columns,
            'description' => $description,
            'wrapper' => 'div',
            'wrapper_id' => '',
            'wrapper_class' => '',
            'container' => 'ul',
            'container_class' => '',
            'container_id' => '',
            'item' => 'li',
            'item_class' => '',
        )));

    }

    return ob_get_clean();
}

/**
 * Output a section heading with the content
 *
 * @param array $atts shortcode arguments
 */
add_shortcode( 'jcr_resource_heading', 'jcr_section_heading_shortcode' );
function jcr_section_heading_shortcode($atts){
	extract(shortcode_atts( array(
	    'name' => false,
    ), $atts ));

    if(!$name)
    	return false;

    return '<h2 id="'.sanitize_title($name).'">'.$name.'</h2>';
}