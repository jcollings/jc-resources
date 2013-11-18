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
	    'slug' => null,
	    'columns' => 2,
	    'description' => true
    ), $atts ));

    ob_start();

    do_action( 'jcr/show_section_list', array(
    	'section' => $id,
    	'columns' => $columns,
    	'description' => $description
    ));

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